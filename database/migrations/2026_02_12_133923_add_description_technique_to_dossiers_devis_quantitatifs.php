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
        Schema::table('dossiers_devis_quantitatifs', function (Blueprint $table) {
            $table->text('description_technique')->nullable()->after('designation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dossiers_devis_quantitatifs', function (Blueprint $table) {
            $table->dropColumn('description_technique');
        });
    }
};
