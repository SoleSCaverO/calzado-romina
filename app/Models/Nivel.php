<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    protected $table = 'nivel';
    protected $primaryKey = 'nivId';
    protected $fillable = ['nivId','nivDescripcion','nivNombre','nivCondicion','nivInicio','nivFin','nivEstado',
                           'dsatipocal','subamId','tipocalId','nivFlag','description_id'];
    public $timestamps = false;

    public function precios()
    {
        return $this->hasMany('App\Models\DDatosCalculo','nivId','nivId');
    }

}
