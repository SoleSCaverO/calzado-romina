@extends('layouts.app')
@section('title','Programación')

@section('styles')
    <!-- Bootstrap Select -->
    <style>
        .select-margin
        {
            margin-top: 6px;
        }
        .custom-box
        {
            background: #f6f6f6;;
            border-color: #bbb;
            padding-top: 10px;
        }

    </style>
    <link rel="stylesheet" href="{{ asset('bootstrap-select/css/bootstrap-select.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <form id="form_programacion_listar" action="{{ route('programacion.list',['','','']) }}" method="GET">
            <div class="row form-group">
                <div class="col-md-4">
                    <div class="col-md-2 select-margin">
                        <label for="">Cliente </label>
                    </div>
                    <div class="col-md-10">
                        <select name="cliente_id" id="cliente_id" title="< Seleccione >" class="selectpicker" data-live-search="true" data-size="10" data-width="100%">
                            @foreach( $clientes as $cliente)
                                <option value="{{ $cliente->cliId }}">{{ $cliente->cliNombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="col-md-5">
                        <div class="col-md-4 select-margin">
                            <label for="">N° Pedido</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="pedido_id" id="pedido_id"  class="form-control">
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="col-md-4 select-margin">
                            <label for="">N° Orden</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="orden_id" id="orden_id"  class="form-control">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-success" data-create><i class="fa fa-plus-circle"></i> Agregar</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>
                        Programaciones
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-hover"  id="dynamic_table_programaciones">
                            <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Fecha OP</th>
                                <th>Fecha entrega</th>
                                <th>Cant. pares</th>
                                <th>General</th>
                                <th>O.Grande</th>
                                <th>O.Chica</th>
                            </tr>
                            </thead>
                            <tbody id="table_programaciones">
                                @foreach( $producciones as $programacion )
                                    <tr data-programacion_id="{{ $programacion->prodId }}" style="cursor: pointer">
                                        <td>{{ $programacion->cliente }}</td>
                                        <td>{{ $programacion->proFecharegistro }}</td>
                                        <td>{{ $programacion->proFechaEntrega }}</td>
                                        <td>{{ $programacion->cantidad_pares }}</td>
                                        <td>
                                            <a href="{{ route('reporte.op_general') }}" class="btn btn-primary btn-xs">
                                                <i class="fa fa-search"></i> Ver
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('reporte.op_grande',$programacion->prodId) }}" class="btn btn-success btn-xs">
                                                <i class="fa fa-search"></i> Ver
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('reporte.op_chica',$programacion->prodId) }}" class="btn btn-info btn-xs">
                                                <i class="fa fa-search"></i> Ver
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="url_programacion_detallles" value="{{ route('programacion.details','') }}">
    <input type="hidden" id="today" value="{{ $today }}">
@endsection

@section('modals')
    <div id="modal_programacion_create" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>
                        Registro de Orden de Pedido
                    </h2>
                </div>
                <form id="form_programacion_create" action="{{route('programacion.create')}}" method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Fecha de registro: </label>
                                </div>
                                <div class="col-md-3">
                                    <input type="date" name="fecha_registro" id="fecha_registro"  value="{{ $today }}" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Fecha de entrega: </label>
                                </div>
                                <div class="col-md-3">
                                    <input type="date" name="fecha_entrega" id="fecha_entrega"  class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Cliente: </label>
                                </div>
                                <div class="col-md-4">
                                    <select name="cliente_id" id="cliente_create_id" title="< Seleccione >" class="selectpicker" data-live-search="true" data-size="10" data-width="100%">
                                        @foreach( $clientes as $cliente)
                                            <option value="{{ $cliente->cliId }}">{{ $cliente->cliNombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group custom-box">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="col-md-3">
                                        <label for="" class="select-margin">Modelo: </label>
                                    </div>
                                    <div class="col-md-9">
                                        <select name="modelo_id" id="modelo_id" title="< Seleccione >" class="selectpicker" data-live-search="true" data-size="10" data-width="100%">
                                            @foreach( $modelos as $modelo)
                                                <option value="{{ $modelo->modId }}">{{ $modelo->modDescripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="col-md-2">
                                        <label for="" class="select-margin">Color: </label>
                                    </div>
                                    <div class="col-md-10">
                                        <select name="color_id" id="color_id" title="< Seleccione >" class="selectpicker" data-live-search="true" data-size="10" data-width="100%">
                                            @foreach( $colores as $color )
                                                <option value="{{ $color->mulId }}">{{ $color->mulDescripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="col-md-2">
                                        <label for="" class="select-margin">Horma: </label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="horma" id="horma" class="form-control">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="" class="select-margin">Pares: </label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" min="1" name="cantidad_pares" id="cantidad_pares" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-info" data-add> <i class="fa fa-plus-circle"></i> Agregar</button>
                            </div>
                            <div class="col-md-2 col-md-offset-2">
                                <label for="" class="pull-right">Pares totales: </label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" id="pares_totales" class="form-control pull-right" value="0" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 table-responsive" >
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Modelo</th>
                                            <th>Color</th>
                                            <th>Horma</th>
                                            <th>Cant.pares</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table_modal_programaciones">

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-close_modal><i class="fa fa-close"></i> Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-diamond" aria-hidden="true"></i> Generar orden de pedido</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Bootstrap Select -->
    <script src="{{ asset('bootstrap-select/js/bootstrap-select.min.js') }}" ></script>
    <script src="{{ asset('js/produccion/programacion/index.js?v=1') }}"></script>
@endsection
