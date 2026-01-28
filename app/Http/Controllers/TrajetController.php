<?php

namespace App\Http\Controllers;

use App\Models\Trajet;
use Illuminate\Http\Request;

class TrajetController extends Controller
{
    // Afficher la liste de tous les trajets
    public function index()
    {
        $trajets = Trajet::with('conducteur')->get();
        return view('trajets.index', compact('trajets'));
    }

    // Afficher le formulaire de création
    public function create()
    {
        return view('trajets.create');
    }

    // Enregistrer un nouveau trajet
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ville_depart' => 'required|string|max:255',
            'description_depart' => 'required|string',
            'ville_arrivee' => 'required|string|max:255',
            'description_arrivee' => 'required|string',
            'date_trajet' => 'required|date',
            'places_disponibles' => 'required|integer|min:1',
            'description_vehicule' => 'required|string',
            'photo_vehicule' => 'nullable|image|max:2048',
        ]);

        // Traiter la photo
        if ($request->hasFile('photo_vehicule')) {
            $path = $request->file('photo_vehicule')->store('vehicules', 'public');
            $validated['photo_vehicule'] = $path;
        }

        $validated['conducteur_id'] = auth()->id();
        Trajet::create($validated);

        return redirect()->route('trajets.index')->with('success', 'Trajet créé avec succès!');
    }

    // Afficher un trajet spécifique
    public function show(Trajet $trajet)
    {
        $trajet->load('conducteur', 'reservations');
        return view('trajets.show', compact('trajet'));
    }

    // Afficher le formulaire d'édition
    public function edit(Trajet $trajet)
    {
        $this->authorize('update', $trajet);
        return view('trajets.edit', compact('trajet'));
    }

    // Mettre à jour un trajet
    public function update(Request $request, Trajet $trajet)
    {
        $this->authorize('update', $trajet);

        $validated = $request->validate([
            'ville_depart' => 'required|string|max:255',
            'description_depart' => 'required|string',
            'ville_arrivee' => 'required|string|max:255',
            'description_arrivee' => 'required|string',
            'date_trajet' => 'required|date',
            'places_disponibles' => 'required|integer|min:1',
            'description_vehicule' => 'required|string',
            'photo_vehicule' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo_vehicule')) {
            $path = $request->file('photo_vehicule')->store('vehicules', 'public');
            $validated['photo_vehicule'] = $path;
        }

        $trajet->update($validated);
        return redirect()->route('trajets.show', $trajet)->with('success', 'Trajet mis à jour!');
    }

    // Supprimer un trajet
    public function destroy(Trajet $trajet)
    {
        $this->authorize('delete', $trajet);
        $trajet->delete();

        return redirect()->route('trajets.index')->with('success', 'Trajet supprimé!');
    }
}
