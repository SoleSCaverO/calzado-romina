<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DProduccion extends Model
{
    protected $table = 'dproduccion';
    protected $primaryKey = 'dprodId';
    protected $fillable = ['dprodId','prodId', 'modId','dprodColor','dprodHorma','dprodCantidad','dprodEstado'];
    public $timestamps = false;
    protected $appends = ['modelo','color','tallas','sub_detalles','subareas','genero'];

    public function getModeloAttribute()
    {
        $modId = $this->attributes['modId'];
        $modelo = Modelo::find($modId);

        return $modelo->modDescripcion;
    }

    public function getColorAttribute()
    {
        $dprodColor = $this->attributes['dprodColor'];
        $multitabla = Multitabla::where('mulDepId',2)->where('mulId',$dprodColor)->first();

        return $multitabla->mulDescripcion;
    }

    public static function tallas_modelo($modId)
    {
        // $modId = $this->attributes['modId'];

        $modelo = Modelo::find($modId);

        if( $modelo->modGenero == 1 )
            $tallas = Multitabla::where('mulDepId',4)->where('mulEstado',1)->orderBy('mulDescripcion','ASC')->get();
        elseif  ( $modelo->modGenero ==2 )
            $tallas = Multitabla::where('mulDepId',5)->where('mulEstado',1)->orderBy('mulDescripcion','ASC')->get();
        else
            $tallas = Multitabla::where('mulDepId',6)->where('mulEstado',1)->orderBy('mulDescripcion','ASC')->get();

        return $tallas;
    }

    public static function ordenes($prodId,$modelo,$color)
    {
        $orders = Orden::where('prodId',$prodId)->where('ordModelod',$modelo)->where('ordColord',$color)->get();
        return $orders;
    }

    // Dprodtallas
    public function getSubDetallesAttribute()
    {
        $dprodId    = $this->attributes['dprodId'];
        return DProdTalla::where('dprodId',$dprodId)->get();
    }

    // DProdSubareas
    public function getSubareasAttribute()
    {
        $dprodId = $this->attributes['dprodId'];
        return DProdSubArea::where('dproduccionId',$dprodId)->where('dprodsubEstado',1)->get();
    }

    public static function materiales( $dproduccionId,$subaId )
    {
        $materiales = DProdSubArea::join('dprodmat as dpm','dprodsubarea.dprodsubId','=','dpm.dprodsubId')->
            join('smaterial as smat','dpm.smatId','=','smat.smatId')->
            join('materialdefecto as md','smat.matdId','=','md.matdId')->
            select('smat.smatId','md.matdNombre','smat.smatDescripcion','dprodsubarea.dprodsubDescripcion')->
            where('dprodsubarea.dproduccionId',$dproduccionId)->where('dprodsubarea.subaId',$subaId)->get();
        $materiales->unique('smatId');

        return($materiales);
    }

    public static function material_desc( $dproduccionId,$subaId ){
        $dprod = DProdSubArea::where('dproduccionId',$dproduccionId)->where('subaId',$subaId)->first();
        return @$dprod->dprodsubDescripcion?@$dprod->dprodsubDescripcion:'';
    }

    public function getGeneroAttribute()
    {
        $modId = $this->attributes['modId'];
        $modelo = Modelo::find($modId);

        return $modelo->modGenero;
    }
}
