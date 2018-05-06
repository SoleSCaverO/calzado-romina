<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Ficha extends Model
{
    protected $fillable = [
        'coleccion','genero','marca','horma','color','modelista','fecha','cliente_id','modelo_id',
        'talla','piezas_cuero','piezas_forro','modelaje','produccion','gerencia','observacion',
        'imagen_derecha','imagen_izquierda','imagen_arriba','imagen_atras'
    ];

    protected $appends = ['nombre_cliente','nombre_modelo'];

    function cliente(){
        return $this->belongsTo(Cliente::class);
    }

    function modelo(){
        return $this->belongsTo(Modelo::class);
    }

    function getNombreClienteAttribute(){
        $cliente = Cliente::find($this->attributes['cliente_id']);

        return $cliente->cliNombre;
    }

    function getNombreModeloAttribute(){
        $modelo = Modelo::find($this->attributes['modelo_id']);

        return $modelo->modDescripcion;
    }

    function getColorAttribute($color){
        $multi = Multitabla::find($color);

        return $multi->mulDescripcion;
    }

    function getFechaAttribute($fecha){
        $fecha = new Carbon($fecha);

        return $fecha->format('d-m-Y');
    }

    function material($fichaId,$areaId){
        return FichaMateriales::where('ficha_id',$fichaId)->where('area_id',$areaId)->get();
    }

    function numeroMateriales($fichaId,$areaId){
        return FichaMateriales::where('ficha_id',$fichaId)->where('area_id',$areaId)->count();
    }
}