<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trajets', function (Blueprint $table) {
            // Ajouter vehicule_id après conducteur_id
            $table->foreignId('vehicule_id')->nullable()->after('conducteur_id')
                  ->constrained('vehicules')->onDelete('cascade');
            
            // Supprimer les colonnes de véhicule de la table trajets
            $table->dropColumn(['photo_vehicule', 'description_vehicule']);
        });
    }

    public function down(): void
    {
        Schema::table('trajets', function (Blueprint $table) {
            // Restaurer les colonnes
            $table->string('photo_vehicule')->nullable();
            $table->text('description_vehicule')->nullable();
            
            // Supprimer la foreign key
            $table->dropForeign(['vehicule_id']);
            $table->dropColumn('vehicule_id');
        });
    }
};