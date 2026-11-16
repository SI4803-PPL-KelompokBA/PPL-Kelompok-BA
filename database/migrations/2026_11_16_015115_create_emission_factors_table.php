<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emission_factors', function (Blueprint $table) {
            $table->id();
            $table->string('kategori');
            $table->string('nama_faktor');
            $table->unsignedTinyInteger('scope')->nullable(); // 1/2/3, null untuk penyerapan
            $table->decimal('nilai_emisi', 15, 6);            // boleh negatif
            $table->string('satuan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emission_factors');
    }
};