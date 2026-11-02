<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmissionFactor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_item',
        'kategori',
        'nilai_emisi',
        'satuan',
    ];

    public function transports()
    {
        return $this->hasMany(Transport::class, 'emission_factor_id');
    }
}