<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du trajet - Covoiturage</title>
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
                @auth
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
                @else
                    <li><a href="{{ route('login') }}" class="nav-link">Connexion</a></li>
                    <li><a href="{{ route('register') }}" class="nav-link">Inscription</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <div class="container">
            <!-- Trip Header -->
            <div style="display: flex; align-items: center; gap: 2rem; margin-bottom: 3rem; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; padding: 2rem; border-radius: var(--radius);">
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 1rem; font-size: 1.5rem; margin-bottom: 1rem;">
                        <span style="font-size: 1.25rem;">📍</span>
                        <span style="font-weight: 700;">{{ $trajet->ville_depart }}</span>
                        <span style="font-size: 1.25rem;">→</span>
                        <span style="font-weight: 700;">{{ $trajet->ville_arrivee }}</span>
                    </div>
                    <div style="display: flex; gap: 2rem; opacity: 0.95;">
                        <div>📅 {{ $trajet->date_trajet->format('d/m/Y à H:i') }}</div>
                        <div>💺 {{ $trajet->places_disponibles }} place(s)</div>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="trip-content-grid">
                <!-- Left Column - Trip Details -->
                <div>
                    <!-- Trip Details Card -->
                    <div class="detail-card">
                        <div class="card-header">
                            <h2>📍 Détails du trajet</h2>
                        </div>
                        <div class="card-body">
                            <div style="margin-bottom: 1.5rem;">
                                <p style="font-size: 0.875rem; color: var(--text-light); font-weight: 600; margin-bottom: 0.5rem;">Lieu de départ</p>
                                <p style="font-size: 1.25rem; font-weight: 700; color: var(--primary);">{{ $trajet->ville_depart }}</p>
                                <p style="color: var(--text-dark); margin-top: 0.5rem;">{{ $trajet->description_depart }}</p>
                            </div>
                            <hr style="border: none; border-top: 2px solid var(--border); margin: 2rem 0;">
                            <div>
                                <p style="font-size: 0.875rem; color: var(--text-light); font-weight: 600; margin-bottom: 0.5rem;">Lieu d'arrivée</p>
                                <p style="font-size: 1.25rem; font-weight: 700; color: var(--primary);">{{ $trajet->ville_arrivee }}</p>
                                <p style="color: var(--text-dark); margin-top: 0.5rem;">{{ $trajet->description_arrivee }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Card -->
                    @if($trajet->vehicule)
                    <div class="detail-card">
                        <div class="card-header">
                            <h2>🚗 Véhicule</h2>
                        </div>
                        <div class="card-body">
                            @if ($trajet->vehicule->photo)
                                <div style="margin-bottom: 1.5rem;">
                                    <img src="{{ asset('storage/' . $trajet->vehicule->photo) }}" alt="Véhicule" style="width: 100%; height: 250px; object-fit: cover; border-radius: var(--radius); box-shadow: var(--shadow-md);">
                                </div>
                            @endif
                            <div>
                                <p style="font-weight: 600; color: var(--primary); font-size: 1.1rem;">{{ $trajet->vehicule->numero_plaque }}</p>
                                <p style="color: var(--text-dark); margin-top: 0.5rem;">{{ $trajet->vehicule->description }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Driver Card -->
                    <div class="detail-card">
                        <div class="card-header">
                            <h2>👤 Conducteur</h2>
                        </div>
                        <div class="card-body">
                            <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
                                <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.5rem;">
                                    {{ strtoupper(substr($trajet->conducteur->prenom, 0, 1)) }}
                                </div>
                                <div>
                                    <p style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">{{ $trajet->conducteur->prenom }} {{ $trajet->conducteur->nom }}</p>
                                    <p style="color: var(--text-light); font-size: 0.875rem;">Conducteur</p>
                                </div>
                            </div>
                            <div style="border-top: 1px solid var(--border); padding-top: 1rem;">
                                <p style="color: var(--text-light); font-size: 0.875rem; margin-bottom: 0.5rem;">📞 Téléphone</p>
                                <p style="font-weight: 500;">{{ $trajet->conducteur->telephone ?? 'Non renseigné' }}</p>
                            </div>
                            <div style="border-top: 1px solid var(--border); padding-top: 1rem; margin-top: 1rem;">
                                <p style="color: var(--text-light); font-size: 0.875rem; margin-bottom: 0.5rem;">✉️ Email</p>
                                <p style="font-weight: 500;">{{ $trajet->conducteur->email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Reservations Card (if driver viewing own trip) -->
                    @if ($trajet->reservations->count() > 0 && auth()->id() === $trajet->conducteur_id)
                        <div class="detail-card">
                            <div class="card-header">
                                <h2>👥 Réservations ({{ $trajet->reservations->count() }})</h2>
                            </div>
                            <div class="card-body">
                                @foreach ($trajet->reservations as $reservation)
                                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border: 1px solid var(--border); border-radius: var(--radius); margin-bottom: 1rem;">
                                        <div style="display: flex; gap: 1rem; align-items: center; flex: 1;">
                                            <div style="width: 50px; height: 50px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                                {{ substr($reservation->passager->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p style="font-weight: 600;">{{ $reservation->passager->name }}</p>
                                                <p style="font-size: 0.875rem; color: var(--text-light);">{{ $reservation->nombre_places }} place(s) • <span style="color: #666;">{{ ucfirst(str_replace('_', ' ', $reservation->statut)) }}</span></p>
                                            </div>
                                        </div>
                                        @if ($reservation->statut === 'en_attente')
                                            <form action="{{ route('reservations.confirm', $reservation) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <button type="submit" class="btn btn-primary">Confirmer</button>
                                            </form>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column - Booking Sidebar -->
                <aside>
                    <!-- Booking Card -->
                    <div class="detail-card">
                        <div class="card-header">
                            <h2>📋 Réservation</h2>
                        </div>
                        <div class="card-body">
                            <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--border);">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                                    <span style="color: var(--text-light); font-size: 0.875rem;">📅 Date et heure</span>
                                    <span style="font-weight: 600;">{{ $trajet->date_trajet->format('d/m/Y à H:i') }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span style="color: var(--text-light); font-size: 0.875rem;">💺 Places disponibles</span>
                                    <span style="font-weight: 600; font-size: 1.25rem; color: var(--primary);">{{ $trajet->places_disponibles }}</span>
                                </div>
                            </div>

                            @auth
                                @if (auth()->id() === $trajet->conducteur_id)
                                    <!-- Owner Actions -->
                                    <a href="{{ route('trajets.edit', $trajet) }}" class="btn btn-primary" style="width: 100%; margin-bottom: 0.5rem;">
                                        ✏️ Modifier le trajet
                                    </a>
                                    <form action="{{ route('trajets.destroy', $trajet) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" style="width: 100%;" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce trajet ?')">
                                            🗑️ Supprimer
                                        </button>
                                    </form>
                                @elseif ($trajet->places_disponibles > 0)
                                    <!-- Booking Form -->
                                    <form action="{{ route('reservations.store', $trajet) }}" method="POST">
                                        @csrf
                                        <div style="margin-bottom: 1rem;">
                                            <label for="nombre_places" style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem;">Nombre de places</label>
                                            <select name="nombre_places" id="nombre_places" class="form-control" required>
                                                @for ($i = 1; $i <= $trajet->places_disponibles; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-success" style="width: 100%;">
                                            ✓ Réserver maintenant
                                        </button>
                                    </form>
                                @else
                                    <!-- Full Booking -->
                                    <div style="text-align: center; padding: 1rem; background: #f5f5f5; border-radius: var(--radius);">
                                        <p style="font-size: 1.5rem; margin-bottom: 0.5rem;">❌</p>
                                        <p style="color: var(--text-light);">Ce trajet est complet</p>
                                    </div>
                                @endif
                            @else
                                <!-- Login Prompt -->
                                <a href="{{ route('login') }}" class="btn btn-success" style="width: 100%;">
                                    🔐 Se connecter pour réserver
                                </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="detail-card">
                        <div class="card-header">
                            <h2>💡 Bon à savoir</h2>
                        </div>
                        <div class="card-body">
                            <ul style="list-style: none; padding: 0; margin: 0;">
                                <li style="padding: 0.5rem 0; color: var(--text-dark);">✓ Soyez ponctuel au point de rendez-vous</li>
                                <li style="padding: 0.5rem 0; color: var(--text-dark);">✓ Contactez le conducteur en cas d'imprévu</li>
                                <li style="padding: 0.5rem 0; color: var(--text-dark);">✓ Respectez les règles de courtoisie</li>
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>
</body>
</html>