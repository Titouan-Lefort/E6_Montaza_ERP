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
        Schema::table('reparations', function (Blueprint $table){
            $table-> boolean('archive')->default(false);
            $table-> dropColumn('date_creation');
            $table-> string('status')->default('En attente');
            $table-> dropColumn('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reparations', function (Blueprint $table){
            $table-> dropColumn('archive');
            $table-> date('date_creation')->nullable();
            $table-> dropColumn('status');
            $table-> string('statut')->default('En attente');
        });
    }
};
