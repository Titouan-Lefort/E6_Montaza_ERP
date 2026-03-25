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
        Schema::create('affaires', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('total_ht', 15, 2)->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->boolean('budget_notified')->default(false);
            $table->string('nom');
            $table->timestamps();
        });
        Schema::table('cdes', function (Blueprint $table) {
            $table->dropColumn(['affaire_numero', 'affaire_nom']);
            $table->foreignId('affaire_id')->nullable()->constrained('affaires');
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cdes', function (Blueprint $table) {
            $table->dropForeign(['affaire_id']);
            $table->dropColumn('affaire_id');
            $table->string('affaire_numero')->nullable();
            $table->string('affaire_nom')->nullable();
        });
        Schema::dropIfExists('affaires');
    }
};
