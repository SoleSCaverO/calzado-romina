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
                    @foreach( $models as $model )
                        <option value="{{ $model->modId }}">{{ $model->modDescripcion }}</option>
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
            <h2><b>SUB AREAS MENORES</b></h2>
        </div>
        <div class="col-md-12">
            <table class="table table-hover" id="dynamic_table_modelos">
                <thead>
                <tr>
                    <th>Modelo</th>
                    @foreach( $minor_subareas as $minor_subarea )
                        <th>{{ $minor_subarea->subamDescripcion }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody id="table_modelos">
                @foreach( $models as $model )
                    <tr>
                        <td>{{ $model->modDescripcion }}</td>
                        @foreach( $minor_subareas as $minor_subarea )
                            <td>
                                @php( $referential_price = $model->tienePrecioReferencial($model->modId,$minor_subarea->subamId) )
                                @if( $referential_price['has_price'])
                                    <button class="btn btn-sm btn-{{ $referential_price['has_referential_price']?'success':'default' }}"
                                            data-code="{{ $referential_price['id'] }}"
                                            data-price="{{ $referential_price['referential_price'] }}">
                                        {{ $referential_price['price'] }}
                                    </button>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('modals')
    <div id="referentialPriceModal" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Asigar Precio Referencial</h2>
                </div>
                <form id="form_prices" action="{{ route('precio.referencial.store') }}" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="price">Ingresar precio</label>
                            <input type="text" class="form-control" name="price" id="price">
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
    <script src="{{ asset('js/mantenimiento/precio-referencial/index.js?v=1') }}"></script>
@endsection