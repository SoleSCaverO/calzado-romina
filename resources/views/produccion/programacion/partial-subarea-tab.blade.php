<div id="{{ 'nav'.$subarea->subaId }}" class="tab-pane fade in active">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-2">
                <button class="btn btn-success" data-add_material="add_{{$detalle->dprodId}}_{{$subarea->subaId}}"><i class="fa fa-plus-circle"></i> Agregar material</button>
            </div>
            <div class="col-md-2">
                <label for="" class="pull-right">Observación:</label>
            </div>

            <div class="col-md-8">
                @php($material_desc = $detalle->material_desc($detalle->dprodId,$subarea->subaId))
                <input type="text" name="description" class="form-control"
                       value="{{ $material_desc }}"
                       id="descripcion_{{$detalle->dprodId}}_{{$subarea->subaId}}"
                >
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="materiales_{{$detalle->dprodId}}_{{$subarea->subaId}}">
                    @php($materials = $detalle->materiales($detalle->dprodId,$subarea->subaId))
                        @if( isset($materials))
                            @foreach( $materials as $material )
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" data-mat_id="{{$material->smatId}}" name="mat_nombre"
                                               value="{{$material->matdNombre}}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="mat_descripcion"
                                               value="{{$material->smatDescripcion}}">
                                    </td>
                                    <td>
                                        <button class="btn btn-danger btn-sm" data-delete_material="{{$detalle->dprodId}}_{{$material->smatId}}" ><i class="fa fa-trash"></i> Eliminar</button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">
                <button class="btn btn-primary pull-right" data-save_material="save_{{$detalle->dprodId}}_{{$subarea->subaId}}"><i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>
