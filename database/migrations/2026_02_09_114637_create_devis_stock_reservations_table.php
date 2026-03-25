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
        Schema::create('devis_stock_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('devis_tuyauterie_id')->constrained('devis_tuyauteries')->cascadeOnDelete();
            $table->foreignId('matiere_id')->constrained('matieres')->cascadeOnDelete();
            $table->decimal('quantite_reservee', 16, 6);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Qui a fait la réservation
            $table->string('statut')->default('reserve'); // reserve, consomme, annule
            $table->text('notes')->nullable();
            $table->timestamps();

            // Index pour les recherches fréquentes
            $table->index(['devis_tuyauterie_id', 'statut']);
            $table->index(['matiere_id', 'statut']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devis_stock_reservations');
    }
};
