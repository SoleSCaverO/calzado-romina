<?php

namespace App\Http\Controllers;

use App\Models\DProdSubArea;
use App\Models\DSubATipoC;
use App\Models\DTrabajador;
use App\Models\Nivel;
use App\Models\Proceso;
use App\Models\RepMaterial;
use App\Models\SMaterial;
use App\Models\SubArea;
use App\Models\TipoCalculo;
use App\Models\SubareaMenor;
use Illuminate\Http\Request;

class SubareaController extends Controller
{
    public function create( Request $request )
    {
        $areId = $request->get('area_id');
        $subaDescripcion = $request->get('subarea_nombre');
        $subaDespacho    = $request->get('subarea_despacho');
        $subaOp          = $request->get('subarea_op');
        $subaEstado      = $request->get('subarea_estado');
        $subaDespacho    = ($subaDespacho)?1:0;
        $subaOp          = ($subaOp)?1:0;
        $subaEstado      = ($subaEstado=='on')?1:0;

        $subarea_test = SubArea::where('subaDescripcion',$subaDescripcion)->first();
        if(  !is_null($subarea_test) )
            return ['success'=>'false','message'=>'Ya existe una subárea con ese nombre.'];

        $subarea = SubArea::create([
            'areId'=>$areId,
            'subaDescripcion'=>$subaDescripcion,
            'subaDespacho'=>$subaDespacho,
            'subaOrdenp'=>$subaOp,
            'subaEstado'=>$subaEstado
        ]);
        $subarea->save();

        return ['success'=>'true','message'=>'Subárea registrada correctamente.','area_id'=>$areId];
    }

    public function edit( Request $request )
    {
        $subaId = $request->get('subarea_id');
        $subaDescripcion = $request->get('subarea_nombre');
        $subaDespacho    = $request->get('subarea_despacho');
        $subaOp          = $request->get('subarea_op');
        $subaEstado      = $request->get('subarea_estado');
        $subaDespacho    = ($subaDespacho)?1:0;
        $subaOp          = ($subaOp)?1:0;
        $subaEstado      = ($subaEstado=='on')?1:0;

        $subareaNombre   = SubArea::where('subaDescripcion',$subaDescripcion)->first();
        if(  !is_null($subareaNombre) && $subareaNombre->subaId <> $subaId )
            return ['success'=>'false','message'=>'Ya existe una subárea con ese nombre.'];

        $subarea = SubArea::where('subaId',$subaId)->first();
        if( is_null($subarea) )
            return ['success'=>'false','message'=>'No existe una subárea con ese código.'];

        $subarea = SubArea::find($subaId);
        $subarea->subaDescripcion = $subaDescripcion;
        $subarea->subaDespacho    = $subaDespacho;
        $subarea->subaOrdenp      = $subaOp;
        $subarea->subaEstado      = $subaEstado;
        $area_id = $subarea->areId;
        $subarea->save();

        return ['success'=>'true','message'=>'Subárea modificada correctamente.','area_id'=>$area_id];
    }

    public function delete( Request $request )
    {
        $subaId = $request->get('subarea_id');

        $subareamenor = SubareaMenor::where('subaId',$subaId)->first();
        if( !is_null($subareamenor) )
            return ['success'=>'false','message'=>'La subárea no puede ser eliminada porque exiten subáreas menores asociadas.'];

        $repMaterial = RepMaterial::where('subaId',$subaId)->first();
        if( !is_null($repMaterial) )
            return ['success'=>'false','message'=>'La subárea no puede ser eliminada porque exiten REP_MATERIAL asociados .'];

        $dprodSubarea = DProdSubArea::where('subaId',$subaId)->first();
        if( !is_null($dprodSubarea) )
            return ['success'=>'false','message'=>'La subárea no puede ser eliminada porque exiten DETALLES_PRODUCCIÓN asociados .'];

        $smaterial = SMaterial::where('subaId',$subaId)->first();
        if( !is_null($smaterial) )
            return ['success'=>'false','message'=>'La subárea no puede ser eliminada porque exiten S_MATERIAL asociados .'];

        $subarea = SubArea::find($subaId);
        $area_id = $subarea->areId;
        $subarea->delete();

        return ['success'=>'true','message'=>'Subárea eliminada correctamente.','area_id'=>$area_id];
    }

}
