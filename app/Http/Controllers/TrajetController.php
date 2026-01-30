<?php

namespace App\Http\Controllers;

use App\Models\Trajet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function Laravel\Prompts\confirm;

class TrajetController extends Controller
{
    public function index()
    {
        //  Charger la relation vehicule
        $trajets = Trajet::with(['conducteur', 'vehicule'])
            ->where('date_trajet', '>=', now())
            ->orderBy('date_trajet', 'asc')
            ->get();
        
        return view('trajets.index', compact('trajets'));
    }

    public function create()
    {
        // Vérifier que l'utilisateur a un véhicule
        if (!Auth::user()->vehicule) {
            return redirect()->route('vehicule.create')
                ->with('error', '⚠️ Vous devez d\'abord enregistrer un véhicule avant de créer un trajet.');
        }

        return view('trajets.create');
    }

    public function store(Request $request)
    {
        // Vérifier que l'utilisateur a un véhicule
        if (!Auth::user()->vehicule) {
            return redirect()->route('vehicule.create')
                ->with('error', '⚠️ Vous devez d\'abord enregistrer un véhicule avant de créer un trajet.');
        }

        $validated = $request->validate([
            'ville_depart' => 'required|string|max:255',
            'description_depart' => 'required|string',
            'ville_arrivee' => 'required|string|max:255',
            'description_arrivee' => 'required|string',
            'date_trajet' => 'required|date|after:now',
            'places_disponibles' => 'required|integer|min:1|max:8',
            // description_vehicule et photo_vehicule SUPPRIMÉS
        ]);

        $validated['conducteur_id'] = Auth::id();
        //  Utiliser automatiquement le véhicule de l'utilisateur
        $validated['vehicule_id'] = Auth::user()->vehicule->id;

        Trajet::create($validated);

        return redirect()->route('trajets.index')
            ->with('success', '✅ Trajet créé avec succès!');
    }

    public function show(Trajet $trajet)
    {
        
        $trajet->load(['conducteur', 'vehicule', 'reservations']);
        return view('trajets.show', compact('trajet'));
    }

    public function edit(Trajet $trajet)
    {
        $this->authorize('update', $trajet);
        $vehicules = Auth::user()->vehicule ? collect([Auth::user()->vehicule]) : collect();
        return view('trajets.edit', compact('trajet', 'vehicules'));
    }

    public function update(Request $request, Trajet $trajet)
    {
        $this->authorize('update', $trajet);

        $validated = $request->validate([
            'ville_depart' => 'required|string|max:255',
            'description_depart' => 'required|string',
            'ville_arrivee' => 'required|string|max:255',
            'description_arrivee' => 'required|string',
            'date_trajet' => 'required|date',
            'places_disponibles' => 'required|integer|min:1|max:8',
            'vehicule_id' => 'nullable|exists:vehicules,id',
        ]);

        $trajet->update($validated);
        
        return redirect()->route('trajets.show', $trajet)
            ->with('success', '✅ Trajet mis à jour!');
    }

    public function destroy(Trajet $trajet)
    {
        $this->authorize('delete', $trajet);
        $trajet->delete();

        return redirect()->route('trajets.index')
            ->with('success', '✅ Trajet supprimé!');
    }

    /**
     *  Afficher les trajets de l'utilisateur
     */
    public function mesTrajets()
    {
        $trajets = Trajet::where('conducteur_id', Auth::id())
            ->with('vehicule')
            ->orderBy('date_trajet', 'desc')
            ->get();

        return view('trajets.mes-trajets', compact('trajets'));
    }
}