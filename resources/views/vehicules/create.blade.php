<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrer mon véhicule - Covoiturage</title>
    <link rel="stylesheet" href="{{ asset('css/professional.css') }}">
</head>
<body>
    <!-- Navbar -->
    <nav>
        <div class="navbar-container">
            <a href="{{ route('trajets.index') }}" class="navbar-brand">
                🚗 Covoiturage
            </a>
            <ul class="navbar-nav">
                <li><a href="{{ route('trajets.index') }}" class="nav-link">Tous les trajets</a></li>
                @auth
                    <li><a href="{{ route('reservations.index') }}" class="nav-link">Mes réservations</a></li>
                    <li><a href="{{ route('trajets.create') }}" class="nav-link">Créer un trajet</a></li>
                    <li class="dropdown-menu">
                        <a href="#" class="nav-link">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</a>
                        <div class="dropdown-content">
                            <a href="{{ route('profile.edit') }}">Mon profil</a>
                            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                @csrf
                                <button type="submit" style="width: 100%; text-align: left; background: none; border: none; padding: 1rem 1.5rem; cursor: pointer; color: var(--text-dark);">Déconnexion</button>
                            </form>
                        </div>
                    </li>
                @else
                    <li><a href="{{ route('login') }}" class="nav-btn">Connexion</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <div class="container">
            <div class="card" style="max-width: 600px; margin: 3rem auto;">
                <h1 style="font-size: 2rem; margin-bottom: 1rem; color: var(--primary);">🚗 Enregistrer mon véhicule</h1>
                
                @if(session('error'))
                    <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
                        {{ session('error') }}
                    </div>
                @endif

                <div style="background: #eff6ff; border-left: 4px solid var(--primary); padding: 1rem; margin-bottom: 2rem; border-radius: 4px;">
                    <p style="color: var(--primary); font-weight: 500;">
                        <strong>ℹ️ Information importante :</strong> Vous devez enregistrer un véhicule pour pouvoir proposer des trajets. Vous ne pouvez enregistrer qu'un seul véhicule.
                    </p>
                </div>

                <form method="POST" action="{{ route('vehicule.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Photo du véhicule -->
                    <div class="form-group">
                        <label for="photo" class="form-label">📷 Photo du véhicule <span style="color: var(--danger);">*</span></label>
                        <input type="file" 
                               name="photo" 
                               id="photo" 
                               accept="image/*" 
                               required
                               onchange="previewImage(event)"
                               class="form-control">
                        <p style="font-size: 0.875rem; color: var(--text-light); margin-top: 0.5rem;">
                            Formats acceptés : JPG, PNG, GIF (max 2MB)
                        </p>
                        @error('photo')
                            <p style="color: var(--danger); font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                        @enderror

                        <!-- Prévisualisation de l'image -->
                        <div id="imagePreview" style="margin-top: 1rem; display: none;">
                            <img id="preview" src="" alt="Aperçu" style="max-width: 100%; max-height: 250px; border-radius: var(--radius); box-shadow: var(--shadow-md);">
                        </div>
                    </div>

                    <!-- Numéro de plaque -->
                    <div class="form-group">
                        <label for="numero_plaque" class="form-label">🔢 Numéro de plaque <span style="color: var(--danger);">*</span></label>
                        <input type="text" 
                               name="numero_plaque" 
                               id="numero_plaque" 
                               value="{{ old('numero_plaque') }}" 
                               required
                               placeholder="Ex: AB-123-CD"
                               class="form-control">
                        @error('numero_plaque')
                            <p style="color: var(--danger); font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description" class="form-label">📝 Description du véhicule <span style="color: var(--danger);">*</span></label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4" 
                                  required
                                  placeholder="Ex: Toyota Corolla 2020, couleur blanche, 5 places, climatisation"
                                  class="form-control">{{ old('description') }}</textarea>
                        <p style="font-size: 0.875rem; color: var(--text-light); margin-top: 0.5rem;">
                            Incluez la marque, le modèle, la couleur et toute particularité utile
                        </p>
                        @error('description')
                            <p style="color: var(--danger); font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Boutons -->
                    <div style="display: flex; gap: 1rem; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border);">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">✅ Enregistrer le véhicule</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary" style="flex: 1; text-align: center;">❌ Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; padding: 3rem 0; text-align: center; margin-top: 3rem;">
        <p>&copy; 2026 Covoiturage. Tous droits réservés.</p>
    </footer>

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
</body>
</html>