@extends('layouts.app')
@section('title','Planillas')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ORDEN</th>
                        <th>DESCRIPCIÓN</th>
                        <th>CODIGO</th>
                        <th>PARES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $inicios as $inicio )
                    <tr>
                        <td>{{ $inicio->orden->ordCodigo }}</td>
                        <td>{{ $inicio->descripcion($subarea_menor_id,$inicio->orden->ordModelod,1) }}</td>
                        <td>{{ $inicio->orden->modelo  }}</td>
                        <td>{{ $inicio->initrabCantidad }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3">TOTAL</td>
                        <td>{{ $total }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>DESCRIPCIÓN</th>
                    <th>NOMBRE</th>
                    <th>PARES</th>
                    <th>PRECIO/DOC</th>
                    <th>TOTAL</th>
                </tr>
                </thead>
                <tbody>
                    @foreach( $data as $item )
                        <tr>
                            <td>{{ $item['description'] }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['pairs'] }}</td>
                            <td>{{ $item['price'] }}</td>
                            <td>{{ $item['total'] }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td>TOTAL</td>
                        <td>{{ $total }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="3">PAGO A TRABAJADOR</td>
                        <td>{{ $payment }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col md-12 text-center">
            <a href="{{ route('planillas.trabajadores',[$planilla_id,$subarea_menor_id]) }}" class="btn btn-warning"> <i class="fa fa-backward"></i> Volver</a>
        </div>
    </div>
@endsection