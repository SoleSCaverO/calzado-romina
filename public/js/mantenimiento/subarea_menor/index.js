$(document).on('ready', main);
var $div_button_subarea_menor_crear;
var $modal_subarea_menor_crear;
var $modal_subarea_menor_editar;
var $modal_subarea_menor_eliminar;
var $tipo_calculos_ids = [];
var $tipo_calculos_nombres = [];
var $requested = 0;

function main() {
    $modal_subarea_menor_crear      = $('#modal-subarea-menor-crear');
    $modal_subarea_menor_editar     = $('#modal-subarea-menor-editar');
    $modal_subarea_menor_eliminar   = $('#modal-subarea-menor-eliminar');
    var $body = $('body');
    var $area_id = $('#area_id');
    start_selectpicker($area_id);

    $body.on('click','[data-subarea_menor_crear]',modal_subarea_menor_crear);
    $body.on('click','[data-subarea_menor_editar]',modal_subarea_menor_editar);
    $body.on('click','[data-subarea_menor_eliminar]',modal_subarea_menor_eliminar);
    $area_id.on('change',load_subareas);
    $('#table_subarea').on('click', 'tr', load_subareas_menores);

    // FORMS - SUBMIT -SUBAREAS
    $('#form-subarea-menor-crear').on('submit',form_subarea_menor_crear);
    $('#form-subarea-menor-editar').on('submit',form_subarea_menor_editar);
    $('#form-subarea-menor-eliminar').on('submit',form_subarea_menor_eliminar);

    load_tipo_calculos();
}

function load_tipo_calculos() {
    var $url_tipo_calculos = $('#url_tipo_calculos');
    var $url =  $url_tipo_calculos.val();
    $.ajax({
        url: $url,
        type:'GET',
        dataType:'Json'
    }).done(function (data) {
        $tipo_calculos_ids = [];
        $tipo_calculos_nombres = [];
        $.each(data.data,function (k,v) {
            $tipo_calculos_ids.push(v.tcalId);
            $tipo_calculos_nombres.push(v.tcalDescripcion);
        })
    });
}

function modal_close_subareas($modal) {
    $modal.modal('hide');
    $modal.find('[name=subarea_menor_nombre]').val('');
}

function load_subareas(){
    var  $area_id =  $(this).val();
    var $url = $('#url_subareas').val()+'/'+$area_id;

    $.ajax({
        url: $url,
        type:'GET',
        dataType:'Json'
    }).done(function (data) {
        var $table_subarea = $('#table_subarea');
        $table_subarea.html('');
        var $table_subareas_menores = $('#table-subareas-menores');
        $table_subareas_menores.html('');
        if(  data.success =='true' ){
            var $to_append = '';
            $.each(data.data,function (k,v) {
                $to_append +=
                    '<tr data-subarea_id="'+v.subaId+'" style="color:#333; font-weight: bold">'+
                    '<td>'+v.subaDescripcion+'</td>'+
                    '<td>'+((v.subaDespacho==1)?'Sí':'No')+'</td>'+
                    '<td>'+((v.subaEstado==1)?'Activa':'No Activa')+'</td>'+
                    '</tr>';
            });
            $table_subarea.append($to_append);

            $div_button_subarea_menor_crear = $('#div-button-subarea-menor-crear');
            $div_button_subarea_menor_crear .html('');
        }
    });
}

// Areas
function load_subareas_menores() {
    select_table_row(this);
    var $subarea_id = $(this).attr('data-subarea_id');
    var $subarea_menor_subarea_id = $('#subarea_menor_subarea_id');
    $div_button_subarea_menor_crear = $('#div-button-subarea-menor-crear');
    $subarea_menor_subarea_id.val($subarea_id);

    $div_button_subarea_menor_crear .html('');
    $div_button_subarea_menor_crear .append('<button class="btn btn-success btn-sm" data-subarea_menor_crear="'+$subarea_id+'"> <i class="fa fa-plus-circle"> </i> Nueva subárea menor </button> ');
    subareas_menores($subarea_id);
}

function subareas_menores($subarea_id){
    //url_tipo_calculos

    var $url = $('#url_subareas_menores').val()+'/'+$subarea_id;
    $.ajax({
        url: $url,
        type:'GET',
        dataType:'Json'
    }).done(function (data) {
        var $table_subareas_menores = $('#table-subareas-menores');
        $table_subareas_menores.html('');
        if(  data.success =='true' ){
            var $to_append = '';
            $.each(data.data,function (k,v) {
                $to_append +=
                    '<tr style="color:#333; font-weight: bold">'+
                    '<td>'+v.subamDescripcion+'</td>'+
                    '<td>'+v.tipo_calculo_nombre+'</td>'+
                    '<td>'+((v.subamEstado==1)?'Activa':'No activa')+'</td>'+
                    '<td>'+
                    '<button class="btn btn-info btn-sm" data-subarea_menor_editar="'+v.subamId+'"' +
                    'data-subarea_menor_nombre="'+v.subamDescripcion+'"' +'data-subarea_menor_tipo_calculo="'+v.tipo_calculo_id+'"'+
                    'data-subarea_menor_estado="'+v.subamEstado+'">'+
                    '<i class="fa fa-edit"></i> Editar'+
                    '</button>'+
                    '<button class="btn btn-danger btn-sm" data-subarea_menor_eliminar="'+v.subamId+'" ' +
                    'data-subarea_menor_nombre="'+v.subamDescripcion+'" >'+
                    '<i class="fa fa-trash-o"></i> Eliminar'+
                    '</button>'+
                    '</td>'+
                    '</tr>';
            });
            $table_subareas_menores.append($to_append);
        }
    });
}

function modal_subarea_menor_crear() {
    var $clear_subarea_menor_estado = $('#clear_subarea_menor_estado');

    $tipo_calculo_id_crear = $('#tipo_calculo_id_crear');
    $tipo_calculo_id_crear.html('');

    var $to_append = '';
    for(  var i=0; i < $tipo_calculos_nombres.length; i++ ){
        $to_append += '<option value='+ $tipo_calculos_ids[i] +'>'+ $tipo_calculos_nombres[i] +'</option>';
    }
    $tipo_calculo_id_crear.append($to_append);

    $clear_subarea_menor_estado.html('');
    $clear_subarea_menor_estado.append('<input type="checkbox"  name="subarea_menor_estado" class="form-control" checked>');

    $modal_subarea_menor_crear.modal('show');
}

function modal_subarea_menor_editar() {
    var $check_subarea_menor_estado   = $('#check-subarea-menor-estado');
    var $subarea_menor_editar   = $(this).data('subarea_menor_editar');
    var $subarea_menor_nombre   = $(this).data('subarea_menor_nombre');
    var $subarea_tipo_calculo   = $(this).data('subarea_menor_tipo_calculo');
    var $subarea_menor_estado   = $(this).data('subarea_menor_estado');

    $check_subarea_menor_estado.html('');
    $modal_subarea_menor_editar.find('[name=subarea_menor_id]').val($subarea_menor_editar);
    $modal_subarea_menor_editar.find('[name=subarea_menor_nombre]').val($subarea_menor_nombre);

    var $tipo_calculo_id = $('#tipo_calculo_id_edit');
    $tipo_calculo_id.html('');
    var $to_append = '';
    for( var i=0 ;i<$tipo_calculos_ids.length;i++ )
    {
        if(  $subarea_tipo_calculo == $tipo_calculos_ids[i] )
            $to_append +='<option value="'+$tipo_calculos_ids[i]+'" selected>'+$tipo_calculos_nombres[i]+'</option>';
        else
            $to_append +='<option value="'+$tipo_calculos_ids[i]+'">'+$tipo_calculos_nombres[i]+'</option>';
    }
    $tipo_calculo_id.append($to_append);

    if( $subarea_menor_estado == 1)
        $check_subarea_menor_estado.append('<input type="checkbox"  name="subarea_menor_estado" class="form-control" checked>');
    else
        $check_subarea_menor_estado.append('<input type="checkbox"  name="subarea_menor_estado" class="form-control">');

    $modal_subarea_menor_editar.modal('show');
}

function modal_subarea_menor_eliminar() {
    var $subarea_menor_eliminar = $(this).data('subarea_menor_eliminar');
    var $subarea_menor_nombre   = $(this).data('subarea_menor_nombre');

    $modal_subarea_menor_eliminar.find('[name=subarea_menor_id]').val($subarea_menor_eliminar);
    $modal_subarea_menor_eliminar.find('[name=subarea_menor_nombre]').val($subarea_menor_nombre);
    $modal_subarea_menor_eliminar.modal('show');
}

function form_subarea_menor_crear() {
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
            subareas_menores(data.subarea_id);
            showmessage(data.message, 1);
            setTimeout(function(){
                modal_close_subareas($modal_subarea_menor_crear);
                $requested = 0;
            },1000);
        }
        else{
            showmessage(data.message,0);
            $requested = 0;
        }
    });
}

function form_subarea_menor_editar() {
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
            subareas_menores(data.subarea_id);
            showmessage(data.message, 1);
            setTimeout(function(){
                modal_close_subareas($modal_subarea_menor_editar);
                $requested = 0;
            },1000);
        }
        else{
            showmessage(data.message,0);
            $requested = 0;
        }
    });
}

function form_subarea_menor_eliminar() {
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
            subareas_menores(data.subarea_id);
            showmessage(data.message, 1);
            setTimeout(function(){
                modal_close_subareas($modal_subarea_menor_eliminar);
                $requested = 0;
            },1000);
        }
        else{
            showmessage(data.message,0);
            $requested = 0;
        }
    });
}
