<?php

namespace App\Http\Controllers;

use App\Models\Description;
use App\Models\Pieza;
use Illuminate\Http\Request;

use App\Http\Requests;

class DescriptionController extends Controller
{
    function data(){
        $descriptions = Description::all();
        $amount = count($descriptions);
        if( $amount == 0 )
            return ['success'=>false,'message'=>'Debe registrar descripciones para niveles.'];

        return ['success'=>true,'data'=>$descriptions];
    }

    function create( Request $request ){
        $data = $request->all();

        $rules = [
            'description_name' => 'required|unique:descriptions,name',
            'description_description' => 'required|unique:descriptions,description'
        ];

        $messages = [
            'description_name.required' => 'Ingrese un nombre',
            'description_name.unique' => 'Ya existe una descripción con ese nombre',
            'description_description.required' => 'Ingrese una descripción',
            'description_description.unique' => 'Ya existe una descripción con esa descripción',
        ];

        $validator = \Validator::make($data,$rules,$messages);

        if( $validator->fails() ){
            return ['success'=>false,'message' => $validator->errors()->first()];
        }

        $data['name'] = $data['description_name'];
        $data['description'] = $data['description_description'];
        $data['state'] = @$data['description_state']=='on'?1:0;

        Description::create($data);

        return ['success'=>true,'message' => 'Datos guardados correctamente.'];
    }

    function edit( Request $request ){
        $data = $request->all();
        $rules = [
            'description_name' => 'required|unique:descriptions,name,'.$data['description_id'],
            'description_description' => 'required|unique:descriptions,description,'.$data['description_id']
        ];

        $messages = [
            'description_name.required' => 'Ingrese un nombre',
            'description_name.unique' => 'Ya existe una descripción con ese nombre',
            'description_description.required' => 'Ingrese una descripción',
            'description_description.unique' => 'Ya existe una descripción con esa descripción',
        ];

        $validator = \Validator::make($data,$rules,$messages);

        if( $validator->fails() ){
            return ['success'=>false,'message' => $validator->errors()->first()];
        }

        $data['name'] = $data['description_name'];
        $data['description'] = $data['description_description'];
        $data['state'] = @$data['description_state']=='on'?1:0;

        $description = Description::find($data['description_id']);
        $description->update($data);

        return ['success'=>true,'message' => 'Datos editados correctamente.'];
    }

    function delete( Request $request ){
        $data = $request->all();
        $id   = $data['description_id'];
        $pieces = Pieza::where('description_id',$id)->first();
        $description = Description::find($id);

        if( count($pieces) > 0 )
            return ['success'=>true,'message' => 'La descripción no puede ser eliminada, porque tiene piezas asociadas.'];

        if( count($description) == 0 )
                    return ['success'=>true,'message' => 'No existe una descripción con ese Id.'];

        $description->delete();

        return ['success'=>true,'message' => 'Dato eliminado correctamente.'];
    }
}
