<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportLogistic extends Model
{
    use HasFactory;

    protected $table = 'export_logistics';

    protected $fillable = [
        'processing_facility',
        'origin_port',
        'destination_port',
        'transport_mode',
        'distance_km',
        'cargo_weight_ton',
    ];
}