<?php

namespace App\Http\Controllers;

use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VehiculeController extends Controller
{

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        // 🔒 VÉRIFICATION : L'utilisateur a-t-il déjà un véhicule ?
        if (Auth::user()->vehicule) {
            return redirect()->route('vehicule.show', Auth::user()->vehicule)
                ->with('error', '⚠️ Vous ne pouvez enregistrer qu\'un seul véhicule. Vous avez déjà un véhicule enregistré.');
        }

        return view('vehicules.create');
    }

    /**
     * Enregistrer un nouveau véhicule
     */
    public function store(Request $request)
    {
        // 🔒 DOUBLE VÉRIFICATION
        if (Auth::user()->vehicule) {
            return redirect()->route('vehicule.show', Auth::user()->vehicule)
                ->with('error', '⚠️ Vous ne pouvez enregistrer qu\'un seul véhicule. Vous avez déjà un véhicule enregistré.');
        }

        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'numero_plaque' => ['required', 'string', 'max:20', 'unique:vehicules'],
            'description' => ['required', 'string', 'max:1000'],
        ]);

        $data = [
            'user_id' => Auth::id(),
            'numero_plaque' => $request->numero_plaque,
            'description' => $request->description,
        ];

        // Gérer l'upload de la photo
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('vehicules', 'public');
        }

        $vehicule = Vehicule::create($data);

        return redirect()->route('vehicule.show', $vehicule)
            ->with('success', '✅ Véhicule enregistré avec succès !');
    }

    /**
     * Afficher le véhicule
     */
    public function show(Vehicule $vehicule)
    {
        // Vérifier que c'est le véhicule de l'utilisateur
        if ($vehicule->user_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        return view('vehicules.show', compact('vehicule'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Vehicule $vehicule)
    {
        if ($vehicule->user_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        return view('vehicules.edit', compact('vehicule'));
    }

    /**
     * Mettre à jour le véhicule
     */
    public function update(Request $request, Vehicule $vehicule)
    {
        if ($vehicule->user_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        $request->validate([
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'numero_plaque' => ['required', 'string', 'max:20', 'unique:vehicules,numero_plaque,' . $vehicule->id],
            'description' => ['required', 'string', 'max:1000'],
        ]);

        $data = [
            'numero_plaque' => $request->numero_plaque,
            'description' => $request->description,
        ];

        // Gérer la nouvelle photo
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo
            if ($vehicule->photo) {
                Storage::disk('public')->delete($vehicule->photo);
            }
            $data['photo'] = $request->file('photo')->store('vehicules', 'public');
        }

        $vehicule->update($data);

        return redirect()->route('vehicule.show', $vehicule)
            ->with('success', '✅ Véhicule mis à jour avec succès !');
    }

    /**
     * Supprimer le véhicule
     */
    public function destroy(Vehicule $vehicule)
    {
        if ($vehicule->user_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        // Vérifier s'il y a des trajets à venir
        $upcomingTrips = $vehicule->trajets()->where('date_trajet', '>', now())->count();

        if ($upcomingTrips > 0) {
            return redirect()->route('vehicule.show', $vehicule)
                ->with('error', '⚠️ Impossible de supprimer ce véhicule car il a des trajets à venir.');
        }

        // Supprimer la photo
        if ($vehicule->photo) {
            Storage::disk('public')->delete($vehicule->photo);
        }

        $vehicule->delete();

        return redirect()->route('dashboard')
            ->with('success', '✅ Véhicule supprimé avec succès !');
    }
}
