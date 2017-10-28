@extends('layouts.app')
@section('title','Trabajadores')
@section('styles')
    <link rel="stylesheet" href="{{ asset('bootstrap-select/css/bootstrap-select.min.css') }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="x_panel">
                <div class="x_title">
                    <h2>
                        ÁREAS
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <select name="area_id" id="area_id" title="< Seleccione área >" class="selectpicker" data-live-search="true" data-width="100%" data-size="10">
                        @foreach( $areas as $area )
                            <option value="{{ $area->areId  }}">{{ $area->areNombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="x_panel">
                <div class="x_title">
                    <h2>
                        SUBÁREAS
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <select name="subarea_id" id="subarea_id" title="< Seleccione subárea >" class="selectpicker" data-live-search="true" data-width="100%" data-size="10">

                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="x_panel">
                <div class="x_title">
                    <h2>
                        SUBÁREAS - MENORES
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <select name="select_subarea_menor_id" id="select_subarea_menor_id" title="< Seleccione subárea menor >" class="selectpicker" data-live-search="true" data-width="100%" data-size="10">

                    </select>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    LISTADO DE TRABAJADORES
                </h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-md-12">
                    <div class="col-md-2" id="div-button-trabajador-crear">

                    </div>

                    <div class="col-md-2" id="button_export_excel">

                    </div>
                </div>
                <div class="col-md-12 table-responsive">
                    <table class="table table-striped" id="table">
                        <thead>
                        <tr>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>DNI</th>
                            <th>Tipo trabajo</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <tbody id="table-trabajadores">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="url-trabajadores-subarea" value="{{ route('trabajadores.subarea.workers','') }}">
    <input type="hidden" id="url-trabajador-search-dni-subarea" value="{{ route('trabajadores.search_dni',['',''])}}">
    <input type="hidden" id="url-trabajador-tipo-trabajos" value="{{ route('trabajadores.type_works',['',''])}}">
    <input type="hidden" id="url-trabajadores-excel" value="{{ route('trabajadores.workers_excel')}}">

    <input type="hidden" id="url_subareas" value="{{ route('areas.subareas.subareas_menores.subareas','') }}">
    <input type="hidden" id="url_subareas_menores" value="{{ route('areas.subareas.subareas_menores','') }}">
    <input type="hidden" id="_token" value="{{ csrf_token() }}">
@endsection

@section('modals')
    <div id="modal-trabajador-crear" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>
                        Nuevo trabajador
                    </h2>
                </div>
                <form id="form-trabajador-crear" action="{{route('trabajadores.create')}}" method="POST">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <input type="hidden" name="subarea_menor_id">

                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Nombres</label>
                            <input type="text" name="trabajador_nombres" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Apellidos</label>
                            <input type="text" name="trabajador_apellidos" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>DNI</label>
                            <input type="text" name="trabajador_dni" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Tipo trabajo</label>
                            <select name="trabajador_tipo_trabajo" id="trabajador_tipo_trabajo" class="form-control">
                                <option value="1">Fijo</option>
                                <option value="2">Destajo</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-1">
                            <input type="checkbox"  name="trabajador_estado" class="form-control" checked>
                        </div>

                        <div class="col-md-11">
                            <label class="beside_check">Estado</label>
                        </div>
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

    <div id="modal-trabajador-editar" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>
                        Modificar trabajador
                    </h2>
                </div>
                <form id="form-trabajador-editar" action="{{route('trabajadores.edit')}}" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="subarea_menor_id">
                        <input type="hidden" name="trabajador_id">

                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Nombres</label>
                                <input type="text" name="trabajador_nombres" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Apellidos</label>
                                <input type="text" name="trabajador_apellidos" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>DNI</label>
                                <input type="number" name="trabajador_dni" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Tipo trabajo</label>
                                <select name="trabajador_tipo_trabajo" id="trabajador_tipo_trabajo_editar" class="form-control">

                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-1" id="check-trabajador-estado">

                            </div>

                            <div class="col-md-11">
                                <label class="beside_check">Estado</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-diamond" aria-hidden="true"></i> Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal-trabajador-eliminar" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>
                        Eliminar trabajador
                    </h2>
                </div>
                <form id="form-trabajador-eliminar" action="{{route('trabajadores.delete')}}" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="subarea_menor_id">
                        <input type="hidden" name="trabajador_id">
                        <label>Seguro que desea eliminar el siguiente trabajador, de esta subárea menor?</label>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <input type="text" name="trabajador_nombres" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-diamond" aria-hidden="true"></i> Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal-trabajador-existe" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>
                        Trabajador existente
                    </h2>
                </div>
                <form id="form-trabajador-existe" action="{{route('trabajadores.search_dni',['',''])}}" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="subarea_id">
                        <input type="hidden" name="trabajador_id">
                        <input type="hidden" name="trabajador_existe">
                        <label for="">Ya existe un trabajador con ese número de DNI, desea agregarlo a esta subárea?</label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm" id="btn-trabajador-existe" ><i class="fa fa-diamond" aria-hidden="true"></i> Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('bootstrap-select/js/bootstrap-select.min.js') }}" ></script>
    <script src="{{ asset('js/mantenimiento/trabajador/index.js?v=1') }}"></script>
@endsection
