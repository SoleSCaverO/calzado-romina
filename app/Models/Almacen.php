<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    // protected $connection = 'db_name';
    protected $table = 'almacen';
    protected $primaryKey = 'almId';
    protected $fillable = ['almId','almDescripcion', 'almEstado'];
    public $timestamps = false;

    public function calzados()
    {
        return $this->belongsToMany('App\Calzado','almacencalzado','almId','calId')
            ->withPivot('almcalStock');
    }

    public function modelos()
    {
        return $this->belongsToMany('App\Modelo','modeloalmacen','almId','modId')
            ->withPivot('modalmPrecioMinimo','modalmPrecioLista');
    }
/*
    public function method()
    {
        return $this->belongsTo('App\TypeProduct','foreign_key', 'local_key');
    }
*/

}
