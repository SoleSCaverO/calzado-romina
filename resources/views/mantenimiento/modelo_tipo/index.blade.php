@extends('layouts.app')
@section('title','Modelo - Tipo')

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
    <link rel="stylesheet" href="{{ asset('bootstrap-select/css/bootstrap-select.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-2 select-margin">
                <label for="" class="pull-right">Modelo: </label>
            </div>
            <div class="col-md-3">
                <select name="modelo_id" id="modelo_id" title="< Seleccione >" class="selectpicker" data-live-search="true" data-width="100%" data-size="10">
                    @foreach( $modelos as $modelo )
                        <option value="{{ $modelo->modId }}">{{ $modelo->modDescripcion }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 select-margin">
                <label for="" class="pull-right">Llenos: </label>
            </div>
            <div class="col-md-1">
                <button class="btn btn-success"><i class="fa fa-square" style="color:#26B99A"></i></button>
            </div>

        </div>
    </div><br>
    <div class="row table-responsive">
        <div class="text-center">
            <h2><b>SUB AREAS</b></h2>
        </div>
        <div class="col-md-12">
            <table class="table table-hover" id="dynamic_table_modelos">
                <thead>
                <tr>
                    <th>Modelo</th>
                    @foreach( $subareas as $subarea )
                        <th>{{ $subarea->subaDescripcion }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody id="table_modelos">
                @foreach( $modelos as $modelo )
                    <tr>
                        <td>{{ $modelo->modDescripcion }}</td>
                        @foreach( $subareas as $subarea )
                            <td>
                                @php( $precios = $subarea->precios($subarea->subaId) )
                                @php( $precio_checked = $subarea->precio_checked($modelo->modId,$subarea->subaId) )
                                @if( count($precios)>0 )
                                    <button class="btn btn-sm btn-{{ count($precio_checked)>0?'success':'default' }}"
                                            data-modelo_id="{{ $modelo->modId }}"
                                            data-modelo_nombre="{{ $modelo->modDescripcion }}"
                                            data-subarea_id="{{ $subarea->subaId }}"
                                            data-subarea_nombre="{{ $subarea->subaDescripcion }}"
                                            data-precio_checked="{{ count($precio_checked)>0?$precio_checked[0]->ddatcDescripcion:'' }}"
                                            data-precio_pieza="{{ count($precio_checked)>0?$precio_checked[0]->moddatosPiezas:'' }}"
                                    >
                                        @if( count($precio_checked)>0 )
                                            <span style="color:black; font-weight: bold">{{ '('.$precio_checked[0]->ddatcNombre.')' }}</span> {{ $precio_checked[0]->ddatcDescripcion.(!is_null($precio_checked[0]->moddatosPiezas)?'- '.$precio_checked[0]->moddatosPiezas.($precio_checked[0]->moddatosPiezas==1?' pieza':' piezas'):'') }}
                                        @else
                                            <span style="color:black; font-weight: bold">{{ '('.$precios[0]->ddatcNombre.')' }}</span> {{ $precios[0]->ddatcDescripcion }}
                                        @endif

                                    </button>
                                    <input type="hidden" id="precios{{$modelo->modId.$subarea->subaId}}" value="{{ json_encode($precios) }}">
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <input type="hidden" id="url_modelos" value="{{ route('modelo_tipo.modelos','') }}">
    <input type="hidden" id="perfilado" value="{{ $perfilado }}">
@endsection

@section('modals')
    <div id="modal_precios" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 id="modal_precios_title">

                    </h2>
                </div>
                <form id="form_precios" action="{{ route('modelo_tipo.create') }}" method="post">
                    <div class="modal-body">
                            {{ csrf_field() }}
                            <input type="hidden" name="modelo_id">
                            <input type="hidden" name="subarea_id">
                            <input type="hidden" name="pivot">
                            <div class="form-group">
                                <div class="row" id="checkboxes_precios">

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row" id="input_piezas">

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
    <script src="{{ asset('bootstrap-select/js/bootstrap-select.min.js') }}" ></script>
    <script src="{{ asset('js/mantenimiento/modelo_tipo/index.js?v=1') }}"></script>
@endsection