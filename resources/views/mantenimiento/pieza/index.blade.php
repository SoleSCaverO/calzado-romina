@extends('layouts.app')
@section('title','Piezas')
@section('content')
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    Lista de descriptiones
                </h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-md-12">
                    <button class="btn btn-primary btn-sm" data-description_create>
                        <i class="fa fa-plus-square"></i> Nueva descripción
                    </button>
                </div>
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
                    @foreach( $descriptions as $description )
                        <tr data-description_id="{{ $description->id }}">
                            <td>{{ $description->name }}</td>
                            <td>{{ $description->description }}</td>
                            <td>{{ ($description->state==1)?'Activa':'Inactiva' }}</td>
                            <td>
                                <button class="btn btn-info btn-sm" data-description_edit="{{ $description ->id}}"
                                        data-description_name="{{ $description->name }}"
                                        data-description_description="{{ $description->description }}"
                                        data-description_state="{{ $description->state }}">
                                        <i class="fa fa-pencil"></i> Editar
                                </button>
                                <button class="btn btn-danger btn-sm" data-description_delete="{{ $description ->id }}"
                                        data-description_name="{{ $description->name }}">
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
                    TIPOS DE PIEZAS PARA CALCULO X NIVELES
                </h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-md-12" id="btn_add_piece">

                </div>
                <div class="col-md-12 table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Múltiplo</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody id="table_piezas">

                        </tbody>
                    </table>
                </div>

                <input type="hidden" id="url_load_descriptions" value="{{ route('description.data') }}">

                <input type="hidden" id="url_pieza_listar" value="{{ route('piezas.list','') }}">
                <input type="hidden" id="url_pieza_crear" value="{{ route('piezas.create') }}">
                <input type="hidden" id="url_pieza_editar" value="{{ route('piezas.edit') }}">
                <input type="hidden" id="url_pieza_eliminar" value="{{ route('piezas.delete') }}">

                <input type="hidden" id="validate_unique_name" value="{{ route('piezas.validate_name') }}">
                <input type="hidden" id="validate_start" value="{{ route('piezas.validate_start') }}">
                <input type="hidden" id="validate_end" value="{{ route('piezas.validate_end') }}">
                <input type="hidden" id="id_global">
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div id="modal_description_create" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Nueva descripción</h2>
                </div>
                <form id="form_description_create" action="{{ route('description.create') }}" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="">Nombre:</label>
                            <input type="text" name="description_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Descripción:</label>
                            <input type="text" name="description_description" class="form-control">
                        </div>

                        <div class="form-group">
                            <div class="row">
                            <div class="col-md-1">
                                <input type="checkbox"  name="description_state" class="form-control" checked>
                            </div>
                            <div class="col-md-11">
                                <label class="beside_check">Estado</label>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="btn_create_cancel" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Cancelar</button>
                        <button id="btn_create_accept" type="submit" class="btn btn-primary btn-sm"><i class="fa fa-diamond" aria-hidden="true"></i> Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="modal_description_edit" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Editar descripción</h2>
                </div>
                <form id="form_description_edit" action="{{ route('description.edit') }}" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="description_id">
                        <div class="form-group">
                            <label for="">Nombre:</label>
                            <input type="text" name="description_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Descripción:</label>
                            <input type="text" name="description_description" class="form-control">
                        </div>

                        <div class="form-group">
                            <div class="row" id="description_state_div">
                                <div class="col-md-1">
                                    <input type="checkbox"  name="description_state" class="form-control">
                                </div>
                                <div class="col-md-11">
                                    <label class="beside_check">Estado</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="btn_edit_cancel" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Cancelar</button>
                        <button id="btn_edit_accept" type="submit" class="btn btn-primary btn-sm"><i class="fa fa-diamond" aria-hidden="true"></i> Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="modal_description_delete" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Eliminar descripción</h2>
                </div>
                <form id="form_description_delete" action="{{ route('description.delete') }}" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="description_id">
                        <div class="form-group">
                            <label for="">¿Está seguro que desea eliminar la siguiente descripción?</label>
                            <input type="text" name="description_name" class="form-control" readonly>
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

    <div id="modal_pieza" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 id="pieza_title">

                    </h2>
                </div>
                <form id="form_pieza" action="" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="description_id" id="description_id">
                        <input type="hidden" name="pieza_id" id="pieza_id">

                        <div class="row" id="row_pieza_tipo">

                        </div>

                        <div class="row" id="row_pieza_multiplo">

                        </div>

                        <div class="row" id="row_pieza_consider">

                        </div>

                        <div class="row" id="row_pieza_inicio">

                        </div>

                        <div class="row">
                            <div id="row_pieza_fin">

                            </div>

                            <div id="row_pieza_infinito" style="margin-top: 20px">

                            </div>
                        </div>

                        <div id="row_pieza_mensaje_eliminar">

                        </div>

                        <div class="row" id="row_pieza_estado">
                            <div class="col-md-1">
                                <input type="checkbox"  name="pieza_estado" class="form-control" checked>
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
@endsection

@section('scripts')
    <script src="{{ asset('js/mantenimiento/pieza/index.js?v=1') }}"></script>
@endsection
