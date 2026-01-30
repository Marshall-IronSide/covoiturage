<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Trajet;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    // Afficher les réservations de l'utilisateur
    public function index()
    {
        $reservations = auth()->user()->reservations()->with('trajet.conducteur')->get();
        return view('reservations.index', compact('reservations'));
    }

    // Créer une réservation
    public function store(Request $request, Trajet $trajet)
    {
        $validated = $request->validate([
            'nombre_places' => 'required|integer|min:1|max:' . $trajet->places_disponibles,
        ]);

        $prix_total = $validated['nombre_places'] * 1000; // À adapter selon vos besoins

        Reservation::create([
            'trajet_id' => $trajet->id,
            'passager_id' => auth()->id(),
            'nombre_places' => $validated['nombre_places'],
            'prix_total' => $prix_total,
            'statut' => 'en_attente',
        ]);

        return redirect()->route('trajets.show', $trajet)->with('success', 'Réservation effectuée!');
    }

    // Accepter une réservation (pour le conducteur)
    public function confirm(Reservation $reservation)
    {
        $this->authorize('confirm', $reservation);

        $reservation->update(['statut' => 'confirmee']);
        $reservation->trajet->decrement('places_disponibles', $reservation->nombre_places);

        return redirect()->back()->with('success', 'Réservation confirmée!');
    }

    // Annuler une réservation
    public function cancel(Reservation $reservation)
    {
        $this->authorize('cancel', $reservation);

        $reservation->update(['statut' => 'annulee']);
        if ($reservation->statut === 'confirmee') {
            $reservation->trajet->increment('places_disponibles', $reservation->nombre_places);
        }

        return redirect()->back()->with('success', 'Réservation annulée!');
    }
}
