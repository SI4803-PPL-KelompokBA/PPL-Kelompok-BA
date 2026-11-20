<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('land_carbon_data', function (Blueprint $table) {
            $table->id();

            $table->foreignId('land_id')
                ->constrained('lands')
                ->cascadeOnDelete();

            $table->date('tanggal_aktivitas');

            $table->unsignedInteger('trees_planted')->default(0);

            $table->decimal('cover_crop_area', 12, 2)->default(0);

            $table->string('tillage_method', 100);

            $table->decimal('biochar_amount', 12, 2)->default(0);

            $table->decimal('compost_amount', 12, 2)->default(0);

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('tanggal_aktivitas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('land_carbon_data');
    }
};
