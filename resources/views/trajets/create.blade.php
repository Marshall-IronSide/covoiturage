@extends('layouts.app')

@section('content')
<div class="section">
    <div class="section-header">
        <h1 class="section-title">Créer un nouveau trajet</h1>
    </div>

    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <form action="{{ route('trajets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Lieu de départ -->
            <h3 style="margin-top: 2rem; color: var(--primary); border-bottom: 2px solid var(--border); padding-bottom: 1rem;">📍 Lieu de départ</h3>
            
            <div class="form-group">
                <label for="ville_depart" class="form-label">Ville de départ</label>
                <input type="text" name="ville_depart" id="ville_depart" class="form-control" placeholder="ex: Paris" value="{{ old('ville_depart') }}" required>
                @error('ville_depart')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="description_depart" class="form-label">Description précise du lieu</label>
                <textarea name="description_depart" id="description_depart" class="form-control" placeholder="ex: Parking près de la gare" rows="3" required>{{ old('description_depart') }}</textarea>
                @error('description_depart')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <!-- Lieu d'arrivée -->
            <h3 style="margin-top: 2rem; color: var(--primary); border-bottom: 2px solid var(--border); padding-bottom: 1rem;">📍 Lieu d'arrivée</h3>
            
            <div class="form-group">
                <label for="ville_arrivee" class="form-label">Ville d'arrivée</label>
                <input type="text" name="ville_arrivee" id="ville_arrivee" class="form-control" placeholder="ex: Lyon" value="{{ old('ville_arrivee') }}" required>
                @error('ville_arrivee')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="description_arrivee" class="form-label">Description précise du lieu</label>
                <textarea name="description_arrivee" id="description_arrivee" class="form-control" placeholder="ex: Centre-ville, devant la gare" rows="3" required>{{ old('description_arrivee') }}</textarea>
                @error('description_arrivee')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <!-- Informations du trajet -->
            <h3 style="margin-top: 2rem; color: var(--primary); border-bottom: 2px solid var(--border); padding-bottom: 1rem;">🚗 Informations du trajet</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="date_trajet" class="form-label">Date et heure</label>
                    <input type="datetime-local" name="date_trajet" id="date_trajet" class="form-control" value="{{ old('date_trajet') }}" required>
                    @error('date_trajet')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="places_disponibles" class="form-label">Nombre de places</label>
                    <input type="number" name="places_disponibles" id="places_disponibles" class="form-control" value="{{ old('places_disponibles', 1) }}" min="1" max="10" required>
                    @error('places_disponibles')<span class="form-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <!-- Informations véhicule -->
            <h3 style="margin-top: 2rem; color: var(--primary); border-bottom: 2px solid var(--border); padding-bottom: 1rem;">🚗 Informations du véhicule</h3>
            
            <div class="form-group">
                <label for="description_vehicule" class="form-label">Description du véhicule</label>
                <textarea name="description_vehicule" id="description_vehicule" class="form-control" placeholder="ex: Renault Clio blanc, très propre" rows="3" required>{{ old('description_vehicule') }}</textarea>
                @error('description_vehicule')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="photo_vehicule" class="form-label">Photo du véhicule</label>
                <input type="file" name="photo_vehicule" id="photo_vehicule" class="form-control" accept="image/*">
                @error('photo_vehicule')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <!-- Actions -->
            <div style="display: flex; gap: 1rem; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border);">
                <button type="submit" class="btn btn-primary" style="flex: 1;">✓ Créer le trajet</button>
                <a href="{{ route('trajets.index') }}" class="btn btn-secondary" style="flex: 1; text-align: center;">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
