$(document).on('ready',main);

var $modal_programacion_create;

var $modelos = [];
var $colores = [];
var $hormas  = [];
var $pares   = [];
var $claves  = [];
var $requested = 0;
function main() {
    var $cliente_id = $('#cliente_id');
    var $form_programacion_listar    = $('#form_programacion_listar');
    var $form_programacion_create        = $('#form_programacion_create');
    $modal_programacion_create       = $('#modal_programacion_create');
    start_selectpicker($cliente_id);

    $body = $('body');
    $('[data-programacion_id]').on('click',programacion_detalles);
    $('[data-create]').on('click',modal_programacion_create);
    $('[data-add]').on('click',agregar_elemento_tabla);
    $('[data-close_modal]').on('click',close_modal);
    $body.on('click','[data-pickout]',quitar_elemento_tabla);
    //$body.on('click',['data-edit'],modal_programacion_create);
    //$body.on('click',['data-delete'],modal_programacion_create);

    $form_programacion_listar.on('submit',form_programacion_listar );
    $form_programacion_create.on('submit',form_programacion_create );
}

function form_programacion_listar () {
    event.preventDefault();

    var $cliente_id = $('#cliente_id').val();
    var $orden_id = $('#orden_id').val();
    var $pedido_id = $('#pedido_id').val();

    $cliente_id = ( !$cliente_id )? 'UNKNOW' : $cliente_id;
    $orden_id   = ( !$orden_id   )?   'UNKNOW' : $orden_id;
    $pedido_id  = ( !$pedido_id  )?  'UNKNOW' : $pedido_id;

    var $url    = $(this).attr('action')+'/'+$cliente_id+'/'+$orden_id+'/'+$pedido_id;
    var $method = $(this).attr('method');
    var $table_programacion = $('#table_programaciones');

    $.ajax({
        url:$url,
        type:$method
    }).done(function (data) {
        var $to_append = '';
        if( data.success == 'true' ){
            $.each(data.data,function (k,v) {
                $table_programacion.html('');
                $to_append +=
                    '<tr data-programacion_id="'+v.prodId+'">'+
                    '<td>'+v.cliente+'</td>'+
                    '<td>'+v.proFecharegistro+'</td>'+
                    '<td>'+v.proFechaEntrega+'</td>'+
                    '<td>'+v.cantidad_pares+'</td>'+
                    '<td></td>'+
                    '<td></td>'+
                    '<td></td>'+
                    '<td></td>'+
                    '<td></td>'+
                    '</tr>';
            });
            $table_programacion.append($to_append);
            start_selectpicker($('#cliente_id'));
        }else
            showmessage(data.message,0);

    });
}

function programacion_detalles() {
    if( $(event.target).parent()[0] == this ) {
        var $programacion_id = $(this).data('programacion_id');
        var $url_programacion_detallles = $('#url_programacion_detallles').val();
        location.href = $url_programacion_detallles + '/' + $programacion_id;
    }
}

function modal_programacion_create() {
    var $cliente_id = $('#cliente_create_id');
    var $modelo_id= $('#modelo_id');
    var $color_id= $('#color_id');

    start_selectpicker($cliente_id);
    start_selectpicker($modelo_id);
    start_selectpicker($color_id);

    $modal_programacion_create.modal('show');
}

function close_modal() {
    var $cliente_id = $('#cliente_create_id');
    var $modelo_id= $('#modelo_id');
    var $color_id= $('#color_id');
    var $table_modal_programaciones = $('#table_modal_programaciones');
    var $today = $('#today').val();
    var $horma = $('#horma');
    var $cantidad_pares = $('#cantidad_pares');
    var $fecha_registro = $('#fecha_registro');
    var $fecha_entrega  = $('#fecha_entrega');
    start_selectpicker($cliente_id);
    start_selectpicker($modelo_id);
    start_selectpicker($color_id);
    $fecha_registro.val($today);
    $fecha_entrega.val('');
    $table_modal_programaciones.html('');
    $horma.val('');
    $cantidad_pares.val('');
    $modal_programacion_create.modal('hide');
}

function form_programacion_create() {
    event.preventDefault();

    if( $requested == 1){
        return;
    }
    $requested = 1;

    var $table_modal_programaciones = $('#table_modal_programaciones');
    var $fecha_registro = $('#fecha_registro').val();
    var $fecha_entrega  = $('#fecha_entrega').val();
    var $cliente_create_id = $('#cliente_create_id').val();
    var $start = new Date($fecha_registro);
    var $end   = new Date($fecha_entrega);
    if( $fecha_entrega.length == 0 ) {
        showmessage('Indique la fecha de entrega.');
        return;
    }

    if( $cliente_create_id == null ) {
        showmessage('Seleccione un cliente.');
        return;
    }

    if( $start > $end ){
        showmessage('La fecha de registro debe ser menor a la fecha de entrega.');
        return;
    }

    if( ($table_modal_programaciones.children()).length == 0 ) {
        showmessage('Agregue un elemento a la lista.');
        return;
    }

    var $url    = $(this).attr('action');
    var $method = $(this).attr('method');
    var $_token = $('#_token').val();

    var $form_data = new FormData();
    $form_data.append('fecha_registro',$fecha_registro);
    $form_data.append('fecha_entrega',$fecha_entrega);
    $form_data.append('cliente_id',$cliente_create_id);
    $form_data.append('modelos',JSON.stringify($modelos));
    $form_data.append('colores',JSON.stringify($colores));
    $form_data.append('hormas',JSON.stringify($hormas));
    $form_data.append('cantidad_pares',JSON.stringify($pares));

    $.ajax({
        url:$url,
        type:$method,
        data:$form_data,
        dataType: "JSON",
        processData: false,
        contentType: false,
        headers:{
            'X-CSRF-TOKEN':$_token
        }
    }).done(function (data) {
        if( data.success == 'true' ) {
            showmessage('El pedido se guardó correctamente',1);
            var $url_programacion_detallles = $('#url_programacion_detallles').val();
            setTimeout(function () {
                location.href = $url_programacion_detallles+'/'+data.produccion_id;
            },1000);
        }
    });
}

function acumulador_de_pares( $pares ){
    $pares_totales = $('#pares_totales');
    $acumulador_pares = 0;
    for( $i=0; $i<$pares.length; $i++ ){
        $acumulador_pares += parseInt($pares[$i]);
    }
    $pares_totales.val($acumulador_pares);
}

function agregar_elemento_tabla() {
    var $table_modal_programaciones = $('#table_modal_programaciones');
    var $modelo = $('#modelo_id');
    var $color = $('#color_id');
    var $horma = $('#horma');
    var $par = $('#cantidad_pares');

    var $modelo_id = $modelo.val();
    var $color_id = $color.val();
    var $horma_id = $horma.val();
    var $par_id = $par.val();
    var $to_append;
    var $clave;

    if( $modelo_id == null ) {
        showmessage('Seleccione un modelo', 0);
        return;
    }
    if( $color_id  == null ) {
        showmessage('Seleccione un color', 0);
        return;
    }
    if( $par_id == null ) {
        showmessage('Ingrese el número de pares', 0);
        return;
    }
    if( parseInt($par_id)<1 ) {
        showmessage('Ingrese una cantidad mayor a CERO', 0);
        return;
    }

    $horma_id = ( $horma_id == null )? '' : $horma_id;
    $clave = $modelo_id+'k'+$color_id;

    if(  clave_repetida($clave)) {
        showmessage('Ya existe un modelo con ese color en la lista.');
        return;
    }

    if( $par_id == '' ){
        showmessage('Ingrese el número de pares', 0);
        return;
    }

    $modelos.push($modelo_id);
    $colores.push($color_id);
    $hormas.push($horma_id);
    $pares.push($par_id);
    $claves.push($clave);
    acumulador_de_pares($pares);
    
    $to_append =
        '<tr data-clave="'+$clave+'">' +
        '<td>'+$modelo.children(':selected').text()+'</td>' +
        '<td>'+$color.children(':selected').text()+'</td>' +
        '<td>'+$horma_id+'</td>' +
        '<td>'+$par_id+'</td>' +
        '<td> <button type="button" class="btn btn-danger" data-pickout><i class="fa fa-trash"></i> Eliminar</button></td>' +
        '</tr>';

    $table_modal_programaciones.append($to_append);
    $horma.val('');
    $par.val('');

    start_selectpicker($modelo);
    start_selectpicker($color);
}

function clave_repetida( $clave ) {
    for( var i=0;i<$claves.length;i++ )
        if( $claves[i]== $clave )
            return 1;
    return 0;
}

function quitar_elemento_tabla() {
    var $tr    = ($(this)).parent().parent();
    var $clave = ($(this)).parent().parent().data('clave');
    var $posicion = posicion_elemento($clave);
    $claves.splice($posicion,1);
    $modelos.splice($posicion,1);
    $colores.splice($posicion,1);
    $hormas.splice($posicion,1);
    $pares.splice($posicion,1);
    acumulador_de_pares($pares);
    
    $tr.remove();
}

function posicion_elemento( $element ) {
    for (var i = 0; i < $claves.length; i++) {
        if ($claves[i] == $element)
            return i;
    }
}
