<?php

namespace App;

use App\Models\DTrabajador;
use App\Models\InicioTrab;
use App\Models\Orden;
use App\Models\Trabajador;
use Illuminate\Database\Eloquent\Model;

class FinTrab extends Model
{
    protected $table = 'fintrab';
    protected $primaryKey = 'fintrabId';
    public $timestamps = false;

    protected $fillable = ['fintrabId','fintrabFecha', 'fintrabCantidad','fintrabEstado','fintrabTipotrabajo',
        'fintrabPlantilla','initrabId','ordenIdy','trabIdy','fintrabObservacion'];


    function inicio()
    {
        return $this->belongsTo('App\Models\InicioTrab','initrabId','initrabId');
    }

    public static function orden( $ordenIdy )
    {
        $orden = Orden::find($ordenIdy);
        if( is_null($orden) )
            return '';
        return $orden;
    }

    public static function trabajador( $trabIdy,$ordenIdy )
    {
        $inicio = InicioTrab::where('ordIdx',$ordenIdy)->where('traId',$trabIdy)->first();
        $trabajador = DTrabajador::find($inicio->dtraId);

        if(is_null($trabajador))
            return '';
        return $trabajador->dtraCodigobarras;
    }

    public function trabajador_nombre( $trabIdy )
    {
        $trabajador = Trabajador::find($trabIdy);

        if(is_null($trabajador))
            return '';

        return $trabajador;
    }
}
