<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    protected $table = 'entrega';
    protected $primaryKey = 'entId';
    protected $fillable = ['entId','pedIdx', 'calId','entOrden','entFecha','entCantidad','entFacturaAsignada',
                           'OrdenAsignada','entFacturaFecha'];
    public $timestamps = false;

}
