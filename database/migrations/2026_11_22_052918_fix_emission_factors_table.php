<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('emission_factors', function (Blueprint $table) {
            $table->renameColumn('nama_item', 'nama_faktor');
        });

        Schema::table('emission_factors', function (Blueprint $table) {
            $table->unsignedTinyInteger('scope')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('emission_factors', function (Blueprint $table) {
            $table->dropColumn('scope');
        });

        Schema::table('emission_factors', function (Blueprint $table) {
            $table->renameColumn('nama_faktor', 'nama_item');
        });
    }
};