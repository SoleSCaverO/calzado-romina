<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModeloAlmacen extends Model
{
    protected $table = 'modeloalmacen';
    protected $fillable = ['modId','almId', 'modalmPrecioMinimo','modalmPrecioLista'];
    public $timestamps = false;

}
