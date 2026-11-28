<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Land extends Model
{
    protected $fillable = [
        'nama_lahan',
        'lokasi',
        'luas_area',
        'target_pengurangan_emisi',
        'batas_emisi_maksimal',
        'latitude',
        'longitude',
        'status_operasional',
    ];

    public function emissions(): HasMany
    {
        return $this->hasMany(Emission::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function landCarbonData(): HasMany
    {
        return $this->hasMany(LandCarbonData::class);
    }
}
