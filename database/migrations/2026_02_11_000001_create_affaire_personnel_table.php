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
        Schema::create('affaire_personnel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affaire_id')->constrained()->onDelete('cascade');
            $table->foreignId('personnel_id')->constrained('personnels')->onDelete('cascade');
            $table->string('role')->nullable(); // Role dans l'affaire (chef de projet, technicien, etc.)
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Éviter les doublons
            $table->unique(['affaire_id', 'personnel_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affaire_personnel');
    }
};
