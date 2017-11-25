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
        $precios = Subarea::
        join('subaream as sm','subarea.subaId','=','sm.subaId')->
        join('nivel as n','sm.subamId','=','n.subamId')->
        join('ddatoscalculo as d','n.nivId','=','d.nivId')->
        select('d.ddatcDescripcion','d.ddatcNombre')->
        distinct('d.ddatcDescripcion')->
        where(['subarea.subaId'=>$subaId,'n.nivFlag'=>0])->
        whereNull('n.description_id')->
        orderBy('d.ddatcDescripcion')->get();

        $lista_precios = [];
        $lista_descripciones = [];
        foreach ( $precios as $precio ){
            array_push($lista_precios,['ddatcDescripcion'=>$precio['ddatcDescripcion'],'ddatcNombre'=>$precio['ddatcNombre']]);
            array_push($lista_descripciones,$precio['ddatcNombre']);
        }

        $mostrar_descripciones = false;
        $subarea = SubArea::find($subaId);
        $subareas_menores = $subarea->subareas_menores;
        foreach ( $subareas_menores as $subareas_menor ){
            $dsuba_tipocal = DSubATipoC::where('subamId',$subareas_menor->subamId)->first();
            if( $dsuba_tipocal->tipocalId == 2 ){
                $mostrar_descripciones = true;
            }
        }

        if(  $mostrar_descripciones ) {
            $descriptions = DB::table('subarea')->
            join('subaream as sm','subarea.subaId','=','sm.subaId')->
            join('nivel as n','sm.subamId','=','n.subamId')->
            join('ddatoscalculo as d','n.nivId','=','d.nivId')->
            join('descriptions','descriptions.id','=','n.description_id')->
            select('descriptions.id','descriptions.name','descriptions.description')->
            distinct('d.ddatcDescripcion')->
            whereNotNull('n.description_id')->
            where(['subarea.subaId'=>$subaId])->get();
            if( count( $descriptions ) ) {
                foreach ($descriptions as $description) {
                    if (!in_array($description->description, $lista_descripciones))
                        array_push($lista_precios, ['ddatcNombre' => $description->name, 'ddatcDescripcion' => $description->description]);
                }
            }
        }

        return $lista_precios;
    }

    public static function precio_checked( $modId,$subaId )
    {
        $precio_checked = SubArea::join('subaream as sm','subarea.subaId','=','sm.subaId')->
        join('nivel as n','sm.subamId','=','n.subamId')->
        join('ddatoscalculo as d','n.nivId','=','d.nivId')->
        join('detalle_modelo_datos as dmd','d.ddatcId','=','dmd.ddatcId')->
        select('d.ddatcDescripcion','d.ddatcNombre','dmd.moddatosPiezas')->
        distinct('d.ddatcNombre')->
        whereNull('n.description_id')->
        where(['subarea.subaId'=>$subaId,'dmd.modId'=>$modId,'n.nivFlag'=>0])->get();

        $is_checked = false;
        foreach ( $precio_checked as $item ) {
            if( !is_null($item->moddatosPiezas) ){
                $is_checked = true;
                break;
            }
        }
        $mostrar_descripcion_checked = false;
        $subarea = SubArea::find($subaId);
        $subareas_menores = $subarea->subareas_menores;
        foreach ( $subareas_menores as $subareas_menor ){
            $dsuba_tipocal = DSubATipoC::where('subamId',$subareas_menor->subamId)->first();
            if( $dsuba_tipocal->tipocalId == 1 ){
                $mostrar_descripcion_checked = true;
            }
        }

        if( $mostrar_descripcion_checked ) {
            if( count($precio_checked) == 0 ||  !$is_checked ) {
                $precio_checked = DetalleModeloDatos::join('descriptions', 'descriptions.id', '=', 'detalle_modelo_datos.description_id')->
                where('modId', $modId)->
                select(
                    'descriptions.name as ddatcDescripcion',
                    'descriptions.description as ddatcNombre',
                    'detalle_modelo_datos.moddatosPiezas'
                )->get();

                foreach ($precio_checked as $item) {
                    if (!is_null($item->moddatosPiezas)) {
                        return [$item];
                    }
                }
            }
        }

        return $precio_checked;
    }
}