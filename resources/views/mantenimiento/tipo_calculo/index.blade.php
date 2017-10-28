@extends('layouts.app')
@section('title','Tipo de cálculo')
@section('content')
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    Listado de tipos de cálculo
                </h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-md-12">
                    <button class="btn btn-primary btn-sm" data-tc_crear>
                        <i class="fa fa-plus-square"></i> Nuevo tipo de cálculo
                    </button>
                </div>
                <div class="col-md-12 table-responsive">
                    <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Nivel</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                    </thead>
                    <tbody id="tc-table">
                    @foreach( $tipo_calculos as $tipo_calculo )
                        <tr data-tc_id="{{ $tipo_calculo ->tcalId }}">
                            <td>{{ $tipo_calculo->tcalDescripcion }}</td>
                            <td>{{ ($tipo_calculo->tcalTipo==1)?'Normal':'Nivel' }}</td>
                            <td>{{ ($tipo_calculo->tcalEstado==1)?'Activo':'Inactivo' }}</td>
                            <td>
                                <button class="btn btn-info btn-sm" data-tc_editar="{{ $tipo_calculo ->tcalId }}"
                                        data-tc_nombre="{{ $tipo_calculo->tcalDescripcion }}"
                                        data-tc_nivel="{{ $tipo_calculo->tcalTipo }}"
                                        data-tc_estado="{{ $tipo_calculo->tcalEstado }}">
                                        <i class="fa fa-pencil"></i> Editar
                                </button>
                                <button class="btn btn-danger btn-sm" data-tc_eliminar="{{ $tipo_calculo ->tcalId }}"
                                        data-tc_nombre="{{ $tipo_calculo->tcalDescripcion }}">
                                    <i class="fa fa-trash-o"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                    {{ $tipo_calculos->render() }}
                </div>

                <input type="hidden" id="url-tipo-calculos-listar" value="{{ route('tipo_calculos.tipo_calculos','') }}">
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div id="modal-tc-crear" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>
                        Nuevo tipo de cálculo
                    </h2>
                </div>
                <form id="form-tc-crear" action="{{route('tipo_calculos.create')}}" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                <label>Nombre</label>
                                <input type="text" name="tc_nombre" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1">
                                <input type="checkbox"  name="tc_nivel" class="form-control">
                            </div>

                            <div class="col-md-11">
                                <label class="beside_check">Nivel</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1">
                                <input type="checkbox"  name="tc_estado" class="form-control" checked>
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

    <div id="modal-tc-editar" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>
                        Modificar tipo de cálculo
                    </h2>
                </div>
                <form id="form-tc-editar" action="{{route('tipo_calculos.edit')}}" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="tc_id">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Nombre</label>
                                <input type="text" name="tc_nombre" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1" id="div_check_tc_nivel">

                            </div>

                            <div class="col-md-11">
                                <label class="beside_check">Nivel</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1" id="div_check_tc_estado">

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

    <div id="modal-tc-eliminar" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>
                        Eliminar tipo de cálculo
                    </h2>
                </div>
                <form id="form-tc-eliminar" action="{{route('tipo_calculos.delete')}}" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="tc_id">
                        <label>Seguro que desea eliminar el siguiente tipo de cálculo?</label>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" name="tc_nombre" class="form-control">
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
    <script src="{{ asset('js/mantenimiento/tipo_calculo/index.js?v=1') }}"></script>
@endsection
