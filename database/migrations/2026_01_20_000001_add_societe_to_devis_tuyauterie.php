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
        Schema::table('devis_tuyauteries', function (Blueprint $table) {
            $table->foreignId('societe_id')->nullable()->constrained('societes')->onDelete('set null');
            $table->foreignId('societe_contact_id')->nullable()->constrained('societe_contacts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devis_tuyauteries', function (Blueprint $table) {
            $table->dropForeign(['societe_id']);
            $table->dropForeign(['societe_contact_id']);
            $table->dropColumn(['societe_id', 'societe_contact_id']);
        });
    }
};
