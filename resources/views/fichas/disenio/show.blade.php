@extends('layouts.app')
@section('title','Ficha Técnica de Diseño')

@section('styles')
    <style>
        body{
            -webkit-print-color-adjust: exact !important;
        }
        @media print {
            .noprint {display:none;}
        }
        .titulo{
            background-color: rgb(51,11,165) !important;
            color: white !important;
            text-align: center !important;
            vertical-align: middle !important;
            box-shadow: inset 0 0 0 1000px rgb(51,11,165) !important;
        }
        .cliente{
            background-color: rgb(255,255,0) !important;
            box-shadow: inset 0 0 0 1000px rgb(255,255,0) !important;
            vertical-align: middle !important;
            text-align: center !important;
        }
        .nombre-cliente{
            vertical-align: middle !important;
            text-align: center !important;
        }
        .area{
            background-color: rgb(249,111,114);
            box-shadow: inset 0 0 0 1000px rgb(249,111,114) !important;
            text-align: center;
            font-weight:bold;
            vertical-align: middle !important;
        }
        .general{
            background-color: rgb(249,111,114);
            box-shadow: inset 0 0 0 1000px rgb(249,111,114) !important;
            font-weight:bold;
        }
        .middle{
            vertical-align: middle !important;
        }
        table{
            color: black;
        }
        tr th,td {
            padding: 1px !important;
            margin: 0;
            border: 1px black solid !important;
            min-height: 20px;
        }
        .modelo{
            background-color: rgb(249,111,114);
            box-shadow: inset 0 0 0 1000px rgb(249,111,114) !important;
            vertical-align: middle !important;
            font-weight:bold;
        }
        .valor-modelo{
            background-color: rgb(0,255,255);
            box-shadow: inset 0 0 0 1000px rgb(0,255,255) !important;
            text-align: center;
            vertical-align: middle !important;
            font-size: 20px;
            font-weight: bold;
        }
        .piezas{
            background-color: rgb(255,255,153);
            box-shadow: inset 0 0 0 1000px rgb(255,255,153) !important;
            font-weight: bold;
        }
        .material{
            background-color: rgb(196,215,155);
            box-shadow: inset 0 0 0 1000px rgb(196,215,155) !important;
            font-weight: bold;
        }
        .pieza{
            background-color: rgb(146,205,220);
            box-shadow: inset 0 0 0 1000px  rgb(146,205,220) !important;
        }
        .image-container{
            text-align: center !important;
            vertical-align: middle !important;
        }
        .image{
            margin: 0 auto ;
            max-height: 100%;
            height: 150px;
            width: auto;
        }
        .image-arriba{
            height: 100px;
        }
        .square{
            width: 5px;
            height: 5px;
            padding: 4px;
            border: 1px black solid;
        }
        .latex{
            display: block;
        }
        .latex-retacon{
            text-align: center !important;
            vertical-align: middle !important;
        }
        .aprobado{
            font-weight: bold;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th></th>
                        <th colspan="{{ $nroMatCuero }}" class="titulo">FICHA TÉCNICA DE DISEÑO Y MATERIALES BASE</th>
                        <th colspan="2" class="cliente">CLIENTE</th>
                        <th colspan="2" class="nombre-cliente">{{ $ficha->nombre_cliente }}</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- CUERO -->
                    <tr>
                        <td colspan="{{ $nroMatCuero }}" class="area"> CUERO </td>
                        <td class="general">COLECCIÓN</td>
                        <td>{{ $ficha->coleccion }}</td>
                        <td rowspan="4" class="modelo">MODELO</td>
                        <td rowspan="4" colspan="2" class="valor-modelo">{{ $ficha->nombre_modelo }}</td>
                    </tr>
                    <tr>
                        @foreach( $ficha->material($ficha->id,1) as $key => $material )
                            <td class="material">MAT {{ $range[$key] }}</td>
                        @endforeach
                        @for( $i = 0; $i<$nroMatCueroFaltante;$i++ )
                            <td class="material"></td>
                        @endfor
                        <td class="general">GÉNERO</td>
                        <td>{{ $ficha->genero }}</td>
                    </tr>
                    <tr>
                        @foreach( $ficha->material($ficha->id,1) as $key => $material )
                            <td>{{ $material->nombre }}</td>
                        @endforeach
                        @for( $i = 0; $i<$nroMatCueroFaltante;$i++ )
                            <td></td>
                        @endfor
                        <td class="general">MARCA</td>
                        <td>{{ $ficha->marca }}</td>
                    </tr>
                    <tr>
                        @foreach( $ficha->material($ficha->id,1) as $key => $material )
                            <td class="pieza"><b>PZAS: </b>{{ $material->piezas }}</td>
                        @endforeach
                        @for( $i = 0; $i<$nroMatCueroFaltante;$i++ )
                            <td></td>
                        @endfor
                        <td class="general">HORMA</td>
                        <td>{{ $ficha->horma }}</td>
                    </tr>
                    <!-- CUERO -->

                    <!-- FORRO - PLANTILLA -->
                    <tr>
                        <td class="area" colspan="{{ $nroMatForro }}">FORRO</td>
                        <td class="area" colspan="{{ $nroMatPlantilla }}">PLANTILLA</td>
                        <td class="general">MODELISTA</td>
                        <td>{{ $ficha->modelista }}</td>
                        <td class="general">TALLA</td>
                        <td colspan="2">{{ $ficha->talla }}</td>
                    </tr>
                    <tr>
                        @foreach( $ficha->material($ficha->id,2) as $key => $material )
                            <td class="material">MAT {{ $range[$key] }}</td>
                        @endforeach
                        @for( $i = 0; $i<$nroMatForroFaltante;$i++ )
                            <td class="material"></td>
                        @endfor

                        @foreach( $ficha->material($ficha->id,3) as $key => $material )
                            <td class="material">MAT {{ $range[$key] }}</td>
                        @endforeach
                        @for( $i = 0; $i<$nroMatPlantillaFaltante;$i++ )
                            <td class="material"></td>
                        @endfor
                        @for( $i = 0; $i<$extraNroPlantilla;$i++ )
                            <td class="material"></td>
                        @endfor
                        <td class="general">FECHA</td>
                        <td>{{ $ficha->fecha }}</td>
                        <td class="piezas">N° PZAS CUERO</td>
                        <td colspan="2">{{ $ficha->piezas_cuero }}</td>
                    </tr>
                    <tr>
                        @foreach( $ficha->material($ficha->id,2) as $key => $material )
                            <td>{{ $material->nombre }}</td>
                        @endforeach
                        @for( $i = 0; $i<$nroMatForroFaltante;$i++ )
                            <td></td>
                        @endfor

                        @foreach( $ficha->material($ficha->id,3) as $key => $material )
                            <td>{{ $material->nombre }}</td>
                        @endforeach
                        @for( $i = 0; $i<$nroMatPlantillaFaltante;$i++ )
                            <td></td>
                        @endfor
                        @for( $i = 0; $i<$extraNroPlantilla;$i++ )
                            <td class="material"></td>
                        @endfor
                        <td class="general middle" rowspan="2">COLOR</td>
                        <td rowspan="2" class="middle">{{ $ficha->color }}</td>
                        <td class="piezas middle" rowspan="2">N° PZAS FORRO</td>
                        <td colspan="2" rowspan="2" class="middle">{{ $ficha->piezas_forro }}</td>
                    </tr>
                    <tr>
                        @foreach( $ficha->material($ficha->id,2) as $key => $material )
                            <td class="pieza"><b>PZAS: </b>{{ $material->piezas }}</td>
                        @endforeach
                        @for( $i = 0; $i<$nroMatForroFaltante;$i++ )
                            <td></td>
                        @endfor

                        @foreach( $ficha->material($ficha->id,3) as $key => $material )
                            <td class="pieza"><b>PZAS: </b>{{ $material->piezas }}</td>
                        @endforeach
                        @for( $i = 0; $i<$nroMatPlantillaFaltante;$i++ )
                            <td></td>
                        @endfor
                        @for( $i = 0; $i<$extraNroPlantilla;$i++ )
                            <td class="material"></td>
                        @endfor
                    </tr>
                    <!-- FORRO - PLANTILLA -->

                    <!-- PERFILADO - COSIDO VENA -->
                    <tr>
                        <td colspan="3" class="area">PERFILADO</td>
                        <td colspan="2" class="area">COSIDO VENA</td>
                        <td colspan="{{ 5 + $diffCuero }}" rowspan="{{ $nroMatPerfilado +1 }}" class="image-container">
                            <img src="{{ asset('images/fichas/'.$ficha->imagen_derecha) }}" alt="" class="img-responsive image">
                        </td>
                    </tr>
                    @for($i=0;$i<count($columna1);$i++ )
                        <tr>
                            <td colspan="2">{{ $columna1[$i] }}</td>
                            <td>{{ $columna2[$i] }}</td>
                            <td colspan="2" class="{{ $i ==$nroMatCosido? 'area':''}}">{{ $columna3[$i] }}</td>
                        </tr>
                    @endfor
                    <!-- PERFILADO - COSIDO VENA - PEGADO -->

                    <!-- ARMADO - ENCAJADO - HAB PLANTILLAVENA -->
                    <tr>
                        <td colspan="2" class="area">ARMADO</td>
                        <td colspan="3" class="area">HAB. PLANTILLA</td>
                        <td colspan="{{ 5 + $diffCuero }}" rowspan="9" class="image-container">
                            <img src="{{ asset('images/fichas/'.$ficha->imagen_izquierda) }}" alt="" class="img-responsive image">
                        </td>
                    </tr>
                    <tr>
                        <td class="material">FALSA</td>
                        <td>{{ $armado[0]->nombre }}</td>
                        <td class="material">Sello pan de oro</td>
                        <td>{{ $habPlantilla[0]->nombre }}</td>
                        <td rowspan="3" class="latex-retacon">
                            @if( $habPlantilla[3]->nombre =='AMBOS' ||  $habPlantilla[3]->nombre =='LATEX')
                                <label for="square" class="latex">
                                    <span class="pull-left">LATEX &emsp;&nbsp;&nbsp;</span>
                                    <span class="square">X</span>
                                </label>
                            @else
                                <label for="square" class="latex">
                                    <span class="pull-left">LATEX&emsp;&nbsp;&nbsp;</span>
                                    <span class="square">&nbsp;&nbsp;</span>
                                </label>
                            @endif

                            @if( $habPlantilla[3]->nombre =='AMBOS' ||  $habPlantilla[3]->nombre =='RETACON')
                                <label for="square" class="latex">
                                    <span class="pull-left">RETACON</span>
                                    <span class="square">X</span>
                                </label>
                            @else
                                <label for="square" class="latex">
                                    <span class="pull-left">RETACON</span>
                                    <span class="square">&nbsp;&nbsp;</span>
                                </label>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="material">CONTRAFUERTE</td>
                        <td>{{ $armado[1]->nombre }}</td>
                        <td class="material">Sello de Especif.</td>
                        <td>{{ $habPlantilla[1]->nombre }}</td>
                    </tr>
                    <tr>
                        <td class="material">PUNTERA</td>
                        <td>{{ $armado[2]->nombre }}</td>
                        <td class="material">N° TROQUEL</td>
                        <td>{{ $habPlantilla[2]->nombre }}</td>
                    </tr>
                    <tr>
                        <td class="material">TALON</td>
                        <td>{{ $armado[3]->nombre }}</td>
                        <td colspan="3" rowspan="6" class="image-container">
                            <img src="{{ asset('images/fichas/'.$ficha->imagen_arriba) }}" alt="" class="img-responsive image image-arriba">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="area">ENCAJADO</td>
                    </tr>
                    <tr>
                        <td class="material">CAJA</td>
                        <td>{{ $encajado[0]->nombre }}</td>
                    </tr>
                    <tr>
                        <td class="material">PAPEL</td>
                        <td>{{ $encajado[1]->nombre }}</td>
                    </tr>
                    <tr>
                        <td class="material">HANTAN</td>
                        <td>{{ $encajado[2]->nombre }}</td>
                    </tr>
                    <tr>
                        <td class="material">BOLSA</td>
                        <td>{{ $encajado[3]->nombre }}</td>
                        <td colspan="{{ 5 + $diffCuero }}" rowspan="8" class="image-container">
                            <img src="{{ asset('images/fichas/'.$ficha->imagen_atras) }}" alt="" class="img-responsive image">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="area">OBSERVACIONES</td>
                    </tr>
                    <tr>
                        <td colspan="5" rowspan="2">{{ $ficha->observacion }}</td>
                    </tr>
                    <tr></tr>
                    <tr>
                        <td colspan="5" class="aprobado text-center">APROBADO</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="aprobado">MODELAJE</td>
                        <td colspan="3">{{ $ficha->modelaje }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="aprobado">PRODUCCION</td>
                        <td colspan="3">{{ $ficha->produccion }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="aprobado">GERENCIA</td>
                        <td colspan="3">{{ $ficha->gerencia }}</td>
                    </tr>
                    <!-- ARMADO - ENCAJADO - HAB PLANTILLAVENA -->
                </tbody>
            </table>
        </div>
    </div>
    <div class="row text-center noprint">
        <a href="{{ route('ficha.tecnica') }}" class="btn btn-warning"><i class="fa fa-backward"></i> Volver</a>
        <button class="btn btn-info" id="btn_print"><i class="fa fa-print"></i> Imprimir</button>
    </div>
@endsection
@section('scripts')
    <script>
        $('#btn_print').on('click', function () {
           window.print();
        });
    </script>
@endsection