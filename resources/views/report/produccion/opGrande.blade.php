<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Órdenes de producción chicas</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Theme style -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        table {
            font-size: 12px;
            width: 100%; text-align: left;
            border-collapse: collapse;
            border-color: darkblue !important;
            margin:0 !important;
        }

        th {font-size: 13px;
            font-weight: normal;
            color: black;
            border: 1px solid black;
        }

        td {
            color: black;
            border: 1px solid black;
        }

        tr:hover td {
            background: #d0dafd; color: #339;
            border-color: black;
            page-break-inside:avoid; page-break-after:auto
        }
        .tableRow
        {
            width: 100%;
            display: inline-block;
            padding:0 !important;
            margin:0 !important;
        }
        .margin
        {
            padding:0 !important;
            width: 100%;
            height: 10px !important;
        }
        .valor
        {
            font-size: 14px;
            font-weight: bold;
        }
        .image
        {
            width: 50px;
            height: 60px;
        }
    </style>
    <!-- link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}" -->
</head>
<body>

    @for( $i=0;$i<count($orders);$i++ )
        <div class="tableRow">
            <table>
                <thead>
                    <tr>
                        <th colspan="25" align="center" style="font-size: 20px; font-weight: bold">ORDEN DE PRODUCCIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td rowspan="2" colspan="4" align="center">CLIENTE</td>
                        <td rowspan="2" colspan="4" align="center"  class="valor">{{$orders[$i]->produccion->cliente}}</td>

                        <td rowspan="6" colspan="4" align="center" style="font-size: 30px; font-weight: bold">{{$orders[$i]->ordIdx }} - 1211</td>

                        <td colspan="6" align="center">FECHA DE PROGRAMACIÓN</td>
                        <td colspan="7" align="center"  style="background-color: yellow" class="valor">{{$fechas[$orders[$i]->ordIdx][0]}}</td>
                    </tr>

                    <tr>
                        <td colspan="6" align="center">FECHA DE ENTREGA</td>
                        <td colspan="7" align="center" style="background-color: yellow" class="valor">{{$fechas[$orders[$i]->ordIdx][1]}}</td>
                    </tr>

                    <tr>
                        <td rowspan="2" colspan="4" align="center">N° PEDIDO</td>
                        <td rowspan="2" colspan="4" align="center"  class="valor">{{$orders[$i]->produccion->pedId}}</td>

                        @if( $orders[$i]->genero == 1 )
                            <td colspan="3" style="font-size: 12px; font-weight: bold;" align="center">TALLA</td>
                            @if( count($orders[$i]->detalles)<>0 )
                                @foreach( $orders[$i]->detalles as $detalle )
                                    <td align="center" style="font-weight: bold">{{$detalle->talla->dprodTalTalla}}</td>
                                @endforeach
                            @endif
                            <td colspan="3" style="font-size: 12px; font-weight: bold;" align="center">TOTAL</td>
                        @endif

                        @if( $orders[$i]->genero == 2 )
                            <td colspan="2" style="font-size: 12px; font-weight: bold;" align="center">TALLA</td>
                            @if( count($orders[$i]->detalles)<>0 )
                                @foreach( $orders[$i]->detalles as $detalle )
                                    <td align="center" style="font-weight: bold">{{$detalle->talla->dprodTalTalla}}</td>
                                @endforeach
                            @endif
                            <td colspan="3" style="font-size: 12px; font-weight: bold;" align="center">TOTAL</td>
                        @endif

                        @if( $orders[$i]->genero == 3 )
                            <td style="font-size: 12px; font-weight: bold;" align="center">TALLA</td>
                            @if( count($orders[$i]->detalles)<>0 )
                                @foreach( $orders[$i]->detalles as $detalle )
                                    <td align="center" style="font-weight: bold">{{$detalle->talla->dprodTalTalla}}</td>
                                @endforeach
                            @endif
                            <td style="font-size: 12px; font-weight: bold;" align="center">TOTAL</td>
                        @endif
                    </tr>

                    <tr>
                        @if( $orders[$i]->genero == 1 )
                            <td colspan="3" style="font-size: 14px; font-weight: bold;" align="center">N</td>
                            @if( count($orders[$i]->detalles) <>0 )
                                @foreach( $orders[$i]->detalles as $detalle )
                                    <td align="center" style="font-weight: bold">{{$detalle->talla->dprodTalCantidad}}</td>
                                @endforeach
                            @endif
                            <td colspan="3" style="font-size: 14px; font-weight: bold;" align="center">{{$orders[$i]->ordCantidad}}</td>
                        @endif

                        @if( $orders[$i]->genero == 2 )
                            <td colspan="2" style="font-size: 14px; font-weight: bold;" align="center">D</td>
                            @if( count($orders[$i]->detalles) <>0 )
                                @foreach( $orders[$i]->detalles as $detalle )
                                    <td align="center" style="font-weight: bold">{{$detalle->talla->dprodTalCantidad}}</td>
                                @endforeach
                            @endif
                            <td colspan="3" style="font-size: 14px; font-weight: bold;" align="center">{{$orders[$i]->ordCantidad}}</td>
                        @endif

                        @if( $orders[$i]->genero == 3 )
                            <td style="font-size: 14px; font-weight: bold;" align="center">C</td>
                            @if( count($orders[$i]->detalles) <>0 )
                                @foreach( $orders[$i]->detalles as $detalle )
                                    <td align="center" style="font-weight: bold">{{$detalle->talla->dprodTalCantidad}}</td>
                                @endforeach
                            @endif
                            <td style="font-size: 14px; font-weight: bold;" align="center">{{$orders[$i]->ordCantidad}}</td>
                        @endif
                    </tr>

                    <tr>
                        <td rowspan="2" colspan="4" align="center">MODELO</td>
                        <td rowspan="2" colspan="4" align="center" class="valor">{{$orders[$i]->modelo}}</td>
                        @if( count($orders[$i]->imagenes) > 1 )
                            <td rowspan="4" colspan="6" align="center">
                                <img src="{{asset('images/'.$orders[$i]->imagenes[0]->imgDescripcion)}}" alt="" class="image">
                            </td>
                            <td rowspan="4" colspan="7" align="center">
                                <img src="{{asset('images/'.$orders[$i]->imagenes[1]->imgDescripcion)}}" alt="" class="image">
                            </td>
                        @elseif( count($orders[$i]->imagenes) > 0)
                            <td rowspan="4" colspan="6" align="center">
                                <img src="{{asset('images/'.$orders[$i]->imagenes[0]->imgDescripcion)}}" alt="" class="image">
                            </td>
                            <td rowspan="4" colspan="7" align="center">
                                <img src="{{asset('images/'.$orders[$i]->imagenes[0]->imgDescripcion)}}" alt="" class="image">
                            </td>
                        @else
                            <td rowspan="4" colspan="6" align="center"></td>
                            <td rowspan="4" colspan="7" align="center"></td>
                        @endif
                    </tr>

                    <tr>
                    </tr>

                    <tr>
                        <td rowspan="2" colspan="4" align="center">HORMA</td>
                        <td rowspan="2" colspan="4" align="center" class="valor">{{ $dproduccion[$orders[$i]->ordIdx]->dprodHorma }}</td>
                        <td rowspan="2" colspan="4" align="center" style="font-size: 16px" class="valor">{{ ($i+1.).'/'.count($orders)}}</td>
                    </tr>
                    <tr></tr>
                </tbody>
            </table>
        </div>
        <div class="margin"></div>
    @endfor
    <!-- script src="{{asset('jquery-3.1.1.min.js')}}"></script -->
    <!-- script src="{{asset('js/bootstrap.min.js')}}"></script -->
</body>
</html>
