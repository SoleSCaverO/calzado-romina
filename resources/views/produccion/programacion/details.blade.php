@extends('layouts.app')
@section('title','Detalle del pedido')
@section('styles')
    <style>
        .select-margin
        {
            margin-top: 6px;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/programacion.css') }}">
@endsection
@section('content')
    <div class="row">
        <h2>DETALLE DEL PEDIDO</h2><br>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="col-md-4">
                        <label for="">Número de pedido: </label>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" value="{{ $produccion->pedId }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="col-md-4">
                        <label for="">Fecha de registro: </label>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" value="{{ $produccion->fecha_registro }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-4">
                        <label for="">Fecha de entrega: </label>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" value="{{ $produccion->fecha_entrega }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="col-md-4">
                        <label for="">Nombre de cliente: </label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" value="{{ $produccion->cliente }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-4">
                        <label for="">Pares totales del pedido </label>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" value="{{ $produccion->cantidad_pares }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <ul class="nav nav-tabs">
                @php( $first = 1)
                @foreach( $produccion->detalles_produccion as $detalle )
                    @if( $first == 1 )
                        <li class="active"><a data-toggle="tab" data-tabber="{{ $detalle->dprodId }}"  href="{{ '#nav_' .$detalle->dprodId }}">{{ $detalle->modelo.' - '.$detalle->color }}</a></li>
                        @php( $first = 0)
                    @else
                        <li><a data-toggle="tab"  data-tabber="{{ $detalle->dprodId }}" href="{{ '#nav_' .$detalle->dprodId }}">{{ $detalle->modelo.' - '.$detalle->color }}</a></li>
                    @endif
                @endforeach
            </ul>

            <div class="tab-content">
                <br>
                @php( $first = 1)
                @foreach( $produccion->detalles_produccion as $detalle )
                    @include('produccion.programacion.partial-tab')
                    @php( $first = 0)
                @endforeach
            </div>
        </div>
    </div>

    <input type="hidden" id="url_order_create" value="{{ route('programacion.order_create') }}">
    <input type="hidden" id="_token" value="{{ csrf_token() }}">
    <input type="hidden" id="url_material_create" value="{{ route('programacion.create_material') }}">
    <input type="hidden" id="url_material_delete" value="{{ route('programacion.delete_material') }}">

    <input type="hidden" id="url_programacion_details" value="{{ route('programacion.details','') }}">
    <input type="hidden" id="url_delete_order" value="{{ route('programacion.delete_order','') }}">
    <div class="text-center">
        <a  href="{{ route('programacion') }}" class="btn btn-warning"><i class="fa fa-backward"></i> Volver</a>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/produccion/programacion/details.js?v=1') }}"></script>
@endsection
