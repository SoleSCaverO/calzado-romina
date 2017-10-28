<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\DTrabajador;
use App\Models\Iniciotrab;
use App\Models\SubareaMenor;
use App\Models\Trabajador;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TrabajadorController extends Controller
{
    public function index()
    {
        $areas = Area::where('areEstado',1)->get();
        return view('mantenimiento.trabajador.index')->with(compact('areas'));
    }

    public function add_zeros( $dato,$cantidad )
    {
        $ceros = '';
        for( $i=0;$i<($cantidad-strlen($dato));$i++ )
        {
            $ceros.=0;
        }

        return $ceros.$dato;
    }

    public function search_dni( $trabajador_dni,$subarea_menor_id )
    {
        $trabajador = Trabajador::where('traDni',$trabajador_dni)->first();
        if( is_null($trabajador) )
            return ['existe'=>0];

        $subarea_menor = SubareaMenor::find($subarea_menor_id);
        $dtrabajador = DTrabajador::where('traId', $trabajador->traId)->get();
        foreach ( $dtrabajador as $item) {
            $subarea_menor_ = SubareaMenor::find($item->subamId);
            if( $subarea_menor_->subaId ==  $subarea_menor->subaId )
                return ['existe'=>1,'mensaje'=>'Ya existe un trabajador con ese DNI, en esta subárea.'];
        }

        return ['existe'=>1,'mensaje'=>''];
    }

    public function create( Request $request )
    {
        $subamId = $request->get('subarea_menor_id');
        $dtraSueldo = $request->get('trabajador_tipo_trabajo');
        $traDni = $request->get('trabajador_dni');
        $traNombre = $request->get('trabajador_nombres');
        $traApellidos = $request->get('trabajador_apellidos');
        $traApellidos = !is_null( $traApellidos) ? $traApellidos: '';
        $traEstado = $request->get('trabajador_estado');
        $traExiste = $request->get('trabajador_existe');
        $traEstado  = ($traEstado=='on')?1:0;

        $trabajador = Trabajador::where('traDni', $traDni)->first();

        if( is_null($trabajador) ){
            $trabajador = Trabajador::create([
                'traDni'=>$traDni,
                'traNombre'=>$traNombre,
                'traApellidos'=>$traApellidos,
                'traEstado'=>$traEstado,
            ]);
            $trabajador->save();
        }
        $dTrabajador = new DTrabajador();
        $dTrabajador->traId      = $trabajador->traId;
        $dTrabajador->subamId     = $subamId;
        $dTrabajador->dtraEstado = 1;
        $dTrabajador->dtraSueldo = $dtraSueldo;

        $subarea_menor   = SubareaMenor::where('subamId',$subamId)->first();
        $subarea_menor_id = $subarea_menor->subamId;
        $subareaId    = $subarea_menor->subarea->subaId;
        $areaId    = $subarea_menor->subarea->area->areId;

        $dtraCodigobarras =
            self::add_zeros($areaId,4).
            self::add_zeros($subareaId,4).
            self::add_zeros($subarea_menor_id,4).
            self::add_zeros($trabajador->traId,5).
            $dtraSueldo.
            self::add_zeros($traEstado,3);
        $dTrabajador->dtraCodigobarras = $dtraCodigobarras;
        $dTrabajador->save();

        return ['success'=>'true','message'=>'Trabajador registrado correctamente.','subarea_menor_id'=>$subamId];
    }

    public function edit( Request $request )
    {
        $subamId = $request->get('subarea_menor_id');
        $traId = $request->get('trabajador_id');
        $dtraSueldo = $request->get('trabajador_tipo_trabajo');
        $traDni = $request->get('trabajador_dni');
        $traNombre = $request->get('trabajador_nombres');
        $traApellidos = $request->get('trabajador_apellidos');
        $traApellidos = !is_null( $traApellidos) ? $traApellidos: '';
        $traEstado = $request->get('trabajador_estado');
        $traExiste = $request->get('trabajador_existe');
        $traEstado  = ($traEstado=='on')?1:0;

        $trabajador = Trabajador::where('traDni', $traDni)->first();
        if ( !is_null($trabajador) ) {
            $dtrabajador = DTrabajador::where(['traId'=>$trabajador->traId,'subamId'=>$subamId])->first();// It's in this subarea
            if( !is_null($dtrabajador) and $trabajador->traId <> $traId)
                return ['success' => 'false', 'message' => 'Ya existe un trabajador con ese DNI en esta subárea.'];
        }

        $trabajador = Trabajador::find($traId);
        $trabajador->traDni       = $traDni;
        $trabajador->traNombre    = $traNombre;
        $trabajador->traApellidos = $traApellidos;
        $trabajador->traEstado    = $traEstado;
        $trabajador->save();

        $dtrabajador = DTrabajador::where('traId',$trabajador->traId)->where('subamId',$subamId)->first();
        $dtrabajador->dtraSueldo = $dtraSueldo;
        $dtrabajador->save();

        return ['success'=>'true','message'=>'Trabajador modificado correctamente.','subarea_menor_id'=>$subamId];
    }

    public function delete( Request $request )
    {
        $subamId = $request->get('subarea_menor_id');
        $traId = $request->get('trabajador_id');

        $inicio = Iniciotrab::where('traId',$traId)->first();
        
        if( count($inicio) >0 ){
            return ['success'=>'false','message'=>'El trabajador tiene inicios de trabajao asociados, eliminelos para eliminar el trabajador.'];
        }

        $dtrabajador = DTrabajador::where(['traId'=>$traId,'subamId'=>$subamId])->first();
        $dtrabajador->delete();

        $dtrabajadores = DTrabajador::where('traId',$traId)->get();
        

        if( count($dtrabajadores) == 0  ) {
            $trabajador = Trabajador::find($traId);
            $trabajador->delete();
        }

        return ['success'=>'true','message'=>'Trabajador eliminado correctamente.','subarea_menor_id'=>$subamId];
    }

    public function workers( $subamId )
    {
        $subarea = SubareaMenor::where('subamId',$subamId)->where('subamEstado',1)->first();
        $trabajadores = $subarea->trabajadores->where('traEstado',1)->unique('traId');

        return ['success'=>'true','data'=>$trabajadores];
    }

    public function type_works()
    {
        return ['types_work'=>DTrabajador::all()];
    }

    public static function workers_excel()
    {
        Excel::create('Reporte de Trabajadores', function($excel){
            $excel->sheet('Trabajadores', function ($sheet) {

                // Cabecera Reporte de Facturas
                $sheet->mergeCells('B2:J3');
                $sheet->cells('B2:J3', function($cells) {
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    $cells->setFontColor('#000000');
                    $cells->setBackGround('#336600');
                    $cells->setFontWeight('bold');
                    $cells->setFontFamily('Calibri');
                    $cells->setFontSize(20);
                    $cells->setAlignment('center');
                    $cells->setVAlignment('center');
                });

                $sheet->cells('B4:J4', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setFontFamily('Calibri');
                    $cells->setAlignment('center');
                    $cells->setVAlignment('center');
                });
                $sheet->setBorder('B4'.':J4', 'thin');

                $sheet->getStyle('B4:J4')->getAlignment()->applyFromArray(
                    array('horizontal' => 'center')
                );

                $header = [];
                $spaces = '       ';
                $date = new Carbon();
                $date = $date->format('d-m-Y');
                $header[] = array($spaces,'REPORTE DE TRABAJADORES '.$date);
                $sheet->fromArray($header,null,'A2',false,false);
                $header = [];
                $header[] = array($spaces,'CÓDIGO','ÁREA','SUBAREA','SUBÁREA MENOR','TIPO SUELDO','NOMBRES','APELLIDOS',' DNI','ESTADO');
                $sheet->fromArray($header,null,'A3',false,false);

                $trabajadores = Trabajador::all();
                $datos = [];
                foreach ( $trabajadores as $trabajador ) {
                    $trabajdorNombre = $trabajador->traNombre;
                    $trabajadorApellidos = $trabajador->traApellidos;
                    $trabajadorDni = $trabajador->traDni;
                    $trabajadorEstado = ($trabajador->traEstado == 1) ? 'Activo' : 'Inactivo';
                    foreach ($trabajador->detalles as $detalle) {
                        {
                            $subarea_menor = SubareaMenor::find($detalle->subamId);
                            $subarea_menor_nombre = $subarea_menor->subamDescripcion;
                            $subarea_nombre = $subarea_menor->subarea->subaDescripcion;
                            $area_nombre   =$subarea_menor->subarea->area->areNombre;

                            $codigo = $detalle->dtraCodigobarras;
                            $dtrabajadorSueldo = $detalle->tipo_sueldo;
                            $datos [] = [$spaces, $codigo, $area_nombre,$subarea_nombre , $subarea_menor_nombre, $dtrabajadorSueldo, $trabajdorNombre, $trabajadorApellidos, $trabajadorDni, $trabajadorEstado];
                        }
                    }
                }

                $i=4;
                foreach ($datos as $dato){
                    $sheet->setBorder('B'.$i.':J'.$i, 'thin');
                    $sheet->getStyle('B'.$i.':J'.$i)->getAlignment()->setWrapText(true);
                    $sheet->appendRow($dato);
                    $i+=1;
                }
                $sheet->setBorder('B'.$i.':J'.$i, 'thin');
            })->export('xlsx');
        });
    }

}
