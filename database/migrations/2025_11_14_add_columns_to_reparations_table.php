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
        // Ajouter les colonnes si elles n'existent pas
        Schema::table('reparations', function (Blueprint $table) {
            if (!Schema::hasColumn('reparations', 'user_id')) {
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('reparations', 'materiel_id')) {
                $table->foreignId('materiel_id')->constrained('materiels')->onDelete('cascade');
            }
            if (!Schema::hasColumn('reparations', 'description')) {
                $table->text('description');
            }
            if (!Schema::hasColumn('reparations', 'status')) {
                $table->string('status')->default('pending');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reparations', function (Blueprint $table) {
            $table->dropForeignIdFor('users');
            $table->dropForeignIdFor('materiels');
            $table->dropColumn(['description', 'status', 'user_id', 'materiel_id']);
        });
    }
};
