@extends('layouts.app')
@section('title','Áreas')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <label for="">Modelo</label>
            <select name="" id="modelo" class="form-control">
                @foreach( $modelos as $modelo  )
                    <option value="{{ $modelo->modId }}">{{ $modelo->modDescripcion }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="">Descripción</label>
            <select name="" id="descripcion" class="form-control">
                @foreach( $lista_precios as $precio  )
                    <option value="{{ $precio }}">{{ $precio }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="">Cantidad de pares</label>
            <input type="number" min="0" step="1" id="pares" class="form-control">
        </div>
        <div class="col-md-3" style="margin-top: 24px">
            <button class="btn btn-success" id="procesar"><i class="fa fa-gear"></i> Procesar</button>
        </div>
    </div>
    <div class="row">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Área</th>
                    <th>Subarea</th>
                    <th>Subarea menor</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody id="table_prices"></tbody>
        </table>
    </div>

    <input type="hidden" id="url_to_prices" value="{{ route('url-to-prices',['','','']) }}">
@endsection

@section('scripts')
    <script>
        $('#procesar').on('click', function () {
            var $modelo = $('#modelo').val();
            var $descripcion = $('#descripcion').val();
            var $pares = $('#pares').val();
            var $table_prices = $('#table_prices');
            $table_prices.html('');

            if( $pares.length == 0 ){
                showmessage('Ingrese el número de pares');
                return;
            }

            $.ajax({
                url: $('#url_to_prices').val()+'/'+$modelo+'/'+$descripcion+'/'+$pares
            }).done( function (data) {
                var $to_append = '';
                $.each(data.data, function (k,v) {
                    $to_append +=
                        '<tr>' +
                            '<td>'+v.area+'</td>'+
                            '<td>'+v.subarea+'</td>'+
                            '<td>'+v.subarea_menor+'</td>'+
                            '<td>'+v.monto+'</td>'+
                        '</tr>';
                });

                $table_prices.append($to_append);
            })
        });
    </script>
@endsection
