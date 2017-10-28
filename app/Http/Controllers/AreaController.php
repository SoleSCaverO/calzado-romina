<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\SubArea;
use App\Models\TipoCalculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::paginate(6);

        return view('mantenimiento.area.index')->with(compact('areas'));
    }

    public function subareas( $areId )
    {
        $area = Area::find($areId);
        if( is_null($area) )
            return ['success'=>'false','message'=>'No existe un área con ese código.'];

        $subareas = SubArea::where('areId',$areId)->get();
        if( count($subareas) == 0 )
            return ['success'=>'false','message'=>'No existen subareas asociadas a ese modelo.'];

        return ['success'=>'true','data'=>$subareas];
    }

    public function areas( $position )
    {
        $areas = Area::all();
        if( count($areas) == 0 )
            return ['success'=>'false','message'=>'No existen áreas activas.'];

        return ['success'=>'true','data'=>$areas->chunk(4)[$position-1],'number_areas'=>count($areas)];
    }

    public function create(Request $request )
    {
        $areNombre = $request->get('area_nombre');
        $areEstado = $request->get('area_estado');
        $areEstado = ($areEstado=='on')?1:0;

        if( strlen($areNombre)==0 )
            return ['success'=>'false','message'=>'Debe ingresar el nombre del área..'];

        $area_test = Area::where('areNombre',$areNombre)->first();
        if( !is_null($area_test) )
            return ['success'=>'false','message'=>'Ya existe un área con ese nombre.'];

        $area = Area::create([
            'areNombre'=>$areNombre,
            'areEstado'=>$areEstado,
            'areProceso'=>0,
            'arePerfilado'=>0,
        ]);
        $area->save();

        return ['success'=>'true','message'=>'Área registrada correctamente.'];
    }

    public function edit(Request $request)
    {
        $areId  = $request->get('area_id');
        $areNombre    = $request->get('area_nombre');
        $areEstado = $request->get('area_estado');
        $areEstado = ($areEstado=='on')?1:0;

        if( strlen($areNombre)==0 )
            return ['success'=>'false','message'=>'Debe ingresar el nombre del área..'];

        $area_test = Area::where('areNombre',$areNombre)->first();
        if( !is_null($area_test) && $area_test->areId <> $areId )
            return ['success' => 'false', 'message' => 'Ya existe un área con ese nombre.'];
/*
        if(!is_null($area_test) ) {
            $subareas = SubArea::where('areId',$areId)->get();
            if(  count($subareas)>0 && ($area_test->areProceso <> $areProceso || $area_test->arePerfilado <> $arePerfilado) )
                return ['success'=>'false','message'=>'Existen subáreas que heredan las características de esta área.'];
        }
*/

        $area = Area::find($areId);
        $area->areNombre = $areNombre;
        $area->areEstado = $areEstado;
        $area->save();

        return ['success'=>'true','message'=>'Área modificada correctamente.'];
    }

    public function delete(Request $request )
    {
        $areId  = $request->get('area_id');
        $subareas = SubArea::where('areId',$areId)->first();
        if( !is_null($subareas) )
            return ['success'=>'false','message'=>'El área indicada no puede ser eliminada, porque tiene subáreas asociadas.'];

        $area = Area::find($areId);
        $area->delete();

        return ['success'=>'true','message'=>'Área eliminada correctamente.'];
    }
}
