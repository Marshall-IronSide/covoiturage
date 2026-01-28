<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trajet extends Model
{
    protected $table = 'trajets';

    protected $fillable = [
        'conducteur_id',
        'ville_depart',
        'description_depart',
        'ville_arrivee',
        'description_arrivee',
        'date_trajet',
        'places_disponibles',
        'photo_vehicule',
        'description_vehicule',
    ];

    protected $casts = [
        'date_trajet' => 'datetime',
    ];

    public function conducteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'conducteur_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'trajet_id');
    }
}
