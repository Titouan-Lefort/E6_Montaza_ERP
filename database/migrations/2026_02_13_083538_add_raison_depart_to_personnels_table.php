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
        Schema::table('personnels', function (Blueprint $table) {
            $table->enum('raison_depart', ['demission', 'licenciement', 'retraite', 'fin_contrat', 'mutation', 'autre'])->nullable()->after('date_depart');
            $table->text('motif_depart')->nullable()->after('raison_depart');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personnels', function (Blueprint $table) {
            $table->dropColumn(['raison_depart', 'motif_depart']);
        });
    }
};
