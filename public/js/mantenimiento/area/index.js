$(document).on('ready', main);

var $div_button_subarea_crear;
var $modal_area_crear;
var $modal_area_editar;
var $modal_area_eliminar;
var $modal_subarea_crear;
var $modal_subarea_editar;
var $modal_subarea_eliminar;
var $requested = 0;

function main() {
    // Global
    $div_button_subarea_crear = $('#div-button-subarea-crear');
    $modal_area_crear    = $('#modal-area-crear');
    $modal_area_editar   = $('#modal-area-editar');
    $modal_area_eliminar = $('#modal-area-eliminar');
    $modal_subarea_crear    = $('#modal-subarea-crear');
    $modal_subarea_editar   = $('#modal-subarea-editar');
    $modal_subarea_eliminar = $('#modal-subarea-eliminar');
    var $body = $('body');

    // Áreas
    $('#table-area').on('click', 'tr', subareas);
    $body.on('click','[data-area_crear]',modal_area_crear);
    $body.on('click','[data-area_editar]',modal_area_editar);
    $body.on('click','[data-area_eliminar]',modal_area_eliminar);

    // Subáreas
    $body.on('click','[data-subarea_crear]',modal_subarea_crear);
    $body.on('click','[data-subarea_editar]',modal_subarea_editar);
    $body.on('click','[data-subarea_eliminar]',modal_subarea_eliminar);

    // FORMS - SUBMIT -AREAS
    $('#form-area-crear').on('submit',form_area_crear);
    $('#form-area-editar').on('submit',form_area_editar);
    $('#form-area-eliminar').on('submit',form_area_eliminar);

    // FORMS - SUBMIT -SUBAREAS
    $('#form-subarea-crear').on('submit',form_subarea_crear);
    $('#form-subarea-editar').on('submit',form_subarea_editar);
    $('#form-subarea-eliminar').on('submit',form_subarea_eliminar);
}

// GLOBAL
function modal_close_areas($modal) {
    $modal.modal('hide');
    $modal.find('[name=area_nombre]').val('');
}

function modal_close_subareas($modal) {
    $modal.modal('hide');
    $modal.find('[name=subarea_nombre]').val('');
    $modal.find('[name=subarea_estado]').attr('checked','');
    $modal.find('[name=subarea_op]').attr('checked','');
}

function load_areas(){
    var $url = $('#area-url').val();
    var $location = location.href;
    var $position = 1;
    if($location.split('page=').length>1 )
        $position = parseInt($location.split('page=').pop());

    $.ajax({
        url: $url+'/'+$position,
        type:'GET',
        dataType:'Json'
    }).done(function (data) {
        var $table_area = $('#table-area');
        $table_area.html('');
        if(  data.success =='true' ){
            var $to_append = '';
            $.each(data.data,function (k,v) {
                $to_append +=
                    '<tr data-area_id="'+v.areId+'">'+
                    '<td>'+v.areNombre+'</td>'+
                    '<td>'+((v.areEstado==1)?'Activa':'No Activa')+'</td>'+
                    '<td>'+
                    '<button class="btn btn-info btn-sm" data-area_editar="'+v.areId+'"'+
                    'data-area_nombre="'+v.areNombre+'"'+
                    'data-area_estado="'+v.areEstado+'"'+
                    '<i class="fa fa-pencil"></i> Editar'+
                    '</button>'+
                    '<button class="btn btn-danger btn-sm" data-area_eliminar="'+v.areId+'"'+
                    'data-area_nombre="'+v.areNombre+'">'+
                    '<i class="fa fa-trash-o"></i> Eliminar'+
                    '</button>'+
                    '</td>'+
                    '</tr>';
            });
            $table_area.append($to_append);

            if( data.number_areas >4 )
                location.reload();
        }
    });
}

// Areas
function subareas() {
    select_table_row(this);
    var $area_id = $(this).attr('data-area_id');
    var $subarea_area_id   = $('#subarea_area_id');
    $subarea_area_id.val($area_id);

    $div_button_subarea_crear.html('');
    $div_button_subarea_crear.append('<button class="btn btn-success btn-sm" data-subarea_crear="'+$area_id+'"> <i class="fa fa-plus-circle"> </i> Nueva subárea </button> ');
    load_subareas($area_id);
}

function load_subareas($area_id){
    var $url = $('#area-subareas-url').val();
    $.ajax({
        url: $url+'/'+$area_id,
        type:'GET',
        dataType:'Json'
    }).done(function (data) {
        var $table_subarea = $('#table-subarea');
        $table_subarea.html('');
        if(  data.success =='true' ){
            var $to_append = '';
            $.each(data.data,function (k,v) {
                $to_append +=
                    '<tr>'+
                    '<td>'+v.subaDescripcion+'</td>'+
                    '<td>'+((v.subaDespacho==1)?'Sí':'No')+'</td>'+
                    '<td>'+((v.subaOrdenp==1)?'Visible':'No Visible')+'</td>'+
                    '<td>'+((v.subaEstado==1)?'Activa':'No activa')+'</td>'+
                    '<td>'+
                    '<button class="btn btn-info btn-sm" data-subarea_editar="'+v.subaId+'"' +
                    'data-subarea_nombre="'+v.subaDescripcion+'"' +'data-subarea_despacho="'+v.subaDespacho+'"'+
                    'data-subarea_op="'+v.subaOrdenp+'" data-subarea_estado="'+v.subaEstado+'">'+
                    '<i class="fa fa-edit"></i> Editar'+
                    '</button>'+
                    '<button class="btn btn-danger btn-sm" data-subarea_eliminar="'+v.subaId+'" data-subarea_nombre="'+v.subaDescripcion+'" >'+
                    '<i class="fa fa-trash-o"></i> Eliminar'+
                    '</button>'+
                    '</td>'+
                    '</tr>';
            });
            $table_subarea.append($to_append);
        }
    });
}

function modal_area_crear() {
    var $clear_area_estado = $('#clear_area_estado');
    $clear_area_estado.html('');
    $clear_area_estado.append('<input type="checkbox"  name="area_estado" class="form-control" checked>');

    $modal_area_crear.modal('show');
}

function modal_area_editar() {
    var $area_editar = $(this).data('area_editar');
    var $area_nombre = $(this).data('area_nombre');
    var $area_estado= $(this).data('area_estado');
    var $div_check_area_estado = $('#div_check_area_estado');

    $div_check_area_estado.html('');
    $modal_area_editar.find('[name=area_id]').val($area_editar);
    $modal_area_editar.find('[name=area_nombre]').val($area_nombre);

    if( $area_estado == 1 )
        $div_check_area_estado.append('<input type="checkbox"  name="area_estado" class="form-control" checked>');
    else
        $div_check_area_estado.append('<input type="checkbox"  name="area_estado" class="form-control">');

    $modal_area_editar.modal('show');
}

function modal_area_eliminar() {
    var $area_eliminar = $(this).data('area_eliminar');
    var $area_nombre = $(this).data('area_nombre');

    $modal_area_eliminar.find('[name=area_id]').val($area_eliminar);
    $modal_area_eliminar.find('[name=area_nombre]').val($area_nombre);
    $modal_area_eliminar.modal('show');
}

function form_area_crear() {
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
            load_areas();
            showmessage(data.message, 1);
            setTimeout(function(){
                modal_close_areas($modal_area_crear);
                $requested = 0;
            },1000);
        }
        else{
            showmessage(data.message,0);
            $requested = 0;
        }
    });
}

function form_area_editar() {
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
            load_areas();
            showmessage(data.message, 1);
            setTimeout(function(){
                modal_close_areas($modal_area_editar);
                $requested = 0;
            },1000);
        }
        else{
            showmessage(data.message,0);
            $requested = 0;
        }
    });
}

function form_area_eliminar() {
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
            load_areas();
            $div_button_subarea_crear.html('');
            showmessage(data.message, 1);
            setTimeout(function(){
                modal_close_areas($modal_area_eliminar);
                $requested = 0;
            },1000);
        }
        else{
            showmessage(data.message,0);
            $requested = 0;
        }
    });
}

// Subáreas
function modal_subarea_crear() {
    var $clear_subarea_despacho = $('#clear_subarea_despacho');
    var $clear_subarea_op     = $('#clear_subarea_op');
    var $clear_subarea_estado = $('#clear_subarea_estado');

    $clear_subarea_op.html('');
    $clear_subarea_despacho.html('');
    $clear_subarea_estado.html('');
    $clear_subarea_despacho.append('<input type="checkbox"  name="subarea_despacho" class="form-control">');
    $clear_subarea_op.append('<input type="checkbox"  name="subarea_op" class="form-control" checked>');
    $clear_subarea_estado.append('<input type="checkbox"  name="subarea_estado" class="form-control" checked>');

    $modal_subarea_crear.modal('show');
}

function modal_subarea_editar() {
    var $div_check_subarea_estado   = $('#div_check_subarea_estado');
    var $div_check_subarea_despacho = $('#div_check_subarea_despacho');
    var $div_check_subarea_op       = $('#div_check_subarea_op');
    var $subarea_editar   = $(this).data('subarea_editar');
    var $subarea_nombre   = $(this).data('subarea_nombre');
    var $subarea_despacho = $(this).data('subarea_despacho');
    var $subarea_op       = $(this).data('subarea_op');
    var $subarea_estado   = $(this).data('subarea_estado');

    $div_check_subarea_despacho.html('');
    $div_check_subarea_op.html('');
    $div_check_subarea_estado.html('');

    $modal_subarea_editar.find('[name=subarea_id]').val($subarea_editar);
    $modal_subarea_editar.find('[name=subarea_nombre]').val($subarea_nombre);

    if( $subarea_despacho== 1)
        $div_check_subarea_despacho.append('<input type="checkbox"  name="subarea_despacho" class="form-control" checked>');
    else
        $div_check_subarea_despacho.append('<input type="checkbox"  name="subarea_despacho" class="form-control">');

    if( $subarea_op== 1)
        $div_check_subarea_op.append('<input type="checkbox"  name="subarea_op" class="form-control" checked>');
    else
        $div_check_subarea_op.append('<input type="checkbox"  name="subarea_op" class="form-control">');

    if( $subarea_estado == 1)
        $div_check_subarea_estado.append('<input type="checkbox"  name="subarea_estado" class="form-control" checked>');
    else
        $div_check_subarea_estado.append('<input type="checkbox"  name="subarea_estado" class="form-control">');

    $modal_subarea_editar.modal('show');
}

function modal_subarea_eliminar() {
    var $subarea_eliminar = $(this).data('subarea_eliminar');
    var $subarea_nombre   = $(this).data('subarea_nombre');

    $modal_subarea_eliminar.find('[name=subarea_id]').val($subarea_eliminar);
    $modal_subarea_eliminar.find('[name=subarea_nombre]').val($subarea_nombre);
    $modal_subarea_eliminar.modal('show');
}

function form_subarea_crear() {
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
            load_subareas(data.area_id);
            showmessage(data.message, 1);
            setTimeout(function(){
                modal_close_subareas($modal_subarea_crear);
                $requested = 0;
            },1000);
        }
        else{
            showmessage(data.message,0);
            $requested = 0;
        }
    });
}

function form_subarea_editar() {
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
            load_subareas(data.area_id);
            showmessage(data.message, 1);
            setTimeout(function(){
                modal_close_subareas($modal_subarea_editar);
                $requested = 0;
            },1000);
        }
        else{
            showmessage(data.message,0);
            $requested = 0;
        }
    });
}

function form_subarea_eliminar() {
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
            load_subareas(data.area_id);
            showmessage(data.message, 1);
            setTimeout(function(){
                modal_close_subareas($modal_subarea_eliminar);
                $requested = 0;
            },1000);
        }
        else{
            showmessage(data.message,0);
            $requested = 0;
        }
    });
}
