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
            $table->foreignId('matiere_id')->nullable()->after('matiere')->constrained('matieres')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devis_tuyauterie_lignes', function (Blueprint $table) {
            $table->dropForeign(['matiere_id']);
            $table->dropColumn('matiere_id');
        });
    }
};
