$(document).on('ready', main);

var $modal_tc_crear;
var $modal_tc_editar;
var $modal_tc_eliminar;
var $requested = 0;

function main() {
    // Global
    $modal_tc_crear    = $('#modal-tc-crear');
    $modal_tc_editar   = $('#modal-tc-editar');
    $modal_tc_eliminar = $('#modal-tc-eliminar');
    var $body = $('body');

    // Áreas
    $body.on('click','[data-tc_crear]',modal_tc_crear);
    $body.on('click','[data-tc_editar]',modal_tc_editar);
    $body.on('click','[data-tc_eliminar]',modal_tc_eliminar);

    // FORMS - SUBMIT -tcS
    $('#form-tc-crear').on('submit',form_tc_crear);
    $('#form-tc-editar').on('submit',form_tc_editar);
    $('#form-tc-eliminar').on('submit',form_tc_eliminar);
}

// GLOBAL
function modal_close($modal) {
    $modal.modal('hide');
    $modal.find('[name=tc_nombre]').val('');
    $modal.find('[name=tc_nivel]').checked= false;
    $modal.find('[name=tc_estado]').checked= false;
}

function load_tcs(){
    var $location = location.href;
    var $position = 1;
    if( $location.split('page=').length != 1 )
        $position = parseInt($location.split('page=').pop());

    var $url = $('#url-tipo-calculos-listar').val();
    $.ajax({
        url: $url+'/'+$position,
        type:'get',
        dataType:'Json'
    }).done(function (data) {
        var $tc_table = $('#tc-table');
        $tc_table .html('');
        if(  data.success =='true' ){
            var $to_append = '';
            $.each(data.data,function (k,v) {
                $to_append +=
                    '<tr data-tc_id="'+v.tcalId+'">'+
                    '<td>'+v.tcalDescripcion+'</td>'+
                    '<td>'+((v.tcalTipo==1)?'Normal':'Nivel')+'</td>'+
                    '<td>'+((v.tcalEstado==1)?'Activo':'Inactivo')+'</td>'+
                    '<td>'+
                    '<button class="btn btn-info btn-sm" data-tc_editar="'+v.tcalId+'"'+
                    'data-tc_nombre="'+v.tcalDescripcion+'"'+
                    'data-tc_nivel="'+v.tcalTipo+'"'+
                    'data-tc_estado="'+v.tcalEstado+'">'+
                    '<i class="fa fa-pencil"></i> Editar'+
                    '</button>'+
                    '<button class="btn btn-danger btn-sm" data-tc_eliminar="'+v.tcalId+'"'+
                    'data-tc_nombre="'+v.tcalDescripcion+'">'+
                    '<i class="fa fa-trash-o"></i> Eliminar'+
                    '</button>'+
                    '</td>'+
                    '</tr>';
            });
            $tc_table .append($to_append);
        }
    });
}

function modal_tc_crear() {
    $modal_tc_crear.modal('show');
}

function modal_tc_editar() {
    var $tc_editar = $(this).data('tc_editar');
    var $tc_nombre = $(this).data('tc_nombre');
    var $tc_nivel  = $(this).data('tc_nivel');
    var $tc_estado = $(this).data('tc_estado');

    var $div_check_tc_nivel= $('#div_check_tc_nivel');
    var $div_check_tc_estado= $('#div_check_tc_estado');
    $div_check_tc_nivel.html('');
    $div_check_tc_estado.html('');

    $modal_tc_editar.find('[name=tc_id]').val($tc_editar);
    $modal_tc_editar.find('[name=tc_nombre]').val($tc_nombre);

    if( $tc_nivel == 2)
        $div_check_tc_nivel.append('<input type="checkbox"  name="tc_nivel" class="form-control" checked>');
    else
        $div_check_tc_nivel.append('<input type="checkbox"  name="tc_nivel" class="form-control">');

    if( $tc_estado)
        $div_check_tc_estado.append('<input type="checkbox"  name="tc_estado" class="form-control" checked>');
    else
        $div_check_tc_estado.append('<input type="checkbox"  name="tc_estado" class="form-control">');
    $modal_tc_editar.modal('show');
}

function modal_tc_eliminar() {
    var $tc_eliminar = $(this).data('tc_eliminar');
    var $tc_nombre = $(this).data('tc_nombre');

    $modal_tc_eliminar.find('[name=tc_id]').val($tc_eliminar);
    $modal_tc_eliminar.find('[name=tc_nombre]').val($tc_nombre);
    $modal_tc_eliminar.modal('show');
}

function form_tc_crear() {
    event.preventDefault();

    if( $requested == 1 ){
        return;
    }

    $requested = 1;


    var $url = $(this).attr('action');
    var $method = $(this).attr('method');
    $.ajax({
        url: $url,
        method: $method,
        data: new FormData(this),
        dataType: "JSON",
        processData: false,
        contentType: false
    }).done(function (data) {
        if( data.success=='true' ) {
            showmessage(data.message, 1);
            load_tcs();
            setTimeout(function(){
                modal_close($modal_tc_crear);
                $requested = 0;
            },1000);
        }
        else{
            showmessage(data.message,0);
            $requested = 0;
        }
    });
}

function form_tc_editar() {
    event.preventDefault();

    if( $requested == 1 ){
        return;
    }

    $requested = 1;

    var $url = $(this).attr('action');
    var $method = $(this).attr('method');
    $.ajax({
        url: $url,
        method: $method,
        data: new FormData(this),
        dataType: "JSON",
        processData: false,
        contentType: false
    }).done(function (data) {
        if( data.success=='true' ) {
            showmessage(data.message, 1);
            load_tcs();
            setTimeout(function(){
                modal_close($modal_tc_editar);
                $requested = 0;
            },1000);
        }
        else{
            showmessage(data.message,0);
            $requested = 0;
        }
    });
}

function form_tc_eliminar() {
    event.preventDefault();

    if( $requested == 1 ){
        return;
    }

    $requested = 1;

    var $url = $(this).attr('action');
    var $method = $(this).attr('method');
    $.ajax({
        url: $url,
        method: $method,
        data: new FormData(this),
        dataType: "JSON",
        processData: false,
        contentType: false
    }).done(function (data) {
        if( data.success=='true' ) {
            showmessage(data.message, 1);
            load_tcs();
            setTimeout(function(){
                modal_close($modal_tc_eliminar);
                $requested = 0;
            },1000);
        }
        else{
            showmessage(data.message,0);
            $requested = 0;
        }
    });
}
