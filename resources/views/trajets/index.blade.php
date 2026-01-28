@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; padding: 4rem 2rem; border-radius: var(--radius); margin-bottom: 3rem; text-align: center;">
    <h1 style="font-size: 2.5rem; margin-bottom: 1rem; font-weight: 700;">🚗 Trajets disponibles</h1>
    <p style="font-size: 1.125rem; margin-bottom: 2rem; opacity: 0.95;">Découvrez les meilleurs trajets près de chez vous et économisez en partageant le covoiturage</p>
    @auth
        <a href="{{ route('trajets.create') }}" class="btn" style="background: white; color: var(--primary); font-weight: 600; padding: 0.75rem 2rem;">+ Créer un trajet</a>
    @else
        <a href="{{ route('login') }}" class="btn" style="background: white; color: var(--primary); font-weight: 600; padding: 0.75rem 2rem;">Se connecter</a>
    @endauth
</div>

@if (session('success'))
    <div class="alert alert-success" style="margin-bottom: 2rem;">
        {{ session('success') }}
    </div>
@endif

@if ($trajets->isEmpty())
    <div class="card" style="text-align: center; padding: 4rem 2rem;">
        <div style="font-size: 4rem; margin-bottom: 1rem;">🚗</div>
        <p class="card-text" style="font-size: 1.1rem; margin-bottom: 1rem;">Aucun trajet disponible pour le moment.</p>
                @auth
                    <p><a href="{{ route('trajets.create') }}" class="btn btn-primary">Créez le premier trajet</a></p>
                @endauth
            </div>
        @else
            <div class="grid grid-3">
                @foreach ($trajets as $trajet)
                    <div class="trajet-card">
                        @if($trajet->photo_vehicule)
                            <img src="{{ asset('storage/' . $trajet->photo_vehicule) }}" alt="Vehicle" class="trajet-image" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="trajet-image">Pas de photo</div>
                        @endif

                        <div class="trajet-body">
                            <div class="trajet-route">
                                <span class="trajet-city">{{ $trajet->ville_depart }}</span>
                                <span class="trajet-arrow">→</span>
                                <span class="trajet-city">{{ $trajet->ville_arrivee }}</span>
                            </div>

                            <div class="trajet-meta">
                                <div class="meta-item">
                                    <span class="meta-label">Date</span>
                                    <span class="meta-value">{{ \Carbon\Carbon::parse($trajet->date_trajet)->format('d/m H:i') }}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Places libres</span>
                                    <span class="meta-value">{{ $trajet->places_disponibles }}</span>
                                </div>
                            </div>

                            <div class="trajet-driver">
                                <div class="driver-avatar">{{ strtoupper(substr($trajet->conducteur->prenom, 0, 1)) }}</div>
                                <div class="driver-info">
                                    <div class="driver-name">{{ $trajet->conducteur->prenom }} {{ $trajet->conducteur->nom }}</div>
                                    <div class="driver-rating">📞 {{ $trajet->conducteur->telephone }}</div>
                                </div>
                            </div>

                            <div class="trajet-description">
                                <strong>De :</strong> {{ $trajet->description_depart }}<br>
                                <strong>À :</strong> {{ $trajet->description_arrivee }}<br>
                                <strong>Véhicule :</strong> {{ $trajet->description_vehicule }}
                            </div>

                            <div class="trajet-actions" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <a href="{{ route('trajets.show', $trajet) }}" class="btn btn-primary btn-sm" style="flex: 1; min-width: 70px;">Détails</a>
                                @auth
                                    @if(auth()->id() === $trajet->conducteur_id)
                                        <a href="{{ route('trajets.edit', $trajet) }}" class="btn btn-warning btn-sm" style="flex: 1; min-width: 70px;">Éditer</a>
                                        <form action="{{ route('trajets.destroy', $trajet) }}" method="POST" style="flex: 1; min-width: 70px; display: flex;" onsubmit="return confirm('Confirmer la suppression?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" style="width: 100%; cursor: pointer; padding: 0.5rem 0.5rem; font-size: 0.875rem;">Supprimer</button>
                                        </form>
                                    @else
                                        @if($trajet->places_disponibles > 0)
                                            <form action="{{ route('reservations.store', $trajet) }}" method="POST" style="flex: 1; min-width: 70px; display: flex;">
                                                @csrf
                                                <button class="btn btn-success btn-sm" style="width: 100%; cursor: pointer;">Réserver</button>
                                            </form>
                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled style="flex: 1; min-width: 70px;">Complet</button>
                                        @endif
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm" style="flex: 1; text-align: center; min-width: 70px;">Réserver</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
@endsection