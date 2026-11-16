<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'land_id', 'periode_laporan', 'total_produksi_kg', 'total_emisi_dihasilkan',
        'total_karbon_diserap', 'net_emisi', 'status_audit', 'catatan_auditor', 'tanggal_audit',
    ];

    protected $casts = ['tanggal_audit' => 'date'];

    public function land() { return $this->belongsTo(Land::class); }
}