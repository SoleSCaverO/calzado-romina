<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FichaMateriales extends Model
{
    protected $fillable = ['ficha_id','area_id','nombre','piezas'];
}
