@extends('layouts.app')
@section('title','Ficha - Ventas')

@section('styles')
    <style>
        .select-margin
        {
            margin-top: 6px;
        }
        .hide_it{
            display: none;
        }
        .img-preview{
            margin-top: 10px;
            width: auto;
            height: 50px;
        }
        .checkboxes{
            width: 20px;
            height: 20px;
        }
        .button-save{
            margin-top: 15px;
            margin-bottom: 15px;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('bootstrap-select/css/bootstrap-select.min.css') }}">
@endsection

@section('content')
    <form id="form_materials" action="{{ route('ficha.ventas.store') }}" method="post">
        <input type="hidden" id="_token" value="{{ csrf_token() }}">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    <h2><b>FICHA VENTAS</b></h2>
                </div>
                <div class="col-md-4">
                    <div class="col-md-4 select-margin">
                        <label for="" class="pull-right">Modelo: </label>
                    </div>
                    <div class="col-md-8">
                        <select name="modelo_id" id="modelo_id" title="< Seleccione >" class="selectpicker" data-live-search="true" data-width="100%" data-size="10">
                            @foreach( $models as $model )
                                <option value="{{ $model->modId }}">{{ $model->modDescripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-4 select-margin">
                        <label for="" class="pull-right">Cliente: </label>
                    </div>
                    <div class="col-md-8">
                        <select name="cliente_id" id="cliente_id" title="< Seleccione >" class="selectpicker" data-live-search="true" data-width="100%" data-size="10">
                            @foreach( $customers as $customer )
                                <option value="{{ $customer->cliId }}">{{ $customer->cliNombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-4 select-margin">
                        <label for="" class="pull-right">Color: </label>
                    </div>
                    <div class="col-md-8">
                        <select name="color_id" id="color_id" title="< Seleccione >" class="selectpicker" data-live-search="true" data-width="100%" data-size="10">
                            @foreach( $colors as $color )
                                <option value="{{ $color->mulId }}">{{ $color->mulDescripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div><br>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>PLANTILLA</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="row">
                                    <div class="col-md-7">
                                        <label for="plantilla">MATERIAL</label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="piezas">CHECK</label>
                                    </div>
                                </div>
                                <div class="row" data-plantilla="" data-type_material="" data-area="">
                                    <div class="material">
                                        <div class="form-group col-md-7">
                                            <input type="text" class="form-control " id="material">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <input type="checkbox" class="form-control checkboxes" id="piezas">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-success" data-add_plantilla>+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>ENCAJADO</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="row">
                                    <div class="col-md-7">
                                        <label for="encajado">TIPO</label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="piezas">CHECK</label>
                                    </div>
                                </div>
                                <div class="row" data-encajado="" data-type_material="" data-area="">
                                    <div class="material">
                                        <div class="form-group col-md-7">
                                            <input type="text" class="form-control " id="material">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <input type="checkbox" class="form-control checkboxes" id="piezas">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-success" data-add_encajado>+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>CUERO</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="plantilla">MATERIAL</label>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="piezas">TIPO</label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="plantilla">COLOR</label>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="piezas">COD. CUERO</label>
                                    </div>
                                </div>
                                <div class="row" data-cuero="" data-type_material="" data-area="">
                                    <div class="material">
                                        <div class="form-group col-md-3">
                                            <input type="text" class="form-control " id="material">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="text" class="form-control " id="material">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <input type="text" class="form-control " id="material">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="text" class="form-control " id="material">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-success" data-add_cuero>+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>FORRO</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="row">
                                    <div class="col-md-7">
                                        <label for="encajado">TIPO</label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="piezas">CHECK</label>
                                    </div>
                                </div>
                                <div class="row" data-forro="" data-type_material="" data-area="">
                                    <div class="material">
                                        <div class="form-group col-md-7">
                                            <input type="text" class="form-control " id="material">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <input type="checkbox" class="form-control checkboxes" id="piezas">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-success" data-add_forro>+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>PLANTILLA</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="row">
                                    <div class="col-md-7">
                                        <label for="encajado">TIPO</label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="piezas">CHECK</label>
                                    </div>
                                </div>
                                <div class="row" data-forro="" data-type_material="" data-area="">
                                    <div class="material">
                                        <div class="form-group col-md-7">
                                            <input type="text" class="form-control " id="material">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <input type="checkbox" class="form-control checkboxes" id="piezas">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-success" data-add_plantilla2>+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>PEGADO</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="row">
                                    <div class="col-md-offset-4 col-md-4">
                                        <label for="plantilla">PLANTA</label>
                                    </div>
                                </div>
                                <div class="row" data-cuero="" data-type_material="" data-area="">
                                    <div class="material">
                                        <div class="form-group">
                                            <label for="plantilla">COD. COLOR</label>
                                            <input type="text" class="form-control " id="material">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>COSIDO LATERAL</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">

                                <div class="row" data-cuero="" data-type_material="" data-area="">
                                    <div class="material">
                                        <div class="form-group col-md-12">
                                            <label for="plantilla">COD. COLOR</label>

                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control " id="material">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="checkbox" class="form-control checkboxes" id="piezas">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>COSIDO VENA</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">

                                <div class="row" data-cuero="" data-type_material="" data-area="">
                                    <div class="material">
                                        <div class="form-group col-md-12">
                                            <label for="plantilla">COD. COLOR</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control " id="material">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="checkbox" class="form-control checkboxes" id="piezas">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>PERFILADO</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="x_panel">
                                            <div class="x_title">
                                                <h2>HILOS</h2>
                                                <ul class="nav navbar-right panel_toolbox">
                                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                                    </li>
                                                </ul>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="x_content">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <label for="encajado">HILO</label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label for="piezas">COD. COLOR</label>
                                                    </div>
                                                </div>
                                                <div class="row" data-forro="" data-type_material="" data-area="">
                                                    <div class="material">
                                                        <div class="form-group col-md-5">
                                                            <input type="text" class="form-control " id="material">
                                                        </div>
                                                        <div class="form-group col-md-5">
                                                            <input type="text" class="form-control " id="material">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn-success" data-add_perfilhilo>+</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="x_panel">
                                            <div class="x_title">
                                                <h2>ACCESORIOS</h2>
                                                <ul class="nav navbar-right panel_toolbox">
                                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                                    </li>
                                                </ul>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="x_content">

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="encajado">ACCESORIO</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="piezas">COD. COLOR</label>
                                                    </div>
                                                </div>
                                                <div class="row" data-forro="" data-type_material="" data-area="">
                                                    <div class="material">
                                                        <div class="form-group col-md-5">
                                                            <input type="text" class="form-control " id="material">
                                                        </div>
                                                        <div class="form-group col-md-5">
                                                            <input type="text" class="form-control " id="material">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn-success" data-add_perfilaccesorio>+</button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>OBSERVACIONES</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">

                                <div class="row" data-cuero="" data-type_material="" data-area="">
                                    <textarea name="" id=""rows="10" class="form-control "></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                {{--<div class="row">
                    @foreach($areas as $area)
                        @if( $area->tipo == 1)
                            <div class="col-md-6">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>{{ $area->nombre }}</h2>
                                        <ul class="nav navbar-right panel_toolbox">
                                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                            </li>
                                        </ul>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <label for="material">MATERIAL</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="piezas">PIEZAS</label>
                                            </div>
                                        </div>
                                        <div class="row" data-material="{{ $area->id }}" data-type_material="{{ $area->tipo }}" data-area="{{ $area->nombre }}">
                                            <div class="material">
                                                <div class="form-group col-md-7">
                                                    <input type="text" class="form-control" id="material">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <input type="text" class="form-control" id="piezas">
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-success" data-add_material>+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            @if( $area->nombre == 'ARMADO')
                                <div class="col-md-6">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h2>{{ $area->nombre }}</h2>
                                            <ul class="nav navbar-right panel_toolbox">
                                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                                </li>
                                            </ul>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content">
                                            <div class="row">
                                                <div class="form-group">
                                                    <label for="falsa">Falsa</label>
                                                    <input type="text" class="form-control" name="falsa" id="falsa">
                                                </div>
                                                <div class="form-group">
                                                    <label for="contrafuerte">Contrafuerte</label>
                                                    <input type="text" class="form-control" name="contrafuerte" id="contrafuerte">
                                                </div>
                                                <div class="form-group">
                                                    <label for="puntera">Puntera</label>
                                                    <input type="text" class="form-control" name="puntera" id="puntera">
                                                </div>
                                                <div class="form-group">
                                                    <label for="talon">Talón</label>
                                                    <input type="text" class="form-control" name="talon" id="talon">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($area->nombre == 'ENCAJADO')
                                <div class="col-md-6">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h2>{{ $area->nombre }}</h2>
                                            <ul class="nav navbar-right panel_toolbox">
                                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                                </li>
                                            </ul>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content">
                                            <div class="row">
                                                <div class="form-group">
                                                    <label for="caja">Caja</label>
                                                    <input type="text" class="form-control" name="caja" id="caja">
                                                </div>
                                                <div class="form-group">
                                                    <label for="papel">Papel</label>
                                                    <input type="text" class="form-control" name="papel" id="papel">
                                                </div>
                                                <div class="form-group">
                                                    <label for="hantan">Hantan</label>
                                                    <input type="text" class="form-control" name="hantan" id="hantan">
                                                </div>
                                                <div class="form-group">
                                                    <label for="bolsa">Bolsa</label>
                                                    <input type="text" class="form-control" name="bolsa" id="bolsa">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($area->nombre == 'HAB. PLANTILLA')
                                <div class="col-md-6">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h2>{{ $area->nombre }}</h2>
                                            <ul class="nav navbar-right panel_toolbox">
                                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                                </li>
                                            </ul>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content">
                                            <div class="row">
                                                <div class="form-group">
                                                    <label for="sello_pan_oro">Sello pan de oro</label>
                                                    <input type="text" class="form-control" name="sello_pan_oro" id="sello_pan_oro">
                                                </div>
                                                <div class="form-group">
                                                    <label for="sello_especificaion">Sello de esp.</label>
                                                    <input type="text" class="form-control" name="sello_especificaion" id="sello_especificaion">
                                                </div>
                                                <div class="form-group">
                                                    <label for="troquel">N° Troquel</label>
                                                    <input type="text" class="form-control" name="troquel" id="troquel">
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        <label for="latex">
                                                            <span>LATEX</span>
                                                            <input type="checkbox" class="checkboxes" name="latex" id="latex" checked>
                                                        </label>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="retacon">
                                                            <span>RETACON</span>
                                                            <input type="checkbox" class="checkboxes" name="retacon" id="retacon">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-6">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h2>{{ $area->nombre }}</h2>
                                            <ul class="nav navbar-right panel_toolbox">
                                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                                </li>
                                            </ul>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <label for="material">MATERIAL</label>
                                                </div>
                                            </div>
                                            <div class="row" data-material="{{ $area->id }}" data-type_material="{{ $area->tipo }}" data-area="{{ $area->nombre }}">
                                                <div class="material">
                                                    <div class="form-group col-md-7">
                                                        <input type="text" class="form-control" id="material">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <button type="button" class="btn btn-success" data-add_material>+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endforeach
                    <div class="col-md-6">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Observaciones</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="row">
                                    <div class="form-group">
                                        <textarea class="form-control" name="observacion" id="observacion" rows="9" style="resize: none;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Aprobado</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="modelaje">Modelaje</label>
                                        <input type="text" class="form-control" name="modelaje" id="modelaje">
                                    </div>
                                    <div class="form-group">
                                        <label for="produccion">Producción</label>
                                        <input type="text" class="form-control" name="produccion" id="produccion">
                                    </div>
                                    <div class="form-group">
                                        <label for="gerencia">Gerencia</label>
                                        <input type="text" class="form-control" name="gerencia" id="gerencia">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Imagenes</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-8">
                                            <label for="imagen1">Seleccionar la imagen</label>
                                            <input type="file" name="imagen1" id="imagen1" accept="image/*">
                                        </div>
                                        <div class="col-md-4">
                                            <img src="" class="img-responsive img-preview" id="imagen1-preview" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-8">
                                            <label for="imagen2">Seleccione imagen</label>
                                            <input type="file" name="imagen2" id="imagen2" accept="image/*">
                                        </div>
                                        <div class="col-md-4">
                                            <img src="" class="img-responsive img-preview" id="imagen2-preview" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-8">
                                            <label for="imagen3">Seleccione imagen</label>
                                            <input type="file" name="imagen3" id="imagen3" accept="image/*">
                                        </div>
                                        <div class="col-md-4">
                                            <img src="" class="img-responsive img-preview" id="imagen3-preview" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-8">
                                            <label for="imagen4">Seleccione imagen</label>
                                            <input type="file" name="imagen4" id="imagen4" accept="image/*">
                                        </div>
                                        <div class="col-md-4">
                                            <img src="" class="img-responsive img-preview" id="imagen4-preview" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>--}}
                <div class="row text-center button-save">
                    <button type="submit" class="btn btn-primary" id="btn_save"><i class="fa fa-diamond"></i> Guardar Datos</button>
                </div>
            </div>
        </div>
    </form>
    <input type="hidden" id="ficha_tecnica" value="{{ route('ficha.tecnica') }}">
@endsection

@section('scripts')
    <script src="{{ asset('bootstrap-select/js/bootstrap-select.min.js') }}" ></script>
    <script src="{{ asset('js/mantenimiento/ficha/ventas/index.js?v=1') }}"></script>
@endsection