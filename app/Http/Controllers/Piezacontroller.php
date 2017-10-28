<?php

namespace App\Http\Controllers;

use App\Models\DDatosCalculo;
use App\Models\DetalleModeloDatos;
use App\Models\Pieza;
use Illuminate\Http\Request;

class Piezacontroller extends Controller
{
    function index()
    {
        $piezas = Pieza::all();

        return view('mantenimiento.pieza.index')->with(compact('piezas'));
    }

    function pieza_list()
    {
        $piezas = Pieza::all();

        return ['success'=>'true','data'=>$piezas];
    }

    function cruce_intervalos( $valor ){
        return Pieza::where('pieInicial','<=',$valor)->where('pieFinal','>=',$valor)->first();
    }

    function create( Request $request )
    {
//        dd($request->all());
        $pieTipo     = $request->get('pieza_tipo');
        $pieMultiplo = $request->get('pieza_multiplo');
        $pieConsider = $request->get('pieza_consider');
        $pieInicial  = $request->get('pieza_inicio');
        $pieFinal    = $request->get('pieza_fin');
        $pie_infi    = $request->get('pieza_infinito');
        $pieEstado   = $request->get('pieza_estado');
        $pieEstado   = ($pieEstado =='on')?1:0;
        $pieFlag     = 0;

        if( $pieConsider == 'on' ){ // No consider
            $pieInicial = null;
            $pieFinal = null;
            $pieFlag = 1;
        }else{
            $pieza_inicial = Pieza::where('pieInicial',$pieInicial)->first();
            if( !is_null($pieza_inicial) )
               return ['success'=>'false','message'=>'Ya existe una pieza con ese valor de inicio'];
            $cruce = $this->cruce_intervalos($pieInicial);
            if( count($cruce) )
                return ['success'=>'false','message'=>'La pieza de inicio se cruza en el intervalo:'.$cruce->pieInicial.'-'.($cruce->pieFinal==99999?'Infinito':$cruce->pieFinal)];
  
            if(  $pie_infi == 'on' )
                $pieFinal=99999;

            $pieza_final   = Pieza::where('pieFinal',$pieFinal)->first();
            if( !is_null($pieza_final) )
                return ['success'=>'false','message'=>'Ya existe una pieza con ese valor de fin'];
            
            $cruce = $this->cruce_intervalos($pieFinal);
            if( count($cruce) )
                return ['success'=>'false','message'=>'La pieza de fin se cruza en el intervalo:'.$cruce->pieInicial.'-'.($cruce->pieFinal==99999?'Infinito':$cruce->pieFinal)];

            if( $pieInicial>$pieFinal )
                return ['success'=>'false','message'=>'El número de piezas inicial debe ser menor al número de piezas final'];
        }

        $pieza = Pieza::create([
            'pieTipo'=>$pieTipo,
            'pieMultiplo'=>$pieMultiplo,
            'pieInicial'=>$pieInicial,
            'pieFinal'=>$pieFinal,
            'pieEstado'=>$pieEstado,
            'pieFlag'=>$pieFlag
        ]);

        $pieza->save();

        return ['success'=>'true','message'=>'Pieza guardada correctamente.'];
    }

    function edit( Request $request )
    {
        $pieId       = $request->get('pieza_id');
        $pieTipo     = $request->get('pieza_tipo');
        $pieMultiplo = $request->get('pieza_multiplo');
        $pieConsider = $request->get('pieza_consider');
        $pieInicial  = $request->get('pieza_inicio');
        $pieFinal    = $request->get('pieza_fin');
        $pie_infi    = $request->get('pieza_infinito');
        $pieEstado   = $request->get('pieza_estado');
        $pieEstado   = ($pieEstado =='on')?1:0;

        if( $pieConsider == 'on' ){ // No consider
            $pieInicial = null;
            $pieFinal = null;
        }else{
            $pieza_inicial = Pieza::where('pieInicial',$pieInicial)->first();
            if( !is_null($pieza_inicial) and $pieId != $pieza_inicial->pieId )
               return ['success'=>'false','message'=>'Ya existe una pieza con ese valor de inicio'];
            $cruce = $this->cruce_intervalos($pieInicial);
            if( count($cruce) and $pieId != $cruce->pieId)
                return ['success'=>'false','message'=>'La pieza de inicio se cruza en el intervalo:'.$cruce->pieInicial.'-'.($cruce->pieFinal==99999?'Infinito':$cruce->pieFinal)];

            if(  $pie_infi == 'on' )
                $pieFinal=99999;
            
            $pieza_final   = Pieza::where('pieFinal',$pieFinal)->first();
            if( !is_null($pieza_final) and $pieId != $pieza_final->pieId )
                return ['success'=>'false','message'=>'Ya existe una pieza con ese valor de fin'];
            
            $cruce = $this->cruce_intervalos($pieFinal);
            if( count($cruce) and $pieId != $cruce->pieId )
                return ['success'=>'false','message'=>'La pieza de fin se cruza en el intervalo:'.$cruce->pieInicial.'-'.($cruce->pieFinal==99999?'Infinito':$cruce->pieFinal)];

            if( $pieInicial>$pieFinal )
                return ['success'=>'false','message'=>'El número de piezas inicial debe ser menor al número de piezas final'];
        }

        $pieza = Pieza::find($pieId);
        $pieza ->pieTipo = $pieTipo;
        $pieza ->pieMultiplo = $pieMultiplo;
        $pieza ->pieInicial = $pieInicial;
        $pieza ->pieFinal = $pieFinal;
        $pieza ->pieEstado = $pieEstado;
        $pieza->save();

        return ['success'=>'true','message'=>'Pieza modificada correctamente.'];

    }

    function delete( Request $request )
    {
        $pieId        = $request->get('pieza_id');
        $ddatoscaculo = DDatosCalculo::where('pieId',$pieId)->first();
        $detallemodelodatos = DetalleModeloDatos::where('pieId',$pieId)->first();
        if( !is_null($ddatoscaculo) )
            return ['success'=>'false','message'=>'La pieza no puede ser eliminada porque existen datos asociados.'];

        if( !is_null($detallemodelodatos) )
            return ['success'=>'false','message'=>'La pieza no puede ser eliminada porque exiten datos asociados.'];

        $pieza = Pieza::find($pieId);
        $pieza->delete();

        return ['success'=>'true','message'=>'Pieza eliminada correctamente.'];
    }

    function validate_name( Request $request ){
        $name = $request->get('pieza_tipo');
        $id = $request->get('id');
        
        $pieza = Pieza::where('pieTipo',$name)->first();
        if( !is_null($pieza) and $id != $pieza->pieId )
            return response()->json(false);

        return response()->json(true);
    }

    function validate_start( Request $request ){
        $name = $request->get('pieza_inicio');
        $id = $request->get('id');

        $pieza = Pieza::where('pieInicial',$name)->first();
        if( !is_null($pieza) and $id != $pieza->pieId )
            return response()->json('Ya existe una pieza con ese inicio.');

        $cruce = $this->cruce_intervalos($name);
        if( !is_null($cruce) and $id != $cruce->pieId )
            return response()->json('El inicio se cruza en el intervalo: '.$cruce->pieInicial.'-'.($cruce->pieFinal==99999?'Infinito':$cruce->pieFinal));

        return response()->json(true);
    }

    function validate_end( Request $request ){
        $name = $request->get('pieza_fin');
        $id = $request->get('id');

        $pieza = Pieza::where('pieFinal',$name)->first();
        if( !is_null($pieza) and $id != $pieza->pieId )
            return response()->json('Ya existe una pieza con ese fin.');

        $cruce = $this->cruce_intervalos($name);
        if( !is_null($cruce) and $id != $cruce->pieId )
            return response()->json('El fin se cruza en el intervalo: '.$cruce->pieInicial.'-'.($cruce->pieFinal==99999?'Infinito':$cruce->pieFinal));

        return response()->json(true);
    }
}
