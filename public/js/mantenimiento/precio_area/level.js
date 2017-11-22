$(document).on('ready',main);

var $modal_nivel;
var $modal_precio_area;
var $modal_number=0;
var $modal_closed=0;
var $nivel;
var $piezas_ids = [];
var $piezas_nombres = [];
var $piezas_inicios = [];
var $piezas_fines = [];

function main() {
    $modal_nivel = $('#modal_nivel');
    $modal_precio_area = $('#modal_precio_area');
    var $description_id = $('#description_id').val();
    piezas($description_id);
    $body = $('body');
    $body.on('click','[data-nivel_crear]',modal_nivel_crear);
    $body.on('click','[data-nivel_editar]',modal_nivel_editar);
    $body.on('click','[data-nivel_eliminar]',modal_nivel_eliminar);
    $body.on('click','[data-cancel]',close_modal_data);
    $body.on('click','[data-nivel_id]',data);
    $body.on('change','#precio_area_tipo_precio',precio_area_tipo_precio);
    $body.on('change','#pieza_id',pieza_id);
    $body.on('click','[data-precio_area_crear]',precio_area_crear);
    $body.on('click','[data-precio_area_editar]',precio_area_editar);
    $body.on('click','[data-precio_area_eliminar]',precio_area_eliminar);
    $body.on('click','#nivel_infinito',nivel_infinito_on_check);

    $('#form_nivel').on('submit',form_nivel);
    $('#form_precio_area').on('submit',form_precio_area);
}

function load_niveles() {
    var $table_niveles = $('#table_niveles');
    var $subarea_menor_id = $('#subarea_menor_id').val();
    var $tipo_calculo_id = $('#tipo_calculo_id').val();
    var $description_id =  $('#description_id').val();
    var $url = $('#url_nivel_listar').val()+'/'+$subarea_menor_id+'/'+$tipo_calculo_id+'/'+$description_id;
    $table_niveles.html('');

    $.ajax({
        url:$url,
        type:'get'
    }).done(function (data) {
        var $to_append = '';
        if( data.success == 'true' ) {

            $.each(data.data,function (k,v) {
                $to_append += '' +
                    '<tr data-nivel_id="'+v.nivId+'">'+
                    '<td>'+ v.nivNombre+ '</td>'+
                    '<td>'+ v.nivCondicion+ '</td>'+
                    '<td>'+ v.nivInicio+ '</td>'+
                    '<td>'+ ((v.nivFin==99999)?'Infinito':v.nivFin)+ '</td>'+
                    '<td>'+ ((v.nivEstado==1)?'Activo':'Inactivo')+'</td>'+
                    '<td>'+
                    '<button class="btn btn-info btn-sm" data-nivel_editar="'+v.nivId+'"'+
                    'data-nivel_nombre="'+ v.nivNombre+ '"'+
                    'data-nivel_descripcion="'+ v.nivDescripcion+ '"'+
                    'data-nivel_condicion="'+ v.nivCondicion+ '"'+
                    'data-nivel_inicio="'+ v.nivInicio+ '"'+
                    'data-nivel_fin="'+ v.nivFin+ '"'+
                    'data-nivel_estado="'+ v.nivEstado+ '">'+
                    '<i class="fa fa-pencil"></i> Editar'+
                    '</button>'+
                    '<button class="btn btn-danger btn-sm" data-nivel_eliminar="'+ v.nivId +'"'+
                    'data-nivel_nombre="'+ v.nivNombre +'">'+
                    '<i class="fa fa-trash-o"></i> Eliminar'+
                    '</button>'+
                    '</td>'+
                    '</tr>';
            });
            $table_niveles.append($to_append);
        }
    });
}

function row_nivel_nombre() {
    var $row_nivel_nombre = $('#row_nivel_nombre');
    var $to_append =
        '<div class="col-md-12 form-group">'+
        '<label>Nombre</label>'+
        '<input type="text" name="nivel_nombre" class="form-control" required>'+
        '</div>';
    $row_nivel_nombre.html('');
    $row_nivel_nombre.append($to_append);
}

function row_nivel_descripcion() {
    var $row_nivel_descripcion = $('#row_nivel_descripcion');
    var $to_append =
        '<div class="col-md-12 form-group">'+
        '<label>Descripción</label>'+
        '<input type="text" name="nivel_descripcion" class="form-control">'+
        '</div>';
    $row_nivel_descripcion.html('');
    $row_nivel_descripcion.append($to_append);
}

function row_nivel_condicion() {
    var $row_nivel_condicion = $('#row_nivel_condicion');
    var $to_append =
        '<div class="col-md-12 form-group">'+
        '<label>Condición</label>'+
        '<input type="text" name="nivel_condicion" class="form-control" required>'+
        '</div>';
    $row_nivel_condicion.html('');
    $row_nivel_condicion.append($to_append);
}

function row_nivel_inicio() {
    var $row_nivel_inicio = $('#row_nivel_inicio');
    var $to_append =
        '<div class="col-md-6 form-group">'+
        '<label>Mayor a</label>'+
        '<input type="number" step="any" min="0" name="nivel_inicio" class="form-control" required>'+
        '</div>';
    $row_nivel_inicio.html('');
    $row_nivel_inicio.append($to_append);
}

function row_nivel_fin() {
    var $row_nivel_fin = $('#row_nivel_fin');
    var $to_append =
        '<div class="col-md-6 form-group">'+
        '<label>Menor igual a</label>'+
        '<input type="number" step="any" min="0" name="nivel_fin" id="nivel_fin" class="form-control">'+
        '</div>';
    $row_nivel_fin.html('');
    $row_nivel_fin.append($to_append);
}

function row_nivel_infinito_unchecked() {
    var $row_nivel_infinito = $('#row_nivel_infinito');
    var $to_append =
        '<div class="col-md-1 col-md-offset-1">'+
        '<input type="checkbox"  name="nivel_infinito" id="nivel_infinito" class="form-control pull-right">'+
        '</div>'+
        '<div class="col-md-4">'+
        '<label class="beside_check">Infinito</label>'+
        '</div>';
    $row_nivel_infinito.html('');
    $row_nivel_infinito.append($to_append);
}

function nivel_infinito_on_check (){
    var boxes = $(":checkbox:checked");
    $nivel = $('#nivel_fin');
    $nivel.prop('readonly',false);

    boxes.each(function () {
        if( this.id == "nivel_infinito" ){
            $nivel.val('');
            $nivel.prop('readonly',true);
        }
    });
}

function row_nivel_infinito_checked() {
    var $row_nivel_infinito = $('#row_nivel_infinito');
    var $to_append =
        '<div class="col-md-1 col-md-offset-1">'+
        '<input type="checkbox"  name="nivel_infinito" id="nivel_infinito" class="form-control" checked>'+
        '</div>'+
        '<div class="col-md-4">'+
        '<label class="beside_check">Infinito</label>'+
        '</div>';
    $row_nivel_infinito.html('');
    $row_nivel_infinito.append($to_append);
}

function row_nivel_estado_unchecked() {
    var $row_nivel_estado = $('#row_nivel_estado');
    var $to_append =
        '<div class="col-md-1">'+
        '<input type="checkbox"  name="nivel_estado" class="form-control">'+
        '</div>'+
        '<div class="col-md-11">'+
        '<label class="beside_check">Estado</label>'+
        '</div>';
    $row_nivel_estado.html('');
    $row_nivel_estado.append($to_append);
}

function row_nivel_estado_checked() {
    var $row_nivel_estado = $('#row_nivel_estado');
    var $to_append =
        '<div class="col-md-1">'+
        '<input type="checkbox"  name="nivel_estado" class="form-control" checked>'+
        '</div>'+
        '<div class="col-md-11">'+
        '<label class="beside_check">Estado</label>'+
        '</div>';
    $row_nivel_estado.html('');
    $row_nivel_estado.append($to_append);
}

function row_nivel_mensaje_eliminar($nivel_nombre) {
    var $mensaje_eliminar = $('#row_nivel_mensaje_eliminar');
    $mensaje_eliminar.html('');
    var $to_append = '<div class="form-group">'+
        '<label>Seguro que desea eliminar el siguiente nivel?</label>'+
        '<div class="row">'+
        '<div class="col-md-12">'+
        '<input type="text" class="form-control" readonly value="'+$nivel_nombre+'">'+
        '</div>'+
        '</div>'+
        '</div>';

    $mensaje_eliminar.append($to_append);
}

function clear_rows($row) {
    var $row_nivel_nombre = $('#row_nivel_nombre');
    var $row_nivel_descripcion = $('#row_nivel_descripcion');
    var $row_nivel_condicion = $('#row_nivel_condicion');
    var $row_nivel_inicio = $('#row_nivel_inicio');
    var $row_nivel_fin = $('#row_nivel_fin');
    var $row_nivel_infinito = $('#row_nivel_infinito');
    var $row_nivel_estado = $('#row_nivel_estado');
    var $row_nivel_mensaje_eliminar = $('#row_nivel_mensaje_eliminar');

    if( $row == 'row_nivel_nombre' )
        $row_nivel_nombre.html('');
    else if( $row == 'row_nivel_descripcion' )
        $row_nivel_descripcion.html('');
    else if( $row == 'row_nivel_condicion' )
        $row_nivel_condicion.html('');
    else if( $row == 'row_nivel_inicio' )
        $row_nivel_inicio.html('');
    else if( $row == 'row_nivel_fin' )
        $row_nivel_fin.html('');
    else if( $row == 'row_nivel_infinito' )
        $row_nivel_infinito.html('');
    else if( $row == 'row_nivel_estado' )
        $row_nivel_estado.html('');
    else if( $row == 'row_nivel_mensaje_eliminar' )
        $row_nivel_mensaje_eliminar.html('');
    else if($row == '' ){
        $row_nivel_nombre.html('');
        $row_nivel_descripcion.html('');
        $row_nivel_condicion.html('');
        $row_nivel_inicio.html('');
        $row_nivel_fin.html('');
        $row_nivel_infinito.html('');
        $row_nivel_estado.html('');
        $row_nivel_mensaje_eliminar.html('');
    }
}

function close_modal() {
    $modal_nivel.modal('hide');
    clear_rows('');
}

function modal_nivel_crear() {
    var $nivel_title = $('#nivel_title');
    $nivel_title.html('Nuevo nivel');
    clear_rows('');
    row_nivel_nombre();
    row_nivel_descripcion();
    row_nivel_condicion();
    row_nivel_inicio();
    row_nivel_fin();
    row_nivel_infinito_unchecked();
    row_nivel_estado_checked();
    $modal_number = 1;

    $modal_nivel.modal('show');
}

function modal_nivel_editar() {
    var $nivel_title       = $('#nivel_title');
    var $nivel_id          = $(this).data('nivel_editar');
    var $nivel_nombre      = $(this).data('nivel_nombre');
    var $nivel_descripcion = $(this).data('nivel_descripcion');
    var $nivel_condicion   = $(this).data('nivel_condicion');
    var $nivel_inicio      = $(this).data('nivel_inicio');
    var $nivel_fin         = $(this).data('nivel_fin');
    var $nivel_estado      = $(this).data('nivel_estado');

    $nivel_title.html('Editar nivel');
    $modal_number = 2;
    clear_rows('');

    row_nivel_nombre();
    row_nivel_descripcion();
    row_nivel_condicion();
    row_nivel_inicio();
    row_nivel_fin();

    $modal_nivel.find('[name=nivel_id]').val($nivel_id);
    $modal_nivel.find('[name=nivel_nombre]').val($nivel_nombre);
    $modal_nivel.find('[name=nivel_descripcion]').val($nivel_descripcion);
    $modal_nivel.find('[name=nivel_condicion]').val($nivel_condicion);
    $modal_nivel.find('[name=nivel_inicio]').val($nivel_inicio);
    if(  $nivel_fin == 99999 ) {
        row_nivel_infinito_checked();
        $modal_nivel.find('[name=nivel_fin]').attr('readonly','true');
    }
    else {
        $modal_nivel.find('[name=nivel_fin]').val($nivel_fin);
        row_nivel_infinito_unchecked();
    }
    if( $nivel_estado == 1  )
        row_nivel_estado_checked();
    else
        row_nivel_estado_unchecked();

    $modal_nivel.modal('show');
}

function modal_nivel_eliminar() {
    var $nivel_title       = $('#nivel_title');
    var $nivel_id          = $(this).data('nivel_eliminar');
    var $nivel_nombre      = $(this).data('nivel_nombre');
    $nivel_title.html('Eliminar nivel');
    $modal_number = 3;
    clear_rows('');
    row_nivel_mensaje_eliminar($nivel_nombre);
    $modal_nivel.find('[name=nivel_id]').val($nivel_id);
    $modal_nivel.modal('show');
}

function form_nivel() {
    event.preventDefault();
    var $CREAR    = 1;
    var $EDITAR   = 2;
    var $ELIMINAR = 3;
    var $method = 'post';
    var $url;

    if( $modal_number == $CREAR )
        $url = $('#url_nivel_crear').val();
    else if ( $modal_number == $EDITAR )
        $url = $('#url_nivel_editar').val();
    else if( $modal_number == $ELIMINAR )
        $url = $('#url_nivel_eliminar').val();

    $.ajax({
        url: $url,
        method: $method,
        data: new FormData(this),
        dataType: "JSON",
        processData: false,
        contentType: false
    }).done(function (data) {
        if (data.success == 'true') {
            showmessage(data.message, 1);
            close_modal();
            load_niveles();
        } else {
            showmessage(data.message, 0);
        }
    });
}

// Data
function data() {
    select_table_row($(this));
    $nivel = $(this).data('nivel_id');
    var $div_button_data_crear = $('#div_button_data_crear');
    $div_button_data_crear.html('');
    $div_button_data_crear.append('<button class="btn btn-success btn-sm" data-precio_area_crear="'+$nivel+'"> <i class="fa fa-plus-circle"> </i> Nuevo precio</button> ');
    load_data();
}

function load_data() {
    var $subarea_menor_id = $('#subarea_menor_id').val();
    var $tipo_calculo_id = $('#tipo_calculo_id').val();
    var $url = $('#url_precio_area_listar').val()+'/'+$subarea_menor_id+'/'+$tipo_calculo_id+'/'+$nivel;

    $.ajax({
        url:  $url,
        type: 'GET'
    }).done(function (data) {
        var $table_precio_area= $('#table_precio_area');
        $table_precio_area.html('');
        if( data.success == 'true' ){
            var $to_append = '';
            $.each(data.data,function (k,v) {
                var $tipo_precio = '';
                if( v.tipoPrecio == 1 )
                    $tipo_precio = 'Fijo';
                else if( v.tipoPrecio == 2)
                    $tipo_precio = 'Variable';

                var $td_according_tipo_precio =
                    '<td>'+((v.ddatcPrecioDocena!=null)?'S/. '+v.ddatcPrecioDocena:'S/. 0.00')+'</td>'+
                    '<td>'+((v.ddatcPrecioDocena!=null)?'S/. '+(v.ddatcPrecioDocena/12).toFixed(2):'S/. 0.00')+'</td>';

                if( v.tipoPrecio == 1  ){
                    $td_according_tipo_precio =
                        '<td></td>'+
                        '<td>'+((v.ddatcPrecioDocena!=null)?'S/. '+v.ddatcPrecioDocena:'')+'</td>';
                }

                $to_append +=
                    '<tr>'+
                    //'<td>'+((v.ddatcDescripcion != null)?v.ddatcDescripcion:'')+'</td>'+
                    '<td>'+v.pieza+'</td>'+
                    '<td>'+$tipo_precio+'</td>'+
                    $td_according_tipo_precio+
                    '<td>'+((v.ddatcEstado==1)?'Activo':'Inactivo')+'</td>'+
                    '<td>'+
                    '<button class="btn btn-info btn-sm" data-precio_area_editar="'+v.ddatcId+'"'+
                    'data-precio_area_nombre="'+v.ddatcDescripcion+'"'+
                    'data-precio_area_tipo="'+v.tipoPrecio+'"'+
                    'data-precio_area_precio="'+v.ddatcPrecioDocena+'"'+
                    'data-precio_area_pie_id="'+v.pieId+'"'+
                    'data-precio_area_estado="'+v.ddatcEstado+'">'+
                    '<i class="fa fa-pencil"></i> Editar'+
                    '</button>'+
                    '<button class="btn btn-danger btn-sm" data-precio_area_eliminar="'+v.ddatcId+'"'+
                    'data-precio_area_nombre="'+v.ddatcDescripcion+'"'+
                     'data-pieza="'+v.pieza+'">'+
                    '<i class="fa fa-trash-o"></i> Eliminar'+
                    '</button>'+
                    '</td>'+
                    '</tr>';
            });
            $table_precio_area.append($to_append);
        }else
            showmessage(data.message,0);
    });
}

function piezas( $description_id ) {
    var $url = $('#url_precio_area_piezas').val()+'/'+$description_id;
    $.ajax({
        url:$url,
        type:'get'
    }).done(function (data) {
        if( data.success=='true')
        {
            $piezas_ids = [];
            $piezas_nombres = [];
            $piezas_inicios = [];
            $piezas_fines = [];
            $.each(data.data,function (k,v) {
                $piezas_ids.push(v.pieId);
                $piezas_nombres.push(v.pieTipo);
                $piezas_inicios.push(v.pieInicial);
                $piezas_fines.push(v.pieFinal);
            });
        }
    });
}

function close_modal_data() {
    $modal_precio_area.modal('hide');
    clear_rows_data('');
    row_precio_area_tipo_precio();
    row_precio_area_estado_checked();

    $modal_closed = 1;
}

function clear_rows_data( $row ) {
    var $row_precio_area_tipo_precio = $('#row_precio_area_tipo_precio');
    var $row_precio_area_nombre = $('#row_precio_area_nombre');
    var $row_precio_area_tipo = $('#row_precio_area_tipo');
    var $row_precio_area_piezas_inicio = $('#row_precio_area_piezas_inicio');
    var $row_precio_area_piezas_fin = $('#row_precio_area_piezas_fin');
    var $row_precio_area_precio = $('#row_precio_area_precio');
    var $row_precio_area_estado = $('#row_precio_area_estado');
    var $row_precio_area_mensaje_eliminar = $('#row_precio_area_mensaje_eliminar');

    if( $row == 'row_precio_area_tipo_precio')
        $row_precio_area_tipo_precio.html('');
    else if( $row == 'row_precio_area_nombre')
        $row_precio_area_nombre.html('');
    else if( $row == 'row_precio_area_tipo')
        $row_precio_area_tipo.html('');
    else if( $row == 'row_precio_area_piezas_inicio')
        $row_precio_area_piezas_inicio.html('');
    else if( $row == 'row_precio_area_piezas_fin')
        $row_precio_area_piezas_fin.html('');
    else if( $row == 'row_precio_area_precio')
        $row_precio_area_precio.html('');
    else if( $row == 'row_precio_area_estado')
        $row_precio_area_estado.html('');
    else if( $row == 'row_precio_area_mensaje_eliminar' )
        $row_precio_area_mensaje_eliminar.html('');
    else if( $row == '' ) {
        $row_precio_area_nombre.html('');
        $row_precio_area_tipo.html('');
        $row_precio_area_piezas_inicio.html('');
        $row_precio_area_piezas_fin.html('');
        $row_precio_area_precio.html('');
        $row_precio_area_mensaje_eliminar.html('');
    }
}

function row_precio_area_tipo_precio() {
    var $row_precio_area_tipo_precio = $('#row_precio_area_tipo_precio');
    $row_precio_area_tipo_precio.html('');
    var $to_append =
            '<div class="col-md-6 form-group">'+
            '<label>Tipo de precio</label>'+
            '<select name="precio_area_tipo_precio" id="precio_area_tipo_precio" title="< Seleccione >" class="selectpicker" data-live-search="true" data-width="100%">'+
            '<option value="2">Variable</option>'+
            '<option value="1">Fijo</option>' +
            '</select>'+
            '</div>'
        ;
    $row_precio_area_tipo_precio.append($to_append);
}

function row_precio_area_nombre() {
    var $row_precio_area_nombre = $('#row_precio_area_nombre');
    $row_precio_area_nombre.html('');
    var $to_append =
            '<div class="col-md-12 form-group">'+
            '<label>Nombre</label>'+
            '<input type="text" name="precio_area_nombre" class="form-control" required>'+
            '</div>'
        ;
    $row_precio_area_nombre.append($to_append);
}

function row_precio_area_tipo() {
    var $row_precio_area_tipo = $('#row_precio_area_tipo');
    $row_precio_area_tipo.html('');
    var $to_append =
            '<div class="col-md-6 form-group">'+
            '<label>Tipo de piezas</label>'+
            '<select name="pieza_id" id="pieza_id" title="< Seleccione >" class="selectpicker" data-live-search="true" data-width="100%">'+

            '</select>'+
            '</div>'
        ;
    $row_precio_area_tipo.append($to_append);
}

function row_precio_area_piezas_inicio() {
    var $row_precio_area_piezas_inicio = $('#row_precio_area_piezas_inicio');
    $row_precio_area_piezas_inicio.html('');
    var $to_append =
        '<div class="col-md-12 form-group">'+
        '<label>N° Piezas Inicial</label>'+
        '<input type="text" name="precio_area_piezas_inicio" id="precio_area_piezas_inicio"  class="form-control" readonly>'+
        '</div>'
        ;

    $row_precio_area_piezas_inicio.append($to_append);
}

function row_precio_area_piezas_fin() {
    var $row_precio_area_piezas_fin = $('#row_precio_area_piezas_fin');
    $row_precio_area_piezas_fin.html('');
    var $to_append =
            '<div class="col-md-12 form-group">'+
            '<label>N° Piezas Final</label>'+
            '<input type="text" name="precio_area_piezas_fin" id="precio_area_piezas_fin"  class="form-control" readonly>'+
            '</div>'
        ;

    $row_precio_area_piezas_fin.append($to_append);
}

function row_precio_area_precio($tipo) {
    var $row_precio_area_precio = $('#row_precio_area_precio');
    $row_precio_area_precio.html('');
    var $to_append =
            '<div class="col-md-6 form-group">'+
            '<label>Precio x '+$tipo+'</label>'+
            '<input type="number" step="any" name="precio_area_precio" class="form-control" required>'+
            '</div>'
        ;
    $row_precio_area_precio.append($to_append);
}

function row_precio_area_estado_unchecked() {
    var $row_precio_area_estado = $('#row_precio_area_estado');
    $row_precio_area_estado.html('');
    var $to_append = '<div class="col-md-1">'+
        '<input type="checkbox"  name="precio_area_estado" class="form-control">'+
        '</div>'+
        '<div class="col-md-11">'+
        '<label class="beside_check">Estado</label>'+
        '</div>';

    $row_precio_area_estado.append($to_append);
}

function row_precio_area_estado_checked() {
    var $row_precio_area_estado = $('#row_precio_area_estado');
    $row_precio_area_estado.html('');
    var $to_append = '<div class="col-md-1">'+
        '<input type="checkbox"  name="precio_area_estado" class="form-control" checked>'+
        '</div>'+
        '<div class="col-md-11">'+
        '<label class="beside_check">Estado</label>'+
        '</div>';

    $row_precio_area_estado.append($to_append);
}

function row_precio_area_mensaje_eliminar($pa_nombre) {
    var $mensaje_eliminar = $('#row_precio_area_mensaje_eliminar');
    $mensaje_eliminar.html('');
    var $to_append = '<div class="form-group">'+
        '<label>Seguro que desea eliminar el siguiente precio por área?</label>'+
        '<div class="row">'+
        '<div class="col-md-12">'+
        '<input type="text" class="form-control" readonly value="'+$pa_nombre+'">'+
        '</div>'+
        '</div>'+
        '</div>';

    $mensaje_eliminar.append($to_append);
}

function precio_area_tipo_precio() {
    var $precio_area_tipo_precio = $('#precio_area_tipo_precio').val();
    var $PRECIO_FIJO = 1;
    var $PRECIO_VARIABLE = 2;
    var $to_append = '';

    clear_rows_data('');
    if ($precio_area_tipo_precio == $PRECIO_FIJO) {
        //row_precio_area_nombre();
        row_precio_area_precio('par');
    }else if($precio_area_tipo_precio == $PRECIO_VARIABLE){
        row_precio_area_precio('docena');
    }

    if ($precio_area_tipo_precio == $PRECIO_FIJO || $precio_area_tipo_precio == $PRECIO_VARIABLE  ) {
        row_precio_area_tipo();
        var $precio_area_tipo = $('#pieza_id');
        $precio_area_tipo.html('');
        for (var i = 0; i < $piezas_ids.length; i++)
            $to_append += '<option value="' + $piezas_ids[i] + '" >' + $piezas_nombres[i] + '</option>';
        $precio_area_tipo.append($to_append);

        start_selectpicker($precio_area_tipo);
        row_precio_area_piezas_inicio();
        row_precio_area_piezas_fin();
    }
}

function pieza_id() {
    var $precio_area_tipo = $(this).val();
    row_precio_area_piezas_inicio();
    row_precio_area_piezas_fin();
    var $precio_area_piezas_inicio = $('#precio_area_piezas_inicio');
    var $precio_area_piezas_fin = $('#precio_area_piezas_fin');
    for( var i=0; i<$piezas_ids.length; i++ )
        if( $precio_area_tipo  == $piezas_ids[i] ) {
            $precio_area_piezas_inicio.val($piezas_inicios[i]);
            $precio_area_piezas_fin.val($piezas_fines[i]==99999?'Infinito':$piezas_fines[i]);
            if( !$piezas_inicios[i] && !$piezas_fines[i] ) {
                clear_rows_data('row_precio_area_piezas_inicio');
                clear_rows_data('row_precio_area_piezas_fin');
            }
        }
}

function precio_area_crear() {
    var $precio_area_tipo_precio = $('#precio_area_tipo_precio');
    var $precio_area_title = $('#precio_area_title');

    $modal_closed    = 0;
    $precio_area_title.html('Nuevo precio por Niveles - Subáreas menores');
    start_selectpicker($precio_area_tipo_precio);
    $modal_precio_area.find('[name=nivel_id]').val($nivel);
    $number_modal = 4;
    $modal_precio_area.modal('show');
}

function precio_area_editar() {
    var $pa_editar      = $(this).data('precio_area_editar');
    var $pa_nombre      = $(this).data('precio_area_nombre');
    var $pa_tipo        = $(this).data('precio_area_tipo');
    var $pa_precio      = $(this).data('precio_area_precio');
    var $pa_pie_id      = $(this).data('precio_area_pie_id');
    var $pa_estado      = $(this).data('precio_area_estado');
    var $FIJO = 1;
    var $ACTIVO = 1;
    var $precio_area_title = $('#precio_area_title');

    $modal_closed = 0;
    $precio_area_title.html('Editar precio por área');
    clear_rows_data('');
    clear_rows_data('row_precio_area_tipo_precio');
    clear_rows_data('row_precio_area_estado');
    if( $pa_tipo == $FIJO ) {
        row_precio_area_precio('par');
    }else {
        row_precio_area_precio('docena');
    }

    row_precio_area_tipo();
    var $to_append='';
    var $index;
    var $precio_area_tipo = $('#pieza_id');
    $precio_area_tipo.html('');

    for( var i = 0; i< $piezas_ids.length; i++ ) {
        if ($pa_pie_id == $piezas_ids[i]) {
            $to_append += '<option value="' + $piezas_ids[i] + '" selected>' + $piezas_nombres[i] + '</option>';
            $index = i;
        }
        else
            $to_append += '<option value="' + $piezas_ids[i] + '">' + $piezas_nombres[i] + '</option>';
    }

    $precio_area_tipo.append($to_append);
    $precio_area_tipo.selectpicker();
    row_precio_area_piezas_inicio();
    row_precio_area_piezas_fin();

    $modal_precio_area.find('[name=precio_area_piezas_inicio]').val($piezas_inicios[$index]?$piezas_inicios[$index]:'Sin nº piezas inicio');
    $modal_precio_area.find('[name=precio_area_piezas_fin]').val($piezas_fines[$index]==99999?'Infinito':($piezas_fines[$index]?$piezas_fines[$index]:'Sin nº piezas fin'));
    $modal_precio_area.find('[name=precio_area_precio]').val($pa_precio);

    if( $pa_estado == $ACTIVO )
        row_precio_area_estado_checked();
    else
        row_precio_area_estado_unchecked();

    $number_modal = 5;
    $modal_precio_area.find('[name=nivel_id]').val($nivel);
    $modal_precio_area.find('[name=precio_area_id]').val($pa_editar);
    $modal_precio_area.modal('show');
}

function precio_area_eliminar() {
    var $pa_eliminar    = $(this).data('precio_area_eliminar');
    var $pa_nombre      = $(this).data('precio_area_nombre');
    var $pieza      = $(this).data('pieza');
    var $precio_area_title = $('#precio_area_title');
    $pa_nombre = ($pa_nombre.length>0)?$pa_nombre:$pieza;
    $modal_closed = 0;
    $precio_area_title.html('Eliminar precio por área');
    clear_rows_data('');
    clear_rows_data('row_precio_area_tipo_precio');
    clear_rows_data('row_precio_area_estado');
    row_precio_area_mensaje_eliminar($pa_nombre);
    $modal_precio_area.find('[name=precio_area_id]').val($pa_eliminar);
    $number_modal = 6;
    $modal_precio_area.modal('show');
}

function form_precio_area() {
    event.preventDefault();

    if( $modal_closed == 1)
        return;

    var $precio_area_tipo_precio = $('#precio_area_tipo_precio').val();
    var $precio_area_tipo = $('#pieza_id').val();

    if( $precio_area_tipo_precio == null && $number_modal == 4 ) {
        showmessage('Debe seleccionar el tipo de precio.', 0);
        return;
    }

    if( $precio_area_tipo_precio  == 2 || $precio_area_tipo_precio  == 1 ){
        if( $precio_area_tipo == null && $number_modal == 4 ) {
            showmessage('Debe seleccionar el tipo de pieza.', 0);
            return;
        }
    }

    if( $number_modal == 4 )
        $url = $('#url_precio_area_crear').val();
    else if ( $number_modal == 5 )
        $url = $('#url_precio_area_editar').val();
    else if ( $number_modal == 6 )
        $url = $('#url_precio_area_eliminar').val();

    var $method = 'post';
    var $url;

    $.ajax({
            url: $url,
            method: $method,
            data: new FormData(this),
            dataType: "JSON",
            processData: false,
            contentType: false
        }).done(function (data) {
        if (data.success == 'true') {
            load_data();
            showmessage(data.message, 1);
            $modal_closed = 0;
            setTimeout(function(){
                close_modal_data();
            },1000);
        } else {
            showmessage(data.message, 0);
        }
    });
}
