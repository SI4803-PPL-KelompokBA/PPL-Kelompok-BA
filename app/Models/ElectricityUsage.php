<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectricityUsage extends Model
{
    use HasFactory;

    protected $table = 'electricity_usages';

    protected $fillable = [
        'location_name',
        'kwh_consumed',
        'record_date',
    ];
}