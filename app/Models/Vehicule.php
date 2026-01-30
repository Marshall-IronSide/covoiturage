<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'photo',
        'numero_plaque',
        'description',
    ];

    /**
     * Un véhicule appartient à un utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Un véhicule peut être utilisé pour plusieurs trajets
     */
    public function trajets(): HasMany
    {
        return $this->hasMany(Trajet::class);
    }
}