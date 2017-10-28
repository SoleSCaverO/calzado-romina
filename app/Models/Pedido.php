<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pedido extends Model
{
    protected $table = 'pedido';
    protected $primaryKey = 'pedIdx';
    protected $fillable = ['pedIdx','pedId', 'cliId','pedFechaRecepcion','pedEstado'];
    public $timestamps = false;
    protected $appends = ['cliente','cantidad_pares'];

    public function calzados()
    {
        return $this->belongsToMany('App\Calzado', 'detallepedido', 'pedIdx', 'calId')
            ->withPivot(['detpedCantidad','detpedFaltante']);
    }

    public function getClienteAttribute()
    {
        $cliId   = $this->attributes['cliId'];
        $cliente = Cliente::find($cliId);

        return $cliente->cliNombre;
    }

    public function getCantidadParesAttribute()
    {
        $pedIdx = $this->attributes['pedIdx'];

        $cantidad = DB::table('pedido as P')
            ->leftJoin('detallepedido as DP', 'P.pedIdx', '=', 'DP.pedIdx')
            ->select(DB::raw('sum(DP.detpedCantidad) as suma'))
            ->where('DP.pedIdx',$pedIdx)
            ->get();

        return intval($cantidad[0]->suma);
    }
}
