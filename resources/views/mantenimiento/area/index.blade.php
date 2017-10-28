@extends('layouts.app')
@section('title','Áreas')

@section('content')
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    LISTADO DE ÁREAS
                </h1>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-md-12">
                    <button class="btn btn-primary btn-sm" data-area_crear>
                        <i class="fa fa-plus-square"></i> Nueva área
                    </button>
                </div>
                <div class="col-md-12 table-responsive">
                    <table class="table table-hover" id="dynamic-table-area">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                    </thead>
                    <tbody id="table-area">
                    @foreach( $areas as $area )
                        <tr data-area_id="{{ $area ->areId }}">
                            <td>{{ $area->areNombre }}</td>
                            <td>{{ ($area->areEstado==1)?'Activa':'No activa'}}</td>
                            <td>
                                <button class="btn btn-info btn-sm" data-area_editar="{{ $area ->areId }}"
                                        data-area_nombre="{{ $area->areNombre }}"
                                        data-area_estado="{{ $area->areEstado }}">
                                        <i class="fa fa-pencil"></i> Editar
                                </button>
                                <button class="btn btn-danger btn-sm" data-area_eliminar="{{ $area ->areId }}"
                                        data-area_nombre="{{ $area->areNombre }}">
                                    <i class="fa fa-trash-o"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                    {{ $areas->render() }}
                </div>
                <input type="hidden" id="area-subareas-url" value="{{ route('areas.subareas.area','') }}">
                <input type="hidden" id="area-url" value="{{ route('areas.list','') }}">
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    LISTADO DE SUB AREAS
                </h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-md-12">
                    <div id="div-button-subarea-crear">

                    </div>
                </div>
                <div class="col-md-12 table-responsive">
                    <table class="table table-striped" id="dynamic-table-subarea">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Despacho</th>
                            <th>En orden de producción</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody id="table-subarea">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div id="modal-area-crear" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>
                        Nueva área
                    </h2>
                </div>
                <form id="form-area-crear" action="{{route('areas.create')}}" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Nombre</label>
                                    <input type="text" name="area_nombre" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-1" id="clear_area_estado">
                                    <input type="checkbox"  name="area_estado" class="form-control" checked>
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

    <div id="modal-area-editar" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>
                        Modificar área
                    </h2>
                </div>
                <form id="form-area-editar" action="{{route('areas.edit')}}" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="area_id">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Nombre</label>
                                    <input type="text" name="area_nombre" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-1" id="div_check_area_estado">

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

    <div id="modal-area-eliminar" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>
                        Eliminar área
                    </h2>
                </div>
                <form id="form-area-eliminar" action="{{route('areas.delete')}}" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="area_id">
                        <div class="form-group">
                            <label>Seguro que desea eliminar la siguiente área?</label>
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="text" name="area_nombre" class="form-control" readonly>
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

    <div id="modal-subarea-crear" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>
                        Nueva subárea
                    </h2>
                </div>
                <form id="form-subarea-crear" action="{{route('areas.subareas.create')}}" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="area_id" id="subarea_area_id">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Nombre</label>
                                    <input type="text" name="subarea_nombre" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-1" id="clear_subarea_despacho">
                                    <input type="checkbox"  name="subarea_despacho" class="form-control">
                                </div>

                                <div class="col-md-11">
                                    <label class="beside_check">Despacho</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-1" id="clear_subarea_op">
                                    <input type="checkbox"  name="subarea_op" checked class="form-control">
                                </div>

                                <div class="col-md-11">
                                    <label class="beside_check">Visible en OP</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-1" id="clear_subarea_estado">
                                    <input type="checkbox"  name="subarea_estado" class="form-control" checked>
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

    <div id="modal-subarea-editar" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>
                        Modificar subárea
                    </h2>
                </div>
                <form id="form-subarea-editar" action="{{route('areas.subareas.edit')}}" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="subarea_id">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Nombre</label>
                                    <input type="text" name="subarea_nombre" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-1" id="div_check_subarea_despacho">

                                </div>

                                <div class="col-md-11">
                                    <label class="beside_check">Despacho</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-1" id="div_check_subarea_op">

                                </div>

                                <div class="col-md-11">
                                    <label class="beside_check">Visible en OP</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-1" id="div_check_subarea_estado">

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

    <div id="modal-subarea-eliminar" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>
                        Eliminar subárea
                    </h2>
                </div>
                <form id="form-subarea-eliminar" action="{{route('areas.subareas.delete')}}" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="subarea_id">

                        <div class="form-group">
                            <label>Seguro que desea eliminar la siguiente subárea?</label>
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="text" name="subarea_nombre" class="form-control" readonly>
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
    <script src="{{ asset('js/mantenimiento/area/index.js?v=1') }}"></script>
@endsection
