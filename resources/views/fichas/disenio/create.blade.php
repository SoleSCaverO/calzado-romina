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
        .margin-top{
            margin-top: 15px;
        }
        .bolded{
            color: #73879C;
            font-weight:bold;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('bootstrap-select/css/bootstrap-select.min.css') }}">
@endsection

@section('content')
    <form id="form_materials" action="{{ route('ficha.tecnica.store') }}" method="post">
        <input type="hidden" id="_token" value="{{ csrf_token() }}">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    <h2><b>FICHA TECNICA</b></h2>
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
                                    <input type="text" class="form-control" name="coleccion" id="coleccion">
                                </div>
                                <div class="form-group">
                                    <label for="genero">Genero</label>
                                    <input type="text" class="form-control" name="genero" id="genero">
                                </div>
                                <div class="form-group">
                                    <label for="marca">Marca</label>
                                    <input type="text" class="form-control" name="marca" id="marca">
                                </div>
                                <div class="form-group">
                                    <label for="horma">Horma</label>
                                    <input type="text" class="form-control" name="horma" id="horma">
                                </div>
                                <div class="form-group">
                                    <label for="modelista">Modelista</label>
                                    <input type="text" class="form-control" name="modelista" id="modelista">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="talla">Talla</label>
                                    <input type="text" class="form-control" name="talla" id="talla">
                                </div>
                                <div class="form-group">
                                    <label for="cuero">N° piezas de cuero</label>
                                    <input type="text" class="form-control" name="cuero" id="cuero">
                                </div>
                                <div class="form-group">
                                    <label for="forro">N° piezas de forro</label>
                                    <input type="text" class="form-control" name="forro" id="forro">
                                </div>
                                <div class="form-group">
                                    <label for="fecha">Fecha</label>
                                    <input type="date" class="form-control" name="fecha" id="fecha" value="{{ $today }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach($areas as $area)
                        @if( $area->tipo == 1)
                            @if( $area->nombre == 'CUERO')
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
                                                <div class="col-md-4">
                                                    <label for="material">Tipo</label>
                                                </div><div class="col-md-3">
                                                    <label for="material">Color</label>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="piezas">Piezas</label>
                                                </div>
                                            </div>
                                            <div class="row" data-material="{{ $area->id }}" data-type_material="{{ $area->tipo }}" data-area="{{ $area->nombre }}">
                                                <div class="material">
                                                    <div class="form-group col-md-4">
                                                        <input type="text" class="form-control" id="material">
                                                    </div>
                                                    <div class="form-group col-md-3">
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
                                                    <label for="material">Material</label>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="piezas">Piezas</label>
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
                            @endif
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
                                                    <input type="text" class="form-control bolded" name="falsa[]" value="Falsa">
                                                    <input type="text" class="form-control margin-top" name="falsa[]">
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control bolded" name="contrafuerte[]" value="Contrafuerte">
                                                    <input type="text" class="form-control margin-top" name="contrafuerte[]" >
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control bolded" name="puntera[]" value="Puntera">
                                                    <input type="text" class="form-control margin-top" name="puntera[]">
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control bolded" name="talon[]" value="Talón">
                                                    <input type="text" class="form-control margin-top" name="talon[]">
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
                                                    <input type="text" class="form-control bolded" name="caja[]" value="Caja" id="caja">
                                                    <input type="text" class="form-control margin-top" name="caja[]">
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control bolded" name="papel[]" value="Papel" id="papel">
                                                    <input type="text" class="form-control margin-top" name="papel[]">
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control bolded" name="hantan[]" value="Hantan" id="talon">
                                                    <input type="text" class="form-control margin-top" name="hantan[]">
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control bolded" name="bolsa[]" value="Bolsa" id="bolsa">
                                                    <input type="text" class="form-control margin-top" name="bolsa[]">
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
                                                    <input type="text" class="form-control bolded" name="sello_pan_oro[]" value="Sello pan de oro">
                                                    <input type="text" class="form-control margin-top" name="sello_pan_oro[]">
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control bolded" name="sello_especificaion[]" value="Sello de esp.">
                                                    <input type="text" class="form-control margin-top" name="sello_especificaion[]">
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control bolded" name="troquel[]" value="N° Troquel">
                                                    <input type="text" class="form-control margin-top" name="troquel[]">
                                                </div>
                                                <div class="row">
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
                                                <div class="row text-center margin-top">
                                                    <button type="button" class="btn btn-sm btn-info" id="add_checkboxes"><i class="fa fa-plus"></i> OTROS </button>
                                                </div>
                                                <div class="row" id="otros">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @if( $area->nombre == 'PERFILADO' )
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
                                                <div class="col-md-4">
                                                    <label for="material">Material</label>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="material">Color</label>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="material">Cantidad</label>
                                                </div>
                                            </div>
                                            <div class="row" data-material="{{ $area->id }}" data-type_material="{{ $area->tipo }}" data-area="{{ $area->nombre }}">
                                                <div class="material">
                                                    <div class="form-group col-md-4">
                                                        <input type="text" class="form-control" id="material">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <input type="text" class="form-control" id="material">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <input type="text" class="form-control" id="material">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-success" data-add_material>+</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row margin-top">
                                                <div class="col-md-5 form-group">
                                                    <label for="aguja">Aguja</label>
                                                    <input type="text" id="aguja" class="form-control">
                                                </div>
                                                <div class="col-md-5 form-group">
                                                    <label for="hilo_forro">Hilo Forro</label>
                                                    <input type="text" id="hilo_forro" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="hilos">Hilos</label>
                                                </div>
                                            </div>
                                            <div class="row" data-material="{{ $area->id }}" data-type_material="{{ $area->tipo }}" data-area="{{ $area->nombre }}">
                                                <div class="material">
                                                    <div class="form-group col-md-10">
                                                        <input type="text" class="form-control" id="material">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-success" data-add_material data-thread="1">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @elseif( $area->nombre == 'COSIDO VENA' )
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
                                                    <div class="col-md-5">
                                                        <label for="">Color de hilo de vena</label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label for="">Tipo de cosido</label>
                                                    </div>
                                                </div>
                                                <div class="row margin-top" data-material="{{ $area->id }}" data-type_material="{{ $area->tipo }}" data-area="{{ $area->nombre }}">
                                                    <div class="material">
                                                        <div class="form-group col-md-5">
                                                            <input type="text" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-5">
                                                            <input type="text" class="form-control">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn-success" data-add_material>+</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif( $area->nombre == 'PEGADO' )
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
                                                <div class="row" data-material="{{ $area->id }}" data-type_material="{{ $area->tipo }}" data-area="{{ $area->nombre }}">
                                                    <div class="material">
                                                        <div class="col-md-12">
                                                            <label for="">Planta</label>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control" placeholder="Tipo">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control" placeholder="Color">
                                                        </div>
                                                    </div>
                                                    <div class="material">
                                                        <div class="col-md-12 margin-top">
                                                            <label for="">Hilo lateral</label>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control" placeholder="Tipo">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control" placeholder="Color">
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
                                            <label for="imagen1">Seleccionar imagen externa</label>
                                            <input type="file" name="imagen1" id="imagen1" accept="image/*">
                                        </div>
                                        <div class="col-md-4">
                                            <img src="" class="img-responsive img-preview" id="imagen1-preview" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-8">
                                            <label for="imagen2">Seleccione imagen interna</label>
                                            <input type="file" name="imagen2" id="imagen2" accept="image/*">
                                        </div>
                                        <div class="col-md-4">
                                            <img src="" class="img-responsive img-preview" id="imagen2-preview" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-8">
                                            <label for="imagen3">Seleccione imagen superior</label>
                                            <input type="file" name="imagen3" id="imagen3" accept="image/*">
                                        </div>
                                        <div class="col-md-4">
                                            <img src="" class="img-responsive img-preview" id="imagen3-preview" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-8">
                                            <label for="imagen4">Seleccione imagen posterior</label>
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
                </div>
                <div class="row text-center button-save">
                    <a href="{{ route('ficha.tecnica') }}" class="btn btn-warning"><i class="fa fa-backward"></i> Volver</a>
                    <button type="submit" class="btn btn-primary" id="btn_save"><i class="fa fa-diamond"></i> Guardar Datos</button>
                </div>
            </div>
        </div>
    </form>
    <input type="hidden" id="ficha_tecnica" value="{{ route('ficha.tecnica') }}">
@endsection

@section('scripts')
    <script src="{{ asset('bootstrap-select/js/bootstrap-select.min.js') }}" ></script>
    <script src="{{ asset('js/mantenimiento/ficha/disenio/index.js?v=1') }}"></script>
@endsection