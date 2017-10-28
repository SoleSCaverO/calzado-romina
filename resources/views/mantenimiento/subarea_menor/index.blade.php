@extends('layouts.app')
@section('title','Subáreas menores')

@section('styles')
    <link rel="stylesheet" href="{{ asset('bootstrap-select/css/bootstrap-select.min.css') }}">
@endsection

@section('content')
    <div class="col-md-12" style="margin-bottom: 15px">
        <div class="form-group">
            <div class="col-md-1">
                <label>Area:</label>
            </div>
            <div class="col-md-3">
                <select name="area_id" id="area_id" class="selectpicker" title="< Seleccione >" data-live-search="true" data-width="100%">
                    @foreach(  $areas as $area )
                        <option value="{{ $area->areId }}">{{ $area->areNombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    Listado de subáreas
                </h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-md-12 table-responsive">
                    <table class="table table-hover" id="dynamic-table-area">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Despacho</th>
                        <th>Estado</th>
                    </tr>
                    </thead>
                    <tbody id="table_subarea">

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
                    Listado de subáreas menores
                </h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-md-12">
                    <div id="div-button-subarea-menor-crear">

                    </div>
                </div>
                <div class="col-md-12 table-responsive">
                    <table class="table table-striped" id="dynamic-table-subarea">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo cálculo</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody id="table-subareas-menores">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="url_subareas" value="{{ route('areas.subareas.subareas_menores.subareas','') }}">
    <input type="hidden" id="url_subareas_menores" value="{{ route('areas.subareas.subareas_menores','') }}">
    <input type="hidden" id="url_tipo_calculos" value="{{ route('areas.subareas.subareas_menores.tipo_calculos_activos') }}">
@endsection

@section('modals')
    <div id="modal-subarea-menor-crear" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>
                        Nueva subárea menor
                    </h2>
                </div>
                <form id="form-subarea-menor-crear" action="{{route('areas.subareas.subareas_menores.create')}}" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="subarea_id" id="subarea_menor_subarea_id">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Nombre</label>
                                    <input type="text" name="subarea_menor_nombre" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Tipo de cálculo</label>
                                    <select name="tipo_calculo_id" class="form-control"  id="tipo_calculo_id_crear">
                                        @foreach( $tipos_calculos as $tipos_calculo  )
                                            <option value="{{$tipos_calculo->tcalId}}">{{$tipos_calculo->tcalDescripcion}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-1" id="clear_subarea_menor_estado">
                                    <input type="checkbox"  name="subarea_menor_estado" class="form-control" checked>
                                </div>

                                <div class="col-md-11">
                                    <label class="beside_check">Estado</label>
                                </div>
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

    <div id="modal-subarea-menor-editar" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>
                        Modificar subárea menor
                    </h2>
                </div>
                <form id="form-subarea-menor-editar" action="{{route('areas.subareas.subareas_menores.edit')}}" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="subarea_menor_id">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Nombre</label>
                                    <input type="text" name="subarea_menor_nombre" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Tipo de cálculo</label>
                                    <select name="tipo_calculo_id" id="tipo_calculo_id_edit" class="form-control">

                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-1" id="check-subarea-menor-estado">

                                </div>

                                <div class="col-md-11">
                                    <label class="beside_check">Estado</label>
                                </div>
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

    <div id="modal-subarea-menor-eliminar" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>
                        Eliminar subárea menor
                    </h2>
                </div>
                <form id="form-subarea-menor-eliminar" action="{{route('areas.subareas.subareas_menores.delete')}}" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="subarea_menor_id">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Seguro que desea eliminar la siguiente subárea menor?</label>
                                    <input type="text" name="subarea_menor_nombre" class="form-control" readonly>
                                </div>
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
@endsection

@section('scripts')
    <script src="{{ asset('bootstrap-select/js/bootstrap-select.min.js') }}" ></script>
    <script src="{{ asset('js/mantenimiento/subarea_menor/index.js?v=1') }}"></script>
@endsection
