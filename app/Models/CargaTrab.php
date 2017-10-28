<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CargaTrab extends Model
{
    protected $table = 'cargatrab';
    protected $primaryKey = 'cargatrabId';
    public $timestamps = false;

    protected $fillable = ['cargatrabId','cargatrabInicio', 'cargatrabFin','cargatrabCantInicio','cargatrabCantFin',
                           'dorden_dordenId','dtraId','subaId','traId'];
}
