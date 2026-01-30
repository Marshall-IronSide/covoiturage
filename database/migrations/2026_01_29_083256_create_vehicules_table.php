<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicules', function (Blueprint $table) {
            $table->id();
            // IMPORTANT : user_id est UNIQUE - un utilisateur = un seul véhicule
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('photo');
            $table->string('numero_plaque')->unique();
            $table->text('description');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicules');
    }
};