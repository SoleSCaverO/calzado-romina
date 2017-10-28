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

}
