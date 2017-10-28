<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calzado extends Model
{
    protected $table = 'calzado';
    protected $primaryKey = 'calId';
    protected $fillable = ['calId','pedId', 'calTipoBarra','calOrden','modId','calColor','calTalla'];
    public $timestamps = false;

    public function almacenes()
    {
        return $this->belongsToMany('App\Almacen','almacencalzado','calId','almId')
            ->withPivot('almcalStock');
    }

    public function pedidos()
    {
        return $this->belongsToMany('App\Pedido','detallepedido','calId','pedIdx')
            ->withPivot(['detpedCantidad','detpedFaltante']);
    }

    public function ventas()
    {
        return $this->belongsToMany('App\Venta','detalleventa','calId','venId')
            ->withPivot(['detvenCantidad','detvenPrecioVenta','detvenMonto']);
    }
}
