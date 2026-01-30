<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="auth-status">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <h2 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 1.5rem; text-align: center;">
            Connexion
        </h2>

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">Adresse email</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            @error('email')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Mot de passe</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
            @error('password')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="checkbox-group">
            <input id="remember_me" type="checkbox" name="remember">
            <label for="remember_me">Se souvenir de moi</label>
        </div>

        <div class="form-footer">
            <button type="submit" class="btn btn-primary">
                Se connecter
            </button>

            @if (Route::has('password.request'))
                <div class="form-link">
                    <a href="{{ route('password.request') }}">
                        Mot de passe oublié?
                    </a>
                </div>
            @endif

            <div class="form-link">
                Pas encore inscrit? <a href="{{ route('register') }}">Créer un compte</a>
            </div>
        </div>
    </form>
</x-guest-layout>
