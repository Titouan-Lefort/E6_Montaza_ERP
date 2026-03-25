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
        // Ajout des statuts et dates aux affaires
        Schema::table('affaires', function (Blueprint $table) {
            if (!Schema::hasColumn('affaires', 'statut')) {
                $table->string('statut')->default('en_attente');
            }
            if (!Schema::hasColumn('affaires', 'date_debut')) {
                $table->date('date_debut')->nullable();
            }
            if (!Schema::hasColumn('affaires', 'date_fin_prevue')) {
                $table->date('date_fin_prevue')->nullable();
            }
            if (!Schema::hasColumn('affaires', 'date_fin_reelle')) {
                $table->date('date_fin_reelle')->nullable();
            }
            if (!Schema::hasColumn('affaires', 'description')) {
                $table->text('description')->nullable();
            }
        });

        // Lier les DDP aux affaires
        Schema::table('ddps', function (Blueprint $table) {
            if (!Schema::hasColumn('ddps', 'affaire_id')) {
                $table->foreignId('affaire_id')->nullable()->constrained('affaires')->nullOnDelete();
            }
        });

        // Lier les Commandes (Cde) aux affaires
        Schema::table('cdes', function (Blueprint $table) {
            if (!Schema::hasColumn('cdes', 'affaire_id')) {
                $table->foreignId('affaire_id')->nullable()->constrained('affaires')->nullOnDelete();
            }
        });

        // Lier les réparations aux affaires
        Schema::table('reparations', function (Blueprint $table) {
            if (!Schema::hasColumn('reparations', 'affaire_id')) {
                $table->foreignId('affaire_id')->nullable()->constrained('affaires')->nullOnDelete();
            }
        });

        // Table pivot pour le matériel assigné aux affaires
        if (!Schema::hasTable('affaire_materiel')) {
            Schema::create('affaire_materiel', function (Blueprint $table) {
                $table->id();
                $table->foreignId('affaire_id')->constrained('affaires')->cascadeOnDelete();
                $table->foreignId('materiel_id')->constrained('materiels')->cascadeOnDelete();
                $table->date('date_debut')->nullable();
                $table->date('date_fin')->nullable();
                $table->string('statut')->default('reserve'); // reserve, en_cours, termine
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affaire_materiel');

        Schema::table('reparations', function (Blueprint $table) {
            $table->dropForeign(['affaire_id']);
            $table->dropColumn('affaire_id');
        });

        Schema::table('cdes', function (Blueprint $table) {
            $table->dropForeign(['affaire_id']);
            $table->dropColumn('affaire_id');
        });

        Schema::table('ddps', function (Blueprint $table) {
            $table->dropForeign(['affaire_id']);
            $table->dropColumn('affaire_id');
        });

        Schema::table('affaires', function (Blueprint $table) {
            $table->dropColumn(['statut', 'date_debut', 'date_fin_prevue', 'date_fin_reelle', 'description']);
        });
    }
};
