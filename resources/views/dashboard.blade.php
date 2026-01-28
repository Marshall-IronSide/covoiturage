<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Covoiturage</title>
    <link rel="stylesheet" href="{{ asset('css/professional.css') }}">
</head>
<body>
    <!-- Navbar -->
    <nav>
        <div class="navbar-container">
            <a href="{{ route('dashboard') }}" class="navbar-brand">
                🚗 Covoiturage
            </a>
            <ul class="navbar-nav">
                <li><a href="{{ route('trajets.index') }}" class="nav-link">Tous les trajets</a></li>
                <li><a href="{{ route('trajets.create') }}" class="nav-link">Créer un trajet</a></li>
                <li><a href="{{ route('reservations.index') }}" class="nav-link">Mes réservations</a></li>
                <li class="dropdown-menu">
                    <a href="#" class="nav-link">{{ Auth::user()->name }}</a>
                    <div class="dropdown-content">
                        <a href="{{ route('profile.edit') }}">Mon profil</a>
                        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                            @csrf
                            <a href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); this.closest('form').submit();">
                                Déconnexion
                            </a>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <div class="container">
            
            <!-- Welcome Section -->
            <div class="card" style="margin-bottom: 2rem;">
                <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">
                    Bienvenue, {{ Auth::user()->name }} ! 👋
                </h3>
                <p style="color: var(--text-light);">Voici un aperçu de votre activité de covoiturage.</p>
            </div>

            <!-- Statistics Cards -->
            <div class="dashboard-grid">
                <div class="stat-card">
                    <div class="stat-label">Trajets créés</div>
                    <div class="stat-value">{{ $stats['trajets_crees'] }}</div>
                </div>

                <div class="stat-card" style="border-left-color: var(--success);">
                    <div class="stat-label">Réservations reçues</div>
                    <div class="stat-value" style="color: var(--success);">{{ $stats['reservations_recues'] }}</div>
                </div>

                <div class="stat-card" style="border-left-color: var(--warning);">
                    <div class="stat-label">Mes réservations</div>
                    <div class="stat-value" style="color: var(--warning);">{{ $stats['mes_reservations'] }}</div>
                    <p style="font-size: 0.875rem; color: var(--text-light); margin-top: 0.5rem;">
                        {{ $stats['reservations_confirmees'] }} confirmées
                    </p>
                </div>

                <div class="stat-card" style="border-left-color: #8b5cf6;">
                    <div class="stat-label">Places partagées</div>
                    <div class="stat-value" style="color: #8b5cf6;">{{ $stats['trajets_crees'] * 3 }}</div>
                    <p style="font-size: 0.875rem; color: var(--text-light); margin-top: 0.5rem;">Estimation</p>
                </div>
            </div>

            <!-- Two Column Layout -->
            <div class="grid grid-2" style="margin-bottom: 2rem;">
                
                <!-- Recent Trips -->
                <div class="card">
                    <div class="card-header">Mes derniers trajets</div>
                    
                    @if($mesTrajets->count() > 0)
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            @foreach($mesTrajets as $trajet)
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: var(--light); border-radius: var(--radius);">
                                    <div>
                                        <div style="font-weight: 600;">
                                            {{ $trajet->ville_depart }} → {{ $trajet->ville_arrivee }}
                                        </div>
                                        <div style="font-size: 0.875rem; color: var(--text-light);">
                                            {{ \Carbon\Carbon::parse($trajet->date_depart)->format('d M Y à H:i') }}
                                        </div>
                                    </div>
                                    @php
                                        $placesRestantes = $trajet->places_disponibles - $trajet->reservations()->where('statut', 'confirmee')->count();
                                    @endphp
                                    <span class="badge {{ $placesRestantes > 1 ? 'badge-success' : ($placesRestantes == 1 ? 'badge-warning' : 'badge-primary') }}">
                                        {{ $placesRestantes }}/{{ $trajet->places_disponibles }} places
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 2rem; color: var(--text-light); font-style: italic;">
                            Aucun trajet créé pour le moment
                        </div>
                    @endif
                    
                    <div style="margin-top: 1.5rem;">
                        <a href="{{ route('trajets.create') }}" class="btn btn-primary" style="width: 100%;">
                            + Créer un nouveau trajet
                        </a>
                    </div>
                </div>

                <!-- Recent Reservations -->
                <div class="card">
                    <div class="card-header">Réservations récentes</div>
                    
                    @if($reservationsRecues->count() > 0)
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            @foreach($reservationsRecues as $reservation)
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: var(--light); border-radius: var(--radius);">
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div class="driver-avatar">
                                            {{ strtoupper(substr($reservation->passager->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight: 600;">{{ $reservation->passager->name }}</div>
                                            <div style="font-size: 0.875rem; color: var(--text-light);">
                                                {{ $reservation->trajet->ville_depart }} → {{ $reservation->trajet->ville_arrivee }}
                                            </div>
                                        </div>
                                    </div>
                                    <span class="badge {{ $reservation->statut == 'confirmee' ? 'badge-success' : 'badge-warning' }}">
                                        {{ ucfirst($reservation->statut) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 2rem; color: var(--text-light); font-style: italic;">
                            Aucune réservation pour vos trajets
                        </div>
                    @endif
                    
                    <div style="margin-top: 1.5rem;">
                        <a href="{{ route('reservations.index') }}" class="btn btn-secondary" style="width: 100%;">
                            Voir toutes mes réservations
                        </a>
                    </div>
                </div>

            </div>

            <!-- Quick Actions -->
            <div class="card" style="margin-bottom: 2rem;">
                <div class="card-header">Actions rapides</div>
                <div class="grid grid-3">
                    
                    <a href="{{ route('trajets.index') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 1.5rem; background: #eff6ff; border-radius: var(--radius); text-decoration: none; color: inherit; transition: all 0.3s;">
                        <div style="font-size: 2.5rem;">🔍</div>
                        <div>
                            <div style="font-weight: 600; color: var(--text-dark);">Rechercher un trajet</div>
                            <div style="font-size: 0.875rem; color: var(--text-light);">Trouvez votre prochain covoiturage</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('trajets.create') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 1.5rem; background: #f0fdf4; border-radius: var(--radius); text-decoration: none; color: inherit; transition: all 0.3s;">
                        <div style="font-size: 2.5rem;">➕</div>
                        <div>
                            <div style="font-weight: 600; color: var(--text-dark);">Proposer un trajet</div>
                            <div style="font-size: 0.875rem; color: var(--text-light);">Partagez votre véhicule</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('profile.edit') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 1.5rem; background: #faf5ff; border-radius: var(--radius); text-decoration: none; color: inherit; transition: all 0.3s;">
                        <div style="font-size: 2.5rem;">👤</div>
                        <div>
                            <div style="font-weight: 600; color: var(--text-dark);">Mon profil</div>
                            <div style="font-size: 0.875rem; color: var(--text-light);">Gérer mes informations</div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Tips Section -->
            <div class="alert alert-info">
                <strong>💡 Astuce du jour :</strong> Créez vos trajets à l'avance pour maximiser vos chances de trouver des passagers !
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; text-align: center; padding: 2rem; margin-top: 4rem;">
        <p>&copy; 2026 Covoiturage. Tous droits réservés.</p>
    </footer>

</body>
</html>