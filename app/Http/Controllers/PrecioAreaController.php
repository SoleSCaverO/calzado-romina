<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\DDatosCalculo;
use App\Models\DetalleModeloDatos;
use App\Models\DSubATipoC;
use App\Models\Nivel;
use App\Models\Pieza;
use App\Models\SubArea;
use App\Models\SubareaMenor;
use App\Models\TipoCalculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrecioAreaController extends Controller
{
    public function index()
    {
        $areas = Area::where('areEstado',1)->get();
        return view('mantenimiento.precio_area.index')->with(compact('areas'));
    }

    public function subareas( $areId )
    {
        $area = Area::find($areId);
        return ['success'=>'true','data'=>$area->subareas->where('subaEstado',1)];
    }

    public function subareas_menores( $subaId )
    {
        $subarea = SubArea::find($subaId );
        return ['success'=>'true','data'=>$subarea->subareas_menores->where('subamEstado',1)];
    }

    public function data( $subamId, $tipocalId, $nivId = null )
    {
        $subarea_menor = SubareaMenor::where('subamId',$subamId)->first();
        if( is_null($subarea_menor) )
            return ['success'=>'false','message'=>'No existe una subárea menor con ese código.'];

        $tipoCalculo = TipoCalculo::find($tipocalId)->where('tcalEstado',1)->first();
        if( is_null($tipoCalculo) )
            return ['success'=>'false','message'=>'No existe una tipo de cálculo con ese código.'];

        if( is_null($nivId) )
            $nivel = Nivel::where(['subamId'=>$subamId,'tipocalId'=>$tipocalId,'nivEstado'=> 1,'nivFlag'=>0])->first();
        else
            $nivel  = Nivel::find($nivId);

        $datos = collect();
        if( !is_null($nivel) ) {
            $nivIdx = $nivel->nivId;
            $datos = DDatosCalculo::where('nivId',$nivIdx)->orderBy('ddatcDescripcion')->get();
        }

        return ['success'=>'true','data'=>$datos];
    }

    public function create( Request $request )
    {
        $subarea_menor_id     = $request->get('subarea_menor_id');
        $tipocalId            = $request->get('tipo_calculo_id');

        $nivId                = $request->get('nivel_id');
        $nivId                = !is_null($nivId) ? $nivId: 0;
        $tipoPrecio           = $request->get('precio_area_tipo_precio');
        $tipoPrecio           = !is_null($tipoPrecio) ? $tipoPrecio: 3;  // 3 = Is not sent
        $ddatcDescripcion     = $request->get('precio_area_nombre');
        $ddatcDescripcion     = !is_null($ddatcDescripcion) ? $ddatcDescripcion: '';
        $ddatcNombre          = $request->get('precio_area_descripcion');
        $ddatcNombre          = !is_null($ddatcNombre) ? $ddatcNombre: '';
        $ddatcCondicion       = $request->get('precio_area_condicion');
        $ddatcCondicion       = ($ddatcCondicion=='on')?1:0;
        $ddatcMayorCondicion  = $request->get('precio_area_mayor_condicion');
        $ddatcMayorCondicion  = !is_null($ddatcMayorCondicion)?$ddatcMayorCondicion:3;
        $ddatcEstado          = $request->get('precio_area_estado');

        $ddatcEstado          = ($ddatcEstado=='on')?1:0;
        $pieId                = $request->get('pieza_id');
        $pieId                = !is_null($pieId)?$pieId:null;

        $ddatcPrecioDocena    = 0;
        $ddatcPrecioCondicion = 0;
        $ddatcDatoCondicion   = 0;
        if( !is_null($request->get('precio_area_precio')) ) {
            $ddatcPrecioDocena= $request->get('precio_area_precio');
            if(  $ddatcPrecioDocena == 0  )
                return ['success' => 'false', 'message' => 'El precio debe ser un valor positivo.'];
        }

        if( !is_null($request->get('precio_area_precio_condicion')) ){
            $ddatcPrecioCondicion = $request->get('precio_area_precio_condicion');
            if(  $ddatcPrecioCondicion == 0  )
                return ['success' => 'false', 'message' => 'El dato  condición debe ser un valor positivo.'];
        }

        if( !is_null($request->get('precio_area_numero_condicion')) ){
            $ddatcDatoCondicion  = $request->get('precio_area_numero_condicion');
            if(  $ddatcDatoCondicion == 0  )
                return ['success' => 'false', 'message' => 'El precio condición debe ser un valor positivo.'];
        }

        $subarea_menor = SubareaMenor::where('subamId',$subarea_menor_id)->where('subamEstado',1)->first();
        if( is_null($subarea_menor) )
            return ['success'=>'false','message'=>'No existe una subárea menor con ese código.'];

        $tipoCalculo = TipoCalculo::find($tipocalId)->where('tcalEstado',1)->first();
        if( is_null($tipoCalculo) )
            return ['success'=>'false','message'=>'No existe una tipo de cálculo con ese código.'];

        if(  $nivId == 0 ) { // Normal o Nivel sin id_nivel
            $nivel = Nivel::where('subamId',$subarea_menor_id)->first();
            if( is_null($nivel) ) {
                $dsubatipocal = DSubATipoC::where('subamId',$subarea_menor_id)->where('tipocalId',$tipocalId)->first();
                $nivel = Nivel::create([
                    'dsatipocal' => $dsubatipocal->dsatipocal,
                    'subamId'    => $subarea_menor_id,
                    'tipocalId'  => $tipocalId,
                    'nivEstado'  => 1,
                    'nivFlag'    => 0,
                    'nivDescripcion'=>' '
                ]);
                $nivel->save();
            }
        }else
            $nivel = Nivel::where('nivId', $nivId)->where('nivEstado',1)->first(); // NIVELES con id_nivel

        $ddc_defaultD = DB::table('nivel as n')->
        join('ddatoscalculo as ddc','n.nivId','=','ddc.nivId')->
        select('ddc.ddatcId')->
        where(['n.nivId'=>$nivel->nivId,'ddc.ddatcDescripcion'=>$ddatcDescripcion])->first();

        $ddc_defaultN = DB::table('nivel as n')->
        join('ddatoscalculo as ddc','n.nivId','=','ddc.nivId')->
        select('ddc.ddatcId')->where(['n.nivId'=>$nivel->nivId,'ddc.ddatcNombre'=>$ddatcNombre])->first();
        if( $ddatcDescripcion ){
            if( count($ddc_defaultD)>0 )
                return ['success'=>'false','message'=>'Ya existe un precio con ese nombre'];
        }

        if( $ddatcNombre ){    
            if( count($ddc_defaultN)>0 )
                return ['success'=>'false','message'=>'Ya existe un precio con esa descripción'];
        }

        if(  $nivId != 0 &&  !is_null($pieId)){
            $ddc_pieza = DB::table('nivel as n')->
            join('ddatoscalculo as ddc','n.nivId','=','ddc.nivId')->
            select('ddc.pieId')->
            where(['n.nivId'=>$nivel->nivId,'ddc.pieId'=>$pieId])->first();


            if( count($ddc_pieza)>0 )
                return ['success'=>'false','message'=>'Ya existe un precio con ese tipo de pieza.'];
        }

        if ($tipoPrecio == 1 ) {// FIJO
            $ddatosCalculo = DDatosCalculo::create([
                'nivId' => $nivel->nivId,
                'tipoPrecio' => $tipoPrecio,
                'ddatcNombre' => $ddatcNombre,
                'ddatcDescripcion' => $ddatcDescripcion,
                'ddatcPrecioDocena' => $ddatcPrecioDocena,
                'ddatcEstado' => $ddatcEstado,
                'pieId'=>$pieId?$pieId:null
            ]);
        } else {// NORMAL VARIABLE
            $ddatosCalculo = DDatosCalculo::create([
                'nivId' => $nivel->nivId,
                'tipoPrecio' => $tipoPrecio,
                'ddatcDescripcion' => $ddatcDescripcion,
                'ddatcNombre' => $ddatcNombre,
                'ddatcPrecioDocena' => $ddatcPrecioDocena,
                'ddatcCondicion' => $ddatcCondicion,
                'ddatcMayorCondicion' => $ddatcMayorCondicion,
                'ddatcDatoCondicion' => $ddatcDatoCondicion,
                'ddatcPrecioCondicion' => $ddatcPrecioCondicion,
                'ddatcEstado' => $ddatcEstado,
                'pieId'=>$pieId
            ]);
        }
        $ddatosCalculo->save();

        return ['success' => 'true','message'=>'Registro satisfactorio','subarea_menor_id'=>$subarea_menor_id,'tipo_calculo_id'=>$tipocalId];
    }

    public function edit( Request $request )
    {
        $subarea_menor_id     = $request->get('subarea_menor_id');
        $tipocalId            = $request->get('tipo_calculo_id');

        $ddatcId              = $request->get('precio_area_id');
        $ddatosCalculo_tipo   = DDatosCalculo::find($ddatcId);
        $tipoPrecio           = $ddatosCalculo_tipo->tipoPrecio;
        $ddatcDescripcion     = $request->get('precio_area_nombre');
        $ddatcDescripcion     = !is_null($ddatcDescripcion) ? $ddatcDescripcion: '';
        $ddatcNombre          = $request->get('precio_area_descripcion');
        $ddatcNombre          = !is_null($ddatcNombre) ? $ddatcNombre: '';
        $ddatcCondicion       = $request->get('precio_area_condicion');
        $ddatcCondicion       = ($ddatcCondicion=='on')?1:0;
        $ddatcMayorCondicion  = $request->get('precio_area_mayor_condicion');
        $ddatcMayorCondicion  = !is_null($ddatcMayorCondicion)?$ddatcMayorCondicion:3;
        $ddatcEstado          = $request->get('precio_area_estado');
        $ddatcEstado          = ($ddatcEstado=='on')?1:0;
        $pieId                = $request->get('pieza_id');
        $pieId                = !is_null($pieId)?$pieId:null;

        $ddatcPrecioDocena    = 0;
        $ddatcPrecioCondicion = 0;
        $ddatcDatoCondicion   = 0;
        if( !is_null($request->get('precio_area_precio')) ) {
            $ddatcPrecioDocena= $request->get('precio_area_precio');
            if(  $ddatcPrecioDocena == 0  )
                return ['success' => 'false', 'message' => 'El precio debe ser un valor positivo.'];
        }

        if( !is_null($request->get('precio_area_precio_condicion')) ){
            $ddatcPrecioCondicion = $request->get('precio_area_precio_condicion');
            if(  $ddatcPrecioCondicion == 0  )
                return ['success' => 'false', 'message' => 'El dato  condición debe ser un valor positivo.'];
        }

        if( !is_null($request->get('precio_area_numero_condicion')) ){
            $ddatcDatoCondicion  = $request->get('precio_area_numero_condicion');
            if(  $ddatcDatoCondicion == 0  )
                return ['success' => 'false', 'message' => 'El precio condición debe ser un valor positivo.'];
        }

        $ddatosCalculo = DDatosCalculo::find($ddatcId);

        $ddc_defaultD = DB::table('nivel as n')->
        join('ddatoscalculo as ddc','n.nivId','=','ddc.nivId')->
        select('ddc.ddatcId')->
        where(['n.nivId'=>$ddatosCalculo->nivel->nivId,'ddc.ddatcDescripcion'=>$ddatcDescripcion])->first();

         $ddc_defaultN = DB::table('nivel as n')->
        join('ddatoscalculo as ddc','n.nivId','=','ddc.nivId')->
        select('ddc.ddatcId')->
        where(['n.nivId'=>$ddatosCalculo->nivel->nivId,'ddc.ddatcNombre'=>$ddatcNombre])->first();

        if( $ddatcDescripcion ){
         if( count($ddc_defaultD)>0 && $ddc_defaultD->ddatcId <>  intval($ddatcId) )
            return ['success'=>'false','message'=>'Ya existe un precio con ese nombre'];
        }

        if( $ddatcNombre ){
        if( count($ddc_defaultN)>0 && $ddc_defaultN->ddatcId <>  intval($ddatcId) )
            return ['success'=>'false','message'=>'Ya existe un precio con esa descripción'];
        }

         if( $pieId ){
            $ddat = dDatosCalculo::find($ddatcId);

            if( !is_null($ddat)  and $ddat->pieId  <>  $pieId )
                return ['success'=>'false','message'=>'Ya existe un precio con ese tipo de pieza.'];
        }

        $ddatosCalculo->tipoPrecio        = $tipoPrecio;
        $ddatosCalculo->ddatcNombre       = $ddatcNombre;
        $ddatosCalculo->ddatcDescripcion     = $ddatcDescripcion;
        $ddatosCalculo->ddatcPrecioDocena = $ddatcPrecioDocena;
        $ddatosCalculo->ddatcEstado       = $ddatcEstado;

        if( $pieId ){
            $ddatosCalculo->pieId                = $pieId;
        }

        // if ($tipoPrecio == 1) // FIJO TODO: DATA OUTSIDE OF 'IF' (ALL DATA WAS GOT OUT)
        if ($tipoPrecio == 2) // VARIABLE
        {
            $ddatosCalculo->ddatcCondicion       = $ddatcCondicion;
            $ddatosCalculo->ddatcMayorCondicion  = $ddatcMayorCondicion;
            $ddatosCalculo->ddatcDatoCondicion   = $ddatcDatoCondicion;
            $ddatosCalculo->ddatcPrecioCondicion = $ddatcPrecioCondicion;
        }
        $ddatosCalculo->save();

        return ['success' => 'true', 'message' => 'Datos modificados correctamente','subarea_menor_id'=>$subarea_menor_id,'tipo_calculo_id'=>$tipocalId];
    }

    public function delete( Request $request )
    {
        $precio_area_id   = $request->get('precio_area_id');
        $subarea_menor_id = $request->get('subarea_menor_id');
        $tipocalId        = $request->get('tipo_calculo_id');

        $detalleModeloDatos = DetalleModeloDatos::where('ddatcId',$precio_area_id)->delete();

        $ddatoscalculo = DDatosCalculo::find($precio_area_id);
        $ddatoscalculo->delete();

        return ['success' => 'true', 'message' => 'Dato eliminado correctamente','subarea_menor_id'=>$subarea_menor_id,'tipo_calculo_id'=>$tipocalId];
    }

    public function piezas( $description_id )
    {
        $piezas = Pieza::where('description_id',$description_id)->where('pieEstado',1)->orderBy('pieTipo')->get();

        return ['success'=>'true','data'=>$piezas];
    }

    public function levels($subarea_menor_id, $tipo_calculo_id, $description_id )
    {
        $niveles = Nivel::where('description_id',$description_id)->where(['subamId'=>$subarea_menor_id,'tipocalId'=>$tipo_calculo_id,'nivFlag'=>1])->
                          orderBy('nivNombre','desc')->get();
        $piezas  = Pieza::where('description_id',$description_id)->where('pieEstado',1)->get();

        return view('mantenimiento.precio_area.level')->with(compact('subarea_menor_id','tipo_calculo_id','niveles','piezas','description_id'));
    }

    public function levels_list( $subarea_menor_id, $tipo_calculo_id, $description_id )
    {
        $niveles = Nivel::where('description_id',$description_id)->where(['subamId'=>$subarea_menor_id,'tipocalId'=>$tipo_calculo_id,'nivFlag'=>1])->
                          orderBy('nivNombre','desc')->get();

        return ['success'=>'true','data'=>$niveles];
    }

    public function level_create( Request $request )
    {
        $subamId        = $request->get('subarea_menor_id');
        $tipocalId      = $request->get('tipo_calculo_id');
        $nivNombre      = $request->get('nivel_nombre');
        $nivDescripcion = $request->get('nivel_descripcion');
        $nivCondicion   = $request->get('nivel_condicion');
        $nivel_inicio   = $request->get('nivel_inicio');
        $nivel_fin      = $request->get('nivel_fin');
        $nivel_infinito = $request->get('nivel_infinito');
        $nivel_infinito = ($nivel_infinito == 'on')?1:0;
        $nivEstado      = $request->get('nivel_estado');
        $description_id = $request->get('description_id');
        $nivEstado      = ($nivEstado == 'on')?1:0;
        $nivDescripcion = @$nivDescripcion?@$nivDescripcion : $nivNombre;

        $nivel = Nivel::where('description_id',$description_id )->where('nivNombre',$nivNombre)->where('subamId',$subamId)->first();
        if( $nivel <> null )
            return ['success'=>'false','message'=>'Ya existe un nivel con ese nombre'];

        if(  $nivel_inicio < 0  )
            return ['success' => 'false', 'message' => 'El valor de inicio debe ser mínimo 0.'];

        if( $nivel_infinito == 1  ){
            $nivel_fin = 99999;
        }else{
            if( $nivel_fin < 0  )
                return ['success' => 'false', 'message' => 'El valor de fin debe ser positivo.'];
            else{
                if(  $nivel_inicio >= $nivel_fin )
                    return ['success' => 'false', 'message' => 'El valor de inicio debe ser menor al valor de fin.'];
            }
        }

        $dsuba = DSubATipoC::where('subamId', $subamId)->where('tipocalId',$tipocalId)->first();
        $nivel = Nivel::create([
            'description_id' => $description_id,
            'nivDescripcion'=>$nivDescripcion,
            'nivNombre'=>$nivNombre,
            'nivCondicion'=>$nivCondicion,
            'nivInicio'=>$nivel_inicio,
            'nivFin'=>$nivel_fin,
            'nivEstado'=>$nivEstado,
            'nivFlag'=>1,
            'dsatipocal'=>$dsuba->dsatipocal,
            'subamId'=>$subamId,
            'tipocalId'=>$tipocalId,
        ]);
        $nivel->save();

        return ['success'=>'true','message'=>'Nivel registrado correctamente'];
    }

    public function level_edit( Request $request )
    {
        $nivId          = $request->get('nivel_id');
        $subamId        = $request->get('subarea_menor_id');
        $tipocalId      = $request->get('tipo_calculo_id');
        $nivNombre      = $request->get('nivel_nombre');
        $nivDescripcion = $request->get('nivel_descripcion');
        $nivCondicion   = $request->get('nivel_condicion');
        $nivel_inicio   = $request->get('nivel_inicio');
        $nivel_fin      = $request->get('nivel_fin');
        $nivel_infinito = $request->get('nivel_infinito');
        $nivel_infinito = ($nivel_infinito == 'on')?1:0;
        $nivEstado      = $request->get('nivel_estado');
        $description_id = $request->get('description_id');
        $nivEstado      = ($nivEstado == 'on')?1:0;

        $nivel = Nivel::where('description_id',$description_id)->where('nivNombre',$nivNombre)->where('subamId',$subamId)->first();
        if( !is_null($nivel) &&  $nivel->nivId <> $nivId )
            return ['success'=>'false','message'=>'Ya existe un nivel con ese nombre'];

        if(  $nivel_inicio < 0  )
            return ['success' => 'false', 'message' => 'El valor de inicio debe ser mínimo 0.'];

        if( $nivel_infinito == 1  ){
            $nivel_fin = 99999;
        }else{
            if( $nivel_fin < 0  )
                return ['success' => 'false', 'message' => 'El valor de fin debe ser positivo.'];
            else{
                if(  $nivel_inicio >= $nivel_fin )
                    return ['success' => 'false', 'message' => 'El valor de inicio debe ser menor al valor de fin.'];
            }
        }

        $nivel = Nivel::find($nivId);

        $nivel->nivDescripcion = $nivDescripcion;
        $nivel->nivNombre      = $nivNombre;
        $nivel->nivCondicion   = $nivCondicion;
        $nivel->nivInicio      = $nivel_inicio;
        $nivel->nivFin         = $nivel_fin;
        $nivel->nivEstado      = $nivEstado;
        $nivel->subamId         = $subamId;
        $nivel->tipocalId      = $tipocalId;
        $nivel->save();

        return ['success'=>'true','message'=>'Nivel modificado correctamente.'];
    }

    public function level_delete( Request $request )
    {
        $nivId         = $request->get('nivel_id');
        $dDatosCalculo = DDatosCalculo::where('nivId',$nivId)->first();

        if( !is_null($dDatosCalculo) )
            return ['success'=>'false','message'=>'El nivel no puede ser eliminado porque existen detalles asociados.'];
        $nivel = Nivel::find($nivId);
        $nivel->delete();

        return ['success'=>'true','message'=>'Nivel eliminado correctamente.'];
    }
}
