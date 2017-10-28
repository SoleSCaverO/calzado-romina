<?php

namespace App\Http\Controllers;

use App\DetallePedido;
use App\Entrega;
use App\Models\Area;
use App\Models\Calzado;
use App\Models\Cliente;
use App\Models\DOrden;
use App\Models\DProdMat;
use App\Models\DProdSubArea;
use App\Models\DProdTalla;
use App\Models\DProduccion;
use App\Models\MaterialDefecto;
use App\Models\Modelo;
use App\Models\Multitabla;
use App\Models\Orden;
use App\Models\Pedido;
use App\Models\Produccion;
use App\Models\SMaterial;
use App\Models\SubArea;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\BinaryOp\Smaller;

class ProgramacionController extends Controller
{
    public function today()
    {
        $today = new Carbon();
        $today->tz = 'America/Lima';
        $today  = $today->format('Y-m-d');

        return $today;
    }

    function index()
    {
        $today = $this->today();
        $clientes = Cliente::orderBy('cliNombre')->where('cliEstado', 1)->get();
        $modelos  = Modelo::all();
        $colores  = Multitabla::where('mulDepId',2)->get();
        $producciones = Produccion::where('proEstado', 1)->get();

        return view('produccion.programacion.index')->with(compact('clientes','producciones','today','modelos','colores'));
    }

    function details( $produccion_id )
    {
        $produccion = Produccion::find($produccion_id);
        $areas = Area::where('areEstado',1)->get();

        return view('produccion.programacion.details')->with(compact('produccion','areas'));
    }

    function programaciones( $cliId, $ordId, $pedId)
    {
        if( $cliId<>'UNKNOW' AND $ordId == 'UNKNOW' AND $pedId == 'UNKNOW' ) {
            $cliente = Cliente::where('cliId', $cliId)->where('cliEstado', 1)->first();
            if ( is_null($cliente) )
                return ['success' => 'false', 'message' => 'No existe un cliente con ese código.'];

            $produccion = Produccion::where('proEstado', 1)->where('cliId', $cliId)->get();
        }
        else if( $cliId=='UNKNOW' AND $ordId<>'UNKNOW' AND $pedId=='UNKNOW' ) {
            $orden = Orden::find($ordId);
            if( is_null($orden) )
                return ['success'=>'false','message'=>'No existe una orden con ese código.'];

            $orden = Orden::find($ordId);
            $prodId = $orden->prodId;

            $produccion = Produccion::find($prodId);
        }
        else if( $cliId=='UNKNOW' AND $ordId=='UNKNOW' AND $pedId<>'UNKNOW' ){
            $pedido = Pedido::where('pedIdx',$pedId)->where('pedEstado',1)->first();
            if( is_null($pedido) )
                return ['success'=>'false','message'=>'No existe un pedido con ese código.'];

            $produccion = Produccion::where('proEstado', 1)->where('pedId', $pedId)->get();
        }
        else if( $cliId <> 'UNKNOW' AND  $pedId <> 'UNKNOW' AND $ordId == 'UNKNOW' ) {
            $pedido = Pedido::where('pedIdx',$pedId)->where('pedEstado',1)->first();
            if( is_null($pedido) )
                return ['success'=>'false','message'=>'No existe un pedido con ese código.'];

            $produccion = Produccion::where('proEstado', 1)->where('cliId', $cliId)->where('pedId', $pedId)->get();
        }
        else if( $cliId == 'UNKNOW' AND $pedId <> 'UNKNOW' AND $ordId <> 'UNKNOW' ) {
            $pedido = Pedido::where('pedIdx',$pedId)->where('pedEstado',1)->first();
            if( is_null($pedido) )
                return ['success'=>'false','message'=>'No existe un pedido con ese código.'];

            $orden = Orden::find($ordId);
            if( is_null($orden) )
                return ['success'=>'false','message'=>'No existe una orden con ese código.'];

            $prodId = $orden->prodId;
            $produccion = Produccion::where('proEstado', 1)->where('prodId', $prodId)->where('pedId', $pedId)->first();
        }
        else if( $cliId <> 'UNKNOW' AND $pedId == 'UNKNOW' AND $ordId <> 'UNKNOW' ) {
            $cliente = Cliente::where('cliId', $cliId)->where('cliEstado', 1)->first();
            if ( is_null($cliente) )
                return ['success' => 'false', 'message' => 'No existe un cliente con ese código.'];

            $orden = Orden::find($ordId);
            if( is_null($orden) )
                return ['success'=>'false','message'=>'No existe una orden con ese código.'];

            $prodId = $orden->prodId;
            $produccion = Produccion::where('proEstado', 1)->where('prodId', $prodId)->where('cliId', $cliId)->first();
        }
        else if( $cliId <> 'UNKNOW' AND $pedId <> 'UNKNOW' AND $ordId <> 'UNKNOW' ) {
            $cliente = Cliente::where('cliId', $cliId)->where('cliEstado', 1)->first();
            if ( is_null($cliente) )
                return ['success' => 'false', 'message' => 'No existe un cliente con ese código.'];

            $pedido = Pedido::where('pedIdx',$pedId)->where('pedEstado',1)->first();
            if( is_null($pedido) )
                return ['success'=>'false','message'=>'No existe un pedido con ese código.'];

            $orden = Orden::find($ordId);
            if( is_null($orden) )
                return ['success'=>'false','message'=>'No existe una orden con ese código.'];

            $prodId = $orden->prodId;
            $produccion = Produccion::where('proEstado', 1)->where('prodId', $prodId)->where('pedId', $pedId)->where('cliId', $cliId)->first();
        }
        else if( $cliId =='UNKNOW' && $pedId == 'UNKNOW' && $ordId == 'UNKNOW' )
            $produccion = Produccion::where('proEstado', 1)->get();

        if( count($produccion) == 0 )
            return ['success'=>'false','message'=>'No existen datos.'];

        return ['success'=>'true','data'=>$produccion];
    }

    function create( Request $request )
    {
        $fecha_registro = $request->get('fecha_registro');
        $fecha_entrega = $request->get('fecha_entrega');
        $cliente_id = $request->get('cliente_id');
        $modelos = json_decode($request->get('modelos'));
        $colores = json_decode($request->get('colores'));
        $hormas = json_decode($request->get('hormas'));
        $cantidad_pares = json_decode($request->get('cantidad_pares'));
        $fecha_registro = new Carbon($fecha_registro);
        $fecha_registro = $fecha_registro->format('Y-m-d');

        $pedido = Pedido::create([
            'cliId' => $cliente_id,
            'pedFechaRecepcion' => $fecha_registro,
            'pedEstado'=>1
        ]);
        $pedido->save();

        $produccion = Produccion::create([
            'proFechaEntrega' => $fecha_entrega,
            'cliId' => $cliente_id,
            'proEstado' => 1,
            'pedId' => $pedido->pedIdx,
            'proFecharegistro' => $fecha_registro,
        ]);
        $produccion->save();

        $prodId = $produccion->prodId;
        for( $i=0;$i<count($modelos);$i++ ) {
            $dProduccion = DProduccion::create([
                'prodId'=> $prodId,
                'modId'=> $modelos[$i],
                'dprodColor'=> $colores[$i],
                'dprodHorma'=> $hormas[$i],
                'dprodCantidad'=> $cantidad_pares[$i],
                'dprodEstado'=> 1
            ]);

            $dProduccion->save();
        }

        return ['success'=>'true','produccion_id'=>$prodId];
    }

    function completarCeros( $dato,$cantidad )
    {
        $ceros = '';
        for( $i=0;$i<($cantidad-strlen($dato));$i++ )
        {
            $ceros.=0;
        }

        return $ceros.$dato;
    }

    function order_create( Request $request )
    {
        $dprodId     = $request->get('id');
        $ordModelod  = $request->get('model');
        $ordColord   = $request->get('color');
        $orders      = $request->get('orders');

        $dproduccion = DProduccion::find($dprodId);
        $prodId = $dproduccion->prodId;

        $produccion = Produccion::find($prodId);

        $iterator = 0;
        foreach ( $orders as $orden ) {
            $iterator++;
            $ordId = $iterator;

            $orden_create = Orden::create([
                'ordId' => $ordId,
                'prodId' => $prodId,
                'ordModelod' => $ordModelod,
                'ordColord' => $ordColord,
            ]);
            $ordCantidad = 0;

            for( $i=0;$i<count($orden); $i++ ) {
                $talla = $dproduccion->tallas_modelo($ordModelod)[$i]->mulDescripcion;
                $dprodTalCantidad = $orden[$i]; // Cantidad
                $dprodTalTalla = $talla;
                $ordCantidad+=$dprodTalCantidad;

                $tipo = 1;
                $calTipoBarra = $tipo . self::completarCeros($produccion->pedId, 3) .
                    self::completarCeros($produccion->cliId, 2) .
                    self::completarCeros($orden_create->ordIdx, 5) .
                    self::completarCeros($dproduccion->modId, 5) .
                    self::completarCeros($dproduccion->dprodColor, 3) .
                    self::completarCeros($dprodTalTalla, 2);

                $calzado = Calzado::create([
                    'calId' => $calTipoBarra,
                    //'calTipoBarra' => $calTipoBarra,
                    'pedId' => $produccion->pedId,
                    'calOrden' => $orden_create->ordIdx,
                    'modId' => $dproduccion->modId,
                    'calColor' => $dproduccion->dprodColor,
                    'calTalla' => $dprodTalTalla
                ]);
                $calzado->save();

                $pedido = Pedido::find($produccion->pedId);
                $detallepedido = DetallePedido::create([
                    'pedIdx' => $pedido->pedIdx,
                    'calId' => $calTipoBarra,
                    'detpedCantidad' => $dprodTalCantidad
                ]);
                $detallepedido->save();

                $dprodtalla = DProdTalla::create([
                    'dprodTalCantidad' => $dprodTalCantidad,
                    'calId' => $calTipoBarra,
                    'dprodTalTalla' => $dprodTalTalla,
                    'dprodId' => $dprodId
                ]);
                $dprodtalla->save();

                $dorden = DOrden::create([
                    'dordCantidad' => $dprodTalCantidad,
                    'ordIdx' => $orden_create->ordIdx,
                    'dprodtalId' => $dprodtalla->dprodtalId,
                    'dordenEstado' => 1
                ]);
                $dorden->save();
            }
            $codigoOrden = self::completarCeros($prodId,4).self::completarCeros($orden_create->ordIdx,4).
                self::completarCeros($ordCantidad ,2).self::completarCeros($dprodId,4).
                self::completarCeros($ordModelod,4).self::completarCeros($ordColord,3);
            $orden_create->ordCantidad = $ordCantidad;
            $orden_create->ordCodigo   = $codigoOrden;

            $orden_create->save();
        }

        return ['success'=>'true','message'=>'Ordenes guardadas correctamente.','id'=>$prodId];
    }

    function delete_order( Request $request )
    {
        dd('');
        $data = $request->all();
        $ordIdx= $data['id'];

        $orden = Orden::find($ordIdx);
        $produccion = Produccion::find($orden->prodId);
        $dordenes  = DOrden::where('ordIdx',$ordIdx)->get();
        $pedId = $produccion->pedId;
        foreach ( $dordenes as $dorden ) {
            $dprodtalla = DProdTalla::find($dorden->dprodtalId);
            $detallepedido = DetallePedido::where('pedIdx',$pedId)->where('calId',$dprodtalla->calId)->get();
            $entrega = Entrega::where('pedIdx',$pedId)->where('calId',$dprodtalla->calId)->first();
            $calzado = Calzado::find($dprodtalla->calId);
            if ( !is_null($entrega) )
                $entrega->delete();
            $dorden->delete();
            foreach($detallepedido as $detalle)
                $detalle->delete();

            $dprodtalla->delete();
            $calzado->delete();
        }

        return ['success'=>'true','message'=>'Orden eliminada correctamente'];
    }

    function create_material( Request $request )
    {
        $dprodId     = $request->get('dprodId');
        $subaId      = $request->get('subaId');
        $descripcion = $request->get('description');
        $smatModelod = $request->get('modelo');
        $smatColord  = $request->get('color');
        $matdids     = $request->get('ids');
        $matdNombres = $request->get('names');
        $smatDescripciones  = $request->get('descriptions');

        $dprodsubarea = DProdSubArea::where('dproduccionId',$dprodId)->where('subaId',$subaId)->first();
        if( is_null($dprodsubarea) ){
            $dprodsubarea = DProdSubArea::create([
                'dproduccionId' => $dprodId,
                'subaId' => $subaId,
                'dprodsubEstado' => 1
            ]);
        }
        $dprodsubarea->dprodsubDescripcion = $descripcion;
        $dprodsubarea->save();

        for ( $i=0;$i<count($matdNombres); $i++ ) {
            $materialdefecto = MaterialDefecto::where('matdNombre', $matdNombres[$i])->where('matdEstado', 1)->first();
            if (is_null($materialdefecto)) {
                $materialdefecto = MaterialDefecto::create([
                    'matdNombre' => $matdNombres[$i],
                    'matdTipo' => 1,
                    'matdEstado' => 1
                ]);
                $materialdefecto->save();
            }

            $smaterial = SMaterial::find($matdids[$i]);
            if( is_null($smaterial)  ){
                $smaterial = new SMaterial();
            }

            $smaterial->subaId = $subaId;
            $smaterial->smatDescripcion = $smatDescripciones[$i];
            $smaterial->smatModelod = $smatModelod;
            $smaterial->smatColord = $smatColord;
            $smaterial->matdId = $materialdefecto->matdId;
            $smaterial->smatEstado = 1;
            $smaterial->save();

            $dprodmat = DProdMat::where('dprodsubId',$dprodsubarea->dprodsubId)->
                where('smatId',$smaterial->smatId)->first();

            if( is_null($dprodmat) ) {
                $dprodmat = DProdMat::create([
                    'dprodsubId' => $dprodsubarea->dprodsubId,
                    'smatId' => $smaterial->smatId,
                    'dprodmatEstado' => 1
                ]);
                $dprodmat->save();
            }
        }

        $dproduccion = DProduccion::find($dprodId);
        $prodId = $dproduccion->prodId;

        return ['success'=>'true','message'=>'Materiales guardados correctamente.','id'=>$prodId];
    }

    function delete_material( Request $request )
    {
        $dprodId = $request->get('dprodId');
        $matId   = $request->get('id');

        $dprodmat = DProdMat::where('smatId',$matId)->first();
        $dprodmat->delete();

        $smaterial = SMaterial::find($matId);
        $smaterial->delete();

        $dproduccion = DProduccion::find($dprodId);
        $prodId = $dproduccion->prodId;

        return ['success'=>'true','message'=>'Material eliminado correctamente.','id'=>$prodId];
    }

    public static function op_grande( $productionId )
    {
        $ordenes = Orden::where('prodId',$productionId)->get();
        $orders = collect();
        for($i=0;$i<count($ordenes); $i++ ) {
            $orders->push($ordenes[$i]);

            $start = new Carbon($ordenes[$i]->produccion->proFecharegistro);
            $start = $start->day.' '.self::getMonthName($start->month);

            $end   = new Carbon($ordenes[$i]->produccion->proFechaEntrega);
            $end   = $end->day.' '.self::getMonthName($end->month);

            $fechas [$ordenes[$i]->ordIdx] = [$start,$end];

            $dproduccion[$ordenes[$i]->ordIdx]= DProduccion::where('dprodColor',$ordenes[$i]->ordColord)->
            where('modId',$ordenes[$i]->ordModelod)->where('prodId',$ordenes[$i]->produccion->prodId)->first();

        }

        /*
                $dproducciones= DProduccion::where('prodId',$productionId)->get();
                $subareas = SubArea::where('subaForro',1)->where('subaEstado',1)->get();
                $forro     = $subareas[0];
                $plantilla = $subareas[1];
                foreach ( $dproducciones as $dproduccion ) {
                    $magics = [];
                    $i = 0;
                    foreach ( $ordenes as $orden )
                        if(  $orden->ordColord == $dproduccion->dprodColor and $orden->ordModelod == $dproduccion->modId and $orden->prodId == $dproduccion->prodId ) {
                            $magics[] = $orden->ordIdx;
                            $dprod[] = $dproduccion;
                            $filasPlantilla = 0;
                            foreach (  $dproduccion->materiales as $material ) {
                                if ($material->subaId == $plantilla->subaId)
                                    $filasPlantilla += 1;
                            }
                            $filas[] = $filasPlantilla;
                        }
                }
        */
        $vista =  view('report.produccion.opGrande',compact('orders','fechas','dproduccion'));
        $pdf = app('dompdf.wrapper');
        //$pdf = app('dompdf.wrapper')->setPaper('a4', 'landscape');;
        $pdf->loadHTML($vista);
        return $pdf->stream();

        /*
        $vista =  view('pdf',compact('production'));
        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($vista);
        return $pdf->download('orden_produccion_chica.pdf');
        */
    }

    public static function op_chica( $productionId )
    {
        $ordenes = Orden::where('prodId',$productionId)->get();
        $dproducciones= DProduccion::where('prodId',$productionId)->get();

        $forro = SubArea::where('subaDescripcion','forro' )->where('subaEstado',1)->first();
        $plantilla = SubArea::where('subaDescripcion','plantilla' )->where('subaEstado',1)->first();

        foreach ( $dproducciones as $dproduccion ) {
            $magics = [];
            $i = 0;
            foreach ( $ordenes as $orden )
                if(  $orden->ordColord == $dproduccion->dprodColor and $orden->ordModelod == $dproduccion->modId and $orden->prodId == $dproduccion->prodId ) {
                    $magics[] = $orden->ordIdx;
                    $dprod[] = $dproduccion;
                    $filasPlantilla = 0;
                    $dprodsubarea = DProdSubArea::where('dproduccionId',$dproduccion->dprodId)->first();
                    $materiales = @$dproduccion->materiales($dproduccion->dprodId,$dprodsubarea->subaId);
                    if( !is_null( $materiales) ) {
                        foreach ($materiales as $material) {
                            if ($material->subaId == $plantilla->subaId)
                                $filasPlantilla += 1;
                        }
                        $filas[] = $filasPlantilla;
                    }
                }
        }

        //return view('report.produccion.opChica',compact('ordenes','magics','dprod','forro','plantilla','filas'));
        $vista =  view('report.produccion.opChica',compact('ordenes','magics','dprod','forro','plantilla','filas'));
        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($vista);
        return $pdf->stream();
    }

    public static function getMonthName( $number )
    {
        switch($number)
        {
            case 1;
                return 'Enero'; break;
            case 2;
                return 'Febrero'; break;
            case 3;
                return 'Marzo'; break;
            case 4;
                return 'Abril'; break;
            case 5;
                return 'Mayo'; break;
            case 6;
                return 'Junio'; break;
            case 7;
                return 'Julio'; break;
            case 8;
                return 'Agosto'; break;
            case 9;
                return 'Setiembre'; break;
            case 10;
                return 'Octubre'; break;
            case 11;
                return 'Noviembre'; break;
            case 12;
                return 'Diciembre'; break;
        }
    }

}
