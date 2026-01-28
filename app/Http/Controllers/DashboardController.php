<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Trajet;
use App\Models\Reservation;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Statistiques
        $stats = [
            'trajets_crees' => Trajet::where('conducteur_id', $user->id)->count(),
            'reservations_recues' => Reservation::whereHas('trajet', function($query) use ($user) {
                $query->where('conducteur_id', $user->id);
            })->count(),
            'mes_reservations' => Reservation::where('passager_id', $user->id)->count(),
            'reservations_confirmees' => Reservation::where('passager_id', $user->id)
                ->where('statut', 'confirmee')->count(),
        ];
        
        // Mes derniers trajets (3 plus récents)
        $mesTrajets = Trajet::where('conducteur_id', $user->id)
            ->orderBy('date_trajet', 'desc')
            ->take(3)
            ->get();
        
        // Réservations récentes pour mes trajets (3 plus récentes)
        $reservationsRecues = Reservation::whereHas('trajet', function($query) use ($user) {
                $query->where('conducteur_id', $user->id);
            })
            ->with(['passager', 'trajet'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        
        return view('dashboard', compact('stats', 'mesTrajets', 'reservationsRecues'));
    }
}