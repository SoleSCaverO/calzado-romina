<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Ficha;
use App\Models\FichaArea;
use App\Models\FichaMateriales;
use App\Models\Modelo;
use App\Models\Multitabla;
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

        return view('fichas.disenio.create')
            ->with(compact('models','customers','colors','areas'));
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
                $lastMaterial = 'retacon';
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

    public function show($id)
    {
        //
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
