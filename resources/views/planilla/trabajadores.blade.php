@extends('layouts.app')
@section('title','Planillas')

@section('styles')
    <style>
        .margin-input{
            margin-top: 6px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-2 margin-input">Número de Planilla:</div>
        <div class="col-md-1">
            <input type="text" id="planilla_id" value="{{ $planilla->plaId }}" class="form-control" readonly>
        </div>
        <div class="col-md-2 col-md-offset-3 pull-right">
            <h4>{{ $subarea_menor_nombre }}</h4>
        </div>
    </div>
    <div class="row"><br>
        <div class="col-md-2 margin-input">Fecha de inicio:</div>
        <div class="col-md-2">
            @php( $date_start = new \Carbon\Carbon($planilla->plaFechaInicio) )
            @php( $date_start = $date_start->format('d-m-Y H:i:d') )
            <input type="text" value="{{ $date_start }}" class="form-control" readonly>
        </div>
        <div class="col-md-1  col-md-offset-1 margin-input">Fecha fin:</div>
        <div class="col-md-2">
            @php( $date_end = new \Carbon\Carbon($planilla->plaFechaFin) )
            @php( $date_end = $date_end->format('d-m-Y H:i:d') )
            <input type="text" value="{{ $date_end }}" class="form-control" readonly>
        </div>
    </div>

    <div class="row"><br>
        <div class="col-md-12 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Personal</th>
                    </tr>
                </thead>
                <tbody style="cursor: pointer" id="trabajadores">
                @foreach( $trabajadores as $item )
                    <tr data-trow="{{ $item->traId }}">
                        <td>{{ $item->traId }}</td>
                        <td>{{ $item->traNombre }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col md-12 text-center">
            <a href="{{ route('planillas.subareas.menores',$planilla->plaId) }}" class="btn btn-warning"> <i class="fa fa-backward"></i> Volver</a>
        </div>
    </div>
    <input type="hidden" id="subarea_menor_id" value="{{ $subarea_menor_id }}">
    <input type="hidden" id="url_pago" value="{{ route('planillas.pago',['','','']) }}">
@endsection

@section('scripts')
    <script type="text/javascript">
        $('body').on('click','[data-trow]',redirect_pago);

        function redirect_pago(){
            var $url_pago = $('#url_pago').val();
            var $planilla_id = $('#planilla_id').val();
            var $subarea_menor_id = $('#subarea_menor_id').val();
            var $trabajador_id = $(this).data('trow');

            location.href = $url_pago+'/'+$planilla_id+'/'+$subarea_menor_id+'/'+$trabajador_id;
        }
    </script>
@endsection