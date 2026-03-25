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
        Schema::table('devis_tuyauterie_lignes', function (Blueprint $table) {
            $table->decimal('quantite_matiere_unitaire', 16, 6)->nullable()->after('matiere_id')->comment('Quantité de matière nécessaire pour fabriquer 1 élément');
            $table->string('unite_matiere', 50)->nullable()->after('quantite_matiere_unitaire')->comment('Unité de la matière (ml, kg, etc.)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devis_tuyauterie_lignes', function (Blueprint $table) {
            $table->dropColumn(['quantite_matiere_unitaire', 'unite_matiere']);
        });
    }
};
