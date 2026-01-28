@extends('layouts.app')

@section('content')
<div class="section">
    <div class="section-header">
        <h1 class="section-title">Modifier le trajet</h1>
    </div>

    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <form action="{{ route('trajets.update', $trajet) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Lieu de départ -->
            <h3 style="margin-top: 2rem; color: var(--primary); border-bottom: 2px solid var(--border); padding-bottom: 1rem;">📍 Lieu de départ</h3>
            
            <div class="form-group">
                <label for="ville_depart" class="form-label">Ville de départ</label>
                <input type="text" name="ville_depart" id="ville_depart" class="form-control" value="{{ old('ville_depart', $trajet->ville_depart) }}" required>
                @error('ville_depart')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="description_depart" class="form-label">Description précise du lieu</label>
                <textarea name="description_depart" id="description_depart" class="form-control" rows="3" required>{{ old('description_depart', $trajet->description_depart) }}</textarea>
                @error('description_depart')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <!-- Lieu d'arrivée -->
            <h3 style="margin-top: 2rem; color: var(--primary); border-bottom: 2px solid var(--border); padding-bottom: 1rem;">📍 Lieu d'arrivée</h3>
            
            <div class="form-group">
                <label for="ville_arrivee" class="form-label">Ville d'arrivée</label>
                <input type="text" name="ville_arrivee" id="ville_arrivee" class="form-control" value="{{ old('ville_arrivee', $trajet->ville_arrivee) }}" required>
                @error('ville_arrivee')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="description_arrivee" class="form-label">Description précise du lieu</label>
                <textarea name="description_arrivee" id="description_arrivee" class="form-control" rows="3" required>{{ old('description_arrivee', $trajet->description_arrivee) }}</textarea>
                @error('description_arrivee')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <!-- Informations du trajet -->
            <h3 style="margin-top: 2rem; color: var(--primary); border-bottom: 2px solid var(--border); padding-bottom: 1rem;">🚗 Informations du trajet</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="date_trajet" class="form-label">Date et heure</label>
                    <input type="datetime-local" name="date_trajet" id="date_trajet" class="form-control" value="{{ old('date_trajet', $trajet->date_trajet->format('Y-m-d\TH:i')) }}" required>
                    @error('date_trajet')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="places_disponibles" class="form-label">Nombre de places</label>
                    <input type="number" name="places_disponibles" id="places_disponibles" class="form-control" value="{{ old('places_disponibles', $trajet->places_disponibles) }}" min="1" max="10" required>
                    @error('places_disponibles')<span class="form-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <!-- Informations véhicule -->
            <h3 style="margin-top: 2rem; color: var(--primary); border-bottom: 2px solid var(--border); padding-bottom: 1rem;">🚗 Informations du véhicule</h3>
            
            <div class="form-group">
                <label for="description_vehicule" class="form-label">Description du véhicule</label>
                <textarea name="description_vehicule" id="description_vehicule" class="form-control" rows="3" required>{{ old('description_vehicule', $trajet->description_vehicule) }}</textarea>
                @error('description_vehicule')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="photo_vehicule" class="form-label">Photo du véhicule</label>
                @if($trajet->photo_vehicule)
                    <div style="margin-bottom: 1rem;">
                        <img src="{{ asset('storage/' . $trajet->photo_vehicule) }}" alt="Photo actuelle" style="max-width: 200px; border-radius: var(--radius); border: 1px solid var(--border);">
                        <p style="font-size: 0.875rem; color: var(--text-light); margin-top: 0.5rem;">Photo actuelle</p>
                    </div>
                @endif
                <input type="file" name="photo_vehicule" id="photo_vehicule" class="form-control" accept="image/*">
                @error('photo_vehicule')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <!-- Actions -->
            <div style="display: flex; gap: 1rem; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border);">
                <button type="submit" class="btn btn-primary" style="flex: 1;">✓ Mettre à jour</button>
                <a href="{{ route('trajets.show', $trajet) }}" class="btn btn-secondary" style="flex: 1; text-align: center;">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
