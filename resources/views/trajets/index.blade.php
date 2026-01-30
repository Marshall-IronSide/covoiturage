@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-content">
            <div class="hero-badge">🚗 Covoiturage Intelligent</div>
            <h1 class="hero-title">Trajets disponibles</h1>
            <p class="hero-description">Découvrez les meilleurs trajets près de chez vous et économisez en partageant le
                covoiturage</p>
            @auth
                <a href="{{ route('trajets.create') }}" class="hero-btn">
                    <span class="btn-icon">+</span>
                    Créer un trajet
                </a>
            @else
                <a href="{{ route('login') }}" class="hero-btn">
                    <span class="btn-icon">→</span>
                    Se connecter
                </a>
            @endauth
        </div>
        <div class="hero-decoration">
            <div class="decoration-circle decoration-circle-1"></div>
            <div class="decoration-circle decoration-circle-2"></div>
            <div class="decoration-circle decoration-circle-3"></div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success-modern">
            <div class="alert-icon">✓</div>
            <div class="alert-content">{{ session('success') }}</div>
        </div>
    @endif

    @if ($trajets->isEmpty())
        <div class="empty-state-modern">
            <div class="empty-icon">🚗</div>
            <h2 class="empty-title">Aucun trajet disponible</h2>
            <p class="empty-text">Soyez le premier à créer un trajet et commencez à économiser !</p>
            @auth
                <a href="{{ route('trajets.create') }}" class="btn btn-primary-modern">Créer le premier trajet</a>
            @endauth
        </div>
    @else
        <div class="trajets-container">
            <div class="section-header">
                <h2 class="section-title">Tous les trajets</h2>
                <div class="section-count">{{ $trajets->count() }} trajets</div>
            </div>

            <div class="grid grid-3">
                @foreach ($trajets as $trajet)
                    <article class="trajet-card-modern">
                        <div class="card-image-wrapper">
                            @if ($trajet->vehicule && $trajet->vehicule->photo)
                                <img src="{{ asset('storage/' . $trajet->vehicule->photo) }}" alt="Vehicle"
                                    class="card-image">
                                <div class="image-overlay"></div>
                            @else
                                <div class="card-image card-image-placeholder">
                                    <div class="placeholder-icon">🚗</div>
                                </div>
                            @endif

                            @if ($trajet->places_disponibles > 0)
                                <div class="availability-badge available">{{ $trajet->places_disponibles }} places</div>
                            @else
                                <div class="availability-badge full">Complet</div>
                            @endif
                        </div>

                        <div class="card-content">
                            <div class="route-header">
                                <div class="route-item">
                                    <div class="route-dot route-dot-start"></div>
                                    <div class="route-city">{{ $trajet->ville_depart }}</div>
                                </div>
                                <div class="route-connector">
                                    <div class="route-line"></div>
                                    <div class="route-arrow">→</div>
                                </div>
                                <div class="route-item">
                                    <div class="route-dot route-dot-end"></div>
                                    <div class="route-city">{{ $trajet->ville_arrivee }}</div>
                                </div>
                            </div>

                            <div class="route-details">
                                <div class="detail-row">
                                    <span class="detail-label">Départ:</span>
                                    <span class="detail-value">{{ $trajet->description_depart }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Arrivée:</span>
                                    <span class="detail-value">{{ $trajet->description_arrivee }}</span>
                                </div>
                            </div>

                            <div class="driver-section">
                                <div class="driver-avatar-modern">
                                    {{ strtoupper(substr($trajet->conducteur->prenom, 0, 1)) }}</div>
                                <div class="driver-details">
                                    <div class="driver-name-modern">{{ $trajet->conducteur->prenom }}
                                        {{ $trajet->conducteur->nom }}</div>
                                    @if ($trajet->vehicule)
                                        <div class="vehicle-info">
                                            <span class="vehicle-icon">🚗</span>
                                            {{ $trajet->vehicule->numero_plaque }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="card-actions">
                                <a href="{{ route('trajets.show', $trajet) }}" class="action-btn action-btn-primary">
                                    <span class="btn-text">Détails</span>
                                    <span class="btn-arrow">→</span>
                                </a>

                                @auth
                                    @if (auth()->id() === $trajet->conducteur_id)
                                        <a href="{{ route('trajets.edit', $trajet) }}" class="action-btn action-btn-warning">
                                            <span class="btn-text">Éditer</span>
                                        </a>
                                        <form action="{{ route('trajets.destroy', $trajet) }}" method="POST"
                                            class="action-form" onsubmit="return confirm('Confirmer la suppression?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="action-btn action-btn-danger">
                                                <span class="btn-text">Supprimer</span>
                                            </button>
                                        </form>
                                    @else
                                        @if ($trajet->places_disponibles > 0)
                                            <button class="action-btn action-btn-success"
                                                onclick="openReservationModal({{ $trajet->id }}, {{ $trajet->places_disponibles }})">
                                                <span class="btn-text">Réserver</span>
                                                <span class="btn-arrow">✓</span>
                                            </button>
                                        @else
                                            <button class="action-btn action-btn-disabled" disabled>
                                                <span class="btn-text">Complet</span>
                                            </button>
                                        @endif
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="action-btn action-btn-success">
                                        <span class="btn-text">Réserver</span>
                                        <span class="btn-arrow">→</span>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </article>

                    <!-- Reservation Modal for this trip -->
                    <div id="modal-{{ $trajet->id }}" class="reservation-modal">
                        <div class="modal-overlay" onclick="closeReservationModal({{ $trajet->id }})"></div>
                        <div class="modal-content">
                            <button class="modal-close"
                                onclick="closeReservationModal({{ $trajet->id }})">&times;</button>

                            <h2 class="modal-title">Réserver des places</h2>

                            <div class="modal-trip-info">
                                <div class="info-row">
                                    <span class="info-label">Trajet:</span>
                                    <span class="info-value">{{ $trajet->ville_depart }} →
                                        {{ $trajet->ville_arrivee }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Places disponibles:</span>
                                    <span class="info-value">{{ $trajet->places_disponibles }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Conducteur:</span>
                                    <span class="info-value">{{ $trajet->conducteur->prenom }}
                                        {{ $trajet->conducteur->nom }}</span>
                                </div>
                            </div>

                            <form action="{{ route('reservations.store', $trajet) }}" method="POST" class="modal-form">
                                @csrf

                                <div class="form-group">
                                    <label for="nombre_places-{{ $trajet->id }}" class="form-label">Nombre de
                                        places:</label>
                                    <div class="seat-selector">
                                        <button type="button" class="seat-btn"
                                            onclick="decrementSeats({{ $trajet->id }})">−</button>
                                        <input type="number" id="nombre_places-{{ $trajet->id }}"
                                            name="nombre_places" value="1" min="1"
                                            max="{{ $trajet->places_disponibles }}" class="seat-input" readonly>
                                        <button type="button" class="seat-btn"
                                            onclick="incrementSeats({{ $trajet->id }}, {{ $trajet->places_disponibles }})">+</button>
                                    </div>
                                    <p class="seat-hint">Sélectionnez le nombre de places à réserver (max:
                                        {{ $trajet->places_disponibles }})</p>
                                </div>

                                <div class="modal-actions">
                                    <button type="button" class="btn btn-secondary"
                                        onclick="closeReservationModal({{ $trajet->id }})">
                                        Annuler
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        Confirmer la réservation
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <style>
        /* Reservation Modal Styles */
        .reservation-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .reservation-modal.active {
            display: flex;
        }

        .modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            cursor: pointer;
        }

        .modal-content {
            position: relative;
            background: white;
            border-radius: 12px;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 25px rgba(0, 0, 0, 0.15);
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6b7280;
            padding: 0;
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            color: #1f2937;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1.5rem;
            margin-top: 0;
        }

        .modal-trip-info {
            background: #f3f4f6;
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-weight: 600;
            color: #6b7280;
            font-size: 0.9rem;
        }

        .info-value {
            color: #1f2937;
            font-weight: 500;
        }

        .modal-form {
            margin-bottom: 0;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }

        .seat-selector {
            display: flex;
            align-items: center;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 0.75rem;
        }

        .seat-btn {
            background: #e5e7eb;
            border: none;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 8px;
            font-size: 1.25rem;
            font-weight: 700;
            cursor: pointer;
            color: #1f2937;
            transition: all 0.2s ease;
        }

        .seat-btn:hover {
            background: #d1d5db;
            transform: scale(1.05);
        }

        .seat-btn:active {
            transform: scale(0.95);
        }

        .seat-input {
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            width: 4rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            color: #1e40af;
            background: white;
        }

        .seat-hint {
            font-size: 0.85rem;
            color: #6b7280;
            text-align: center;
            margin: 0;
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .modal-actions .btn {
            flex: 1;
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .modal-actions .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .modal-actions .btn-secondary:hover {
            background: #d1d5db;
        }

        .modal-actions .btn-primary {
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
            color: white;
        }

        .modal-actions .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(30, 64, 175, 0.3);
        }

        @media (max-width: 640px) {
            .modal-content {
                padding: 1.5rem;
                width: 95%;
            }

            .modal-title {
                font-size: 1.25rem;
            }
        }
    </style>

    <script>
        function openReservationModal(trajetId, maxPlaces) {
            const modal = document.getElementById('modal-' + trajetId);
            if (modal) {
                modal.classList.add('active');
                // Reset to 1 place when opening
                document.getElementById('nombre_places-' + trajetId).value = 1;
            }
        }

        function closeReservationModal(trajetId) {
            const modal = document.getElementById('modal-' + trajetId);
            if (modal) {
                modal.classList.remove('active');
            }
        }

        function incrementSeats(trajetId, maxPlaces) {
            const input = document.getElementById('nombre_places-' + trajetId);
            const current = parseInt(input.value) || 1;
            if (current < maxPlaces) {
                input.value = current + 1;
            }
        }

        function decrementSeats(trajetId) {
            const input = document.getElementById('nombre_places-' + trajetId);
            const current = parseInt(input.value) || 1;
            if (current > 1) {
                input.value = current - 1;
            }
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                event.target.parentElement.classList.remove('active');
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.reservation-modal.active').forEach(modal => {
                    modal.classList.remove('active');
                });
            }
        });
    </script>
@endsection
