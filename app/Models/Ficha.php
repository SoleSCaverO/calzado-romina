<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ficha extends Model
{
    protected $fillable = [
        'coleccion','genero','marca','horma','color','modelista','fecha','cliente_id','modelo_id',
        'talla','piezas_cuero','piezas_forro','modelaje','produccion','gerencia','observacion',
        'imagen_derecha','imagen_izquierda','imagen_arriba','imagen_atras'
    ];

    function cliente(){
        return $this->belongsTo(Cliente::class);
    }

    function modelo(){
        return $this->belongsTo(Modelo::class);
    }

    function getColorAttribute($color){
        $multi = Multitabla::find($color);

        return $multi->mulDescripcion;
    }
}
