<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    public function confirm(User $user, Reservation $reservation): bool
    {
        return $user->id === $reservation->trajet->conducteur_id;
    }

    public function cancel(User $user, Reservation $reservation): bool
    {
        return $user->id === $reservation->passager_id || $user->id === $reservation->trajet->conducteur_id;
    }
}
