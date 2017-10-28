@extends('layouts.app')
@section('title','Término de trabajo')

@section('styles')
    <style>
        .margin_top{
            margin-top:6px;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('bootstrap-time/css/bootstrap-timepicker.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-4 margin_top">
                <label for="">Código de trabajador: </label>
            </div>
            <div class="col-md-8">
                <div class="input-group">
                    <input type="number" class="form-control" id="worker_code">
                    <span class="input-group-btn"><button class="btn btn-info" data-search type="button"><i class="fa fa-search"></i></button></span>
                </div>
            </div>
        </div>

        <div class="col-md-1 margin_top">
            <label for="" class="pull-right">Fecha:</label>
        </div>
        <div class="col-md-2">
            <input type="date" id="date" value="{{$today}}" class="form-control">
        </div>

        <div class="col-md-1 margin_top">
            <label for="" class="pull-right">Hora:</label>
        </div>
        <div class="col-md-2">
            <div class="input-group bootstrap-timepicker timepicker">
                <input name="time" id="time" type="text" class="form-control input-small">
                <span class="input-group-addon" style="background:#5bc0de"><i class="glyphicon glyphicon-time" style="color: white"></i></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="col-md-4 margin_top">
                <label for="">Área: </label>
            </div>
            <div class="col-md-8">
                <input name="area_id" id="area_id" class="form-control" readonly>
            </div>
        </div>

        <div class="col-md-1 margin_top">
            <label for="" class="pull-right">Subárea: </label>
        </div>
        <div class="col-md-5">
            <input name="subarea_id" id="subarea_id" class="form-control" readonly>
        </div>
    </div><br>

    <div class="row">
        <div class="col-md-6">
            <div class="col-md-4 margin_top">
                <label for="">Tipo de trabajo: </label>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" id="type_work" readonly>
                <input type="hidden" class="form-control" id="type_work_id">
            </div>
            <div class="col-md-3">
                <button class="btn btn-success pull-right" data-change_type_work><i class="fa fa-refresh"></i> Cambiar</button>
            </div>
        </div>
        <div class="col-md-1 margin_top">
            <label for="" class="pull-right">Nombres: </label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" id="nombres" readonly>
        </div>
    </div>

    <div class="row"><br><br>
        <div class="col-md-10 col-md-offset-1">
            <div class="x_panel">
                <div class="x_title row">
                    <div class="col-md-11">
                        <h2> Generar término de trabajo </h2>
                    </div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-2">
                            <label for="">N° Orden: </label>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="number" id="search_order" class="form-control">
                                <span class="input-group-btn"><button class="btn btn-info" data-search_order type="button"><i class="fa fa-search"></i></button></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label for=""> Pares totales en OP: </label>
                        </div>
                        <div class="col-md-2">
                            <input type="number" id="total_pairs" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row"><br>
                        <div class="col-md-2">
                            <label for=""> Pares Iniciados: </label>
                        </div>
                        <div class="col-md-2">
                            <input type="number" id="pairs" class="form-control" readonly>
                            <input type="hidden" id="order_id" class="form-control">
                        </div>
                    </div>

                    <div class="row"><br>
                        <div class="col-md-2">
                            <label for="">Pares x completar Inicio</label>
                        </div>
                        <div class="col-md-2">
                            <input type="number" id="disponible" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="row"><br>
                        <div class="col-md-2">
                            <label for="">Pares terminados: </label>
                        </div>
                        <div class="col-md-2">
                            <input type="number" id="pairs_user" class="form-control">
                        </div>
                    </div><br>
                    <div class="row">
                        <div class="col-md-2">
                            <label for="">Observación: </label>
                        </div>
                        <div class="col-md-10">
                            <textarea id="description" class="form-control" style="resize: none"></textarea>
                        </div>
                    </div>
                    <div class="row"><br>
                        <div class="col-md-6">
                            <button class="btn btn-primary" data-save_end_work><i class="fa fa-diamond"></i> Generar término de Trabajo</button>
                        </div>
                        <div class="col-md-6">
                            <a class="btn btn-warning pull-right" id="back_end" href="{{ route('termino_trabajo') }}"><i class="fa fa-backward"></i> Volver</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="_token" value="{{ csrf_token() }}">
    <input type="hidden" id="url_termino_trabajo_store" value="{{ route('termino_trabajo.store') }}">

    <input type="hidden" id="url_worker_data" value="{{ route('termino_trabajo.trabajador.data','') }}">
    <input type="hidden" id="url_change_type_work" value="{{ route('termino_trabajo.trabajador.change_type_work',['','']) }}">
    <input type="hidden" id="url_search_order" value="{{ route('termino_trabajo.orden.search_order',['','']) }}">
@endsection

@section('scripts')
    <script src="{{ asset('bootstrap-time/js/bootstrap-timepicker.js') }}"></script>
    <script src="{{ asset('js/produccion/termino-trabajo/create.js') }}"></script>
@endsection