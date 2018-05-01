@extends('layouts.app')
@section('title','Modelo - Tipo')

@section('styles')
    <style>
        .select-margin
        {
            margin-top: 6px;
        }
        .hide_it{
            display: none;
        }

    </style>
    <link rel="stylesheet" href="{{ asset('bootstrap-select/css/bootstrap-select.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="text-center">
                <h2><b>FICHA TECNICA</b></h2>
            </div>
            <div class="col-md-3">
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
            <div class="col-md-3">
                <div class="col-md-4 select-margin">
                    <label for="" class="pull-right">Cliente: </label>
                </div>
                <div class="col-md-8">
                    <select name="cliente_id" id="modelo_id" title="< Seleccione >" class="selectpicker" data-live-search="true" data-width="100%" data-size="10">
                        @foreach( $customers as $customer )
                            <option value="{{ $customer->cliId }}">{{ $customer->cliNombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="col-md-4 select-margin">
                    <label for="" class="pull-right">Color: </label>
                </div>
                <div class="col-md-8">
                    <select name="color_id" id="color_id" title="< Seleccione >" class="selectpicker" data-live-search="true" data-width="100%" data-size="10">

                        <option value="">Color 1</option>
                        <option value="">Color 1</option>
                        <option value="">Color 1</option>

                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="col-md-4 select-margin">
                    <label for="" class="pull-right">Ficha: </label>
                </div>
                <div class="col-md-8">
                    <select name="ficha_id" id="ficha_id" title="< Seleccione >" class="selectpicker" data-live-search="true" data-width="100%" data-size="10">

                        <option value="">Ficha 1</option>
                        <option value="">Ficha 1</option>
                        <option value="">Ficha 1</option>

                    </select>
                </div>
            </div>


        </div>
    </div><br>
    <div class="row">

        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Información básica</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="coleccion">Colección</label>
                                <input type="text" class="form-control" id="coleccion" >
                            </div>
                            <div class="form-group">
                                <label for="genero">Genero</label>
                                <input type="text" class="form-control" id="genero" >
                            </div>
                            <div class="form-group">
                                <label for="marca">Marca</label>
                                <input type="text" class="form-control" id="marca" >
                            </div>
                            <div class="form-group">
                                <label for="horma">Horma</label>
                                <input type="text" class="form-control" id="horma" >
                            </div>
                            <div class="form-group">
                                <label for="modelista">Modelista</label>
                                <input type="text" class="form-control" id="modelista" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="talla">Talla</label>
                                <input type="email" class="form-control" id="talla" >
                            </div>
                            <div class="form-group">
                                <label for="cuero">N° piezas de cuero</label>
                                <input type="email" class="form-control" id="cuero" >
                            </div>
                            <div class="form-group">
                                <label for="forro">N° piezas de forro</label>
                                <input type="email" class="form-control" id="forro" >
                            </div>
                            <div class="form-group">
                                <label for="fecha">Fecha</label>
                                <input type="date" class="form-control" id="fecha" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Cuero</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row">

                                <div class="col-md-7">
                                    <label for="exampleFormControlInput1">MATERIAL</label>
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleFormControlInput1">PIEZAS</label>
                                </div>
                                <div class="col-md-1">
                                    <a href="" class="btn btn-success">+</a>
                                </div>
                                <div class="form-group col-md-7">
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                                <div class="form-group col-md-3">
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                                <div class="form-group col-md-7">
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                                <div class="form-group col-md-3">
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Forro</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row">
                                <div class="col-md-7">
                                    <label for="exampleFormControlInput1">MATERIAL</label>
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleFormControlInput1">PIEZAS</label>
                                </div>
                                <div class="col-md-1">
                                    <a href="" class="btn btn-success">+</a>
                                </div>
                                <div class="form-group col-md-7">
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                                <div class="form-group col-md-3">
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                                <div class="form-group col-md-7">
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                                <div class="form-group col-md-3">
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Plantilla</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row">
                                <div class="col-md-10">
                                    <label for="exampleFormControlInput1">MATERIAL</label>
                                </div>
                                <div class="col-md-1">
                                    <a href="" class="btn btn-success">+</a>
                                </div>
                                <div class="form-group col-md-10">
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                                <div class="form-group col-md-10">
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
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
                            <h2>Perfilado</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row">
                                <div class="col-md-10">
                                    <label for="exampleFormControlInput1">MATERIAL</label>
                                </div>
                                <div class="col-md-1">
                                    <a href="" class="btn btn-success">+</a>
                                </div>
                                <div class="form-group col-md-10">
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                                <div class="form-group col-md-10">
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Cosido Vena</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row">
                                <div class="col-md-10">
                                    <label for="exampleFormControlInput1">MATERIAL</label>
                                </div>
                                <div class="col-md-1">
                                    <a href="" class="btn btn-success">+</a>
                                </div>
                                <div class="form-group col-md-10">
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                                <div class="form-group col-md-10">
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Pegado</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row">
                                <div class="col-md-10">
                                    <label for="exampleFormControlInput1">MATERIAL</label>
                                </div>
                                <div class="col-md-1">
                                    <a href="" class="btn btn-success">+</a>
                                </div>
                                <div class="form-group col-md-10">
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                                <div class="form-group col-md-10">
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
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
                            <h2>Armado</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Falsa</label>
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Contrafuerte</label>
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Puntera</label>
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Talón</label>
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Encajado</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Caja</label>
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Papel</label>
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Hantan</label>
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Bolsa</label>
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
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
                            <h2>Hab. Plantilla</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Sello pan de oro</label>
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Sello de esp.</label>
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">N° Troquel</label>
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
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
                                    <textarea class="form-control" rows="3"></textarea>
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
                                    <label for="exampleFormControlInput1">Modelaje</label>
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Producción</label>
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Gerencia</label>
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
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
                                    <label for="exampleInputFile">Seleccionar la imagen</label>
                                    <input type="file" id="exampleInputFile">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Seleccione la imagen</label>
                                    <input type="file" id="exampleInputFile">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Seleccione la imagen</label>
                                    <input type="file" id="exampleInputFile">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>







        </div>
    </div>
@endsection

@section('modals')
@endsection


@section('scripts')
    <script src="{{ asset('bootstrap-select/js/bootstrap-select.min.js') }}" ></script>
    <script src="{{ asset('js/mantenimiento/precio-referencial/index.js?v=1') }}"></script>
@endsection