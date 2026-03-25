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
        schema::table('materiels', function (Blueprint $table) {
            $table->boolean('desactive')->default(false)->after('acquisition_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        schema::table('materiels', function (Blueprint $table) {
            $table->dropColumn('desactive');
        });
    }
};
