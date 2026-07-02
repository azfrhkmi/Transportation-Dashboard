<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaritimeStatistic extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'year',
        'quarter',
        'port_name',
        'int_mother', 'int_feeder', 'int_cargo', 'int_tanker', 'int_bulk', 'int_others', 'int_total',
        'dom_mother', 'dom_feeder', 'dom_cargo', 'dom_tanker', 'dom_bulk', 'dom_others', 'dom_total',
        'others', 'grand_total'
    ];
}
