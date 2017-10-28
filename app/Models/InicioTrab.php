<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InicioTrab extends Model
{
    protected $table = 'iniciotrab';
    protected $primaryKey = 'initrabId';
    public $timestamps = false;

    protected $fillable = ['initrabId','initrabFecha', 'initrabCantidad','initrabEstado','initrabTipotrabajo',
        'initrabPlantilla','ordIdx','dtraId','subamId','traId','initrabObservacion'];

    public function orden()
    {
        return $this->belongsTo('App\Models\Orden','ordIdx','ordIdx');
    }

    public static function trabajador( $dtraId )
    {
        $trabajador = DTrabajador::find($dtraId);

        if(is_null($trabajador))
            return '';
        return $trabajador->dtraCodigobarras;
    }

    public function trabajador_nombre()
    {
        return $this->belongsTo('App\Models\Trabajador','traId','traId');
    }

    public static function descripcion( $subarea_menor_id,$modelo_id,$type )
    {
        $niveles = Nivel::where('subamId',$subarea_menor_id)->get();
        foreach ( $niveles as $nivel ){
            $ddatos = DDatosCalculo::where('nivId',$nivel->nivId)->get();
            foreach ( $ddatos as $ddato  ){
                $dmd = DetalleModeloDatos::where('ddatcId',$ddato->ddatcId)->where('modId',$modelo_id)->first();
                if( !is_null($dmd) ) {
                    if( $type == 1 )
                        return $ddato->ddatcDescripcion;
                    else
                        return $ddato->ddatcId;
                }
            }
        }

        return '';
    }

}
