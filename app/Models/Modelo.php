<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    protected $table = 'modelo';
    protected $primaryKey = 'modId';
    protected $fillable = ['modId','modLinea', 'modGenero','modDescripcion','modPrecioMinimo','modPrecioLista'];
    public $timestamps = false;
    protected $appends = ['linea','genero'];

    public function imagenes()
    {
        return $this->hasMany('App\Models\MImagen','modId','modId');
    }

    public function almacenes()
    {
        return $this->belongsToMany('App\Almacen','modeloalmacen','modId','almId')
            ->withPivot('modalmPrecioMinimo','modalmPrecioLista');
    }

    public function getLineaAttribute()
    {
        $lineaId = $this->attributes['modLinea'];
        $multitabla =  Multitabla::where('mulDepId',1)->where('mulValor',$lineaId)->first();
        $linea = $multitabla->mulDescripcion;
        return $linea;
    }

    public function getGeneroAttribute()
    {
        $generoId = $this->attributes['modGenero'];
        $multitabla =  Multitabla::where('mulDepId',3)->where('mulValor',$generoId)->first();
        $genero = $multitabla->mulDescripcion;
        return $genero;
    }

    public function tienePrecioReferencial($model,$minor_subarea){
        $data_model_detail = DetalleModeloDatos::join('ddatoscalculo','ddatoscalculo.ddatcId','=','detalle_modelo_datos.ddatcId')->
            join('nivel','ddatoscalculo.nivId','=','nivel.nivId')->
            where('detalle_modelo_datos.modId',$model)->
            where('nivel.subamId',$minor_subarea)->
            select('detalle_modelo_datos.*')->first();

        $has_price = true;
        if( !count($data_model_detail)){
            $has_price = false;
            return ['has_price'=>$has_price];
        }

        $has_referential_price = false;
        if(!is_null($data_model_detail->referential_price)){
            $has_referential_price = true;
        }

        $price = $data_model_detail->precio;
        $price = 'S/ '.$price->ddatcPrecioDocena.($price->tipoPrecio == 1? ' x PAR' : ' x DOC');

        return [
            'has_price'=>$has_price,
            'price'=>$price,
            'has_referential_price'=>$has_referential_price,
            'referential_price'=>$data_model_detail->referential_price,
            'id'=>$data_model_detail->moddatosId
        ];
    }
}
