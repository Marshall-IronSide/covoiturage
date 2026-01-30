@extends('layouts.app')

@section('content')
    <div class="section">
        <div class="section-header">
            <h1 class="section-title">Mes réservations</h1>
        </div>

        @if ($reservations->isEmpty())
            <div class="card" style="text-align: center; padding: 4rem 2rem;">
                <p class="card-text" style="font-size: 1.1rem;">Vous n'avez pas encore de réservations.</p>
                <a href="{{ route('trajets.index') }}" class="btn btn-primary" style="margin-top: 1rem;">Voir les trajets
                    disponibles</a>
            </div>
        @else
            <div class="grid grid-2">
                @foreach ($reservations as $reservation)
                    <div class="card">
                        <div class="card-header"
                            style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid var(--border); padding-bottom: 1rem; margin-bottom: 1rem;">
                            <div>
                                <h3 class="card-title" style="margin: 0;">{{ $reservation->trajet->ville_depart }} →
                                    {{ $reservation->trajet->ville_arrivee }}</h3>
                            </div>
                            <span class="badge"
                                style="@if ($reservation->statut === 'confirmee') background: #dcfce7; color: var(--success); @elseif($reservation->statut === 'annulee') background: #fee2e2; color: var(--danger); @else background: #dbeafe; color: var(--primary); @endif">
                                {{ $reservation->statut }}
                            </span>
                        </div>

                        <div class="card-text">
                            <p><strong>📅 Date:</strong>
                                {{ \Carbon\Carbon::parse($reservation->trajet->date_trajet)->format('d/m/Y H:i') }}</p>
                            <p><strong>💺 Places:</strong> {{ $reservation->nombre_places }} place(s)</p>
                            <p><strong>💰 Prix:</strong> {{ number_format($reservation->prix_total, 2) }} F.CFA</p>
                            <p><strong>🧑 Conducteur:</strong> {{ $reservation->trajet->conducteur->prenom }}
                                {{ $reservation->trajet->conducteur->nom }}</p>
                            <p><strong>📞 Téléphone:</strong> {{ $reservation->trajet->conducteur->telephone }}</p>
                        </div>

                        <div class="card-footer" style="display: flex; gap: 1rem; flex-wrap: wrap;">
                            <a href="{{ route('trajets.show', $reservation->trajet) }}" class="btn btn-primary btn-sm">Voir
                                le trajet</a>

                            @if ($reservation->statut === 'en_attente')
                                <form action="{{ route('reservations.cancel', $reservation) }}" method="POST"
                                    style="flex: 1; min-width: 150px;">
                                    @csrf
                                    <button class="btn btn-danger btn-sm" style="width: 100%;">Annuler</button>
                                </form>
                            @elseif($reservation->statut === 'confirmee')
                                <form action="{{ route('reservations.cancel', $reservation) }}" method="POST"
                                    style="flex: 1; min-width: 150px;">
                                    @csrf
                                    <button class="btn btn-warning btn-sm" style="width: 100%;">Annuler</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
