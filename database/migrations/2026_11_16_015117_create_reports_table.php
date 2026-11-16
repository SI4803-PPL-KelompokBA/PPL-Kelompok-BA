<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('land_id')->constrained()->restrictOnDelete();
            $table->string('periode_laporan');
            $table->decimal('total_produksi_kg', 15, 2)->nullable();
            $table->decimal('total_emisi_dihasilkan', 15, 4)->default(0);
            $table->decimal('total_karbon_diserap', 15, 4)->default(0);
            $table->decimal('net_emisi', 15, 4)->default(0);
            $table->enum('status_audit', ['draft', 'menunggu_audit', 'revisi', 'disetujui'])->default('draft');
            $table->text('catatan_auditor')->nullable();
            $table->date('tanggal_audit')->nullable();
            $table->timestamps();
            $table->unique(['land_id', 'periode_laporan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};