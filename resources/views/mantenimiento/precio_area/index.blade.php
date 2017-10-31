@extends('layouts.app')
@section('title','Precios por area')

@section('styles')
    <!-- Bootstrap Select -->
    <style>
        .select-margin
        {
            margin-top: 6px;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('bootstrap-select/css/bootstrap-select.min.css') }}">

@endsection

@section('content')
    <div class="row">
        <form id="form-precio-area-datos" action="{{ route('precio_area.data',['','','']) }}" method="GET">
            <div class="row form-group">
                <div class="col-md-4">
                    <div class="col-md-4 select-margin">
                        <label for="" class="pull-right">Área</label>
                    </div>
                    <div class="col-md-8">
                        <select name="pa_areas" id="pa_areas" title="< Seleccione >" class="selectpicker" data-live-search="true" data-width="100%">
                            @foreach( $areas as $area )
                                <option value="{{ $area->areId }}">{{ $area->areNombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="col-md-4 select-margin">
                            <label for="" class="pull-right">Subárea</label>
                    </div>
                    <div class="col-md-8">
                        <select name="pa_subareas" id="pa_subareas" class="selectpicker" data-live-search="true" title="< Seleccione >" data-width="100%">

                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="col-md-4 select-margin">
                        <label for="" class="pull-right">Subárea menor</label>
                    </div>
                    <div class="col-md-8">
                        <select name="pa_subareas_menores" id="pa_subareas_menores" class="selectpicker" data-live-search="true" title="< Seleccione >" data-width="100%">

                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="col-md-4">
                        <label for="pa_tipo_calculo">Tipo de cálculo </label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" name="pa_tipo_calculo" id="pa_tipo_calculo" class="form-control" readonly>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="col-md-4">
                        <div class="col-md-3"></div>
                        <div class="col-md-9">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa fa-spinner"></i> Cargar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <br><br>
    <div class="row hidden_it" id="precio-area-data">
        <div class="x_panel">
            <div class="x_title">
                <h2 id="title_data">
                    Listado de datos
                </h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div id="table_block_data">
                    <div class="col-md-12" id="div-button-precio-area-crear">

                    </div>
                    <div class="col-md-12 table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Tipo de precio</th>
                                <th>Precio/Docena</th>
                                <th>Precio/Par</th>
                                <th>Condición</th>
                                <th>Precio/Par</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                            </thead>
                            <tbody id="table-precio-area">

                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="table_block_descriptions">
                    <div class="col-md-12 table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody id="table_descriptions">

                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="url_descriptions" value="{{ route('description.data') }}">
    <input type="hidden" id="url-subareas" value="{{ route('precio_area.subareas','') }}">
    <input type="hidden" id="url-subareas-menores" value="{{ route('precio_area.subareas_menores','') }}">
    <input type="hidden" id="url-precio-area-niveles" value="{{ route('precio_area.levels',['','','']) }}">

    <input type="hidden" id="url-precio-area-crear"    value="{{ route('precio_area.create') }}">
    <input type="hidden" id="url-precio-area-editar"   value="{{ route('precio_area.edit') }}">
    <input type="hidden" id="url-precio-area-eliminar" value="{{ route('precio_area.delete') }}">


@endsection

@section('modals')
    <div id="modal-precio-area" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 id="modal_title">

                    </h2>
                </div>
                <form id="form-precio-area" action="{{route('precio_area.create')}}" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="subarea_menor_id" id="subarea_menor_id">
                        <input type="hidden" name="tipo_calculo_id" id="tipo_calculo_id">
                        <input type="hidden" name="precio_area_id" id="precio_area_id">

                        <div class="row" id="row_precio_area_tipo_precio">
                            <div class="col-md-6 form-group">
                                <label>Tipo de precio</label>
                                <select name="precio_area_tipo_precio" id="precio_area_tipo_precio" title="< Seleccione >" class="selectpicker" data-live-search="true" data-width="100%">
                                    <option value="2">Variable</option>
                                    <option value="1">Fijo</option>
                                </select>
                            </div>
                        </div>

                        <div id="row_precio_area_mensaje_eliminar">

                        </div>

                        <div class="row" id="row_precio_area_nombre">

                        </div>

                        <div class="row" id="row_precio_area_descripcion">

                        </div>

                        <div class="row" id="row_precio_area_precio">

                        </div>

                        <div class="row form-group" id="row_precio_area_condicion">

                        </div>

                        <div class="row form-group" id="row_precio_area_mayor_condicion">

                        </div>

                        <div class="row" id="row_precio_area_numero_condicion">

                        </div>

                        <div class="row" id="row_precio_area_precio_condicion">

                        </div>

                        <div class="row" id="row_precio_area_estado">
                            <div class="col-md-1">
                                <input type="checkbox"  name="precio_area_estado" class="form-control" checked>
                            </div>

                            <div class="col-md-11">
                                <label class="beside_check">Estado</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger btn-sm" data-cancel><i class="fa fa-close"></i> Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-diamond" aria-hidden="true"></i> Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('bootstrap-select/js/bootstrap-select.min.js') }}" ></script>
    <script src="{{ asset('js/mantenimiento/precio_area/index.js?v=1') }}"></script>
@endsection
