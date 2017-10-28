@extends('layouts.app')
@section('title','Término de trabajo')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('termino_trabajo.create') }}" class="btn btn-success btn-sm"><i class="fa fa-plus-circle"></i> Agregar</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha Término</th>
                        <th>Nro de orden</th>
                        <th>Código de trabajador</th>
                        <th>Trabajador</th>
                        <th>Pares Inicio</th>
                        <th>Pares terminados</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                @foreach( $fines as $fin )
                    <tr>
                        @php( $date = new \Carbon\Carbon($fin->fintrabFecha) )
                        @php( $date = $date->format('d-m-Y H:i:d') )
                        <td>{{ $date }}</td>
                        <td>{{ $fin->orden($fin->ordenIdy)->ordCodigo }}</td>
                        <td>{{ $fin->trabajador($fin->trabIdy,$fin->orden($fin->ordenIdy)->ordIdx) }}</td>
                        <td>{{ $fin->trabajador_nombre($fin->trabIdy)->traNombre.' '.$fin->trabajador_nombre($fin->trabIdy)->traApellidos }}</td>
                        <td>{{ $fin->inicio->initrabCantidad }}</td>
                        <td>{{ $fin->fintrabCantidad }}</td>
                        <td>
                            <a href="{{ route('termino_trabajo.update',$fin->fintrabId ) }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Editar</a>
                            <button class="btn btn-danger btn-sm"  data-delete_end_work="{{ $fin->fintrabId }}"
                                data-trabajador="{{ $fin->trabajador_nombre($fin->trabIdy)->traNombre }}" data-orden="{{ $fin->orden($fin->ordenIdy)->ordCodigo }}">
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
    <div id="modal_fin_eliminar" class="modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>
                        Eliminar término de trabajo
                    </h2>
                </div>
                <form id="form_fin_eliminar" action="{{route('termino_trabajo.delete')}}" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label>Seguro que desea eliminar el siguiente término de trabajo?</label>
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
    <script src="{{ asset('js/produccion/termino-trabajo/index.js') }}"></script>
@endsection
