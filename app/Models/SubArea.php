<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SubArea extends Model
{
    protected $table = 'subarea';
    protected $primaryKey = 'subaId';
    protected $fillable = ['subaId','areId','subaDescripcion','subaEstado','subaFlag','subaOrdenp','subaDespacho'];
    public $timestamps = false;
    protected $appends = ['id_area','nombre_area'];

    public function area()
    {
        return $this->belongsTo('App\Models\Area','areId','areId');
    }

    public function subareas_menores()
    {
        return $this->hasMany('App\Models\SubareaMenor','subaId','subaId');
    }

    public function getIdAreaAttribute()
    {
        $subaId = $this->attributes['subaId'];
        $subarea = SubArea::find($subaId);
        $area = Area::find($subarea->areId);

        return $area->areId;
    }

    public function getNombreAreaAttribute()
    {
        $subaId = $this->attributes['subaId'];
        $subarea = SubArea::find($subaId);
        $area = Area::find($subarea->areId);

        return $area->areNombre;
    }

    public static function precios( $subaId )
    {
        $precios = DB::table('subarea as s')->
        join('subaream as sm','s.subaId','=','sm.subaId')->
        join('nivel as n','sm.subamId','=','n.subamId')->
        join('ddatoscalculo as d','n.nivId','=','d.nivId')->
        select('d.ddatcDescripcion','d.ddatcNombre')->
        distinct('d.ddatcDescripcion')->
        where(['s.subaId'=>$subaId,'n.nivFlag'=>0])->
        whereNotNull('n.description_id')->
        orderBy('d.ddatcDescripcion')->get();

        $descriptions = Description::where('state',1)->get();

        return $precios;
    }

    public static function precio_checked( $modId,$subaId )
    {
        $precio_checked = DB::table('subarea as s')->
        join('subaream as sm','s.subaId','=','sm.subaId')->
        join('nivel as n','sm.subamId','=','n.subamId')->
        join('ddatoscalculo as d','n.nivId','=','d.nivId')->
        join('detalle_modelo_datos as dmd','d.ddatcId','=','dmd.ddatcId')->
        select('d.ddatcDescripcion','d.ddatcNombre','dmd.moddatosPiezas')->
        distinct('d.ddatcDescripcion')->
        where(['s.subaId'=>$subaId,'dmd.modId'=>$modId,'n.nivFlag'=>0])->get();

        return $precio_checked;
    }
}
