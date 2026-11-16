<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lands', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lahan');
            $table->string('lokasi');
            $table->decimal('luas_area', 12, 2);                           // hektar
            $table->decimal('target_pengurangan_emisi', 5, 2)->default(0); // persen
            $table->decimal('batas_emisi_maksimal', 15, 4)->nullable();    // Sprint 2
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->enum('status_operasional', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lands');
    }
};