<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emission extends Model
{
    protected $fillable = [
        'land_id', 'kategori_aktivitas', 'emission_factor_id', 'jumlah_penggunaan',
        'total_emisi_kotor', 'detail_tambahan', 'bukti_dokumen', 'tanggal_aktivitas',
    ];

    protected $casts = [
        'detail_tambahan'   => 'array',
        'tanggal_aktivitas' => 'date',
    ];

    public function land()   { return $this->belongsTo(Land::class); }
    public function factor() { return $this->belongsTo(EmissionFactor::class, 'emission_factor_id'); }
}