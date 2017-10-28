<?php

namespace App\Http\Controllers;

use App\Models\Calzado;
use App\Models\DetalleModeloDatos;
use App\Models\DProduccion;
use App\Models\MImagen;
use App\Models\Modelo;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ModeloController extends Controller
{
    function index()
    {
        $modelos = Modelo::all();
        return view('mantenimiento.modelo.index')->with(compact('modelos'));
    }

    function create( $request )
    {
        $data     = ( json_decode($request->item) );

        $modLinea = $data[0]->modLinea;
        $modGenero       = ( property_exists($data[0], 'modGenero') )?$data[0]->modGenero:0;
        $modDescripcion  = ( property_exists($data[0], 'modDescripcion') )?$data[0]->modDescripcion:'';
        $modPrecioMinimo = ( property_exists($data[0], 'modPrecioMinimo') )?$data[0]->modPrecioMinimo:0;
        $modPrecioLista  = ( property_exists($data[0], 'modPrecioLista') )?$data[0]->modPrecioLista:0;

        $modelo = Modelo::where('modDescripcion',$modDescripcion)->first();
        if( $modelo <> null )
            return ['success'=>'false','message'=>'Ya existe un modelo con ese nombre.'];

        $modelo = Modelo::create([
            'modLinea'=>$modLinea,
            'modGenero'=>$modGenero,
            'modDescripcion'=>$modDescripcion,
            'modPrecioMinimo'=>$modPrecioMinimo,
            'modPrecioLista'=>$modPrecioLista
        ]);
        $modelo->save();

        return ['success'=>'true','message'=>'Modelo registrado correctamente.'];
    }

    function edit( $request )
    {
        $data  = ( json_decode($request->item) );

        $modId = $data[0]->modId;
        $modLinea        = $data[0]->modLinea;
        $modGenero       = ( property_exists($data[0], 'modGenero') )?$data[0]->modGenero:0;
        $modDescripcion  = ( property_exists($data[0], 'modDescripcion') )?$data[0]->modDescripcion:'';
        $modPrecioMinimo = ( property_exists($data[0], 'modPrecioMinimo') )?$data[0]->modPrecioMinimo:0;
        $modPrecioLista  = ( property_exists($data[0], 'modPrecioLista') )?$data[0]->modPrecioLista:0;

        $modelo = Modelo::where('modDescripcion',$modDescripcion)->first();
        if( $modelo <> null && $modelo->modId <> $modId )
            return ['success'=>'false','message'=>'Ya existe un modelo con ese nombre.'];

        $modelo = Modelo::find($modId);
        $modelo->modLinea        = $modLinea;
        $modelo->modGenero       = $modGenero;
        $modelo->modDescripcion  = $modDescripcion;
        $modelo->modPrecioMinimo = $modPrecioMinimo;
        $modelo->modPrecioLista  = $modPrecioLista;
        $modelo->save();

        return ['success'=>'true','message'=>'Modelo modificado correctamente.'];
    }

    function delete( $request )
    {
        $data = ( json_decode($request->item) );

        $modId = $data[0]->modId;

        $calzado = Calzado::where('modId',$modId)->first();
        if( $calzado <> null  )
            return ['success'=>'false','message'=>'El modelo no se puede eliminar porque tiene calzados asociados.'];

        $mImagen = MImagen::where('modId',$modId)->first();
        if( $mImagen <> null  )
            return ['success'=>'false','message'=>'El modelo no se puede eliminar porque tiene imágenes asociadas.'];

        $dmdatos = DetalleModeloDatos::where('modId',$modId)->first();
        if( $dmdatos <> null  )
            return ['success'=>'false','message'=>'El modelo no se puede eliminar porque tiene DETALLE_MODELO_DATOS asociadas.'];

        $dProduccion = DProduccion::where('modId',$modId)->first();
        if( $dProduccion <> null  )
            return ['success'=>'false','message'=>'El modelo no se puede eliminar porque exiten DETALLES_PRODUCCION asociados.'];

        $modelo = Modelo::find($modId);
        $modelo->delete();

        return ['success'=>'true','message'=>'Modelo eliminado correctamente.'];
    }

    function images( $modId )
    {
        $modelo = Modelo::find($modId);
        if( is_null($modelo) )
            return ['success'=>'false','message'=>'No existe un modelo con ese código.'];

        $imagenes = MImagen::where('modId',$modId)->get();
        $number_images = count($imagenes);
        if( $number_images == 0 )
            return ['success'=>'false','message'=>'No existen imágenes asociadas a ese modelo.'];

        return ['success'=>'true','data'=>$imagenes,'number_images'=>$number_images];
    }

    function uploadImage( Request $request )
    {
        $file = $request->file('modelo_imagen');
        $imgEstado =  $request->get('imagen_estado');
        $modId= $request->get('modelo_id');

        $modelo = Modelo::find($modId);
        if( is_null($modelo) )
            return ['success'=>'false','message'=>'No existe un modelo con ese código'];

        $mImagen = new MImagen();

        $mImagen->modId =$modId;
        $mImagen->imgEstado = ($imgEstado=='on')?1:0;
        $mImagen ->imgActivo= 1;

        if( $file )
        {
            $path = public_path().'/images/modelo';
            $extension = $file->getClientOriginalExtension();
            $fileName = rand(111111,999999).'_'.date('d-m-Y') . '.' . $extension;
            $file->move($path, $fileName);
            $mImagen->imgDescripcion = $fileName;
        }
        $mImagen->save();

        return ['success'=>'true','message'=>'Imagen registrada correctamente','modelo_id'=>$modId];
    }

    function editImage( Request $request )
    {
        $file = $request->file('modelo_imagen');
        $imgEstado =  $request->get('imagen_estado');
        $imgId= $request->get('imagen_id');
        $modelo_id= $request->get('modelo_id');
        $mImagen = MImagen::find($imgId);
        $mImagen->imgEstado = ($imgEstado=='on')?1:0;
        $mImagen->imgActivo = 1;

        if( $file )
        {
            $path = public_path().'/images/modelo';
            File::delete($path.'/'.$mImagen->imgDescripcion);

            $extension = $file->getClientOriginalExtension();
            $fileName = rand(111111,999999).'_'.date('d-m-Y') . '.' . $extension;
            $file->move($path, $fileName);
            $mImagen->imgDescripcion = $fileName;
        }
        $mImagen->save();

        return ['success'=>'true','message'=>'Imagen modificada correctamente','modelo_id'=>intval($modelo_id)];
    }

    function deleteImage( Request $request )
    {
        $imgId= $request->get('imagen_id');
        $modelo_id= $request->get('modelo_id');

        $mImagen = MImagen::find($imgId);
        $mImagen->delete();

        return ['success'=>'true','message'=>'Imagen eliminada correctamente','modelo_id'=>intval($modelo_id)];
    }
}
