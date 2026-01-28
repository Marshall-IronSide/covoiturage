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
                <li><a href="{{ route('register') }}" class="nav-btn" style="background: rgba(255, 255, 255, 0.2); border: 2px solid white; color: white;">S'inscrire</a></li>
            @endauth
        </ul>
    </div>
</nav>
