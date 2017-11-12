<?php

namespace App\Http\Controllers;

use App\Models\DDatosCalculo;
use App\Models\DetalleModeloDatos;
use App\Models\DetalleModeloDefecto;
use App\Models\DSubATipoC;
use App\Models\Modelo;
use App\Models\Nivel;
use App\Models\Pieza;
use App\Models\SubArea;
use App\Models\SubareaMenor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModeloTipoController extends Controller
{
    function index( $perfilado = null )
    {
        dd('xd');
        $modelos  = Modelo::take(11)->get();

        $subareas = collect();
        if( $perfilado ){
            $subareas_menores = SubareaMenor::where('subamEstado',1)->get();
            foreach ($subareas_menores as $item ){
                $dsuba = DSubATipoC::where('subamId',$item->subamId)->where('tipocalId',1)->first();
                if( $dsuba ){
                    $subareas->push($item->subarea);
                }
            }
            $subareas = $subareas->unique();
        }else
            $subareas = Subarea::where('subaEstado',1)->get();

        return view('mantenimiento.modelo_tipo.index')->with(compact('modelos','subareas','perfilado'));
    }

    function create( Request $request )
    {
        $subarea_id = $request->get('subarea_id');
        $modId   = $request->get('modelo_id');
        $ddatcId = $request->get('ddc_id');

        $pivot   = $request->get('pivot');
        $piezas  = $request->get('numero_piezas');
        
        if( !is_null($pivot) ){
            if( strlen($pivot)>0 ){
                $pieza = Pieza::where('pieInicial','<=',$piezas)->where('pieFinal','>=',$piezas)->first();
                if( count($pieza) == 0 )
                    return ['success'=>'false','message'=>'El número de piezas no se encuentra en ningún rango.'];

                $ddc_ids_ = DB::table('subarea as s')->
                join('subaream as sm','s.subaId','=','sm.subaId')->
                join('nivel as n','sm.subamId','=','n.subamId')->
                join('ddatoscalculo as d','n.nivId','=','d.nivId')->
                select('d.ddatcId')->
                where(['s.subaId'=>$subarea_id,'ddatcDescripcion'=>$pivot,'n.nivFlag'=>0])->get();
                foreach ( $ddc_ids_ as $ddc_id ) {
                    $dmd_ = DetalleModeloDatos::where('ddatcId',$ddc_id->ddatcId)->first();
                    if( count($dmd_)>0 ){
                        $dmd_->pieId = $pieza->pieId;
                        $dmd_->moddatosPiezas = $piezas;
                        $dmd_->save();
                    }
                }

                return ['success'=>'true','message'=>'Datos guardados correctamente.'];
              }
        }

        $dmd_registrados = DB::table('subarea as s')->
        join('subaream as sm','s.subaId','=','sm.subaId')->
        join('nivel as n','sm.subamId','=','n.subamId')->
        join('ddatoscalculo as d','n.nivId','=','d.nivId')->
        join('detalle_modelo_datos as dmd','d.ddatcId','=','dmd.ddatcId')->
        select('dmd.moddatosId')->
        where(['s.subaId'=>$subarea_id,'dmd.modId'=>$modId,'n.nivFlag'=>0])->get();

        if( count($dmd_registrados)>0 ){
            foreach ( $dmd_registrados as $dmd_registrado ) {
                $dmd_to_delete = DetalleModeloDatos::find($dmd_registrado->moddatosId);
                $dmd_to_delete ->delete();
            }
        }

        $ddc_ids = DB::table('subarea as s')->
        join('subaream as sm','s.subaId','=','sm.subaId')->
        join('nivel as n','sm.subamId','=','n.subamId')->
        join('ddatoscalculo as d','n.nivId','=','d.nivId')->
        select('d.ddatcId')->
        where(['s.subaId'=>$subarea_id,'ddatcDescripcion'=>$ddatcId,'n.nivFlag'=>0])->get();

        foreach ( $ddc_ids as $dmc_id ) {
            $dmd = DetalleModeloDatos::create([
                'ddatcId'=>$dmc_id->ddatcId,
                'modId'=>$modId,
                'moddatosEstado'=>1
            ]);
            $dmd->save();
        }

        return ['success'=>'true','message'=>'Datos guardados correctamente.'];
    }
}
