<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmissionFactor extends Model
{
    protected $fillable = ['kategori', 'nama_faktor', 'scope', 'nilai_emisi', 'satuan'];

    public function emissions() { return $this->hasMany(Emission::class); }
}