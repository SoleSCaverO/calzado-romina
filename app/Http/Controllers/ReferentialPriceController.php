<?php

namespace App\Http\Controllers;

use App\Models\DetalleModeloDatos;
use App\Models\Modelo;
use App\Models\SubareaMenor;
use Illuminate\Http\Request;

use App\Http\Requests;

class ReferentialPriceController extends Controller
{
    function index(){
        $models = Modelo::take(10)->get();
        $minor_subareas = SubareaMenor::where('subamEstado',1)->get();

        return view('mantenimiento.precio-referencial.index')->with(compact('models','minor_subareas'));
    }

    function store(Request $request){
        $data = $request->all();
        $id = $data['id'];
        $price = $data['price'];
        $data_order_detail = DetalleModeloDatos::find($id);
        $data_order_detail->referential_price = $price;
        $data_order_detail->save();

        return ['success'=>true,'message'=>'Datos guardado correctamente'];
    }
}