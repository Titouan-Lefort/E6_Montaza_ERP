<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelChangesTable extends Migration
{
    public function up(): void
    {
        Schema::create('model_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('model_type'); // Type du modèle, ex : App\Models\Post
            // $table->unsignedBigInteger('model_id'); // ID de l'instance du modèle
            $table->json('before')->nullable(); // Valeurs avant modification
            $table->json('after')->nullable();  // Valeurs après modification
            $table->string('event'); // Type d'événement : created, updated, deleted
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('model_changes');
    }
}
