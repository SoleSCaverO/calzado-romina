@extends('layouts.app')
@section('title','Piezas')
@section('content')
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    TIPOS DE PIEZAS PARA CALCULO X NIVELES
                </h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-md-12">
                    <button class="btn btn-primary btn-sm" data-pieza_crear>
                        <i class="fa fa-plus-square"></i> Nueva pieza
                    </button>
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
                    @foreach( $piezas as $pieza )
                        <tr>
                            <td>{{ $pieza->pieTipo }}</td>
                            <td>{{ $pieza->pieMultiplo }}</td>
                            <td>{{ !is_null($pieza->pieInicial)?$pieza->pieInicial:'' }}</td>
                            <td>{{ !is_null($pieza->pieFinal)?($pieza->pieFinal==99999?'Infinito':$pieza->pieFinal):'' }}</td>
                            <td>{{ ($pieza->pieEstado==1)?'Activa':'Inactiva' }}</td>
                            <td>
                                <button class="btn btn-info btn-sm" data-pieza_editar="{{ $pieza ->pieId }}"
                                        data-pieza_tipo="{{ $pieza->pieTipo }}"
                                        data-pieza_multiplo="{{ $pieza->pieMultiplo }}"
                                        data-pieza_flag="{{ $pieza->pieFlag }}"
                                        data-pieza_inicio="{{ $pieza->pieInicial }}"
                                        data-pieza_fin="{{ $pieza->pieFinal }}"
                                        data-pieza_estado="{{ $pieza->pieEstado }}">
                                        <i class="fa fa-pencil"></i> Editar
                                </button>
                                <button class="btn btn-danger btn-sm" data-pieza_eliminar="{{ $pieza ->pieId }}"
                                        data-pieza_tipo="{{ $pieza->pieTipo }}">
                                    <i class="fa fa-trash-o"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>

                <input type="hidden" id="url_pieza_listar" value="{{ route('piezas.list') }}">
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
                        <input type="hidden" name="pieza_id" id="pieza_id" data-id>

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
