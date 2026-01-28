<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trajets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conducteur_id')->constrained('users')->onDelete('cascade');
            $table->string('ville_depart');
            $table->text('description_depart');
            $table->string('ville_arrivee');
            $table->text('description_arrivee');
            $table->dateTime('date_trajet');
            $table->integer('places_disponibles');
            $table->string('photo_vehicule')->nullable();
            $table->text('description_vehicule');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trajets');
    }
};
