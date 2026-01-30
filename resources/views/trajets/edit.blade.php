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
                <h3
                    style="margin-top: 2rem; color: var(--primary); border-bottom: 2px solid var(--border); padding-bottom: 1rem;">
                    📍 Lieu de départ</h3>

                <div class="form-group">
                    <label for="ville_depart" class="form-label">Ville de départ</label>
                    <input type="text" name="ville_depart" id="ville_depart" class="form-control"
                        value="{{ old('ville_depart', $trajet->ville_depart) }}" required>
                    @error('ville_depart')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description_depart" class="form-label">Description précise du lieu</label>
                    <textarea name="description_depart" id="description_depart" class="form-control" rows="3" required>{{ old('description_depart', $trajet->description_depart) }}</textarea>
                    @error('description_depart')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Lieu d'arrivée -->
                <h3
                    style="margin-top: 2rem; color: var(--primary); border-bottom: 2px solid var(--border); padding-bottom: 1rem;">
                    📍 Lieu d'arrivée</h3>

                <div class="form-group">
                    <label for="ville_arrivee" class="form-label">Ville d'arrivée</label>
                    <input type="text" name="ville_arrivee" id="ville_arrivee" class="form-control"
                        value="{{ old('ville_arrivee', $trajet->ville_arrivee) }}" required>
                    @error('ville_arrivee')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description_arrivee" class="form-label">Description précise du lieu</label>
                    <textarea name="description_arrivee" id="description_arrivee" class="form-control" rows="3" required>{{ old('description_arrivee', $trajet->description_arrivee) }}</textarea>
                    @error('description_arrivee')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Informations du trajet -->
                <h3
                    style="margin-top: 2rem; color: var(--primary); border-bottom: 2px solid var(--border); padding-bottom: 1rem;">
                    🚗 Informations du trajet</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="date_trajet" class="form-label">Date et heure</label>
                        <input type="datetime-local" name="date_trajet" id="date_trajet" class="form-control"
                            value="{{ old('date_trajet', $trajet->date_trajet->format('Y-m-d\TH:i')) }}" required>
                        @error('date_trajet')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="places_disponibles" class="form-label">Nombre de places</label>
                        <input type="number" name="places_disponibles" id="places_disponibles" class="form-control"
                            value="{{ old('places_disponibles', $trajet->places_disponibles) }}" min="1"
                            max="10" required>
                        @error('places_disponibles')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Informations véhicule -->
                <h3
                    style="margin-top: 2rem; color: var(--primary); border-bottom: 2px solid var(--border); padding-bottom: 1rem;">
                    🚗 Véhicule assigné</h3>

                @if ($trajet->vehicule)
                    <div class="form-group"
                        style="background: #f9f9f9; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                        <div style="font-weight: 600; color: var(--primary); margin-bottom: 0.5rem;">
                            {{ $trajet->vehicule->numero_plaque }}</div>
                        <div style="color: #666; margin-bottom: 0.5rem;">{{ $trajet->vehicule->description }}</div>
                        @if ($trajet->vehicule->photo)
                            <img src="{{ asset('storage/' . $trajet->vehicule->photo) }}" alt="Véhicule"
                                style="max-width: 200px; border-radius: 0.5rem;">
                        @endif
                        <div style="margin-top: 1rem; font-size: 0.9rem; color: #888;">
                            ℹ️ Ce véhicule ne peut pas être modifié depuis cette page. Modifiez-le dans votre profil si
                            nécessaire.
                        </div>
                    </div>
                @else
                    <div class="form-group">
                        <label for="vehicule_id" class="form-label">Sélectionner un véhicule</label>
                        @if ($vehicules->count() > 0)
                            <select name="vehicule_id" id="vehicule_id" class="form-control">
                                <option value="">-- Choisir un véhicule --</option>
                                @foreach ($vehicules as $vehicule)
                                    <option value="{{ $vehicule->id }}"
                                        {{ old('vehicule_id', $trajet->vehicule_id) == $vehicule->id ? 'selected' : '' }}>
                                        {{ $vehicule->numero_plaque }} - {{ $vehicule->description }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vehicule_id')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                            <p style="font-size: 0.875rem; color: var(--text-light); margin-top: 0.5rem;">
                                ℹ️ Sélectionnez le véhicule à utiliser pour ce trajet
                            </p>
                        @else
                            <div style="background: #ffe0e0; padding: 1rem; border-radius: 0.5rem;">
                                ❌ Aucun véhicule disponible. Enregistrez d'abord un véhicule.
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Actions -->
                <div
                    style="display: flex; gap: 1rem; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border);">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">✓ Mettre à jour</button>
                    <a href="{{ route('trajets.show', $trajet) }}" class="btn btn-secondary"
                        style="flex: 1; text-align: center;">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
