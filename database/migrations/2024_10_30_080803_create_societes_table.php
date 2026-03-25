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
        Schema::create('commentaires', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text(column: 'contenu')->nullable();
        });
        Schema::create('pays', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string(column: 'nom', length: 100);
        });
        Schema::create('forme_juridiques', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string(column: 'code', length: 10);
            $table->string(column: 'nom', length: 100);
        });
        Schema::create('code_apes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string(column: 'code', length: 10);
            $table->string(column: 'nom');
        });
        Schema::create('societe_types', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string(column: 'nom', length: 100);
        });
        Schema::create('condition_paiements', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->timestamps();
        });
        Schema::create('societes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string(column: 'raison_sociale', length: 100);
            $table->integer(column: 'siren', autoIncrement: false)->unique()->nullable();
            $table->foreignId('forme_juridique_id')->constrained('forme_juridiques');
            $table->foreignId('code_ape_id')->nullable()->constrained('code_apes');
            $table->foreignId('societe_type_id')->constrained('societe_types');
            $table->string('telephone', length: 20)->nullable();
            $table->string('email', length: 100)->nullable();
            $table->string('site_web', length: 100)->nullable();
            $table->string('numero_tva', length: 100)->nullable();
            $table->foreignId('condition_paiement_id')->constrained('condition_paiements');
            $table->foreignId('commentaire_id')->constrained('commentaires')->nullable();
            $table->softDeletes();
        });
        Schema::create('etablissements', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string(column: 'nom', length: 100);
            $table->string(column: 'adresse', length: 100)->nullable();
            $table->string(column: 'code_postal', length: 10)->nullable();
            $table->string(column: 'ville', length: 100)->nullable();
            $table->string(column: 'region', length: 100)->nullable();
            $table->foreignId('pay_id')->constrained('pays');
            $table->string('siret', length: 14)->unique()->nullable();
            $table->foreignId('societe_id')->constrained('societes');
            $table->foreignId('commentaire_id')->constrained('commentaires');
            $table->softDeletes();
        });
        Schema::create('societe_contacts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string(column: 'nom', length: 100);
            $table->string(column: 'fonction', length: 100)->nullable();
            $table->string(column: 'email', length: 100);
            $table->string(column: 'telephone_fixe', length: 20)->nullable();
            $table->string(column: 'telephone_portable', length: 20)->nullable();
            $table->foreignId('etablissement_id')->constrained('etablissements');
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('societe_contacts');
        Schema::dropIfExists('etablissements');
        Schema::dropIfExists('societes');
        Schema::dropIfExists('condition_paiements');
        Schema::dropIfExists('code_apes');
        Schema::dropIfExists('forme_juridiques');
        Schema::dropIfExists('societe_types');
        Schema::dropIfExists('pays');
    }
};
