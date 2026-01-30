<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Véhicule - Covoiturage</title>
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
            {{-- Messages de succès --}}
            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom: 1.5rem;">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Messages d'erreur --}}
            @if(session('error'))
                <div class="alert alert-error" style="margin-bottom: 1.5rem;">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Header -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; position: relative; z-index: 30;">
                <h1 style="font-size: 2rem; font-weight: 700; color: var(--primary);">🚗 Mon véhicule</h1>
                <a href="{{ route('vehicule.edit', $vehicule) }}" class="btn btn-primary">
                    ✏️ Modifier
                </a>
            </div>

            <!-- Vehicle Info Grid -->
            <div class="trip-content-grid" style="grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <!-- Photo du véhicule -->
                <div class="detail-card">
                    <div class="card-header">
                        <h2>📷 Photo du véhicule</h2>
                    </div>
                    <div class="card-body">
                        <img src="{{ asset('storage/' . $vehicule->photo) }}"
                             alt="Véhicule"
                             style="width: 100%; height: 300px; object-fit: cover; border-radius: var(--radius); box-shadow: var(--shadow-md);">
                    </div>
                </div>

                <!-- Informations du véhicule -->
                <div class="detail-card">
                    <div class="card-header">
                        <h2>ℹ️ Informations</h2>
                    </div>
                    <div class="card-body">
                        <div style="margin-bottom: 1.5rem;">
                            <p style="font-size: 0.875rem; color: var(--text-light); font-weight: 600;">🔢 Numéro de plaque</p>
                            <p style="font-size: 1.25rem; font-weight: 700; color: var(--primary); margin-top: 0.5rem;">{{ $vehicule->numero_plaque }}</p>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <p style="font-size: 0.875rem; color: var(--text-light); font-weight: 600;">📝 Description</p>
                            <p style="margin-top: 0.5rem; color: var(--text-dark); line-height: 1.6;">{{ $vehicule->description }}</p>
                        </div>

                        <div style="margin-bottom: 0;">
                            <p style="font-size: 0.875rem; color: var(--text-light); font-weight: 600;">📅 Date d'enregistrement</p>
                            <p style="margin-top: 0.5rem; color: var(--text-dark);">{{ $vehicule->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Section -->
            <div class="detail-card" style="margin-bottom: 2rem;">
                <div class="card-header">
                    <h2>📊 Statistiques</h2>
                </div>
                <div class="card-body">
                    <div class="dashboard-grid" style="grid-template-columns: repeat(3, 1fr);">
                        <div style="background: #e3f2fd; padding: 1.5rem; border-radius: var(--radius); text-align: center;">
                            <p style="font-size: 0.875rem; color: var(--primary); font-weight: 600; margin-bottom: 0.5rem;">📈 Total trajets</p>
                            <p style="font-size: 2rem; font-weight: 700; color: var(--primary);">{{ $vehicule->trajets()->count() }}</p>
                        </div>
                        <div style="background: #e8f5e9; padding: 1.5rem; border-radius: var(--radius); text-align: center;">
                            <p style="font-size: 0.875rem; color: #2e7d32; font-weight: 600; margin-bottom: 0.5rem;">🔜 Trajets à venir</p>
                            <p style="font-size: 2rem; font-weight: 700; color: #2e7d32;">{{ $vehicule->trajets()->where('date_trajet', '>', now())->count() }}</p>
                        </div>
                        <div style="background: #f5f5f5; padding: 1.5rem; border-radius: var(--radius); text-align: center;">
                            <p style="font-size: 0.875rem; color: var(--text-dark); font-weight: 600; margin-bottom: 0.5rem;">✅ Trajets passés</p>
                            <p style="font-size: 2rem; font-weight: 700; color: var(--text-dark);">{{ $vehicule->trajets()->where('date_trajet', '<=', now())->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    ← Retour au tableau de bord
                </a>
                <a href="{{ route('trajets.create') }}" class="btn btn-primary">
                    ➕ Créer un trajet
                </a>
                <a href="{{ route('trajets.mes-trajets') }}" class="btn btn-primary">
                    📋 Mes trajets
                </a>
            </div>
        </div>
    </main>
</body>
</html>