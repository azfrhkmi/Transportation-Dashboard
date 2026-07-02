<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandLicense extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'year',
        'category',
        'license_type',
        'q1', 'q2', 'q3', 'q4'
    ];
}
