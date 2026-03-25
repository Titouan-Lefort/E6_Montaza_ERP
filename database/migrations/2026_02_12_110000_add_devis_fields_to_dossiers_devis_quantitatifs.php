<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dossiers_devis_quantitatifs', function (Blueprint $table) {
            // Ajouter les champs manquants pour correspondre à une ligne de devis
            $table->string('type')->default('fourniture')->after('categorie'); // fourniture, main_d_oeuvre, sous_traitance, consommable
            $table->decimal('prix_achat', 15, 2)->nullable()->after('unite');
            $table->decimal('prix_unitaire', 15, 2)->nullable()->after('prix_achat');
            $table->decimal('quantite_matiere_unitaire', 16, 6)->nullable()->after('matiere_id')->comment('Quantité de matière nécessaire pour fabriquer 1 élément');
            $table->string('unite_matiere', 50)->nullable()->after('quantite_matiere_unitaire')->comment('Unité de la matière (ml, kg, etc.)');
        });
    }

    public function down(): void
    {
        Schema::table('dossiers_devis_quantitatifs', function (Blueprint $table) {
            $table->dropColumn(['type', 'prix_achat', 'prix_unitaire', 'quantite_matiere_unitaire', 'unite_matiere']);
        });
    }
};
