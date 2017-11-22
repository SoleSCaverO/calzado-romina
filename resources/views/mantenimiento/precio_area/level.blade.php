@extends('layouts.app')
@section('title','Niveles')

@section('styles')
    <link rel="stylesheet" href="{{ asset('bootstrap-select/css/bootstrap-select.min.css') }}">
@endsection

@section('content')
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    Listado de niveles
                </h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-md-12">
                    <button class="btn btn-primary btn-sm" data-nivel_crear>
                        <i class="fa fa-plus-square"></i> Nuevo nivel
                    </button>
                </div>
                <div class="col-md-12 table-responsive">
                    <table class="table table-hover" id="dynamic_table_nivel">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Condición</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody id="table_niveles">
                        @foreach( $niveles as $nivel )
                            <tr data-nivel_id="{{ $nivel ->nivId }}">
                                <td>{{ $nivel->nivNombre }}</td>
                                <td>{{ $nivel->nivCondicion }}</td>
                                <td>{{ $nivel->nivInicio }}</td>
                                <td>{{ ($nivel->nivFin ==99999)?'Infinito':$nivel->nivFin}}</td>
                                <td>{{ ($nivel->nivEstado==1)?'Activo':'Inactivo' }}</td>
                                <td>
                                    <button class="btn btn-info btn-sm" data-nivel_editar="{{ $nivel ->nivId }}"
                                            data-nivel_nombre="{{ $nivel->nivNombre }}"
                                            data-nivel_descripcion="{{ $nivel->nivDescripcion }}"
                                            data-nivel_condicion="{{ $nivel->nivCondicion }}"
                                            data-nivel_inicio="{{ $nivel->nivInicio }}"
                                            data-nivel_fin="{{ $nivel->nivFin }}"
                                            data-nivel_estado="{{ $nivel->nivEstado }}">
                                        <i class="fa fa-pencil"></i> Editar
                                    </button>
                                    <button class="btn btn-danger btn-sm" data-nivel_eliminar="{{ $nivel ->nivId }}"
                                            data-nivel_nombre="{{ $nivel->nivNombre }}">
                                        <i class="fa fa-trash-o"></i> Eliminar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    Listado de precios
                </h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-md-12">
                    <div id="div_button_data_crear">

                    </div>
                </div>
                <div class="col-md-12 table-responsive">
                    <table class="table table-striped" id="dynamic_table_data">
                        <thead>
                        <tr>
                            <!-- <th>Nombre</th> -->
                            <th>Tipo pieza</th>
                            <th>Tipo precio</th>
                            <th>Precio/Doc</th>
                            <th>Precio/Par</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody id="table_precio_area">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row text-center">
        <a href="{{ route('precio_area') }}" class="btn btn-warning btn-sm"><i class="fa fa-backward"></i>  Volver</a>
    </div>

    <input type="hidden" id="url_nivel_listar" value="{{ route('precio_area.nivel.list',['','','']) }}">
    <input type="hidden" id="url_nivel_crear" value="{{ route('precio_area.nivel.create') }}">
    <input type="hidden" id="url_nivel_editar" value="{{ route('precio_area.nivel.edit') }}">
    <input type="hidden" id="url_nivel_eliminar" value="{{ route('precio_area.nivel.delete') }}">

    <input type="hidden" id="url_precio_area_piezas" value="{{ route('precio_area.piezas','') }}">
    <input type="hidden" id="url_precio_area_listar" value="{{ route('precio_area.data',['','','']) }}">
    <input type="hidden" id="url_precio_area_crear" value="{{ route('precio_area.create') }}">
    <input type="hidden" id="url_precio_area_editar" value="{{ route('precio_area.edit') }}">
    <input type="hidden" id="url_precio_area_eliminar" value="{{ route('precio_area.delete') }}">
@endsection

@section('modals')
    <div id="modal_nivel" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 id="nivel_title">

                    </h2>
                </div>
                <form id="form_nivel" action="" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="subarea_menor_id" id="subarea_menor_id" value="{{$subarea_menor_id}}">
                        <input type="hidden" name="tipo_calculo_id" id="tipo_calculo_id" value="{{$tipo_calculo_id}}">
                        <input type="hidden" name="description_id" id="description_id" value="{{ $description_id }}">
                        <input type="hidden" name="nivel_id" id="nivel_id">

                        <div class="row" id="row_nivel_nombre">

                        </div>

                        <!-- div class="row" id="row_nivel_descripcion">

                        </div -->

                        <div class="row" id="row_nivel_condicion">

                        </div>

                        <div class="row" id="row_nivel_inicio">

                        </div>
                        <div class="row">

                            <div id="row_nivel_fin">

                            </div>

                            <div id="row_nivel_infinito" style="margin-top: 20px">

                            </div>
                        </div>

                        <div class="row" id="row_nivel_estado">
                            <div class="col-md-1">
                                <input type="checkbox"  name="nivel_estado" class="form-control" checked>
                            </div>

                            <div class="col-md-11">
                                <label class="beside_check">Estado</label>
                            </div>
                        </div>

                        <div id="row_nivel_mensaje_eliminar">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-diamond" aria-hidden="true"></i> Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal_precio_area" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 id="precio_area_title">

                    </h2>
                </div>
                <form id="form_precio_area" action="" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="subarea_menor_id" value="{{$subarea_menor_id}}">
                        <input type="hidden" name="tipo_calculo_id" value="{{$tipo_calculo_id}}">
                        <input type="hidden" name="nivel_id" id="nivel_id" >
                        <input type="hidden" name="precio_area_id">

                        <div class="row" id="row_precio_area_tipo_precio">
                            <div class="col-md-6 form-group">
                                <label>Tipo de precio</label>
                                <select name="precio_area_tipo_precio" id="precio_area_tipo_precio" title="< Seleccione >" class="selectpicker" data-live-search="true" data-width="100%">
                                    <option value="2">Variable</option>
                                    <option value="1">Fijo</option>
                                </select>
                            </div>
                        </div>

                        <!-- div class="row" id="row_precio_area_nombre">

                        </div -->

                        <div class="row" id="row_precio_area_tipo">

                        </div>

                        <div class="row">
                            <div class="col-md-6" id="row_precio_area_piezas_inicio">

                            </div>

                            <div class="col-md-6" id="row_precio_area_piezas_fin">

                            </div>
                        </div>

                        <div class="row" id="row_precio_area_precio">

                        </div>

                        <div class="row" id="row_precio_area_estado">
                            <div class="col-md-1">
                                <input type="checkbox"  name="precio_area_estado" class="form-control" checked>
                            </div>

                            <div class="col-md-11">
                                <label class="beside_check">Estado</label>
                            </div>
                        </div>

                        <div id="row_precio_area_mensaje_eliminar">

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
    <script src="{{ asset('js/mantenimiento/precio_area/level.js?v=1') }}"></script>
@endsection
