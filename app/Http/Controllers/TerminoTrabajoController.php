<?php

namespace App\Http\Controllers;

use App\FinTrab;
use App\Models\DTrabajador;
use App\Models\InicioTrab;
use App\Models\Orden;
use App\Models\SubareaMenor;
use App\Models\Trabajador;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TerminoTrabajoController extends Controller
{
    function index()
    {
        $fines = FinTrab::orderBy('FinTrabFecha','desc')->get();

        return view('produccion.termino-trabajo.index')->with(compact('fines'));
    }

    function create()
    {
        $today = new Carbon();
        $today->tz = 'America/Lima';
        $today = $today->toDateString();

        return view('produccion.termino-trabajo.create')->with(compact('today'));
    }

    function update( $fin_id )
    {
        $fin = FinTrab::find($fin_id);
        $date = new Carbon($fin->fintrabFecha);
        $date = $date->toDateString();

        $time = new Carbon($fin->fintrabFecha);
        $time = $time->format('g:i A');


        $dtrabajador = DTrabajador::where('traId',$fin->trabIdy)->first();
        $type_work = $dtrabajador->dtraSueldo==1?'Fijo':'Destajo';
        $trabajador = Trabajador::find($fin->trabIdy);
        $data = $trabajador->area_subarea($trabajador->traId);
        $area = $data['area'];
        $subarea = $data['subarea'];
        $trabajador_nombre_completo = $trabajador->traNombre.', '.$trabajador->traApellidos;

        $pares_disponibles = FinTrab::where('initrabId',$fin->inicio->initrabId)->sum('FinTrabCantidad');
        $pares = $fin->inicio->initrabCantidad - $pares_disponibles;
        $pares = $pares?$pares:0;
        $cantidad_orden = $fin->inicio->orden->ordCantidad;

        return view('produccion.termino-trabajo.edit')->with(compact('fin','date','time','area','subarea','type_work','pares','trabajador_nombre_completo','cantidad_orden'));
    }

    function edit( Request $request)
    {
        $fin_id    = $request->get('fin_id');
        $pairs_user   = $request->get('pairs_user');
        $description  = $request->get('description');

        $fin = FinTrab::find($fin_id);
        $inicio = InicioTrab::find($fin->initrabId);
        $cantidad_fin = $inicio->initrabCantidad;

        $asignados  = FinTrab::where('initrabId',$fin->initrabId)->
        where('fintrabId','<>',$fin->fintrabId)->sum('fintrabCantidad');

        $disponible = $cantidad_fin-$asignados;

        if( $pairs_user > $disponible )
            return ['success' =>'false','message'=>'El número de pares disponibles para terminar es: '.$disponible];

        $fin->fintrabCantidad    = $pairs_user;
        $fin->fintrabObservacion = $description;
        $fin->save();

        return ['success'=>'true','message'=>'Datos guardados correctamente.'];
    }

    function area_subarea_type_work( $worker_code )
    {
        $dtrabajador = DTrabajador::where('dtraCodigobarras',$worker_code)->first();
        if( is_null($dtrabajador) ){
            return ['success'=>'false','message'=>'No existe un trabajador con ese código.'];
        }

        $subare_menor = SubareaMenor::find($dtrabajador->subamId);
        $trabajador   = Trabajador::find($dtrabajador->traId);

        $data['id'] = $dtrabajador->traId ;
        $data['area'] = $subare_menor->subarea->area->areNombre;;
        $data['subarea'] = $subare_menor->subarea->subaDescripcion;;
        $data['type_work_id'] = $dtrabajador ->dtraSueldo;
        $data['type_work_name'] = $dtrabajador ->dtraSueldo==1?'Fijo':'Destajo';
        $data['nombres'] = $trabajador ->traNombre.' '.$trabajador->traApellidos;
        
        return ['success'=>'true','data'=>$data];
    }

    function change_type_work( $worker_code,$type_work )
    {
        $dtrabajador = DTrabajador::where('dtraCodigobarras',$worker_code)->first();
        if( is_null($dtrabajador) ){
            return ['success'=>'false','message'=>'No existe un trabajador con ese código.'];
        }
        $type_work = ($type_work==1)?2:1;
        $dtrabajador->dtraSueldo = $type_work;
        $dtrabajador->save();

        if( $type_work == 1 )
            $type = 'Fijo';
        else
            $type = 'Destajo';

        return ['success'=>'true','data'=>['type_id'=>$type_work,'type_name'=>$type]];
    }

    function search_order( $worker_code,$order_code )
    {
        $dtrabajador = DTrabajador::where('dtraCodigobarras',$worker_code)->first();
        if( is_null($dtrabajador) ){
            return ['success'=>'false','message'=>'No existe un trabajador con ese código.'];
        }

        $orden = Orden::where('ordCodigo',$order_code)->first();
        if( is_null($orden)) {
            return ['success' =>'false','message'=>'No existe una orden con ese código.'];
        }

        $inicio = InicioTrab::where('ordIdx',$orden->ordIdx)->where('dtraId',$dtrabajador->dtraId)->first();
        if( is_null($inicio)){
            return ['success' =>'false','message'=>'La orden no ha sido iniciada por ese trabajador.'];
        }

        $cantidad_asignada = FinTrab::where('initrabId',$inicio->initrabId)->sum('fintrabCantidad');
        $cantidad_asignada = $cantidad_asignada?$cantidad_asignada:0;
        return ['success'=>'true','order'=>$inicio->orden->ordCantidad,'data'=>$inicio->initrabCantidad,'cant_asignada'=>$cantidad_asignada];
    }

    function store( Request $request )
    {
        $worker_code  = $request->get('worker_code');
        $date         = $request->get('date');
        $time         = $request->get('time');
        $type_work_id = $request->get('type_work_id');
        $order_code   = $request->get('search_order');
        $pairs_user   = $request->get('pairs_user');
        $description  = $request->get('description');


        $time = new Carbon($time);
        $time = $time->format('H:i:s');
        $date = $date.' '.$time;

        $dtrabajador = DTrabajador::where('dtraCodigobarras',$worker_code)->first();
        if( is_null($dtrabajador) ){
            return ['success'=>'false','message'=>'No existe un trabajador con ese código.'];
        }

        $orden = Orden::where('ordCodigo',$order_code)->first();
        if( is_null($orden)) {
            return ['success' =>'false','message'=>'No existe una orden con ese código.'];
        }

        $inicio = InicioTrab::where('ordIdx',$orden->ordIdx)->first();
        if( is_null($inicio)){
            return ['success' =>'false','message'=>'La orden no ha sido iniciada aún.'];
        }

        $inicio = InicioTrab::where('ordIdx',$orden->ordIdx)->where('traId',$dtrabajador->traId)->first();

        $cantidades_en_fines = FinTrab::where('initrabId',$inicio->initrabId)->sum('fintrabCantidad');
        $cantidad_de_inicio     = $inicio->initrabCantidad;

        $total = $cantidades_en_fines+$pairs_user;

        if( $total > $cantidad_de_inicio ){
            return ['success' =>'false','message'=>'El número de pares disponibles para terminar es: '.
                ($cantidad_de_inicio-$cantidades_en_fines)];
        }

        $fintrab = FinTrab::create([
            'fintrabFecha'=> $date,
            'fintrabCantidad' => $pairs_user,
            'fintrabEstado' => 1,
            'fintrabTipotrabajo' => $type_work_id,
            'fintrabObservacion' => $description?$description:'',
            'ordenIdy' => $orden->ordIdx,
            'trabIdy' => $dtrabajador->traId,
            'initrabId'=>$inicio->initrabId
        ]);
        $fintrab->save();

        return ['success' =>'true','message'=>'Fin de trabajo creado correctamente.'];
    }

    function delete( Request $request )
    {
        $fin_id = $request->get('id');
        $fin    = FinTrab::find($fin_id);
        $fin->delete();

        return ['success'=>'true','message'=>'Dato eliminado correctamente.'];
    }
}
