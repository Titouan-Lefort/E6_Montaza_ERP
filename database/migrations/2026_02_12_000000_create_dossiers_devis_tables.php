<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table principale des dossiers de devis
        Schema::create('dossiers_devis', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // DD-2026-001
            $table->string('nom');
            $table->foreignId('affaire_id')->nullable()->constrained('affaires')->nullOnDelete();
            $table->foreignId('societe_id')->nullable()->constrained('societes')->nullOnDelete();
            $table->foreignId('societe_contact_id')->nullable()->constrained('societe_contacts')->nullOnDelete();

            $table->string('reference_projet')->nullable();
            $table->string('lieu_intervention')->nullable();
            $table->text('description')->nullable();
            $table->date('date_creation')->nullable();
            $table->string('statut')->default('quantitatif'); // quantitatif, en_devis, valide, archive

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Table du quantitatif (besoins) - sans prix
        Schema::create('dossiers_devis_quantitatifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_devis_id')->constrained('dossiers_devis')->cascadeOnDelete();
            $table->string('categorie')->nullable(); // Ex: Fourniture, Main d'œuvre, Matériel
            $table->string('designation');
            $table->string('reference')->nullable();
            $table->decimal('quantite', 10, 2);
            $table->string('unite')->default('u'); // u, m, m², kg, h, etc.
            $table->text('remarques')->nullable();
            $table->integer('ordre')->default(0);
            $table->timestamps();
        });

        // Ajouter une référence au dossier de devis dans la table devis_tuyauteries
        Schema::table('devis_tuyauteries', function (Blueprint $table) {
            $table->foreignId('dossier_devis_id')->nullable()->after('affaire_id')->constrained('dossiers_devis')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('devis_tuyauteries', function (Blueprint $table) {
            $table->dropForeign(['dossier_devis_id']);
            $table->dropColumn('dossier_devis_id');
        });

        Schema::dropIfExists('dossiers_devis_quantitatifs');
        Schema::dropIfExists('dossiers_devis');
    }
};
