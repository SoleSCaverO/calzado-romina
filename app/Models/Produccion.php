<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Produccion extends Model
{
    protected $table = 'produccion';
    protected $primaryKey = 'prodId';
    protected $fillable = ['prodId','proFechaEntrega','cliId','proEstado','pedId','proFecharegistro'];
    public $timestamps = false;

    protected $appends = ['cantidad_pares','cliente'];

    public function getClienteAttribute()
    {
        $cliente_id = $this->attributes['cliId'];
        $cliente = Cliente::find($cliente_id);
        return $cliente->cliNombre;
    }

    public function getFechaRegistroAttribute()
    {
        $fecha = $this->attributes['proFecharegistro'];
        $fecha = new Carbon($fecha);

        return $fecha->format('d-m-Y');
    }

    public function getFechaEntregaAttribute()
    {
        $fecha = $this->attributes['proFechaEntrega'];
        $fecha = new Carbon($fecha);

        return $fecha->format('d-m-Y');
    }

    public function getCantidadParesAttribute()
    {
        $prodId = $this->attributes['prodId'];

        $cantidad = DB::table('produccion as P')
            ->leftJoin('dproduccion as DP', 'P.prodId', '=', 'DP.prodId')
            ->select(DB::raw('sum(DP.dprodCantidad) as suma'))
            ->where('DP.prodId',$prodId)
            ->get();

        return intval($cantidad[0]->suma);
    }

    public function detalles_produccion()
    {
        return $this->hasMany('App\Models\DProduccion','prodId','prodId');
    }


/*
    public function getAreasAttribute()
    {
        return $areas = Area::where('areEstado',1)->get();
    }
*/
}
