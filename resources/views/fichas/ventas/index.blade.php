@extends('layouts.app')
@section('title','Ficha Técnica de Diseño')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('ficha.ventas.create') }}" class="btn btn-success btn-sm"><i class="fa fa-plus-circle"></i> Agregar</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Ficha</th>
                        <th>Cliente</th>
                        <th>Modelo</th>
                        <th>Color</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                @foreach( $fichas as $ficha)
                    <tr>
                        <td>{{ $ficha->id }}</td>
                        <td>{{ $ficha->nombre_cliente }}</td>
                        <td>{{ $ficha->nombre_modelo }}</td>
                        <td>{{ $ficha->color }}</td>
                        <td>
                            <a href="{{ route('ficha.tecnica.show',$ficha->id ) }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Ver</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection