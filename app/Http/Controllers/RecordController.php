<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Modelo;
use Illuminate\Http\Request;

use App\Http\Requests;

class RecordController extends Controller
{
    public  function technique(){
        $models = Modelo::take(10)->get();
        $customers = Cliente::take(10)->get();

        return view('mantenimiento.ficha.tecnica.index')
            ->with(compact('models','customers'));
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
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
