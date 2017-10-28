<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\DSubATipoC;
use App\Models\DTrabajador;
use App\Models\Nivel;
use App\Models\SubArea;
use App\Models\SubareaMenor;
use App\Models\TipoCalculo;
use Illuminate\Http\Request;

class SubAreaMenorController extends Controller
{
    function areas()
    {
        $areas = Area::where('areEstado',1)->orderBy('areId')->get();
        $tipos_calculos = TipoCalculo::where('tcalEstado',1)->get()->sortByDesc('tcalDescripcion');

        return view('mantenimiento.subarea_menor.index')->with(compact('areas','tipos_calculos'));
    }

    function subareas_activas( $area_id )
    {
        $subareas = SubArea::where('subaEstado',1)->where('areId',$area_id)->orderBy('subaId')->get();
        return ['success'=>'true','data'=> $subareas];
    }

    function subareas_menores( $subarea_id )
    {
        return ['success'=>'true','data'=>SubareaMenor::where('subaId',$subarea_id)->get()];
    }

    function tipo_calculos_activos()
    {
        return ['success'=>'true','data'=>TipoCalculo::where('tcalEstado',1)->orderBy('tcalDescripcion','desc')->get()->toArray()];
    }

    function create( Request $request )
    {
        $subarea_id = $request->get('subarea_id');
        $subarea_menor_nombre = $request->get('subarea_menor_nombre');
        $tipo_calculo_id      = $request->get('tipo_calculo_id');
        $subarea_menor_estado = $request->get('subarea_menor_estado');
        $subarea_menor_estado = $subarea_menor_estado =='on'?1:0;

        $subarea_menor = SubareaMenor::where('subamDescripcion',$subarea_menor_nombre)->first();
        if( !is_null($subarea_menor ))
            return ['success'=>'false','message'=>'Ya existe una subárea menor registrada con ese nombre'];

        $subarea_menor = new SubareaMenor();
        $subarea_menor->subamDescripcion = $subarea_menor_nombre;
        $subarea_menor->subamEstado = $subarea_menor_estado;
        $subarea_menor->subaId=$subarea_id;
        $subarea_menor->save();

        $dsubatipoc = DSubATipoC::create([
            'subamId'=>$subarea_menor->subamId,
            'tipocalId'=>$tipo_calculo_id
        ]);
        $dsubatipoc->save();

        return ['success'=>'true','message'=>'Subárea menor registrada correctamente.','subarea_id'=>$subarea_id];
    }

    function edit( Request $request )
    {
        $subamId    = $request->get('subarea_menor_id');
        $subarea_menor_nombre = $request->get('subarea_menor_nombre');
        $tipo_calculo_id      = $request->get('tipo_calculo_id');
        $subarea_menor_estado = $request->get('subarea_menor_estado');
        $subarea_menor_estado = $subarea_menor_estado =='on'?1:0;

        $nivel = Nivel::where('subamId',$subamId)->first();
        if( !is_null($nivel) ){
            if( $nivel->tipocalId <> $tipo_calculo_id )
                return ['success'=>'false','message'=>'No puede cambiar el tipo de cálculo, porque la subárea menor tiene precios asociados.'];
        }

        $subarea_menor = SubareaMenor::where('subamDescripcion',$subarea_menor_nombre)->first();
        if( !is_null($subarea_menor ) && $subarea_menor->subamId <> $subamId )
            return ['success'=>'false','message'=>'Ya existe una subárea menor registrada con ese nombre'];

        $subarea_menor = SubareaMenor::find($subamId);
        $subarea_menor->subamDescripcion = $subarea_menor_nombre;
        $subarea_menor->subamEstado = $subarea_menor_estado;

        $dsubatipoc = DSubATipoC::where('subamId',$subamId)->first();
        $dsubatipoc->tipocalId = $tipo_calculo_id;
        $dsubatipoc->save();
        $subarea_menor->save();

        return ['success'=>'true','message'=>'Subárea menor modificada correctamente.','subarea_id'=>$subarea_menor->subaId];
    }

    function delete( Request $request )
    {
        $subamId    = $request->get('subarea_menor_id');
        $subarea_menor = SubareaMenor::find($subamId);

        $dtrabajador = DTrabajador::where('subamId',$subamId)->first();
        $dsubatipoc = DSubATipoC::where('subamId',$subamId)->first();
        $precios = Nivel::where('subamId',$subamId)->first();

        if( !is_null($dtrabajador ))
            return ['success'=>'false','message'=>'No puede eliminar la subárea menor, porque existen trabajadores asociados'];

         if( !is_null($precios ))
            return ['success'=>'false','message'=>'No puede eliminar la subárea menor, porque existen precios asociados'];

        if( !is_null($dsubatipoc) )
            $dsubatipoc ->delete(); 
        $subarea_menor->delete();

        return ['success'=>'true','message'=>'Subárea menor eliminada correctamente.','subarea_id'=>$subarea_menor->subaId];
    }
}
