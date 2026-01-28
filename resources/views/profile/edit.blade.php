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
                    <input type="text" name="prenom" id="prenom" class="form-control" value="{{ old('prenom', auth()->user()->prenom) }}" required>
                    @error('prenom')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom', auth()->user()->nom) }}" required>
                    @error('nom')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                    @error('email')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="telephone" class="form-label">Téléphone</label>
                    <input type="tel" name="telephone" id="telephone" class="form-control" value="{{ old('telephone', auth()->user()->telephone) }}">
                    @error('telephone')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border);">
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
                    @error('current_password')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                    @error('password')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                    @error('password_confirmation')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border);">
                    <button type="submit" class="btn btn-primary">✓ Mettre à jour</button>
                </div>
            </form>
        </div>

        <!-- Supprimer le compte -->
        <div class="card" style="border-left: 4px solid var(--danger);">
            <div class="card-header" style="color: var(--danger);">
                🗑️ Supprimer le compte
            </div>

            <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte? Cette action est irréversible.');" class="card-body">
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
@endsection
