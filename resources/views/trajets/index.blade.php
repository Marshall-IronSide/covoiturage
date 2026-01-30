@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-content">
        <div class="hero-badge">🚗 Covoiturage Intelligent</div>
        <h1 class="hero-title">Trajets disponibles</h1>
        <p class="hero-description">Découvrez les meilleurs trajets près de chez vous et économisez en partageant le covoiturage</p>
        @auth
            <a href="{{ route('trajets.create') }}" class="hero-btn">
                <span class="btn-icon">+</span>
                Créer un trajet
            </a>
        @else
            <a href="{{ route('login') }}" class="hero-btn">
                <span class="btn-icon">→</span>
                Se connecter
            </a>
        @endauth
    </div>
    <div class="hero-decoration">
        <div class="decoration-circle decoration-circle-1"></div>
        <div class="decoration-circle decoration-circle-2"></div>
        <div class="decoration-circle decoration-circle-3"></div>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success-modern">
        <div class="alert-icon">✓</div>
        <div class="alert-content">{{ session('success') }}</div>
    </div>
@endif

@if ($trajets->isEmpty())
    <div class="empty-state-modern">
        <div class="empty-icon">🚗</div>
        <h2 class="empty-title">Aucun trajet disponible</h2>
        <p class="empty-text">Soyez le premier à créer un trajet et commencez à économiser !</p>
        @auth
            <a href="{{ route('trajets.create') }}" class="btn btn-primary-modern">Créer le premier trajet</a>
        @endauth
    </div>
@else
    <div class="trajets-container">
        <div class="section-header">
            <h2 class="section-title">Tous les trajets</h2>
            <div class="section-count">{{ $trajets->count() }} trajets</div>
        </div>

        <div class="grid grid-3">
            @foreach ($trajets as $trajet)
                <article class="trajet-card-modern">
                    <div class="card-image-wrapper">
                        @if($trajet->vehicule && $trajet->vehicule->photo)
                            <img src="{{ asset('storage/' . $trajet->vehicule->photo) }}" alt="Vehicle" class="card-image">
                            <div class="image-overlay"></div>
                        @else
                            <div class="card-image card-image-placeholder">
                                <div class="placeholder-icon">🚗</div>
                            </div>
                        @endif
                        
                        @if($trajet->places_disponibles > 0)
                            <div class="availability-badge available">{{ $trajet->places_disponibles }} places</div>
                        @else
                            <div class="availability-badge full">Complet</div>
                        @endif
                    </div>

                    <div class="card-content">
                        <div class="route-header">
                            <div class="route-item">
                                <div class="route-dot route-dot-start"></div>
                                <div class="route-city">{{ $trajet->ville_depart }}</div>
                            </div>
                            <div class="route-connector">
                                <div class="route-line"></div>
                                <div class="route-arrow">→</div>
                            </div>
                            <div class="route-item">
                                <div class="route-dot route-dot-end"></div>
                                <div class="route-city">{{ $trajet->ville_arrivee }}</div>
                            </div>
                        </div>

                        <div class="route-details">
                            <div class="detail-row">
                                <span class="detail-label">Départ:</span>
                                <span class="detail-value">{{ $trajet->description_depart }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Arrivée:</span>
                                <span class="detail-value">{{ $trajet->description_arrivee }}</span>
                            </div>
                        </div>

                        <div class="driver-section">
                            <div class="driver-avatar-modern">{{ strtoupper(substr($trajet->conducteur->prenom, 0, 1)) }}</div>
                            <div class="driver-details">
                                <div class="driver-name-modern">{{ $trajet->conducteur->prenom }} {{ $trajet->conducteur->nom }}</div>
                                @if($trajet->vehicule)
                                    <div class="vehicle-info">
                                        <span class="vehicle-icon">🚗</span>
                                        {{ $trajet->vehicule->numero_plaque }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card-actions">
                            <a href="{{ route('trajets.show', $trajet) }}" class="action-btn action-btn-primary">
                                <span class="btn-text">Détails</span>
                                <span class="btn-arrow">→</span>
                            </a>
                            
                            @auth
                                @if(auth()->id() === $trajet->conducteur_id)
                                    <a href="{{ route('trajets.edit', $trajet) }}" class="action-btn action-btn-warning">
                                        <span class="btn-text">Éditer</span>
                                    </a>
                                    <form action="{{ route('trajets.destroy', $trajet) }}" method="POST" class="action-form" onsubmit="return confirm('Confirmer la suppression?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="action-btn action-btn-danger">
                                            <span class="btn-text">Supprimer</span>
                                        </button>
                                    </form>
                                @else
                                    @if($trajet->places_disponibles > 0)
                                        <form action="{{ route('reservations.store', $trajet) }}" method="POST" class="action-form">
                                            @csrf
                                            <button class="action-btn action-btn-success">
                                                <span class="btn-text">Réserver</span>
                                                <span class="btn-arrow">✓</span>
                                            </button>
                                        </form>
                                    @else
                                        <button class="action-btn action-btn-disabled" disabled>
                                            <span class="btn-text">Complet</span>
                                        </button>
                                    @endif
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="action-btn action-btn-success">
                                    <span class="btn-text">Réserver</span>
                                    <span class="btn-arrow">→</span>
                                </a>
                            @endauth
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
@endif
@endsection