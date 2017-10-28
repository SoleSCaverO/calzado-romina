@extends('layouts.app')
@section('title','Inicio de trabajo')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('inicio_trabajo.create') }}" class="btn btn-success btn-sm"><i class="fa fa-plus-circle"></i> Agregar</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha inicio</th>
                        <th>Nro de orden Producción</th>
                        <th>Trabajador</th>
                        <th>Pares totales</th>
                        <th>Pares iniciados</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                @foreach( $inicios as $inicio )
                    <tr>
                        @php( $date = new \Carbon\Carbon($inicio->initrabFecha) )
                        @php( $date = $date->format('d-m-Y H:i:d') )
                        <td>{{ $date }}</td>
                        <td>{{ $inicio->orden->ordCodigo }}</td>
                        <td>{{ $inicio->trabajador_nombre->traNombre.' '.$inicio->trabajador_nombre->traApellidos}}</td>
                        <td>{{ $inicio->orden->ordCantidad }}</td>
                        <td>{{ $inicio->initrabCantidad }}</td>
                        <td>
                            <a href="{{ route('inicio_trabajo.update',$inicio->initrabId ) }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Editar</a>
                            <button class="btn btn-danger btn-sm"  data-delete_start_work="{{ $inicio->initrabId }}"
                                data-trabajador="{{ $inicio->trabajador_nombre->traNombre }}" data-orden="{{ $inicio->orden->ordCodigo }}">
                                <i class="fa fa-trash"></i> Eliminar
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('modals')
    <div id="modal_inicio_eliminar" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>
                        Eliminar inicio de trabajo
                    </h2>
                </div>
                <form id="form_inicio_eliminar" action="{{route('inicio_trabajo.delete')}}" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label>Seguro que desea eliminar el siguiente inicio de trabajo?</label>
                        </div>
                        <div class="form-group">
                            <label for="">Trabajador:</label>
                            <input type="text" name="trabajador" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Orden:</label>
                            <input type="text" name="orden" class="form-control" readonly>
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
    <script src="{{ asset('js/produccion/inicio-trabajo/index.js') }}"></script>
@endsection
