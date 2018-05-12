<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Ficha;
use App\Models\FichaArea;
use App\Models\FichaMateriales;
use App\Models\Modelo;
use App\Models\Multitabla;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Exception;

class RecordController extends Controller
{
    public function index()
    {
        $fichas = Ficha::all();
        return view('fichas.disenio.index')->with(compact('fichas'));
    }

    public function create()
    {
        $models = Modelo::take(10)->get();
        $customers = Cliente::take(10)->get();
        $colors  = Multitabla::where('mulDepId',2)->get();
        $areas = FichaArea::all();
        $today = new Carbon();
        $today->tz = 'America/Lima';
        $today = $today->format('Y-m-d');

        return view('fichas.disenio.create')
            ->with(compact('models','customers','colors','areas','today'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try{
            $ficha = new Ficha();
            $ficha->coleccion = $data['coleccion'];
            $ficha->genero = $data['genero'];
            $ficha->marca = $data['marca'];
            $ficha->horma = $data['horma'];
            $ficha->color = $data['color_id'];
            $ficha->modelista = $data['modelista'];
            $ficha->fecha = $data['fecha'];
            $ficha->cliente_id = $data['cliente_id'];
            $ficha->modelo_id  = $data['modelo_id'];
            $ficha->talla = $data['talla'];
            $ficha->piezas_cuero = $data['cuero'];
            $ficha->piezas_forro = $data['forro'];
            $ficha->modelaje = $data['modelaje'];
            $ficha->produccion = $data['produccion'];
            $ficha->gerencia = $data['gerencia'];
            $ficha->observacion = $data['observacion'];

            $imagen1 = $request->file('imagen1');
            $imagen2 = $request->file('imagen2');
            $imagen3 = $request->file('imagen3');
            $imagen4 = $request->file('imagen4');

            $imagenes = [$imagen1,$imagen2,$imagen3,$imagen4];
            $path = public_path().'/images/fichas';
            foreach ($imagenes as $key => $imagen){
                $extension = $imagen->getClientOriginalExtension();
                $fileName = date('d-m-Y_h-i-s') . '_'.$key.'.' . $extension;
                $imagen->move($path, $fileName);
                if( $key == 0 ){
                    $ficha->imagen_derecha = $fileName;
                }elseif ( $key===1 ){
                    $ficha->imagen_izquierda = $fileName;
                }elseif ( $key===2 ){
                    $ficha->imagen_arriba = $fileName;
                }else{
                    $ficha->imagen_atras = $fileName;
                }
            }
            $ficha->save();

            /*MATERIALES ARMADO*/
            $materiales = [$data['falsa'],$data['contrafuerte'],$data['puntera'],$data['talon']];
            foreach ( $materiales as $material) {
                FichaMateriales::create([
                    'ficha_id' => $ficha->id,
                    'area_id' => 7,
                    'nombre' => $material
                ]);
            }
            /*MATERIALES ARMADO*/

            /*MATERIALES ENCAJADO*/
            $materiales = [$data['caja'],$data['papel'],$data['hantan'],$data['bolsa']];
            foreach ( $materiales as $material) {
                FichaMateriales::create([
                    'ficha_id' => $ficha->id,
                    'area_id' => 8,
                    'nombre' => $material
                ]);
            }
            /*MATERIALES ENCAJADO*/

            /*MATERIALES HAB. PLANTILLA*/
            if(@$data['latex'] && @$data['retacon']){
                $lastMaterial = 'AMBOS';
            }else if(@$data['latex']){
                $lastMaterial = 'LATEX';
            }else if(@$data['retacon']){
                $lastMaterial = 'RETACON';
            }else{
                $lastMaterial = 'NINGUNO';
            }

            $materiales = [$data['sello_pan_oro'],$data['sello_especificaion'],$data['troquel'],$lastMaterial];
            foreach ( $materiales as $material) {
                FichaMateriales::create([
                    'ficha_id' => $ficha->id,
                    'area_id' => 9,
                    'nombre' => $material
                ]);
            }
            /*MATERIALES HAB. PLANTILLA*/

            /*MATERIALES DE OTRAS AREAS*/
            $areas = (json_decode($data['areas']));
            foreach ($areas as $area){
                foreach ($area->materials as $material){
                    FichaMateriales::create([
                        'ficha_id' => $ficha->id,
                        'area_id' => $area->id,
                        'nombre' => $material->material,
                        'piezas' => @$material->pieza
                    ]);
                }
            }
            /*MATERIALES DE OTRAS AREAS*/
            DB::commit();

            return ['success'=>true,'message'=>'Datos guardados correctamente.'];

        }catch (\Exception $e){
            DB::rollback();
            return ['success'=>false,'message'=>$e->getMessage().' Línea: '. $e->getLine()];
        }
    }

    public function show($id,$pdf=null)
    {
        $ficha = Ficha::find($id);
        /* CUERO - FORRO - PLANTILLA */
        $FORRO_DEFAULT = 3;
        $PLANTILLA_DEFAULT = 2;
        $nroMatCuero = $ficha->numeroMateriales($ficha->id,1);
        $nroMatForro = $ficha->numeroMateriales($ficha->id,2);
        $nroMatPlantilla = $ficha->numeroMateriales($ficha->id,3);
        $nroMatCueroFaltante = 0;
        $nroMatForroFaltante = 0;
        $nroMatPlantillaFaltante = 0;

        if($nroMatForro < $FORRO_DEFAULT ){
            $nroMatForroFaltante = $FORRO_DEFAULT - $nroMatForro;
            $nroMatForro = $FORRO_DEFAULT;
        }

        if($nroMatPlantilla< $PLANTILLA_DEFAULT ){
            $nroMatPlantillaFaltante = $PLANTILLA_DEFAULT - $nroMatPlantilla;
            $nroMatPlantilla = $PLANTILLA_DEFAULT;
        }
        $diffCuero = 0; // PAra ancho de imágenes grandes
        $extraNroPlantilla = 0; // Columnas extras en Plantilla
        if( $nroMatCuero >= $nroMatForro + $nroMatPlantilla ){
            $diferencia = $nroMatCuero - ($nroMatForro + $nroMatPlantilla);
            $nroMatPlantilla += $diferencia;
            $diffCuero = $diferencia;
        }else{
            $diferencia  = $nroMatForro + $nroMatPlantilla - $nroMatCuero;
            $nroMatCueroFaltante = $diferencia;
            $nroMatCuero += $diferencia;
            $diffCuero = $diferencia;
        }

        if( $nroMatPlantilla > $ficha->numeroMateriales($ficha->id,3)){
            $extraNroPlantilla = $nroMatPlantilla - $ficha->numeroMateriales($ficha->id,3) - $nroMatPlantillaFaltante;
        }
        //dd($extraNroPlantilla,$nroMatPlantilla, $ficha->numeroMateriales($ficha->id,3));
        $range = range('A','Z');
        /* CUERO - FORRO - PLANTILLA */

        /* PERFILADO - COSIDO VENA - PEGADO */
        $COSIDO_DEFAULT = 2;
        $PEGADO_DEFAULT = 2;
        $nroMatPerfilado = $ficha->numeroMateriales($ficha->id,4);
        $nroMatCosido = $ficha->numeroMateriales($ficha->id,5);
        $nroMatPegado = $ficha->numeroMateriales($ficha->id,6);

        if( $nroMatCosido < $COSIDO_DEFAULT ){
            $nroMatCosido = $COSIDO_DEFAULT;
        }

        if( $nroMatPegado < $PEGADO_DEFAULT ){
            $nroMatPegado = $PEGADO_DEFAULT;
        }

        $nroMatPerfilado = intval(ceil($nroMatPerfilado/2));
        if( $nroMatPerfilado > ($nroMatCosido + $nroMatPegado + 1) ){
            $diferencia = $nroMatPerfilado - ($nroMatCosido + $nroMatPegado + 1);
            $nroMatPegado += $diferencia;
        }else{
            $diferencia = ($nroMatCosido + $nroMatPegado + 1) - $nroMatPerfilado;
            $nroMatPerfilado += $diferencia;
        }

        $perfilado = $ficha->material($ficha->id,4);
        $cosido  = $ficha->material($ficha->id,5);
        $pegado  = $ficha->material($ficha->id,6);

        $columna1 = [];$columna2 = [];$columna3 = [];
        $perfilado = $perfilado->toArray();
        if( count($perfilado) >= $nroMatPerfilado ){
            for( $i=0;$i<$nroMatPerfilado;$i++ ){
                array_push($columna1,$perfilado[$i]['nombre']);
            }

            for( $i=$nroMatPerfilado;$i<2*$nroMatPerfilado;$i++ ){
                array_push($columna2,@$perfilado[$i]?$perfilado[$i]['nombre']:'');
            }
        }else{
            for( $i=0;$i<$nroMatPerfilado;$i++ ){
                array_push($columna1, @$perfilado[$i] ? $perfilado[$i]['nombre'] : '');
            }
            for( $i=0;$i<$nroMatPerfilado;$i++ ){
                array_push($columna2,'');
            }
        }

        foreach ( $cosido as $item ){
            array_push($columna3,$item->nombre);
        }

        if( count($cosido) < $COSIDO_DEFAULT ){
            array_push($columna3,'');
        }
        array_push($columna3,'PEGADO');
        foreach ( $pegado as $item ){
            array_push($columna3,$item->nombre);
        }

        if( count($pegado) < $PEGADO_DEFAULT ){
            array_push($columna3,'');
        }
        /* PERFILADO - COSIDO VENA - PEGADO */

        /* ARMADO - HAB. PLANTILLA - ENCAJADO */
        $armado = $ficha->material($ficha->id,7);
        $encajado = $ficha->material($ficha->id,8);
        $habPlantilla = $ficha->material($ficha->id,9);
        /* ARMADO - HAB. PLANTILLA - ENCAJADO */

        if( $pdf == 'pdf'  ){
            $view = view('fichas.disenio.pdf')->with(compact(
                'ficha', 'nroMatCuero', 'nroMatForro', 'nroMatPlantilla',
                'nroMatCueroFaltante', 'nroMatForroFaltante', 'nroMatPlantillaFaltante', 'range',
                'nroMatPerfilado', 'nroMatCosido', 'columna1', 'columna2', 'columna3',
                'armado', 'encajado', 'habPlantilla',
                'diffCuero', 'extraNroPlantilla'
            ));

            $pdf = app('dompdf.wrapper');

            $pdf->loadHTML($view);
            return $pdf->stream();
        }
        return view('fichas.disenio.show')->with(compact(
            'ficha', 'nroMatCuero', 'nroMatForro', 'nroMatPlantilla',
            'nroMatCueroFaltante', 'nroMatForroFaltante', 'nroMatPlantillaFaltante', 'range',
            'nroMatPerfilado', 'nroMatCosido', 'columna1', 'columna2', 'columna3',
            'armado', 'encajado', 'habPlantilla',
            'diffCuero', 'extraNroPlantilla'
        ));
    }

    public function indexSales()
    {
        $fichas = Ficha::all();
        return view('fichas.ventas.index')->with(compact('fichas'));
    }

    public function createSales()
    {
        $models = Modelo::take(10)->get();
        $customers = Cliente::take(10)->get();
        $colors  = Multitabla::where('mulDepId',2)->get();
        $areas = FichaArea::all();

        return view('fichas.ventas.create')
            ->with(compact('models','customers','colors','areas'));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
