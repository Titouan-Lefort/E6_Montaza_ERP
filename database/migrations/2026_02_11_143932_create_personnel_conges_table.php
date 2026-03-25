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
        Schema::create('personnel_conges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personnel_id')->constrained('personnels')->onDelete('cascade');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('type', ['conge_paye', 'conge_maladie', 'conge_sans_solde', 'autre'])->default('conge_paye');
            $table->text('motif')->nullable();
            $table->enum('statut', ['demande', 'valide', 'refuse'])->default('valide');
            $table->timestamps();

            $table->index(['personnel_id', 'date_debut', 'date_fin']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnel_conges');
    }
};
