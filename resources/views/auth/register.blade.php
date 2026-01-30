<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <h2 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 1.5rem; text-align: center;">
            Créer un compte
        </h2>

        <!-- Prénom -->
        <div class="form-group">
            <label for="prenom" class="form-label">Prénom</label>
            <input id="prenom" class="form-control" type="text" name="prenom" value="{{ old('prenom') }}" required autofocus />
            @error('prenom')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <!-- Nom -->
        <div class="form-group">
            <label for="nom" class="form-label">Nom</label>
            <input id="nom" class="form-control" type="text" name="nom" value="{{ old('nom') }}" required />
            @error('nom')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">Adresse email</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
            @error('email')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <!-- Téléphone -->
        <div class="form-group">
            <label for="telephone" class="form-label">Téléphone (optionnel)</label>
            <input id="telephone" class="form-control" type="tel" name="telephone" value="{{ old('telephone') }}" />
            @error('telephone')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Mot de passe</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
            @error('password')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
            @error('password_confirmation')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-footer">
            <button type="submit" class="btn btn-primary">
                S'inscrire
            </button>

            <div class="form-link">
                Déjà inscrit? <a href="{{ route('login') }}">Se connecter</a>
            </div>
        </div>
    </form>
</x-guest-layout>
