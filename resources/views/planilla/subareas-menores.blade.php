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
                        <th>Área</th>
                        <th>Subárea</th>
                        <th>Subárea menor</th>
                        <th>Total personal</th>
                    </tr>
                </thead>
                <tbody style="cursor: pointer" id="subareas">
                @foreach( $subareas_menores as $item )
                    @php( $trabajadores = count($item->trabajadores) )
                    @if($trabajadores>0 )
                        <tr data-trow="{{ $item->subamId }}">
                            <td>{{ $item->subarea->area->areNombre }}</td>
                            <td>{{ $item->subarea->subaDescripcion }}</td>
                            <td>{{ $item->subamDescripcion }}</td>
                            <td>{{ $trabajadores }}</td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col md-12 text-center">
            <a href="{{ route('planillas') }}" class="btn btn-warning"> <i class="fa fa-backward"></i> Volver</a>
        </div>
    </div>
    <input type="hidden" id="url_trabajadores" value="{{ route('planillas.trabajadores',['','']) }}">
@endsection
@section('scripts')
    <script>
        $('#subareas').on('click','tr',function () {
            $subare_menor_id = $(this).data('trow');
            $url = $('#url_trabajadores').val();
            $planilla_id= $('#planilla_id').val();
            location.href = $url+'/'+$planilla_id+'/'+$subare_menor_id;
        });
    </script>
@endsection
