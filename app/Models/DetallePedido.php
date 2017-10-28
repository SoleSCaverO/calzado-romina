<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table = 'detallepedido';
    protected $fillable = ['pedIdx','calId', 'detpedCantidad','detpedFaltante'];
    public $timestamps = false;

}
