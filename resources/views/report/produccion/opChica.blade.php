<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Órdenes de producción chicas</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <style>

        body{
            margin:0;
            padding: 0;
        }
        table {
            font-size: 8px;
            width: 100%; text-align: left;
            border-collapse: separate !important;
        }

        th {
            font-size: 13px;
            font-weight: normal;
            background: #b9c9fe;
            color: #039;
        }

        td {
            background: #e8edff;
            color: #303030;
        }

        .tableRow
        {
            width: 33%;
            display: inline-block;
            padding-bottom: -80px !important;
        }
        .margin
        {
            margin-top:-18px !important;
            padding:0 !important;
            height: 10px !important;
        }
    </style>
    <!-- link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}" -->
</head>
<body>
    @foreach( $ordenes->chunk(3) as $orders)
        @foreach( $orders as $order )
            <div class="tableRow">
                <table>
                    <thead>
                        <tr>
                            <th colspan="12" ><center><b>ORDEN DE PRODUCCIÓN</b></center></th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="1" align="center" style="font-size: 12px">CLIENTE</td>
                        <td colspan="4" align="center" style="font-size: 12px;"><b>{{$order->produccion->cliId}}</b></td>
                        <td colspan="7" rowspan="3" style="font-size: 35px;"><center><b>{{$order->ordIdx}}</b></center></td>
                    </tr>
                    <tr>
                        <td align="center" style="font-size: 12px">PEDIDO</td>
                        <td colspan="4" align="center" style="font-size: 12px;"><b>{{$order->produccion->pedId}}</b></td>
                    </tr>
                    <tr>
                        <td align="center" style="font-size: 12px">MODELO</td>
                        <td colspan="4" align="center" style="font-size: 12px;"><b>{{$order->modelo}}</b></td>
                    </tr>

                    @if( $order->genero == 1 )
                        <tr>
                            <td rowspan="2" align="center" style="font-size: 12px">TALLA</td>
                            @if( count($order->detalles)<>0 )
                                @foreach( $order->detalles as $detalle )
                                    <td align="center" style="font-size: 11px"><b>{{$detalle->talla->dprodTalTalla}}</b></td>
                                @endforeach
                            @endif
                            <td colspan="4"><b>TOTAL</b></td>
                        </tr>
                        <tr>
                            @if( count($order->detalles) <>0 )
                                @foreach( $order->detalles as $detalle )
                                    <td align="center" style="font-size: 12px"><b>{{$detalle->talla->dprodTalCantidad}}</b></td>
                                @endforeach
                            @endif
                            <td colspan="4" style="font-size: 12px;" align="center"><b>{{$order->ordCantidad}}</b></td>
                        </tr>
                    @endif

                    @if( $order->genero == 2 )
                        <tr>
                            <td rowspan="2" align="center">TALLA</td>
                            @if( count($order->detalles)<>0 )
                                @foreach( $order->detalles as $detalle )
                                    <td align="center" style="font-size: 11px">{{$detalle->talla->dprodTalTalla}}</td>
                                @endforeach
                            @endif
                            <td colspan="3"><center><b>TOTAL</b></center></td>
                        </tr>
                        <tr>
                            @if( count($order->detalles) <>0 )
                                @foreach( $order->detalles as $detalle )
                                    <td align="center" style="font-size: 12px"><b>{{$detalle->talla->dprodTalCantidad}}</b></td>
                                @endforeach
                            @endif
                            <td colspan="3" style="font-size: 12px;"><center><b>{{$order->ordCantidad}}</b></center></td>
                        </tr>
                    @endif

                    @if( $order->genero == 3 )
                        <tr>
                            @if( count($order->detalles)<>0 )
                                @foreach( $order->detalles as $detalle )
                                    <td align="center" style="font-size: 11px">{{$detalle->talla->dprodTalTalla}}</td>
                                @endforeach
                            @endif
                            <td align="center" colspan="1"><b>TOTAL</b></td>
                        </tr>
                        <tr>
                            @if( count($order->detalles) <>0 )
                                @foreach( $order->detalles as $detalle )
                                    <td align="center" style="font-size: 12px"><b>{{$detalle->talla->dprodTalCantidad}}</b></td>
                                @endforeach
                            @endif
                            <td align="center" style="font-size: 12px;"><b>{{$order->ordCantidad}}</b></td>
                        </tr>
                    @endif
                    </tbody>
                </table>

                @if( !is_null($forro) )
                    <table>
                    <thead>
                        <tr>
                            <th colspan="2" align="center">{{$forro->subaDescripcion}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i=0;$i<count($magics);$i++  )
                            @if( $magics[$i] == $order->ordIdx  )
                                @foreach( $dprod[$i]->materiales as $material )
                                    @if( $material->subaId == $forro->subaId )
                                        <tr>
                                            <td>{{$material->nombre}}</td>
                                            <td>{{$material->descripcion}}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        @endfor
                    </tbody>
                </table>
                @endif
                @if( !is_null($plantilla) )
                    <table>
                    <thead>
                        <tr>
                            <th colspan="12" align="center">{{$plantilla->subaDescripcion}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td align="center">MODELO</td>
                            <td colspan="10" align="center" style="font-size: 12px;"><b>{{$order->modelo}}</b></td>
                            <input type="hidden" value="{{ $j = 0 }}">
                            <td colspan="1" rowspan="{{3+$filas[$j]}}" align="center" style="font-size: 35px;"><b>{{$order->ordIdx}}</b></td>
                            <input type="hidden" value="{{ $j += 1 }}">
                        </tr>
                        @if( $order->genero == 1 )
                            <tr>
                                @if( count($order->detalles)<>0 )
                                    @foreach( $order->detalles as $detalle )
                                        <td align="center" style="font-size: 11px"><b>{{$detalle->talla->dprodTalTalla}}</b></td>
                                    @endforeach
                                @endif
                                <td colspan="4"><b>TOTAL</b></td>
                            </tr>
                            <tr>
                                @if( count($order->detalles) <>0 )
                                    @foreach( $order->detalles as $detalle )
                                        <td align="center" style="font-size: 11px"><b>{{$detalle->talla->dprodTalCantidad}}</b></td>
                                    @endforeach
                                @endif
                                <td colspan="4" style="font-size: 12px;" align="center"><b>{{$order->ordCantidad}}</b></td>
                            </tr>
                        @endif

                        @if( $order->genero == 2 )
                            <tr>
                                @if( count($order->detalles)<>0 )
                                    @foreach( $order->detalles as $detalle )
                                        <td align="center" style="font-size: 11px">{{$detalle->talla->dprodTalTalla}}</td>
                                    @endforeach
                                @endif
                                <td colspan="2"><center><b>TOTAL</b></center></td>
                            </tr>
                            <tr>
                                @if( count($order->detalles) <>0 )
                                    @foreach( $order->detalles as $detalle )
                                        <td align="center" style="font-size: 11px;"><b>{{$detalle->talla->dprodTalCantidad}}</b></td>
                                    @endforeach
                                @endif
                                <td colspan="3" style="font-size: 12px;"><center><b>{{$order->ordCantidad}}</b></center></td>
                            </tr>
                        @endif

                        @if( $order->genero == 3 )
                            <tr>
                                @if( count($order->detalles)<>0 )
                                    @foreach( $order->detalles as $detalle )
                                        <td align="center" style="font-size: 11px">{{$detalle->talla->dprodTalTalla}}</td>
                                    @endforeach
                                @endif
                                <!-- td align="center" colspan="1"><b>TOTAL</b></td -->
                            </tr>
                            <tr>
                                @if( count($order->detalles) <>0 )
                                    @foreach( $order->detalles as $detalle )
                                        <td align="center" style="font-size: 11px;"><b>{{$detalle->talla->dprodTalCantidad}}</b></td>
                                    @endforeach
                                @endif
                                <!-- td align="center" style="font-size: 12px;"><b>{{$order->ordCantidad}}</b></td -->
                            </tr>
                        @endif

                        @for($i=0;$i<count($magics);$i++  )
                            @if( $magics[$i] == $order->ordIdx  )
                                @foreach( $dprod[$i]->materiales as $material )
                                    @if( $material->subaId == $plantilla->subaId )
                                        <tr>
                                            <td>{{$material->nombre}}</td>
                                            <td>{{$material->descripcion}}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        @endfor
                    </tbody>
                </table>
                @endif
            </div>
        @endforeach
        <div class="margin"></div>
    @endforeach

    <!-- script src="{{asset('jquery-3.1.1.min.js')}}"></script -->
    <!-- script src="{{asset('js/bootstrap.min.js')}}"></script -->
</body>
</html>
