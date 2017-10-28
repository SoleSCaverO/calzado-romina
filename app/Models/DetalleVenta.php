<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalleventa';
    protected $fillable = ['venId','calId', 'detvenCantidad','detvenPrecioVenta','detvenMonto'];
    public $timestamps = false;

}
