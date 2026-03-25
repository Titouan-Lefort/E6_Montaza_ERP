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
       Schema::create('materiels', function (Blueprint $table){
              $table->id();
              $table->string('reference');
              $table->string('designation');
              $table->text('description')->nullable();
              $table->string('numero_serie')->unique();
              $table->string('status')->default('actif');
              $table->date('acquisition_date')->nullable();
              $table->timestamps();
       }); //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materiels');
    }
};
