<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SubareaMenor extends Model
{
    protected $table = 'subaream';
    protected $primaryKey = 'subamId';
    protected $fillable = ['subaId','subamDescripcion', 'subamEstado'];
    public $timestamps = false;
    protected $appends = ['tipo_calculo_id','tipo_calculo_nombre','tipo_calculo_tipo','nombre_subarea'];

    public function subarea()
    {
        return $this->belongsTo('App\Models\Subarea','subaId','subaId');
    }

    public function getNombreSubareaAttribute()
    {
        $subamId = $this->attributes['subamId'];
        $subarea_menor = SubareaMenor::find($subamId);
        $subarea = SubArea::find($subarea_menor->subaId);

        return $subarea ->subaDescripcion;
    }

    public function getTipoCalculoIdAttribute()
    {
        $subaId = $this->attributes['subamId'];
        $dsubatipoc = DSubATipoC::where('subamId',$subaId)->first();
        $tipocalculo = TipoCalculo::find(@$dsubatipoc->tipocalId);

        return @$tipocalculo->tcalId;
    }

    public function getTipoCalculoNombreAttribute()
    {
        $subaId = $this->attributes['subamId'];
        $dsubatipoc = DSubATipoC::where('subamId',$subaId)->first();
        $tipocalculo = TipoCalculo::find(@$dsubatipoc->tipocalId);

        return @$tipocalculo->tcalDescripcion;
    }

    public function getTipoCalculoTipoAttribute()
    {
        $subaId = $this->attributes['subamId'];
        $dsubatipoc = DSubATipoC::where('subamId',$subaId)->first();
        $tipocalculo = TipoCalculo::find(@$dsubatipoc->tipocalId);

        return @$tipocalculo->tcalTipo;
    }

    public function trabajadores()
    {
        return $this->belongsToMany('App\Models\Trabajador','dtrabajador','subamId','traId')
            ->withPivot('dtraEstado');
    }

    public function niveles()
    {
        return $this->hasMany('App\Models\Nivel','subamId','subamId');
    }


    public function monto( $subarea_menor, $modelo, $descripcion, $pares ){
        $dsuba_tipocal = DSubATipoC::where('subamId',$subarea_menor)->first();
        $precios = SubareaMenor::
        join('nivel as n','subaream.subamId','=','n.subamId')->
        join('ddatoscalculo as d','n.nivId','=','d.nivId')->
        join('detalle_modelo_datos as dmd','dmd.ddatcId','=','d.ddatcId')->
        select('d.*')->
        distinct('d.ddatcNombre')->
        where('subaream.subamId',$subarea_menor)->
        where('dmd.modId',$modelo);

        if( $dsuba_tipocal->tipocalId == 2 ){
            $precios->where('n.nivFlag',0)->where('d.ddatcNombre',$descripcion)->whereNull('n.description_id');
        }else{
            $precios->where('n.nivFlag',1)->whereNotNull('n.description_id');
        }

        $precios = $precios->orderBy('d.ddatcNombre')->get();

        if( count( $precios ) == 0 )
            return '0.00';
        elseif ( count($precios) == 1) {
            $precio = $precios[0];
            $monto = 0;
            if( $precio->ddatcCondicion == 1 ){
                if( $precio->ddatcMayorCondicion == 1 ){
                    if( $precio->ddatcDatoCondicion < $pares  ){
                        $monto = $precio->ddatcPrecioCondicion * $pares;
                    }else{
                        $monto = ($precio->ddatcPrecioDocena * $pares)/12;
                    }
                }else{
                    if( $precio->ddatcDatoCondicion > $pares  ){
                        $monto = $precio->ddatcPrecioCondicion * $pares;
                    }else{
                        $monto = ($precio->ddatcPrecioDocena * $pares)/12;
                    }
                }
            }else{
                if( $precio->tipoPrecio == 1 ){ // Precio por par
                    $monto = ($precio->ddatcPrecioDocena * $pares);
                }else{ // Precio por docena
                    $monto = ($precio->ddatcPrecioDocena * $pares)/12;
                }
            }

            return number_format($monto,2);
        }else{
            foreach ( $precios as $precio ){
                $dmd = DetalleModeloDatos::where('ddatcId', $precio->ddatcId)->where('modId',$modelo)->whereNotNull('pieId')->first();
                if( count($dmd) )
                    break;
            }

            if( !isset($dmd) ){
                return '0.00';
            }

            $precio = DDatosCalculo::find($dmd->ddatcId);
            $monto = number_format(($precio->ddatcPrecioDocena * $pares)/12,2);

            return $monto;
        }
    }

}