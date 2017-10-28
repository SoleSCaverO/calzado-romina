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
}
