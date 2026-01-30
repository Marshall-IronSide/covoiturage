@extends('layouts.app')

@section('content')
    <div class="section">
        <div class="section-header">
            <h1 class="section-title">Mon profil</h1>
        </div>

        <div style="max-width: 600px; margin: 0 auto;">
            <!-- Infos personnelles -->
            <div class="card mb-4">
                <div class="card-header">
                    👤 Informations personnelles
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="card-body">
                    @csrf
                    @method('PATCH')

                    <div class="form-group">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" name="prenom" id="prenom" class="form-control"
                            value="{{ old('prenom', auth()->user()->prenom) }}" required>
                        @error('prenom')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" name="nom" id="nom" class="form-control"
                            value="{{ old('nom', auth()->user()->nom) }}" required>
                        @error('nom')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                            value="{{ old('email', auth()->user()->email) }}" required>
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="tel" name="telephone" id="telephone" class="form-control"
                            value="{{ old('telephone', auth()->user()->telephone) }}">
                        @error('telephone')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div
                        style="display: flex; gap: 1rem; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border);">
                        <button type="submit" class="btn btn-primary">✓ Mettre à jour</button>
                    </div>
                </form>
            </div>

            <!-- Changer le mot de passe -->
            <div class="card mb-4">
                <div class="card-header">
                    🔐 Changer le mot de passe
                </div>

                <form action="{{ route('password.update') }}" method="POST" class="card-body">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="current_password" class="form-label">Mot de passe actuel</label>
                        <input type="password" name="current_password" id="current_password" class="form-control" required>
                        @error('current_password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                            required>
                        @error('password_confirmation')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div
                        style="display: flex; gap: 1rem; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border);">
                        <button type="submit" class="btn btn-primary">✓ Mettre à jour</button>
                    </div>
                </form>
            </div>

            <!-- Supprimer le compte -->
            <div class="card" style="border-left: 4px solid var(--danger);">
                <div class="card-header" style="color: var(--danger);">
                    🗑️ Supprimer le compte
                </div>

                <form action="{{ route('profile.destroy') }}" method="POST"
                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte? Cette action est irréversible.');"
                    class="card-body">
                    @csrf
                    @method('DELETE')

                    <p class="card-text" style="color: var(--text-light); margin-bottom: 1.5rem;">
                        Une fois votre compte supprimé, il n'y a pas de retour en arrière. Veuillez être certain.
                    </p>

                    <div style="display: flex; gap: 1rem;">
                        <button type="submit" class="btn btn-danger">🗑️ Supprimer mon compte</button>
                    </div>
                </form>
            </div>

            <!-- Véhicule -->
            @if (auth()->user()->vehicule)
                <div class="card" style="margin-top: 2rem; border-left: 4px solid var(--primary);">
                    <div class="card-header">
                        🚗 Mon véhicule
                    </div>

                    <form action="{{ route('vehicule.update', auth()->user()->vehicule) }}" method="POST"
                        enctype="multipart/form-data" class="card-body">
                        @csrf
                        @method('PATCH')

                        <!-- Photo du véhicule -->
                        <div class="form-group">
                            <label for="photo" class="form-label">📷 Photo du véhicule</label>
                            @if (auth()->user()->vehicule->photo)
                                <div style="margin-bottom: 1rem;">
                                    <img src="{{ asset('storage/' . auth()->user()->vehicule->photo) }}" alt="Véhicule"
                                        style="max-width: 250px; border-radius: var(--radius); box-shadow: var(--shadow-md);">
                                    <p style="font-size: 0.875rem; color: var(--text-light); margin-top: 0.5rem;">Photo
                                        actuelle</p>
                                </div>
                            @endif
                            <input type="file" name="photo" id="photo" accept="image/*" class="form-control"
                                onchange="previewImage(event)">
                            <div id="imagePreview" style="margin-top: 1rem; display: none;">
                                <img id="preview" src="" alt="Aperçu"
                                    style="max-width: 250px; border-radius: var(--radius); box-shadow: var(--shadow-md);">
                                <p style="font-size: 0.875rem; color: var(--text-light); margin-top: 0.5rem;">Aperçu de la
                                    nouvelle photo</p>
                            </div>
                            @error('photo')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Numéro de plaque -->
                        <div class="form-group">
                            <label for="numero_plaque" class="form-label">🔢 Numéro de plaque</label>
                            <input type="text" name="numero_plaque" id="numero_plaque" class="form-control"
                                value="{{ old('numero_plaque', auth()->user()->vehicule->numero_plaque) }}" required>
                            @error('numero_plaque')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description" class="form-label">📝 Description du véhicule</label>
                            <textarea name="description" id="description" rows="4" class="form-control" required>{{ old('description', auth()->user()->vehicule->description) }}</textarea>
                            <p style="font-size: 0.875rem; color: var(--text-light); margin-top: 0.5rem;">
                                Incluez la marque, le modèle, la couleur et toute particularité utile
                            </p>
                            @error('description')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div
                            style="display: flex; gap: 1rem; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border);">
                            <button type="submit" class="btn btn-primary">✓ Mettre à jour</button>
                            <a href="{{ route('vehicule.show', auth()->user()->vehicule) }}" class="btn btn-secondary"
                                style="text-align: center;">Voir plus</a>
                        </div>
                    </form>
                </div>
            @else
                <div class="card" style="margin-top: 2rem; border-left: 4px solid var(--warning);">
                    <div class="card-header" style="color: var(--warning);">
                        🚗 Véhicule
                    </div>

                    <div class="card-body">
                        <p class="card-text" style="color: var(--text-light); margin-bottom: 1.5rem;">
                            Vous n'avez pas encore enregistré de véhicule. Enregistrez-en un pour pouvoir proposer des
                            trajets.
                        </p>

                        <div style="display: flex; gap: 1rem;">
                            <a href="{{ route('vehicule.create') }}" class="btn btn-primary">🚗 Enregistrer un
                                véhicule</a>
                        </div>
                    </div>
                </div>
            @endif
            </form>
        </div>
    </div>
    </div>

    <style>
        .mb-4 {
            margin-bottom: 2rem;
        }

        .card-body {
            padding: 1.5rem;
        }
    </style>

    <script>
        function previewImage(event) {
            const preview = document.getElementById('preview');
            const previewContainer = document.getElementById('imagePreview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
