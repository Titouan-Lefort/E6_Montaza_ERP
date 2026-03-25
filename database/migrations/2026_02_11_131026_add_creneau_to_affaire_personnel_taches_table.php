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
        Schema::table('affaire_personnel_taches', function (Blueprint $table) {
            $table->enum('creneau_debut', ['matin', 'apres_midi'])->default('matin')->after('date_debut');
            $table->enum('creneau_fin', ['matin', 'apres_midi'])->default('apres_midi')->after('date_fin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affaire_personnel_taches', function (Blueprint $table) {
            $table->dropColumn(['creneau_debut', 'creneau_fin']);
        });
    }
};
