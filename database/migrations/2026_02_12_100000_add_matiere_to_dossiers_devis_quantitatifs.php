<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dossiers_devis_quantitatifs', function (Blueprint $table) {
            $table->foreignId('matiere_id')->nullable()->after('dossier_devis_id')->constrained('matieres')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('dossiers_devis_quantitatifs', function (Blueprint $table) {
            $table->dropForeign(['matiere_id']);
            $table->dropColumn('matiere_id');
        });
    }
};
