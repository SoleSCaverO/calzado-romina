<?php

namespace App\Http\Controllers;

use App\Models\DDatosCalculo;
use App\Models\DPlanilla;
use App\Models\InicioTrab;
use App\Models\Planilla;
use App\Models\SubareaMenor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PlanillaController extends Controller
{
    function index()
    {
        $planillas = Planilla::orderBy('plaId', 'desc')->get();

        return view('planilla.index')->with(compact('planillas'));
    }

    function planillas()
    {
        $planillas = Planilla::orderBy('plaId', 'desc')->get();
        return ['success' => 'true', 'data' => $planillas];
    }

    function filter($fecha_inicio, $fecha_fin)
    {
        $planillas = Planilla::whereDate('plaFechaInicio', '>=', $fecha_inicio)->
        whereDate('plaFechaInicio', '<=', $fecha_fin)->orderBy('plaId', 'desc')->get();
        return ['success' => 'true', 'data' => $planillas];
    }

    function create(Request $request)
    {
        $plaFechaInicio = $request->get('fecha_inicio');
        $plaFechaFin = $request->get('fecha_fin');
        $plaHoraInicio = $request->get('hora_inicio');
        $plaHoraFin = $request->get('hora_fin');
        $plaEstado = 1;

        //FECHA INICIO - HORA INICIO
        $fechaInicio = new Carbon($plaFechaInicio);
        $fechaInicio = $fechaInicio->format('Y-m-d');

        $horaInicio = new Carbon($plaHoraInicio);
        $horaInicio = $horaInicio->format('H:i:s');

        $planillaInicio = $fechaInicio . ' ' . $horaInicio;

        //FECHA FIN - HORA FIN
        $fechaFin = new Carbon($plaFechaFin);
        $fechaFin = $fechaFin->format('Y-m-d');

        $horaFin = new Carbon($plaHoraFin);
        $horaFin = $horaFin->format('H:i:s');

        $planillaFin = $fechaFin . ' ' . $horaFin;

        $planilla = Planilla::create([
            'plaFechaInicio' => $planillaInicio,
            'plaFechaFin' => $planillaFin,
            'plaEstado' => $plaEstado
        ]);
        $planilla->save();

        return ['success' => 'true', 'message' => 'Planilla registrada correctamente.'];
    }

    function edit(Request $request)
    {
        $plaId = $request->get('planilla_id');
        $plaFechaInicio = $request->get('fecha_inicio');
        $plaFechaFin = $request->get('fecha_fin');
        $plaHoraInicio = $request->get('hora_inicio');
        $plaHoraFin = $request->get('hora_fin');

        //FECHA INICIO - HORA INICIO
        $fechaInicio = new Carbon($plaFechaInicio);
        $fechaInicio = $fechaInicio->format('Y-m-d');

        $horaInicio = new Carbon($plaHoraInicio);
        $horaInicio->tz = 'America/Lima';
        $horaInicio = $horaInicio->format('H-i-s');

        $planillaInicio = $fechaInicio . ' ' . $horaInicio;

        //FECHA FIN - HORA FIN
        $fechaFin = new Carbon($plaFechaFin);
        $fechaFin = $fechaFin->format('Y-m-d');

        $horaFin = new Carbon($plaHoraFin);
        $horaFin->tz = 'America/Lima';
        $horaFin = $horaFin->format('H-i-s');

        $planillaFin = $fechaFin . ' ' . $horaFin;

        $planilla = Planilla::find($plaId);
        $planilla->plaFechaInicio = $planillaInicio;
        $planilla->plaFechaFin = $planillaFin;
        $planilla->save();

        return ['success' => 'true', 'message' => 'Planilla modificada correctamente.'];
    }

    function delete(Request $request)
    {
        $plaId = $request->get('planilla_id');
        $dplanilla = DPlanilla::where('plaId', $plaId)->first();

        if (!is_null($dplanilla))
            return ['success' => 'false', 'message' => 'No puede eliminar la planilla porque tiene detalles asociados.'];

        $planilla = Planilla::find($plaId);
        $planilla->delete();

        return ['success' => 'true', 'message' => 'Planilla eliminada correctamente.'];
    }

    function subareas_menores($planilla_id)
    {
        $planilla = Planilla::find($planilla_id);
        $subareas_menores = SubareaMenor::all();
        return view('planilla.subareas-menores')->with(compact('planilla', 'subareas_menores'));
    }

    function trabajadores($planilla_id, $subarea_menor_id)
    {
        $planilla = Planilla::find($planilla_id);
        $subarea_menor = SubareaMenor::find($subarea_menor_id);
        $subarea_menor_nombre = $subarea_menor->subamDescripcion;
        $trabajadores = $subarea_menor->trabajadores;

        return view('planilla.trabajadores')->with(compact('planilla', 'trabajadores', 'subarea_menor_id', 'subarea_menor_nombre'));
    }

    function pago($planilla_id, $subarea_menor_id, $trabajador_id)
    {
        $planilla = Planilla::find($planilla_id);
        $inicios = InicioTrab::where('initrabFecha', '>=', $planilla->plaFechaInicio)->
        where('initrabFecha', '<=', $planilla->plaFechaFin)->where('traId', $trabajador_id)->
        where('subamId', $subarea_menor_id)->get();

        $total = 0;
        foreach ($inicios as $inicio)
            $total += $inicio->initrabCantidad;

        $ddatos_ids = collect();
        foreach ($inicios as $inicio) {
            $id = $inicio->descripcion($subarea_menor_id, $inicio->orden->ordModelod,0);
            $ddc = DDatosCalculo::find($id);
            if( ! is_null($ddc) )
                $ddatos_ids->push($id);
        }

        if( count($ddatos_ids) ){
            $ddatos_ids->unique();
        }

        $data = collect();
        foreach ( $ddatos_ids as $ddatos_id ) {
            $dato = [];
            $ddc_ = DDatosCalculo::find($ddatos_id);
            $suma = 0;
            foreach ( $inicios as $inicio ){
                $id = $inicio->descripcion($subarea_menor_id, $inicio->orden->ordModelod,0);
                if( $id == $ddatos_id )
                    $suma += $inicio->initrabCantidad;
            }
            $dato['description'] = $ddc_->ddatcDescripcion;
            $dato['name'] = $ddc_->ddatcNombre;
            $dato['pairs'] = $suma;
            $dato['price'] = $ddc_->ddatcPrecioDocena;
            $dato['total'] = number_format( (($ddc_->ddatcPrecioDocena)/12)*$suma ,2);
            $data->push($dato);
        }

        $payment = 0;
        foreach ( $data as $item ){
            $payment+= $item['total'];
        }

        return view('planilla.pago')->with(compact('inicios','planilla_id','subarea_menor_id','trabajador_id','total','data','payment'));
    }
}
