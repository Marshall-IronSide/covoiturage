@extends('layouts.app')

@section('content')
<div class="section">
    <h1>{{ $trajet->ville_depart }} → {{ $trajet->ville_arrivee }}</h1>
    
    <div class="trajet-detail-grid">
        <div class="trajet-main">
            <section class="detail-section">
                <h2>📍 Lieu de départ</h2>
                <div class="detail-box">
                    <p><strong>Ville:</strong> {{ $trajet->ville_depart }}</p>
                    <p><strong>Description:</strong> {{ $trajet->description_depart }}</p>
                </div>
            </section>

            <section class="detail-section">
                <h2>📍 Lieu d'arrivée</h2>
                <div class="detail-box">
                    <p><strong>Ville:</strong> {{ $trajet->ville_arrivee }}</p>
                    <p><strong>Description:</strong> {{ $trajet->description_arrivee }}</p>
                </div>
            </section>

            <section class="detail-section">
                <h2>🚗 Véhicule</h2>
                <div class="detail-box">
                    @if ($trajet->photo_vehicule)
                        <img src="{{ asset('storage/' . $trajet->photo_vehicule) }}" alt="Véhicule" class="vehicle-detail-photo">
                    @else
                        <p class="no-photo">Pas de photo disponible</p>
                    @endif
                    <p><strong>Description:</strong> {{ $trajet->description_vehicule }}</p>
                </div>
            </section>

            <section class="detail-section">
                <h2>🧑 Conducteur</h2>
                <div class="detail-box">
                    <p><strong>Nom:</strong> {{ $trajet->conducteur->name }}</p>
                    <p><strong>Téléphone:</strong> {{ $trajet->conducteur->telephone ?? 'Non renseigné' }}</p>
                    <p><strong>Email:</strong> {{ $trajet->conducteur->email }}</p>
                </div>
            </section>
        </div>

        <aside class="trajet-sidebar">
            <div class="reservation-box">
                <h3>Informations du trajet</h3>
                <p class="info">
                    <strong>Date et heure:</strong><br>
                    {{ $trajet->date_trajet->format('d/m/Y à H:i') }}
                </p>
                <p class="info">
                    <strong>Places disponibles:</strong><br>
                    {{ $trajet->places_disponibles }}
                </p>

                @auth
                    @if (auth()->id() === $trajet->conducteur_id)
                        <a href="{{ route('trajets.edit', $trajet) }}" class="btn btn-warning btn-block">Modifier</a>
                        <form action="{{ route('trajets.destroy', $trajet) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Êtes-vous sûr?')">Supprimer</button>
                        </form>
                    @elseif ($trajet->places_disponibles > 0)
                        <form action="{{ route('reservations.store', $trajet) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="nombre_places">Nombre de places:</label>
                                <select name="nombre_places" id="nombre_places" class="form-input" required>
                                    @for ($i = 1; $i <= $trajet->places_disponibles; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Réserver</button>
                        </form>
                    @else
                        <button class="btn btn-disabled btn-block" disabled>Complet</button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-block">Se connecter pour réserver</a>
                @endauth
            </div>

            @if ($trajet->reservations->count() > 0)
                <div class="reservations-box">
                    <h3>Réservations</h3>
                    @if (auth()->id() === $trajet->conducteur_id)
                        <ul class="reservations-list">
                            @foreach ($trajet->reservations as $reservation)
                                <li>
                                    <p><strong>{{ $reservation->passager->name }}</strong></p>
                                    <p>{{ $reservation->nombre_places }} place(s) - {{ $reservation->statut }}</p>
                                    @if ($reservation->statut === 'en_attente')
                                        <form action="{{ route('reservations.confirm', $reservation) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-small">Confirmer</button>
                                        </form>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif
        </aside>
    </div>
</div>
@endsection
