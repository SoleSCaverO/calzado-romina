<?php

namespace App\Http\Controllers;

use App\Models\DDatosCalculo;
use App\Models\Description;
use App\Models\DetalleModeloDatos;
use App\Models\Pieza;
use Illuminate\Http\Request;

class Piezacontroller extends Controller
{
    function index()
    {
        $descriptions = Description::all();

        return view('mantenimiento.pieza.index')->with(compact('descriptions'));
    }

    function pieza_list($description_id)
    {
        $piezas = Pieza::where('description_id',$description_id)->get();

        return ['success'=>'true','data'=>$piezas];
    }

    function cruce_intervalos( $valor, $description_id ){
        return Pieza::where('description_id',$description_id)->
            where('pieInicial','<=',$valor)->
            where('pieFinal','>=',$valor)->first();
    }

    function create( Request $request )
    {
        $data = $request->all();
        $pieTipo     = $data['pieza_tipo'];
        $pieMultiplo = $data['pieza_multiplo'];
        $pieConsider = @$data['pieza_consider'];
        $pieInicial  = @$data['pieza_inicio'];
        $pieFinal    = @$data['pieza_fin'];
        $pie_infi    = @$data['pieza_infinito'];
        $pieEstado   = @$data['pieza_estado'];
        $description_id = $data['description_id'];
        $pieEstado   = ($pieEstado =='on')?1:0;
        $pieFlag     = 0;

        if( $pieConsider == 'on' ){ // No consider
            $pieInicial = null;
            $pieFinal = null;
            $pieFlag = 1;
        }else{
            $pieza_inicial = Pieza::where('description_id',$description_id)->
                where('pieInicial',$pieInicial)->first();
            if( !is_null($pieza_inicial) )
               return ['success'=>'false','message'=>'Ya existe una pieza con ese valor de inicio'];
            $cruce = $this->cruce_intervalos($pieInicial,$description_id);
            if( count($cruce) )
                return ['success'=>'false','message'=>'La pieza de inicio se cruza en el intervalo:'.$cruce->pieInicial.'-'.($cruce->pieFinal==99999?'Infinito':$cruce->pieFinal)];
  
            if(  $pie_infi == 'on' )
                $pieFinal=99999;

            $pieza_final   = Pieza::where('description_id',$description_id)->where('pieFinal',$pieFinal)->first();
            if( !is_null($pieza_final) )
                return ['success'=>'false','message'=>'Ya existe una pieza con ese valor de fin'];
            
            $cruce = $this->cruce_intervalos($pieFinal,$description_id);
            if( count($cruce) )
                return ['success'=>'false','message'=>'La pieza de fin se cruza en el intervalo:'.$cruce->pieInicial.'-'.($cruce->pieFinal==99999?'Infinito':$cruce->pieFinal)];

            if( $pieInicial>$pieFinal )
                return ['success'=>'false','message'=>'El número de piezas inicial debe ser menor al número de piezas final'];
        }

        $pieza = Pieza::create([
            'description_id' => $description_id,
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
        $data = $request->all();
        $pieId       = $data['pieza_id'];
        $pieTipo     = $data['pieza_tipo'];
        $pieMultiplo = $data['pieza_multiplo'];
        $pieConsider = @$data['pieza_consider'];
        $pieInicial  = @$data['pieza_inicio'];
        $pieFinal    = @$data['pieza_fin'];
        $pie_infi    = @$data['pieza_infinito'];
        $pieEstado   = @$data['pieza_estado'];
        $description_id = $data['description_id'];
        $pieEstado   = ($pieEstado =='on')?1:0;

        if( $pieConsider == 'on' ){ // No consider
            $pieInicial = null;
            $pieFinal = null;
        }else{
            $pieza_inicial = Pieza::where('description_id',$description_id)->where('pieInicial',$pieInicial)->first();
            if( !is_null($pieza_inicial) and $pieId != $pieza_inicial->pieId )
               return ['success'=>'false','message'=>'Ya existe una pieza con ese valor de inicio'];
            $cruce = $this->cruce_intervalos($pieInicial,$description_id);
            if( count($cruce) and $pieId != $cruce->pieId)
                return ['success'=>'false','message'=>'La pieza de inicio se cruza en el intervalo:'.$cruce->pieInicial.'-'.($cruce->pieFinal==99999?'Infinito':$cruce->pieFinal)];

            if(  $pie_infi == 'on' )
                $pieFinal=99999;
            
            $pieza_final   = Pieza::where('description_id',$description_id)->where('pieFinal',$pieFinal)->first();
            if( !is_null($pieza_final) and $pieId != $pieza_final->pieId )
                return ['success'=>'false','message'=>'Ya existe una pieza con ese valor de fin'];
            
            $cruce = $this->cruce_intervalos($pieFinal,$description_id);
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
        $data = $request->all();
        $name = $data['pieza_tipo'];
        $id   = $data['id'];
        $description_id = $data['description_id'];
        
        $pieza = Pieza::where('description_id',$description_id)->where('pieTipo',$name)->first();
        if( !is_null($pieza) and $id != $pieza->pieId )
            return response()->json(false);

        return response()->json(true);
    }

    function validate_start( Request $request ){
        $data = $request->all();
        $name = $data['pieza_inicio'];
        $id   = $data['id'];
        $description_id = $data['description_id'];

        $pieza = Pieza::where('description_id',$description_id)->where('pieInicial',$name)->first();
        if( !is_null($pieza) and $id != $pieza->pieId )
            return response()->json('Ya existe una pieza con ese inicio.');

        $cruce = $this->cruce_intervalos($name,$description_id);
        if( !is_null($cruce) and $id != $cruce->pieId )
            return response()->json('El inicio se cruza en el intervalo: '.$cruce->pieInicial.'-'.($cruce->pieFinal==99999?'Infinito':$cruce->pieFinal));

        return response()->json(true);
    }

    function validate_end( Request $request ){
        $data = $request->all();
        $name = $data['pieza_fin'];
        $id   = $data['id'];
        $description_id = $data['description_id'];

        $pieza = Pieza::where('description_id',$description_id)->where('pieFinal',$name)->first();
        if( !is_null($pieza) and $id != $pieza->pieId )
            return response()->json('Ya existe una pieza con ese fin.');

        $cruce = $this->cruce_intervalos($name,$description_id);
        if( !is_null($cruce) and $id != $cruce->pieId )
            return response()->json('El fin se cruza en el intervalo: '.$cruce->pieInicial.'-'.($cruce->pieFinal==99999?'Infinito':$cruce->pieFinal));

        return response()->json(true);
    }
}
