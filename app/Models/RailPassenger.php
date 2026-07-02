<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RailPassenger extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'year',
        'service_type',
        'q1', 'q2', 'q3', 'q4'
    ];
}
