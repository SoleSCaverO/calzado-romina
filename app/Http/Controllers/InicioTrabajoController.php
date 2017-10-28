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
use App\Models\Nivel;
use App\Models\DDatosCalculo;
use App\Models\DetalleModeloDatos;

class InicioTrabajoController extends Controller
{
    function index()
    {
        $inicios = InicioTrab::orderBy('initrabFecha','desc')->get();
        return view('produccion.inicio-trabajo.index')->with(compact('inicios'));
    }

    function create()
    {
        $today = new Carbon();
        $today->tz = 'America/Lima';
        $today = $today->toDateString();
        return view('produccion.inicio-trabajo.create')->with(compact('today'));
    }

    function update( $inicio_id )
    {
        $inicio = InicioTrab::find($inicio_id);
        $date = new Carbon($inicio->initrabFecha);
        $date = $date->toDateString();

        $time = new Carbon($inicio->initrabFecha);
        $time = $time->format('g:i A');

        $dtrabajador = DTrabajador::where('traId',$inicio->traId)->first();
        $type_work = $dtrabajador->dtraSueldo==1?'Fijo':'Destajo';
        $trabajador = Trabajador::find($inicio->traId);
        $data = $trabajador->area_subarea($trabajador->traId);
        $area = $data['area'];
        $subarea = $data['subarea'];
        $trabajador_nombre_completo = $trabajador->traNombre.', '.$trabajador->traApellidos;

        $pares_disponibles = InicioTrab::where('ordIdx',$inicio->orden->ordIdx)->sum('initrabCantidad');
        $pares = $inicio->orden->ordCantidad - $pares_disponibles;
        $pares = $pares?$pares:0;

        return view('produccion.inicio-trabajo.edit')->with(compact('inicio','date','time','area','subarea','type_work','pares','trabajador_nombre_completo'));
    }

    function edit( Request $request)
    {
        $inicio_id    = $request->get('inicio_id');
        $pairs_user   = $request->get('pairs_user');
        $description  = $request->get('description');

        $inicio = InicioTrab::find($inicio_id);
        $orden  = Orden::find($inicio->ordIdx);
        $cantidad_orden = $orden->ordCantidad;

        $asignados  = InicioTrab::where('ordIdx',$inicio->ordIdx)->
                      where('initrabId','<>',$inicio->initrabId)->sum('initrabCantidad');

        $disponible = $cantidad_orden-$asignados;

        if( $pairs_user > $disponible )
            return ['success' =>'false','message'=>'El número de pares disponibles para asignar es: '.$disponible];

        $inicio->initrabCantidad    = $pairs_user;
        $inicio->initrabObservacion = $description;
        $inicio->save();

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

    function search_order( $order_code )
    {
        $orden = Orden::where('ordCodigo',$order_code)->first();
        if( is_null($orden)) {
            return ['success' =>'false','message'=>'No existe una orden con ese código.'];
        }

        $cantidad_asignada = InicioTrab::where('ordIdx',$orden->ordIdx)->sum('initrabCantidad');
        $cantidad_asignada = $cantidad_asignada?$cantidad_asignada:0;
        return ['success'=>'true','data'=>$orden->ordCantidad,'cant_asignada'=>$cantidad_asignada];
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

        $modelo_id = $orden->ordModelod;
        $subamId = $dtrabajador->subamId;
        $nivel   = Nivel::where('subamId',$subamId)->first();
        $ddaatos = DDatosCalculo::where('nivId',$nivel->nivId)->get();

        $detalle_modelo_datos = DetalleModeloDatos::where('modId',$modelo_id)->get();

        if( count($detalle_modelo_datos) == 0 ){
            return ['success' =>'false','message'=>'El modelo de esta orden no tiene una descripción asociada.'];
        }

        $array_detalles = [];
        foreach ($detalle_modelo_datos as $value) {
            array_push($array_detalles,$value->ddatcId);
        }

        $array_datos = [];
        foreach ($ddaatos as $value) {
            array_push($array_datos,$value->ddatcId);
        }


        $coincidences = array_intersect($array_detalles, $array_datos);
        if( count(coincidences) == 0 )
            return ['success' =>'false','message'=>'Error, No existe para este trabajdor dentro de su subárea menor, la descripción seleccionada con este modelo.'];
        
        $inicio = InicioTrab::where('ordIdx',$orden->ordIdx)->where('traId',$dtrabajador->traId)->first();
        if( !is_null($inicio) )
            return ['success' =>'false','message'=>'Usted ya ha sido asignado a esta orden.'];

        $cantidades_en_inicios = InicioTrab::where('ordIdx',$orden->ordIdx)->sum('initrabCantidad');
        $cantidad_de_orden    = $orden->ordCantidad;

        $total = $cantidades_en_inicios+$pairs_user;

        if( $total > $cantidad_de_orden ){
            return ['success' =>'false','message'=>'El número de pares disponibles para asignar es: '.
                ($cantidad_de_orden-$cantidades_en_inicios)];
        }

        $initrab = InicioTrab::create([
            'initrabFecha'=> $date,
            'initrabCantidad' => $pairs_user,
            'initrabEstado' => 1,
            'initrabTipotrabajo' => $type_work_id,
            'initrabObservacion' => $description?$description:'',
            'ordIdx' => $orden->ordIdx,
            'dtraId' => $dtrabajador->dtraId,
            'subamId' => $dtrabajador->subamId,
            'traId' => $dtrabajador->traId
        ]);
        $initrab->save();

        return ['success' =>'true','message'=>'Inicio de trabajo creado correctamente.'];
    }

    function delete( Request $request )
    {
        $inicio_id = $request->get('id');
        $inicio    = InicioTrab::find($inicio_id);
        $fin = FinTrab::where('initrabId',$inicio_id)->first();

        if( !is_null($fin) )
            return ['success'=>'false','message'=>'Existen fin de trabajos asociados a este inicio de trabajao.'];
        $inicio->delete();

        return ['success'=>'true','message'=>'Dato eliminado correctamente.'];
    }
}
