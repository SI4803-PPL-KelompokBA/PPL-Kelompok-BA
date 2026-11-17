<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandCarbonData extends Model
{
    protected $fillable = [
        'land_id',
        'tanggal_aktivitas',
        'trees_planted',
        'cover_crop_area',
        'tillage_method',
        'biochar_amount',
        'compost_amount',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_aktivitas' => 'date',
            'trees_planted' => 'integer',
            'cover_crop_area' => 'decimal:2',
            'biochar_amount' => 'decimal:2',
            'compost_amount' => 'decimal:2',
        ];
    }

    public function land(): BelongsTo
    {
        return $this->belongsTo(Land::class);
    }
}
