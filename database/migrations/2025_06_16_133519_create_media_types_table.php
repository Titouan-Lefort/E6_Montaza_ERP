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
        Schema::create('media_types', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->string('background_color_light')->nullable();
            $table->string('background_color_dark')->nullable();
            $table->string('text_color_light')->nullable();
            $table->string('text_color_dark')->nullable();
            $table->timestamps();
        });
        Schema::table('media', function (Blueprint $table) {
            $table->foreignId('commentaire_id')->nullable()->constrained('commentaires')->onDelete('cascade');
            $table->foreignId('media_type_id')->nullable()->constrained('media_types');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropForeign(['commentaire_id']);
            $table->dropColumn('commentaire_id');
            $table->dropForeign(['media_type_id']);
            $table->dropColumn('media_type_id');
        });
        Schema::dropIfExists('media_types');

    }
};
