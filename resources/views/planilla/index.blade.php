@extends('layouts.app')
@section('title','Planillas')

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
    <link rel="stylesheet" href="{{ asset('bootstrap-time/css/bootstrap-timepicker.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-2 select-margin">
                <label for="" class="pull-right">Fecha inicio: </label>
            </div>
            <div class="col-md-3">
                <input type="date" id="filter_fecha_inicio" class="form-control">
            </div>

            <div class="col-md-2 select-margin">
                <label for="" class="pull-right">Fecha fin: </label>
            </div>
            <div class="col-md-3">
                <input type="date" id="filter_fecha_fin" class="form-control">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary" data-filter><i class="fa fa-search"></i> Buscar</button>
            </div>
        </div>
    </div><br><br>
    <div class="row">
        <button class="btn btn-success" data-create><i class="fa fa-plus-circle"></i> Nueva planilla</button>
    </div>
    <div class="row table-responsive">
        <div class="col-md-12">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Código</th>
                    <th>Fecha inicio</th>
                    <th>Fecha fin</th>
                    <th>Estado</th>
                    <th>R1</th>
                    <th>R2</th>
                    <th>Acción</th>
                </tr>
                </thead>
                <tbody id="table_planilla" style="cursor: pointer">
                    @foreach( $planillas as $planilla )
                        <tr data-trow="{{ $planilla->plaId }}">
                            <td>{{ $planilla->plaId }}</td>
                            <td>{{ $planilla->fecha_inicio }}</td>
                            <td>{{ $planilla->fecha_fin }}</td>
                            <td>{{ ($planilla->plaEstado==1)?'Activa':'No activa'}}</td>
                            <td></td>
                            <td></td>
                            <td>
                                <button class="btn btn-info btn-sm" data-edit="{{ $planilla->plaId }}"
                                    data-fecha_inicio="{{$planilla->fecha_inicio}}" data-fecha_fin="{{$planilla->fecha_fin}}">
                                    <i class="fa fa-edit"></i> Editar
                                </button>
                                <button class="btn btn-danger btn-sm" data-delete="{{ $planilla->plaId }}"
                                        data-fecha_inicio="{{$planilla->fecha_inicio}}" data-fecha_fin="{{$planilla->fecha_fin}}">
                                    <i class="fa fa-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <input type="hidden" id="url_planillas" value="{{ route('planillas.list') }}">
    <input type="hidden" id="url_planillas_filter" value="{{ route('planillas.filter',['','']) }}">
    <input type="hidden" id="url_planillas_create" value="{{ route('planillas.create') }}">
    <input type="hidden" id="url_planillas_edit" value="{{ route('planillas.edit') }}">
    <input type="hidden" id="url_planillas_delete" value="{{ route('planillas.delete') }}">
    <input type="hidden" id="url_planillas_subareas_menores" value="{{ route('planillas.subareas.menores','') }}">
@endsection

@section('modals')
    <div id="modal_planilla" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 id="modal_planilla_title">

                    </h2>
                </div>
                <form id="form_planilla" action="{{ route('planillas.create') }}" method="post">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="planilla_id">

                        <div class="form-group">
                            <div class="row" id="row_fecha_inicio">

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row" id="row_mensaje_eliminar">

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row" id="row_dia_fecha_inicio">

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row" id="row_fecha_fin">

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row" id="row_dia_fecha_fin">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-modal_close><i class="fa fa-close"></i> Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-diamond" aria-hidden="true"></i> Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('bootstrap-time/js/bootstrap-timepicker.js') }}"></script>
    <script src="{{ asset('js/planilla/index.js?v=1') }}"></script>
@endsection