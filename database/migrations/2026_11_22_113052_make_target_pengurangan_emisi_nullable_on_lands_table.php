
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lands', function (Blueprint $table) {
            $table->decimal('target_pengurangan_emisi', 5, 2)
                ->nullable()
                ->default(null)
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('lands', function (Blueprint $table) {
            $table->decimal('target_pengurangan_emisi', 5, 2)
                ->default(0)
                ->nullable(false)
                ->change();
        });
    }
};
