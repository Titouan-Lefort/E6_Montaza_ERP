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
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('numero_facture')->unique();
            $table->date('date_emission');
            $table->decimal('montant_total', 10, 2);
            $table->unsignedBigInteger('reparation_id');
            $table->foreign('reparation_id')->references('id')->on('reparations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
