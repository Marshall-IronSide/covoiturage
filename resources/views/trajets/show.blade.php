@extends('layouts.app')

@section('content')
<div class="trip-show-container">
    <div class="trip-show-header">
        <div class="route-info">
            <div class="location-badge departure">
                <span class="icon">📍</span>
                <span class="city">{{ $trajet->ville_depart }}</span>
            </div>
            <div class="route-arrow">→</div>
            <div class="location-badge arrival">
                <span class="icon">📍</span>
                <span class="city">{{ $trajet->ville_arrivee }}</span>
            </div>
        </div>
        <div class="trip-meta">
            <div class="meta-item">
                <span class="icon">📅</span>
                <span>{{ $trajet->date_trajet->format('d/m/Y à H:i') }}</span>
            </div>
            <div class="meta-item">
                <span class="icon">💺</span>
                <span>{{ $trajet->places_disponibles }} place(s) disponible(s)</span>
            </div>
        </div>
    </div>

    <div class="trip-content-grid">
        <div class="trip-details">
            <div class="detail-card">
                <div class="card-header">
                    <h2>📍 Détails du trajet</h2>
                </div>
                <div class="card-body">
                    <div class="location-detail">
                        <div class="location-label">Lieu de départ</div>
                        <div class="location-city">{{ $trajet->ville_depart }}</div>
                        <div class="location-desc">{{ $trajet->description_depart }}</div>
                    </div>
                    <div class="route-divider"></div>
                    <div class="location-detail">
                        <div class="location-label">Lieu d'arrivée</div>
                        <div class="location-city">{{ $trajet->ville_arrivee }}</div>
                        <div class="location-desc">{{ $trajet->description_arrivee }}</div>
                    </div>
                </div>
            </div>

            <div class="detail-card">
                <div class="card-header">
                    <h2>🚗 Véhicule</h2>
                </div>
                <div class="card-body">
                    @if ($trajet->photo_vehicule)
                        <div class="vehicle-image-container">
                            <img src="{{ asset('storage/' . $trajet->photo_vehicule) }}" alt="Véhicule" class="vehicle-image">
                        </div>
                    @endif
                    <div class="vehicle-description">
                        {{ $trajet->description_vehicule }}
                    </div>
                </div>
            </div>

            <div class="detail-card">
                <div class="card-header">
                    <h2>👤 Conducteur</h2>
                </div>
                <div class="card-body">
                    <div class="driver-info1">
                        <div class="driver-avatar2">{{ strtoupper(substr($trajet->conducteur->prenom, 0, 1)) }}</div>
                            <span class="avatar-text">{{ substr($trajet->conducteur->name, 0, 1) }}</span>
                        </div>
                        <div class="driver-details2">
                            <div class="driver-name">{{ $trajet->conducteur->prenom }} {{ $trajet->conducteur->nom }}</div>
                            <div class="driver-contact">
                                <div class="contact-item">
                                    <span class="icon">📞</span>
                                    <span>{{ $trajet->conducteur->telephone ?? 'Non renseigné' }}</span>
                                </div>
                                <div class="contact-item">
                                    <span class="icon">✉️</span>
                                    <span>{{ $trajet->conducteur->email }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($trajet->reservations->count() > 0 && auth()->id() === $trajet->conducteur_id)
                <div class="detail-card">
                    <div class="card-header">
                        <h2>👥 Réservations ({{ $trajet->reservations->count() }})</h2>
                    </div>
                    <div class="card-body">
                        <div class="reservations-list-new">
                            @foreach ($trajet->reservations as $reservation)
                                <div class="reservation-item">
                                    <div class="reservation-passenger">
                                        <div class="passenger-avatar">
                                            <span>{{ substr($reservation->passager->name, 0, 1) }}</span>
                                        </div>
                                        <div class="passenger-info">
                                            <div class="passenger-name">{{ $reservation->passager->name }}</div>
                                            <div class="passenger-details">
                                                {{ $reservation->nombre_places }} place(s) • 
                                                <span class="status-{{ $reservation->statut }}">{{ ucfirst(str_replace('_', ' ', $reservation->statut)) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($reservation->statut === 'en_attente')
                                        <form action="{{ route('reservations.confirm', $reservation) }}" method="POST" class="reservation-action">
                                            @csrf
                                            <button type="submit" class="btn btn-confirm">Confirmer</button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <aside class="trip-sidebar">
            <div class="booking-card">
                <div class="booking-header">
                    <h3>Réservation</h3>
                </div>
                <div class="booking-body">
                    <div class="trip-info-summary">
                        <div class="info-row">
                            <span class="info-label">Date et heure</span>
                            <span class="info-value">{{ $trajet->date_trajet->format('d/m/Y à H:i') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Places disponibles</span>
                            <span class="info-value places-count">{{ $trajet->places_disponibles }}</span>
                        </div>
                    </div>

                    @auth
                        @if (auth()->id() === $trajet->conducteur_id)
                            <div class="owner-actions">
                                <a href="{{ route('trajets.edit', $trajet) }}" class="btn btn-edit">
                                    <span>✏️</span> Modifier le trajet
                                </a>
                                <form action="{{ route('trajets.destroy', $trajet) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce trajet ?')">
                                        <span>🗑️</span> Supprimer
                                    </button>
                                </form>
                            </div>
                        @elseif ($trajet->places_disponibles > 0)
                            <form action="{{ route('reservations.store', $trajet) }}" method="POST" class="booking-form">
                                @csrf
                                <div class="form-group-inline">
                                    <label for="nombre_places">Nombre de places</label>
                                    <select name="nombre_places" id="nombre_places" class="form-select" required>
                                        @for ($i = 1; $i <= $trajet->places_disponibles; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-book">
                                    <span>✓</span> Réserver maintenant
                                </button>
                            </form>
                        @else
                            <div class="booking-unavailable">
                                <span class="icon">❌</span>
                                <span>Ce trajet est complet</span>
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-book">
                            <span>🔐</span> Se connecter pour réserver
                        </a>
                    @endauth
                </div>
            </div>

            <div class="info-card">
                <div class="info-card-header">💡 Bon à savoir</div>
                <div class="info-card-body">
                    <ul class="info-list">
                        <li>Soyez ponctuel au point de rendez-vous</li>
                        <li>Contactez le conducteur en cas d'imprévu</li>
                        <li>Respectez les règles de courtoisie</li>
                    </ul>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection