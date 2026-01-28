<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    protected $table = 'reservations';

    protected $fillable = [
        'trajet_id',
        'passager_id',
        'nombre_places',
        'prix_total',
        'statut',
    ];

    public function trajet(): BelongsTo
    {
        return $this->belongsTo(Trajet::class, 'trajet_id');
    }

    public function passager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'passager_id');
    }

    public function avis(): HasMany
    {
        return $this->hasMany(Avis::class, 'reservation_id');
    }
}
