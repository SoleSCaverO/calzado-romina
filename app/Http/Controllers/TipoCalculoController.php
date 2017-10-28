<?php

namespace App\Http\Controllers;

use App\Models\DSubATipoC;
use App\Models\TipoCalculo;
use Illuminate\Http\Request;

class TipoCalculoController extends Controller
{
    public function index()
    {
        $tipo_calculos = TipoCalculo::paginate(4);
        return view('mantenimiento.tipo_calculo.index')->with(compact('tipo_calculos'));
    }

    public function create( Request $request )
    {
        $tcalDescripcion = $request->get('tc_nombre');
        $tcalTipo        = $request->get('tc_nivel');
        $tcalEstado      = $request->get('tc_estado');
        $tcalTipo        = ($tcalTipo =='on')?2:1;
        $tcalEstado      = ($tcalEstado =='on')?1:0;
        $tipoCalculo = TipoCalculo::where('tcalDescripcion',$tcalDescripcion)->first();
        if( !is_null($tipoCalculo) )
            return ['success'=>'false','message'=>'Ya existe un tipo de cálculo con ese nombre.'];

        $tipoCalculo = TipoCalculo::create([
            'tcalDescripcion'=>$tcalDescripcion,
            'tcalEstado'=>$tcalEstado,
            'tcalTipo'=>$tcalTipo
        ]);
        $tipoCalculo->save();

        return ['success'=>'true','message'=>'Tipo de cálculo registrado correctamente.'];
    }

    public function tipo_calculos( $position )
    {
        $tipo_calculos = TipoCalculo::all();
        return ['success'=>'true','data'=>$tipo_calculos->chunk(4)[$position-1]];
    }

    public function edit( Request $request )
    {
        $tcalId = $request->get('tc_id');
        $tcalDescripcion = $request->get('tc_nombre');
        $tcalTipo        = $request->get('tc_nivel');
        $tcalEstado      = $request->get('tc_estado');
        $tcalTipo        = ($tcalTipo =='on')?2:1;
        $tcalEstado      = ($tcalEstado =='on')?1:0;

        $tipoCalculo = TipoCalculo::where('tcalDescripcion',$tcalDescripcion)->first();
        if( !is_null($tipoCalculo) && $tipoCalculo->tcalId <> $tcalId  )
            return ['success'=>'false','message'=>'Ya existe un tipo de cálculo con ese nombre.'];

        $tipoCalculo = TipoCalculo::find($tcalId);
        $tipoCalculo->tcalDescripcion = $tcalDescripcion ;
        $tipoCalculo->tcalEstado      = $tcalEstado ;
        $tipoCalculo->tcalTipo        = $tcalTipo ;
        $tipoCalculo->save();

        return ['success'=>'true','message'=>'Tipo de cálculo modificado correctamente.'];
    }

    public function delete( Request $request )
    {
        $tcalId = $request->get('tc_id');

        $dsubatipocal = DSubATipoC::where('tipocalId',$tcalId)->first();
        if( !is_null($dsubatipocal) )
            return ['success'=>'false','message'=>'No puede eliminar el tipo de cálculo porque exiten subáreas asociadas.'];

        $tipocal = TipoCalculo::find($tcalId);
        $tipocal->delete();

        return ['success'=>'true','message'=>'Tipo de cálculo eliminado correctamente.'];
    }
}
