<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Land extends Model
{
    protected $fillable = [
        'nama_lahan', 'lokasi', 'luas_area', 'target_pengurangan_emisi',
        'batas_emisi_maksimal', 'latitude', 'longitude', 'status_operasional',
    ];

    public function emissions() { return $this->hasMany(Emission::class); }
    public function reports()   { return $this->hasMany(Report::class); }
}