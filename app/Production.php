<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $table = 'productions';

    protected $fillable = [
        'product_line',
        'quantity_produced',
        'quantity_defects',
        'efficiency',
        'production_date'
    ];
}
