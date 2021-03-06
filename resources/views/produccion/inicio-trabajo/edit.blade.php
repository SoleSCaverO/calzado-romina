@extends('layouts.app')
@section('title','Inicio de trabajo')

@section('styles')
    <style>
        .margin_top{
            margin-top:6px;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('bootstrap-time/css/bootstrap-timepicker.css') }}">
@endsection

@section('content')
    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <div class="col-md-4 margin_top">
                    <label for="">Código de trabajador: </label>
                </div>
                <div class="col-md-8">
                    <input type="number" value="{{ $inicio->trabajador($inicio->dtraId) }}" class="form-control" id="worker_code" readonly>
                </div>
            </div>

            <div class="col-md-1 margin_top">
                <label for="" class="pull-right">Fecha:</label>
            </div>
            <div class="col-md-2">
                <input type="date" id="date" value="{{ $date }}" class="form-control" readonly>
            </div>

            <div class="col-md-1 margin_top">
                <label for="" class="pull-right">Hora:</label>
            </div>
            <div class="col-md-2">
                <input name="time" id="time"  value="{{ $time }}" type="text" class="form-control input-small" readonly>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <div class="col-md-4 margin_top">
                    <label for="">Área: </label>
                </div>
                <div class="col-md-8">
                    <input name="area_id" id="area_id" value="{{ $area }}"  class="form-control" readonly>
                </div>
            </div>

            <div class="col-md-1 margin_top">
                <label for="" class="pull-right">Subárea: </label>
            </div>
            <div class="col-md-5">
                <input name="subarea_id" id="subarea_id" value="{{ $subarea }}" class="form-control" readonly>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <div class="col-md-4 margin_top">
                    <label for="">Tipo de trabajo: </label>
                </div>
                <div class="col-md-5">
                    <input type="text" class="form-control" value="{{ $type_work }}" id="type_work" readonly>
                    <input type="hidden" class="form-control" id="type_work_id">
                </div>
            </div>
            
            <div class="col-md-1 margin_top">
                <label for="">Nombres: </label>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" value="{{ $trabajador_nombre_completo }}" id="type_work" readonly>
            </div>
        </div>
    </div><br>

    <div class="form-group">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="x_panel">
                    <div class="x_title row">
                        <div class="col-md-11">
                            <h2> Editar inicio de trabajo </h2>
                        </div>
                    </div>
                    <div class="x_content">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="">N° Orden: </label>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" id="search_order" value="{{ $inicio->orden->ordCodigo }}" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="">Pares totales de OP </label>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" id="pairs" value="{{ $inicio->orden->ordCantidad  }}" class="form-control" readonly>
                                    <input type="hidden" id="order_id" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="">Pares x completar OP</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" id="disponible" value="{{ $pares }}" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <form id="form_edit_inicio" action="{{ route('inicio_trabajo.edit') }}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" value="{{ $inicio->initrabId }}" name="inicio_id">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="">Pares iniciados: </label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="pairs_user" id="pairs_user"  value="{{ $inicio->initrabCantidad }}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="">Observación: </label>
                                    </div>
                                    <div class="col-md-10">
                                        <textarea name="description" id="description"  class="form-control" style="resize: none"> {{ $inicio->initrabObservacion }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary" data-save_start_work><i class="fa fa-diamond"></i> Guardar inicio de Trabajo</button>
                                </div>
                                <div class="col-md-6">
                                    <a class="btn btn-warning pull-right" id="back_start" href="{{ route('inicio_trabajo') }}"><i class="fa fa-backward"></i> Volver</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('bootstrap-time/js/bootstrap-timepicker.js') }}"></script>
    <script src="{{ asset('js/produccion/inicio-trabajo/edit.js') }}"></script>
@endsection