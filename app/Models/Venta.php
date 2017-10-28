<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'venta';
    protected $primaryKey = 'venId';
    protected $fillable = ['venId','persId','cliId','venFecha','venCredito','venMontoTotal','venMontoCancelado',
                           'venClienteTemporal','venEstado'];
    public $timestamps = false;


    public function calzados()
    {
        return $this->belongsToMany('App\Calzado','detalleventa','venId','calId')
            ->withPivot('detvenCantidad','detvenPrecioVenta','detvenMonto');
    }
}
