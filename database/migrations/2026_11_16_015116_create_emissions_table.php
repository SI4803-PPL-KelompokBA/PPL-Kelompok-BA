<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('land_id')->constrained()->restrictOnDelete();
            $table->string('kategori_aktivitas');
            $table->foreignId('emission_factor_id')->constrained('emission_factors')->restrictOnDelete();
            $table->decimal('jumlah_penggunaan', 15, 4);
            $table->decimal('total_emisi_kotor', 15, 4);   // signed, negatif = penyerapan
            $table->json('detail_tambahan')->nullable();
            $table->string('bukti_dokumen')->nullable();
            $table->date('tanggal_aktivitas');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emissions');
    }
};