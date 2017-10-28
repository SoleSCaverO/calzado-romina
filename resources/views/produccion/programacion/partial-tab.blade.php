<div id="{{ 'nav_'.$detalle->dprodId }}" class="tab-pane fade {{ $first==1?'in active':'' }}">
    <div class="row">
        <div class="col-md-4">
            <div class="col-md-2">
                <label for="" class="select-margin">Color: </label>
            </div>
            <div class="col-md-10">
                <input type="text" class="form-control" value="{{ $detalle->color }}" readonly>
            </div>
        </div>
        <div class="col-md-4">
            <div class="col-md-2"></div>
            <div class="col-md-2">
                <label for="" class="select-margin">Horma: </label>
            </div>
            <div class="col-md-7">
                <input type="text" class="form-control" value="{{ $detalle->dprodHorma }}" readonly>
            </div>
        </div>
        <div class="col-md-4">
            <div class="col-md-5">
                <label for="" class="select-margin">Cantidad de pares:</label>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="pares_totales" value="{{ $detalle-> dprodCantidad}}" readonly>
            </div>
        </div>
    </div>
    <br><br>
    <div class="x_panel">
        <div class="x_title row">
            <div class="col-md-1">
                <a class="collapse-link"> <i class="fa fa-chevron-down"></i></a>
            </div>
            <div class="col-md-11">
                <h2> Cantidad de Pares </h2>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="x_content">
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-success" data-agregar_orden="{{ $detalle->dprodId }}"> <i class="fa fa-plus-circle"></i> Agregar orden de produccion</button>
                </div>
            </div>
            <div class="row table-responsive">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>N° OP</th>
                            @foreach( $detalle->tallas_modelo($detalle->modId) as $talla )
                                <th>{{ $talla->mulDescripcion }}</th>
                            @endforeach
                            <th>Sumatoria</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody id="table_ordenes{{$detalle->dprodId}}">
                        @php($iterator =1)
                        @foreach( $detalle->ordenes($produccion->prodId,$detalle->modId,$detalle->dprodColor) as $item)
                            <tr>
                                <td>{{ $iterator }}</td>
                                @php($iterator_talla =0)
                                @foreach( $item->detalles as $cantidad )
                                    @php( $tallas_ = $detalle->tallas_modelo($detalle->modId))
                                    <td data-talla_id{{ $tallas_[$iterator_talla]->mulDescripcion }}{{$detalle->dprodId}}>
                                        {{ $cantidad->dordCantidad }}
                                    </td>
                                    @php($iterator_talla++)
                                @endforeach
                                <td style="font-weight: bold">{{ $item->ordCantidad }}</td>
                                <td><button class="btn btn-danger btn-sm" data-delete_order="{{ $item->ordIdx }}"><i class="fa fa-trash"></i> Eliminar</button></td>
                            </tr>
                            @php($iterator++)
                        @endforeach
                        <tr readonly>
                            <td>Sumatoria</td>
                            @foreach( $detalle->tallas_modelo($detalle->modId) as $talla )
                                <td style="font-weight: bold" id="sum_talla_id{{$talla->mulDescripcion}}{{$detalle->dprodId}}">0</td>
                            @endforeach
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10">
                    <div class="col-md-10">
                        <label for="" class="select-margin pull-right">Total</label>
                    </div>
                    <div class="col-md-2">
                        <input type="text" id="total{{$detalle->dprodId}}" value="0" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-md-2">
                    <button id="guardar_ordenes{{$detalle->dprodId}}" class="btn btn-primary pull-right"> <i class="fa fa-save"></i> Guardar Órdenes</button>
                </div>
            </div>
            <input type="hidden" id="number_zeros{{$detalle->dprodId}}" value="{{ count($detalle->tallas_modelo($detalle->modId)) }}">
            <input type="hidden" id="tallas{{$detalle->dprodId}}" value="{{ $detalle->tallas_modelo($detalle->modId) }}">
            <input type="hidden" id="tabber" value="{{ $detalle->dprodId }}">
            <input type="hidden" id="id{{$detalle->dprodId}}" value="{{ $detalle->dprodId }}">
            <input type="hidden" id="modelo{{$detalle->dprodId}}" value="{{ $detalle->modId }}">
            <input type="hidden" id="color{{$detalle->dprodId}}" value="{{ $detalle->dprodColor }}">
        </div>
    </div><br>

    <div class="x_panel">
        <div class="x_title row">
            <div class="col-md-1">
                <a class="collapse-link"> <i class="fa fa-chevron-down"></i></a>
            </div>
            <div class="col-md-11">
                <h2> Materiales por Área </h2>
            </div>
        </div>
        <div class="x_content">
            @foreach( $areas as $area )
                @php( $subareas = $area->subareas->where('subaEstado',1) )
                @if( count($subareas)>0 )
                    <div class="x_panel">
                        <div class="x_title row">
                            {{ $area->areNombre }}
                        </div>
                        <div class="x_content">
                            <div class="row">
                                <ul class="nav nav-tabs naver">
                                    @php( $first = 1)
                                    @foreach( $subareas as $subarea )
                                        @if( $first == 1 )
                                            <li class="active"><a data-toggle="tab" href="{{ '#nav' .$subarea->subaId }}">{{ $subarea->subaDescripcion}}</a></li>
                                            @php( $first = 0)
                                        @else
                                            <li><a data-toggle="tab" href="{{ '#nav' .$subarea->subaId }}">{{ $subarea->subaDescripcion }}</a></li>
                                        @endif
                                    @endforeach
                                </ul>

                                <div class="tab-content">
                                    <br>
                                    @php( $first = 1)
                                    @foreach( $subareas as $subarea )
                                        @if( $first == 1 )
                                            @include('produccion.programacion.partial-subarea-tab')
                                            @php( $first = 0)
                                        @else
                                            <div id="{{ 'nav' .$subarea->subaId }}" class="tab-pane fade">
                                                @include('produccion.programacion.partial-subarea-tab')
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
