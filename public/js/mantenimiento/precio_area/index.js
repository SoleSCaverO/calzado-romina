$(document).on('ready',main);

var $subareas_menores_ids = [];
var $tipo_calculos_nombres = [];
var $tipo_calculos_ids = [];
var $tipo_calculos_tipos = [];

var $modal_precio_area;

// Controladores
var $modal_closed   = 0;
var $control_ajax_call = 0;
var $number_modal = 0;

function main() {
    var $pa_areas    = $('#pa_areas');
    var $pa_subareas = $('#pa_subareas');
    var $pa_subareas_menores = $('#pa_subareas_menores');
    var $form_precio_area_datos   = $('#form-precio-area-datos');
    var $form_precio_area    = $('#form-precio-area');
    var $pa_tipo_calculo = $('#pa_tipo_calculo');
    $pa_tipo_calculo.val('');

    start_selectpicker($pa_areas);
    start_selectpicker($pa_subareas);
    start_selectpicker($pa_subareas_menores);

    $modal_precio_area = $('#modal-precio-area');

    // Events
    $pa_areas.on('change',select_areas_change);
    $pa_subareas.on('change',select_subareas_change);
    $pa_subareas_menores.on('change',select_subareas_menores_change);

    $body = $('body');
    $body.on('click','[data-precio_area_crear]',modal_precio_area_crear);
    $body.on('click','[data-pa_editar]',modal_precio_area_editar);
    $body.on('click','[data-pa_eliminar]',modal_precio_area_eliminar);
    $body.on('click','[data-cancel]',close_modal);
    $body.on('change','#precio_area_tipo_precio',precio_area_tipo_precio);
    $body.on('click','#precio_area_condicion',precio_area_condicion);

    $form_precio_area_datos.on('submit',form_precio_area_datos);
    $form_precio_area.on('submit',form_precio_area);
}

function select_areas_change() {
    var  $area_id =  $(this).val();
    var $url = $('#url-subareas').val()+'/'+$area_id ;
    var $pa_subareas =  $('#pa_subareas');
    var $pa_subareas_menores =  $('#pa_subareas_menores');
    var $precio_area_data = $('#precio-area-data');
    var $pa_tipo_calculo = $('#pa_tipo_calculo');
    var $redirect_to_level = $('#redirect_to_level');

    $pa_subareas.html('');
    $pa_subareas_menores.html('');
    $pa_tipo_calculo.val('');
    $redirect_to_level.html('');
    $precio_area_data.addClass('hidden_it');

    $.ajax({
        url:$url ,
        type:'GET'
    }).done( function (data) {
        if(data.success == 'true')
        {
            var $to_append = '';
            $.each(data.data,function (k,v) {
                $to_append += '<option value="'+v.subaId+'">'+v.subaDescripcion+'</option>';
            });
            $pa_subareas.append($to_append);
            $pa_subareas.selectpicker('refresh');
            $pa_subareas.selectpicker('val','');

            $pa_subareas_menores.selectpicker('refresh');
            $pa_subareas_menores.selectpicker('val','');
        }
    });
}

function select_subareas_change() {
    var  $subarea_id =  $(this).val();
    if( $subarea_id == null)
        return;

    var $url = $('#url-subareas-menores').val()+'/'+$subarea_id  ;
    var $pa_subareas_menores =  $('#pa_subareas_menores');
    var $precio_area_data = $('#precio-area-data');
    var $pa_tipo_calculo = $('#pa_tipo_calculo');
    var $redirect_to_level = $('#redirect_to_level');

    $pa_tipo_calculo.val('');
    $pa_subareas_menores.html('');
    $redirect_to_level.html('');
    $precio_area_data.addClass('hidden_it');

    $.ajax({
        url:$url ,
        type:'GET'
    }).done( function (data) {
        if(data.success == 'true')
        {
            $subareas_menores_ids = [];
            $subareas_menores_especiales = [];
            $tipo_calculos_nombres = [];
            $tipo_calculos_ids = [];
            $tipo_calculos_tipos = [];
            var $to_append = '';
            $.each(data.data,function (k,v) {
                $to_append += '<option value="'+v.subamId+'">'+v.subamDescripcion+'</option>';
                $subareas_menores_ids.push(v.subamId);
                $tipo_calculos_nombres.push(v.tipo_calculo_nombre);
                $tipo_calculos_ids.push(v.tipo_calculo_id);
                $tipo_calculos_tipos.push(v.tipo_calculo_tipo);
            });
            $pa_subareas_menores.append($to_append);
            $pa_subareas_menores.selectpicker('refresh');
            $pa_subareas_menores.selectpicker('val','');
        }
    });
}

function select_subareas_menores_change() {
    var $subaream_id = $(this).val();
    var $pa_tipo_calculo = $('#pa_tipo_calculo');
    var $precio_area_data = $('#precio-area-data');
    var $url_precio_area_niveles = $('#url-precio-area-niveles').val();
    var $redirect_to_level = $('#redirect_to_level');

    $pa_tipo_calculo.val('');
    $precio_area_data.addClass('hidden_it');
    if( $subaream_id == null )
        return;

    for( var i=0;i<$subareas_menores_ids.length;i++ ){
        if( $subareas_menores_ids[i] == $subaream_id ) {
            $pa_tipo_calculo.val($tipo_calculos_nombres[i]);

            $redirect_to_level.html('');
            if( $tipo_calculos_ids[i] == 1 ) {
                var $url = $url_precio_area_niveles+'/'+$subaream_id+'/'+$tipo_calculos_ids[i];
                var $to_append = '<a class="btn btn-success btn-sm" href="'+$url+'"> Niveles  <i class="fa fa-spinner"></i></a>';
                $redirect_to_level.append($to_append);
            }
        }
    }
}

// Load data
function form_precio_area_datos() {
    event.preventDefault();

    var $subarea_menor_id = $('#pa_subareas_menores ').val();
    var $tipo_calculo = $('#pa_tipo_calculo').val();
    var $tipo_calculo_id;
    var $tipo_calculo_tipo;

    for( var i=0;i<$subareas_menores_ids.length;i++ ){
        if( $subareas_menores_ids[i] == $subarea_menor_id ) {
            $tipo_calculo_id   = $tipo_calculos_ids[i];
            $tipo_calculo_tipo = $tipo_calculos_tipos[i];
        }
    }

    if( $tipo_calculo.length == 0 ) {
        showmessage('Seleccione una área, luego una subárea y después una subárea menor.',0);
        return;
    }

    precio_area_load_data($subarea_menor_id,$tipo_calculo_id);
}

function precio_area_load_data($subarea_id,$tipo_calculo_id) {
    var $form = $('#form-precio-area-datos');
    var $url = $form.attr('action');

    $.ajax({
        url:  $url+'/'+$subarea_id+'/'+$tipo_calculo_id,
        type: 'GET'
    }).done(function (data) {

        var $precio_area_data = $('#precio-area-data');
        var $div_button_precio_area_crear= $('#div-button-precio-area-crear');
        var $table_precio_area= $('#table-precio-area');
        var $to_append_button;
        var $to_append = '';
        $precio_area_data.removeClass('hidden_it');
        $div_button_precio_area_crear.html('');
        $table_precio_area.html('');

        $to_append_button =
            '<button data-precio_area_crear class="btn btn-success btn-sm">' +
            '<i class="fa fa-plus-square"> </i> Agregar'+
            '</button>';
        $div_button_precio_area_crear.append($to_append_button);

        if( data.success == 'true' ){
            $.each(data.data,function (k,v) {
                var $tipo_precio = '';
                var $es_tipo_fijo = 0;
                var $td_according_tipo_precio =
                    '<td>'+((v.ddatcPrecioDocena!=null)?'S/. '+v.ddatcPrecioDocena:'S/. 0.00')+'</td>'+
                    '<td>'+((v.ddatcPrecioDocena!=null)?'S/. '+(v.ddatcPrecioDocena/12).toFixed(2):'S/. 0.00')+'</td>';

                var $con_condicion = 
                    '<td>'+((v.ddatcMayorCondicion==0?'Menor a ':'Mayor a ')+v.ddatcDatoCondicion)+'</td>'+
                    '<td>'+(v.ddatcPrecioCondicion?'S/. '+v.ddatcPrecioCondicion:'S/. 0.00')+'</td>';

                if( v.tipoPrecio == 1 ) {
                    $tipo_precio = 'Fijo';
                    $es_tipo_fijo = 1;
                }
                else if( v.tipoPrecio == 2)
                    $tipo_precio = 'Variable';

                if( $es_tipo_fijo == 1  ){
                    $td_according_tipo_precio =
                        '<td></td>'+
                        '<td>'+((v.ddatcPrecioDocena!=null)?'S/. '+v.ddatcPrecioDocena:'')+'</td>';
                }

                if( !v.ddatcCondicion  ){
                     $con_condicion =
                    '<td></td>'+
                    '<td></td>';
                }
                
                $to_append +=
                    '<tr>'+
                    '<td>'+((v.ddatcDescripcion != null)?v.ddatcDescripcion:'')+'</td>'+
                    '<td>'+v.ddatcNombre+'</td>'+
                    '<td>'+$tipo_precio+'</td>'+
                    $td_according_tipo_precio +
                    $con_condicion+
                    '<td>'+((v.ddatcEstado==1)?'Activo':'Inactivo')+'</td>'+
                    '<td>'+
                    '<button class="btn btn-info btn-sm" data-pa_editar="'+v.ddatcId+'"'+
                    'data-pa_nombre="'+v.ddatcDescripcion+'"'+
                    'data-pa_descripcion="'+v.ddatcNombre+'"'+
                    'data-pa_tipo="'+v.tipoPrecio+'"'+
                    'data-pa_precio="'+v.ddatcPrecioDocena+'"'+
                    'data-pa_condicion="'+v.ddatcCondicion+'"'+
                    'data-pa_precio_condicion="'+v.ddatcPrecioCondicion+'"'+
                    'data-pa_dato_condicion="'+v.	ddatcDatoCondicion+'"'+
                    'data-pa_mayor_condicion="'+v.	ddatcMayorCondicion+'"'+
                    'data-pa_estado="'+v.ddatcEstado+'">'+
                    '<i class="fa fa-pencil"></i> Editar'+
                    '</button>'+
                    '<button class="btn btn-danger btn-sm" data-pa_eliminar="'+v.ddatcId+'"'+
                    'data-pa_nombre="'+v.ddatcDescripcion+'">'+
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

function clear_div_inputs($div) {
    var $row_precio_area_tipo_precio      = $('#row_precio_area_tipo_precio');
    var $row_precio_area_nombre           = $('#row_precio_area_nombre');
    var $row_precio_area_descripcion      = $('#row_precio_area_descripcion');
    var $row_precio_area_precio           = $('#row_precio_area_precio');
    var $row_precio_area_condicion        = $('#row_precio_area_condicion');
    var $row_precio_area_mayor_condicion  = $('#row_precio_area_mayor_condicion');
    var $row_precio_area_numero_condicion = $('#row_precio_area_numero_condicion');
    var $row_precio_area_precio_condicion = $('#row_precio_area_precio_condicion');
    var $row_precio_area_estado           = $('#row_precio_area_estado');
    var $row_precio_area_mensaje_eliminar = $('#row_precio_area_mensaje_eliminar');

    if($div=='row_precio_area_tipo_precio')
        $row_precio_area_tipo_precio.html('');
    else if ($div=='row_precio_area_nombre')
        $row_precio_area_nombre.html('');
    else if($div=='row_precio_area_descripcion')
        $row_precio_area_descripcion.html('');
    else if($div=='row_precio_area_precio')
        $row_precio_area_precio.html('');
    else if($div=='row_precio_area_condicion')
        $row_precio_area_condicion.html('');
    else if($div=='row_precio_area_mayor_condicion')
        $row_precio_area_mayor_condicion.html('');
    else if($div=='row_precio_area_numero_condicion')
        $row_precio_area_numero_condicion.html('');
    else if($div=='row_precio_area_precio_condicion')
        $row_precio_area_precio_condicion.html('');
    else if($div=='row_precio_area_estado')
        $row_precio_area_estado.html('');
    else
    {
        $row_precio_area_nombre.html('');
        $row_precio_area_descripcion.html('');
        $row_precio_area_precio.html('');
        $row_precio_area_condicion.html('');
        $row_precio_area_mayor_condicion.html('');
        $row_precio_area_numero_condicion.html('');
        $row_precio_area_precio_condicion.html('');
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

function row_precio_area_descripcion() {
    var $row_precio_area_descripcion = $('#row_precio_area_descripcion');
    $row_precio_area_descripcion.html('');
    var $to_append =
        '<div class="col-md-12 form-group">'+
        '<label>Descripción</label>'+
        '<input type="text" name="precio_area_descripcion" class="form-control" required>'+
        '</div>'
        ;
    $row_precio_area_descripcion.append($to_append);
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

function row_precio_area_condicion_unchecked() {
    var $row_precio_area_condicion = $('#row_precio_area_condicion');
    $row_precio_area_condicion.html('');
    var $to_append =
            '<div class="col-md-1">'+
            '<input type="checkbox" name="precio_area_condicion" id="precio_area_condicion" class="form-control">'+
            '</div>'+
            '<div class="col-md-11">'+
            '<label class="beside_check">Condición</label>'+
            '</div>'
        ;
    $row_precio_area_condicion.append($to_append);
}

function row_precio_area_condicion_checked() {
    var $row_precio_area_condicion = $('#row_precio_area_condicion');
    $row_precio_area_condicion.html('');
    var $to_append =
        '<div class="col-md-1">'+
        '<input type="checkbox" name="precio_area_condicion" id="precio_area_condicion" class="form-control" checked>'+
        '</div>'+
        '<div class="col-md-11">'+
        '<label class="beside_check">Condición</label>'+
        '</div>'
        ;
    $row_precio_area_condicion.append($to_append);
}

function row_precio_area_mayor_condicion() {
    var $row_precio_area_mayor_condicion = $('#row_precio_area_mayor_condicion');
    $row_precio_area_mayor_condicion.html('');
    var $to_append =
        '<div class="row form-group" id="row_precio_area_mayor_condicion">'+
        '<div class="col-md-6">'+
        '<div class="col-md-3 col-md-offset-3">'+
        '<input type="radio" name="precio_area_mayor_condicion" value="0" class="form-control">'+
        '</div>'+
        '<div class="col-md-6">'+
        '<label class="beside_check">Menor a</label>'+
        '</div>'+
        '</div>'+
        '<div class="col-md-6">'+
        '<div class="col-md-3">'+
        '<input type="radio" name="precio_area_mayor_condicion" value="1" class="form-control" checked>'+
        '</div>'+
        '<div class="col-md-6">'+
        '<label class="beside_check">Mayor a</label>'+
        '</div>'+
        '</div>'+
        '</div>'
        ;
    $row_precio_area_mayor_condicion.append($to_append);
}

function row_precio_area_menor_condicion() {
    var $row_precio_area_mayor_condicion = $('#row_precio_area_mayor_condicion');
    $row_precio_area_mayor_condicion.html('');
    var $to_append =
            '<div class="row form-group" id="row_precio_area_mayor_condicion">'+
            '<div class="col-md-6">'+
            '<div class="col-md-3 col-md-offset-3">'+
            '<input type="radio" name="precio_area_mayor_condicion" value="0" class="form-control" checked>'+
            '</div>'+
            '<div class="col-md-6">'+
            '<label class="beside_check">Menor a</label>'+
            '</div>'+
            '</div>'+
            '<div class="col-md-6">'+
            '<div class="col-md-3">'+
            '<input type="radio" name="precio_area_mayor_condicion" value="1" class="form-control">'+
            '</div>'+
            '<div class="col-md-6">'+
            '<label class="beside_check">Mayor a</label>'+
            '</div>'+
            '</div>'+
            '</div>'
        ;
    $row_precio_area_mayor_condicion.append($to_append);
}

function row_precio_area_numero_condicion() {
    var $row_precio_area_numero_condicion = $('#row_precio_area_numero_condicion');
    $row_precio_area_numero_condicion.html('');
    var $to_append =
        '<div class="col-md-6 form-group">'+
        '<label>Número condición</label>'+
        '<input type="number" min="0" name="precio_area_numero_condicion"  id="precio_area_numero_condicion" class="form-control" required>'+
        '</div>'
        ;
    $row_precio_area_numero_condicion.append($to_append);
}

function row_precio_area_precio_condicion() {
    var $row_precio_area_precio_condicion = $('#row_precio_area_precio_condicion');
    $row_precio_area_precio_condicion.html('');
    var $to_append =
        '<div class="col-md-6 form-group">'+
        '<label>Precio condición x par</label>'+
        '<input type="number" min="1" step="any" name="precio_area_precio_condicion"  id="precio_area_precio_condicion" class="form-control" required>'+
        '</div>'
        ;
    $row_precio_area_precio_condicion.append($to_append);
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
        '<input type="text" class="form-control" value="'+$pa_nombre+'" readonly>'+
        '</div>'+
        '</div>'+
        '</div>';

    $mensaje_eliminar.append($to_append);
}

function precio_area_tipo_precio() {
    var $precio_area_tipo_precio = $('#precio_area_tipo_precio').val();
    var $precio_fijo = 1;
    var $precio_variable = 2;

    clear_div_inputs();
    if ($precio_area_tipo_precio == $precio_fijo) {
        row_precio_area_nombre();
        row_precio_area_descripcion();
        row_precio_area_precio('par');
    }

    if ( $precio_area_tipo_precio == $precio_variable ) {
        row_precio_area_nombre();
        row_precio_area_descripcion();
        row_precio_area_precio('docena');
        row_precio_area_condicion_unchecked();
    }
}

function precio_area_condicion() {
    var boxes = $(":checkbox:checked");
    clear_div_inputs('row_precio_area_mayor_condicion');
    clear_div_inputs('row_precio_area_numero_condicion');
    clear_div_inputs('row_precio_area_precio_condicion');
    boxes.each(function () {
        if( this.name == "precio_area_condicion" ){
            row_precio_area_menor_condicion();
            row_precio_area_numero_condicion();
            row_precio_area_precio_condicion();
        }
    });
}

function subarea_tipo_calculo_default() {
    // Default values
    var $subarea_menor_id =  $('#subarea_menor_id');
    var $tipo_calculo_id  = $('#tipo_calculo_id');

    var $subarea_menor_id_select = $('#pa_subareas_menores').val();

    $subarea_menor_id.val($subarea_menor_id_select);
    for( var i=0;i<$subareas_menores_ids.length;i++ ){
        if( $subareas_menores_ids[i] == $subarea_menor_id_select )
            $tipo_calculo_id.val($tipo_calculos_ids[i]);
    }
}

function modal_precio_area_crear() {
    var $modal_title = $('#modal_title');
    $modal_title.html('Nuevo precio por subárea menor');
    $control_ajax_call = 1;
    $modal_closed    = 0;
    $number_modal    = 1;

    subarea_tipo_calculo_default();

    var $precio_area_tipo_precio = $('#precio_area_tipo_precio');
    start_selectpicker($precio_area_tipo_precio);

    $modal_precio_area.modal('show');
}

function modal_precio_area_editar() {
    var $modal_title         = $('#modal_title');
    var $precio_area_id      = $('#precio_area_id');
    var $pa_editar           = $(this).data('pa_editar');
    var $pa_tipo             = $(this).data('pa_tipo');
    var $pa_nombre           = $(this).data('pa_nombre');
    var $pa_descripcion      = $(this).data('pa_descripcion');
    var $pa_precio           = $(this).data('pa_precio');
    var $pa_condicion        = $(this).data('pa_condicion');
    var $pa_precio_condicion = $(this).data('pa_precio_condicion');
    var $pa_dato_condicion   =  $(this).data('pa_dato_condicion');
    var $pa_mayor_condicion  = $(this).data('pa_mayor_condicion');
    var $pa_estado           = $(this).data('pa_estado');
    var $precio_fijo = 2;
    var $con_condicion = 1;
    var $mayor    = 1;
    var $activo   = 1;
    $modal_closed = 0;
    $control_ajax_call = 1;
    $number_modal = 2;
    $modal_title.html('Editar precio por área');
    clear_div_inputs('row_precio_area_tipo_precio');
    clear_div_inputs('row_precio_area_estado');
    clear_div_inputs('');
    subarea_tipo_calculo_default();
    row_precio_area_nombre();
    row_precio_area_descripcion();

    if( $pa_tipo == 1 )
        row_precio_area_precio('par');
    else
        row_precio_area_precio('docena');

    $precio_area_id.val($pa_editar);
    $modal_precio_area.find('[name=precio_area_nombre]').val($pa_nombre);
    $modal_precio_area.find('[name=precio_area_descripcion]').val($pa_descripcion);
    $modal_precio_area.find('[name=precio_area_precio]').val($pa_precio);

    if( $pa_tipo == $precio_fijo ){
        row_precio_area_descripcion();
        $modal_precio_area.find('[name=precio_area_descripcion]').val($pa_descripcion);
        if(  $pa_condicion == !$con_condicion )
            row_precio_area_condicion_unchecked();
        else {
            row_precio_area_condicion_checked();
            if( $pa_mayor_condicion == $mayor )
                row_precio_area_mayor_condicion();
            else
                row_precio_area_menor_condicion();
            row_precio_area_numero_condicion();
            row_precio_area_precio_condicion();
            $modal_precio_area.find('[name=precio_area_numero_condicion]').val($pa_dato_condicion);
            $modal_precio_area.find('[name=precio_area_precio_condicion]').val($pa_precio_condicion);
        }
    }

    if( $pa_estado == $activo )
        row_precio_area_estado_checked();
    else
        row_precio_area_estado_unchecked();

    $modal_precio_area.modal('show');
}

function modal_precio_area_eliminar() {
    var $modal_title      = $('#modal_title');
    var $precio_area_id   = $('#precio_area_id');
    var $pa_eliminar      = $(this).data('pa_eliminar');
    var $pa_nombre        = $(this).data('pa_nombre');
    $number_modal         = 3;
    $control_ajax_call    = 1;
    $modal_closed         = 0;
    $modal_title.html('Eliminar precio por área');
    clear_div_inputs('');
    clear_div_inputs('row_precio_area_tipo_precio');
    clear_div_inputs('row_precio_area_estado');
    row_precio_area_mensaje_eliminar($pa_nombre);
    subarea_tipo_calculo_default();
    $precio_area_id.val($pa_eliminar);

    $modal_precio_area.modal('show');
}

function close_modal() {
    $modal_precio_area.modal('hide');

    clear_div_inputs('');
    row_precio_area_tipo_precio();
    row_precio_area_estado_checked();
    $modal_closed = 1;
}

function form_precio_area() {
    event.preventDefault();

    if( $modal_closed == 1)
        return;

    var $precio_area_tipo_precio = $('#precio_area_tipo_precio').val();
    var $method = 'post';
    var $url;

    if( $precio_area_tipo_precio == null && $number_modal == 1 ) {
        showmessage('Debe seleccionar el tipo de precio.', 0);
        return;
    }

    if( $number_modal == 1 )
        $url = $('#url-precio-area-crear').val();
    else if ( $number_modal == 2 )
        $url = $('#url-precio-area-editar').val();
    else
        $url = $('#url-precio-area-eliminar').val();

    if ($control_ajax_call == 1) {
        $control_ajax_call = 0;

        $.ajax({
            url: $url,
            method: $method,
            data: new FormData(this),
            dataType: "JSON",
            processData: false,
            contentType: false
        }).done(function (data) {
            $control_ajax_call = 1;
            if (data.success == 'true') {
                $control_ajax_call = 0;
                console.log("subarea_menor_id-"+data.subarea_menor_id);
                precio_area_load_data(data.subarea_menor_id, data.tipo_calculo_id);
                showmessage(data.message, 1);
                $modal_closed = 0;
                setTimeout(function(){
                    close_modal();
                },1000);
            } else {
                showmessage(data.message, 0);
            }
        });
    }
}
