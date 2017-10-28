<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CargaTrabOrden extends Model
{
    protected $table = 'cargatraborden';
    protected $primaryKey = 'cargatraboId';
    public $timestamps = false;

    protected $fillable = ['cargatraboId','cargatraboInicio', 'cargatraboFin','cargatraboCantInicio','cargatraboCantFin',
        'ordIdx','dtraId','subaId','traId'];
}
