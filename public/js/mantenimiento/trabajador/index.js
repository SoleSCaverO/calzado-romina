$(document).on('ready', main);

var $div_button_trabajador_crear;
var $modal_trabajador_crear;
var $modal_trabajador_editar;
var $modal_trabajador_eliminar;
var $modal_trabajador_existe;

var $trabajadores  = [];
var $subareas      = [];
var $tipo_trabajos = [];
var $requested = 0;

function main() {
    // Global
    trabajador_tipo_trabajos();
    $div_button_trabajador_crear = $('#div-button-trabajador-crear');
    $modal_trabajador_crear      = $('#modal-trabajador-crear');
    $modal_trabajador_editar     = $('#modal-trabajador-editar');
    $modal_trabajador_eliminar   = $('#modal-trabajador-eliminar');
    $modal_trabajador_existe     = $('#modal-trabajador-existe');
    
    var $body = $('body');
    var $area_id = $('#area_id');
    var $subarea_id = $('#subarea_id');
    var $subarea_menor_id = $('#select_subarea_menor_id');
    
    start_selectpicker($area_id);
    start_selectpicker($subarea_id);
    start_selectpicker($subarea_menor_id);

    //Subáreas
    $area_id.on('change',load_subareas);
    // Subáreas menores
    $subarea_id.on('change',load_subareas_menores);
    // Trabajadores
    $subarea_menor_id.on('change',trabajadores);
    
     // Trabajadores
    $body.on('click','[data-trabajador_crear]',modal_trabajador_crear);
    $body.on('click','[data-trabajador_editar]',modal_trabajador_editar);
    $body.on('click','[data-trabajador_eliminar]',modal_trabajador_eliminar);

    // FORMS - SUBMIT
    $('#form-trabajador-crear').on('submit',form_trabajador_crear);
    $('#form-trabajador-editar').on('submit',form_trabajador_editar);
    $('#form-trabajador-eliminar').on('submit',form_trabajador_eliminar);

    // Excel
    $body.on('click','[data-trabajadores_excel]',function () {
        $excel = $('#url-trabajadores-excel').val();
        location.href = $excel;
    });


    $body.on('click','#btn-trabajador-existe', function () {
        console.log('wtf');
        var $form = $('#form-trabajador-crear');
        var $data = $form.serialize()
        var $url  = $form.attr('action');
        var $method  = $form.attr('method');
        $data += '&existe=1';

        $modal_trabajador_existe.modal('hide');
        form_trabajador_crear_ajax($url, $method, $data);
    });
}

// Áreas
function load_subareas(){
    var  $area_id =  $(this).val();
    var $url = $('#url_subareas').val()+'/'+$area_id;
    var $select_subarea = $('#subarea_id');
    var $subareas_menores = $('#select_subarea_menor_id');
    var $trabajadores = $('#table-trabajadores');
    var $button_export_excel = $('#button_export_excel');

    $.ajax({
        url: $url,
        type:'GET',
        dataType:'Json'
    }).done(function (data) {
        $div_button_trabajador_crear.html('');
        $select_subarea.html('');
        $subareas_menores.html('');
        $subareas_menores.selectpicker('refresh');
        $subareas_menores.val('');
        $button_export_excel.html('');
        $trabajadores.html('');

        if(  data.success =='true' ){
            var $to_append = '';
            $.each(data.data,function (k,v) {
                $to_append +=
                    '<option value="'+v.subaId+'">'+v.subaDescripcion +'</option>';
            });
            $select_subarea.append($to_append);
            $select_subarea.selectpicker('refresh');
            $select_subarea.selectpicker('val','');
        }
    });
}

// Subareas
function load_subareas_menores() {
    var $subarea_id = $(this).val();
    if( !$subarea_id )
        return;

    var $button_export_excel = $('#button_export_excel');
    var $subarea_menor_id = $('#select_subarea_menor_id');
    var $trabajadores = $('#table-trabajadores');
    var $url = $('#url_subareas_menores').val()+'/'+$subarea_id;
    $.ajax({
        url: $url,
        type:'GET',
        dataType:'Json'
    }).done(function (data) {
        $subarea_menor_id.html('');
        $div_button_trabajador_crear.html('');
        $button_export_excel.html('');
        $trabajadores.html('');

        if(  data.success =='true' ){
            var $to_append = '';
            $.each(data.data,function (k,v) {
                $to_append +=
                    '<option value="'+ v.subamId +'" >'+ v.subamDescripcion +'</option>';
            });
            $subarea_menor_id.append($to_append);
            $subarea_menor_id.selectpicker('refresh');
            $subarea_menor_id.selectpicker('val','');
        }
    });
}

// Subáreas-menores
function trabajadores() {
    var $subarea_id = $(this).val();
    if( !$subarea_id )
        return;

    $div_button_trabajador_crear.html('');
    $div_button_trabajador_crear.append('<button class="btn btn-primary btn-sm" data-trabajador_crear="'+$subarea_id+'"> <i class="fa fa-plus-circle"> </i> Nuevo trabajador </button> ');

    var $button_export_excel = $('#button_export_excel');
    $button_export_excel.html('');
    $appending = '<button class="btn btn-success btn-sm" data-trabajadores_excel><i class="fa fa-file-excel-o"></i> Exportar Códigos</button>';
    $button_export_excel.append($appending);

    load_trabajadores($subarea_id);
}

function load_trabajadores ($subarea_id){
    trabajador_tipo_trabajos();
    var $url = $('#url-trabajadores-subarea').val();
    $.ajax({
        url: $url+'/'+$subarea_id,
        type:'get',
        dataType:'Json'
    }).done(function (data) {
        var $table_trabajadores = $('#table-trabajadores');
        $table_trabajadores.html('');
        if(  data.success =='true' ){
            var $to_append = '';
            var $type_work;
            var $there_is_data = 0;
            $.each(data.data,function (k,v) {
                $there_is_data = 1;
                for( var i=0;i<$trabajadores.length;i++ ) {
                    if ($trabajadores[i] == v.traId && $subareas[i] == $subarea_id) {
                        $type_work = $tipo_trabajos[i];
                    }
                }
                $to_append +=
                    '<tr>' +
                    '<td>' + v.traNombre + '</td>' +
                    '<td>' + v.traApellidos + '</td>' +
                    '<td>' + v.traDni + '</td>' +
                    '<td>' + ($type_work == 1 ? 'Fijo' : 'Destajo') + '</td>' +
                    '<td>' + ((v.traEstado) == 1 ? 'Activo' : 'Innactivo') + '</td>' +
                    '<td>' +
                    '<button class="btn btn-info btn-sm" data-trabajador_editar="' + v.traId + '"' +
                    'data-trabajador_nombres="' + v.traNombre + '" data-trabajador_apellidos="' + v.traApellidos + '"' +
                    'data-trabajador_dni="' + v.traDni + '"  data-trabajador_tipo_sueldo="' + $type_work + '"' +
                    'data-trabajador_estado="' + v.traEstado + '" data-subarea_id="' + $subarea_id + '"' +
                    '>' +
                    '<i class="fa fa-edit"></i> Editar' +
                    '</button>' +
                    '<button class="btn btn-danger btn-sm" data-trabajador_eliminar="' + v.traId + '" data-trabajador_nombre="' + (v.traNombre + ' ' + v.traApellidos) + '" data-subarea_id="' + $subarea_id + '">' +
                    '<i class="fa fa-trash-o"></i> Eliminar' +
                    '</button>' +
                    '</td>' +
                    '</tr>';
            });

            if( $there_is_data == 1)
                $table_trabajadores.append($to_append);
            else
                $table_trabajadores.append('<tr><td colspan="5" style="text-align: center"><b>NO EXISTEN DATOS</b></td></tr>')
        }
    });
}

// Trabajadores
function modal_trabajador_crear() {
    var $subarea_id = $('[data-trabajador_crear]').data('trabajador_crear');
    $modal_trabajador_crear.find('[name=subarea_menor_id]').val($subarea_id);
    $modal_trabajador_crear.modal('show');
}

function modal_trabajador_editar() {
    var $trabajador_id  = $(this).data('trabajador_editar');
    var $subarea_id     = $(this).data('subarea_id');
    var $trabajador_nombres   = $(this).data('trabajador_nombres');
    var $trabajador_apellidos = $(this).data('trabajador_apellidos');
    var $trabajador_dni       = $(this).data('trabajador_dni');
    var $trabajador_tipo_sueldo = $(this).data('trabajador_tipo_sueldo');
    var $trabajador_estado    = $(this).data('trabajador_estado');

    var $check_trabajador_estado = $('#check-trabajador-estado');
    var trabajador_tipo_trabajo_editar = $('#trabajador_tipo_trabajo_editar');
    $check_trabajador_estado.html('');
    trabajador_tipo_trabajo_editar.html('');

    $modal_trabajador_editar.find('[name=subarea_menor_id]').val($subarea_id);
    $modal_trabajador_editar.find('[name=trabajador_id]').val($trabajador_id);
    $modal_trabajador_editar.find('[name=trabajador_nombres]').val($trabajador_nombres);
    $modal_trabajador_editar.find('[name=trabajador_apellidos]').val($trabajador_apellidos);
    $modal_trabajador_editar.find('[name=trabajador_dni]').val($trabajador_dni);

    var to_append = '<option value="1">Fijo</option><option value="2" selected>Destajo</option>';
    if( $trabajador_tipo_sueldo==1 )
        to_append = '<option value="1" selected>Fijo</option><option value="2">Destajo</option>';
    trabajador_tipo_trabajo_editar.append(to_append);

    if( $trabajador_estado == 1)
        $check_trabajador_estado.append('<input type="checkbox"  name="trabajador_estado" class="form-control" checked>');
    else
        $check_trabajador_estado.append('<input type="checkbox"  name="trabajador_estado" class="form-control">');

    $modal_trabajador_editar.modal('show');
}

function modal_trabajador_eliminar() {
    var $trabajador_id     = $(this).data('trabajador_eliminar');
    var $subarea_id        = $(this).data('subarea_id');
    var $trabajador_nombre = $(this).data('trabajador_nombre');

    $modal_trabajador_eliminar.find('[name=trabajador_id]').val($trabajador_id);
    $modal_trabajador_eliminar.find('[name=subarea_menor_id]').val($subarea_id);
    $modal_trabajador_eliminar.find('[name=trabajador_nombres]').val($trabajador_nombre);
    $modal_trabajador_eliminar.modal('show');
}

function modal_close($modal) {
    $modal.modal('hide');
    $modal.find('[name=trabajador_id]').val('');
    $modal.find('[name=subarea_menor_id]').val('');
    $modal.find('[name=trabajador_nombres]').val('');
    $modal.find('[name=trabajador_apellidos]').val('');
    $modal.find('[name=trabajador_dni]').val('');
    $modal.find('[name=trabajador_estado]').attr('checked','');
}

function trabajador_tipo_trabajos() {
    $trabajadores  = [];
    $subareas      = [];
    $tipo_trabajos = [];
    var $url = $('#url-trabajador-tipo-trabajos').val();
    $.ajax({
        url: $url,
        method: 'GET',
        dataType: "JSON"
    }).done(function (data) {
        $.each(data.types_work,function (k,v) {
            $trabajadores.push(v.traId);
            $subareas.push(v.subamId);
            $tipo_trabajos.push(v.dtraSueldo);
        });
    });
}

function form_trabajador_crear() {
    event.preventDefault();

    var $subarea_id = $modal_trabajador_crear.find('[name=subarea_menor_id]').val();
    var $trabajador_dni = $modal_trabajador_crear.find('[name=trabajador_dni]').val();
    var $url_trabajador_search_dni_subarea = $('#url-trabajador-search-dni-subarea').val();

    if( $trabajador_dni.length != 8 ) {
        showmessage('Debe ingresar 8 dígitos', 0);
        return;
    }

    var $url = $(this).attr('action');
    var $method = $(this).attr('method');
    
    if( $requested == 1 ){
        return;
    }
    $requested = 1;
    $doble_modal = 0;
    $.ajax({
        url: $url_trabajador_search_dni_subarea+'/'+$trabajador_dni+'/'+$subarea_id,
        method: 'GET',
        dataType: "JSON"
    }).done(function (data) {
        if( data.existe == 1 && data.mensaje != '' ){ // Existe en esta subárea menor
            showmessage(data.mensaje,0);
            $requested = 0;
        }else{
            var $data = $('#form-trabajador-crear').serialize();
            $data += '&existe='+data.existe;

            if( data.existe == 0 ){ // Trabajador nuevo
                form_trabajador_crear_ajax($url,$method,$data);
            }else { // Trabajador que existe pero puede ser agregado a esta subárea menor
                $modal_trabajador_existe.modal('show');
                $requested=0;
            }
        }
    });
}

function form_trabajador_crear_ajax(url,method,data) {
    $.ajax({
        url: url,
        method: method,
        data:  data,
        headers:{
            'X-CSRF-TOKEN' : $('#_token').val()
        }
    }).done(function (data) {
        if( data.success=='true' ) {
            showmessage(data.message, 1);
            load_trabajadores(data.subarea_menor_id);
            setTimeout(function(){
                modal_close($modal_trabajador_crear);
                $requested = 0;
            },1000);
        }
        else{
            showmessage(data.message,0);
            $requested = 0;
        }
    });
}

function form_trabajador_editar() {
    event.preventDefault();

    var $trabajador_dni = $modal_trabajador_editar.find('[name=trabajador_dni]').val();
    if( $trabajador_dni.length != 8 ) {
        showmessage('Debe ingresar 8 dígitos', 0);
        return;
    }

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
            load_trabajadores(data.subarea_menor_id);
            setTimeout(function(){
                modal_close($modal_trabajador_editar);
                $requested = 0;
            },1000);
        }
        else{
            showmessage(data.message,0);
            $requested = 0;
        }
    });
}

function form_trabajador_eliminar() {
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
            load_trabajadores(data.subarea_menor_id);
            setTimeout(function(){
                modal_close($modal_trabajador_eliminar);
                $requested = 0;
            },1000);
        }
        else{
            showmessage(data.message,0);
            $requested = 0;
        }
    });
}