<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('export_logistics', function (Blueprint $table) {
            $table->id();
            $table->string('processing_facility');
            $table->string('origin_port');
            $table->string('destination_port');
            $table->string('transport_mode');
            $table->decimal('distance_km', 10, 2);
            $table->decimal('cargo_weight_ton', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('export_logistics');
    }
};