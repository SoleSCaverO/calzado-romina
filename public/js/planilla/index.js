$(document).on('ready',main);

var $modal_planilla;
var $modal_title;
var $modal_number;

function main() {
    var $body = $('body');
    $modal_planilla   = $('#modal_planilla');
    $modal_title      = $('#modal_planilla_title');
    var $table_planilla = $('#table_planilla');

    $body.on('click','[data-create]',modal_create);
    $body.on('click','[data-edit]',modal_edit);
    $body.on('click','[data-delete]',modal_delete);
    $body.on('click','[data-filter]',filter);
    $body.on('click','[data-modal_close]',modal_close);
    $('#form_planilla').on('submit',form_planilla);
    $table_planilla.on('click','[data-trow]',redirect_planilla);
    $body.on('change','#fecha_inicio',fecha_inicio_changed);
    $body.on('change','#fecha_fin',fecha_fin_changed);
}

function load_planillas($url_load_data) {
    var $table_planilla = $('#table_planilla');
    var $url;
    if( $url_load_data == '' )
        $url = $('#url_planillas').val();
    else
       $url = $url_load_data;

    $.ajax({
        url: $url,
        method: 'get'
    }).done(function (data) {
        if (data.success == 'true') {
            var $to_append = '';
            $table_planilla.html('');
            $.each(data.data,function (k,v) {
                $to_append +=
                    '<tr>'+
                    '<td>'+ v.plaId +'</td>'+
                    '<td>'+ v.fecha_inicio +'</td>'+
                    '<td>'+ v.fecha_fin+ '</td>'+
                    '<td>'+ ((v.plaEstado==1)?'Activa':'No activa') +'</td>'+
                    '<td></td>'+
                    '<td></td>'+
                    '<td>' +
                    '<button class="btn btn-info btn-sm" data-edit="'+ v.plaId +'"'+
                    'data-fecha_inicio="'+ v.fecha_inicio +'" data-fecha_fin="'+ v.fecha_fin +'">'+
                    '<i class="fa fa-edit"></i> Editar'+
                    '</button>'+
                    '<button class="btn btn-danger btn-sm" data-delete="'+ v.plaId +'"'+
                    'data-fecha_inicio="'+ v.fecha_inicio +'" data-fecha_fin="'+ v.fecha_fin +'">'+
                    '<i class="fa fa-trash"></i> Eliminar'+
                    '</button>'+
                    '</td>'+
                    '</tr>';
            });
            $table_planilla.append($to_append);
        }
    });
}

function filter() {
    var $fecha_inicio = $('#filter_fecha_inicio').val();
    var $fecha_fin    = $('#filter_fecha_fin').val();
    var $url          = $('#url_planillas_filter').val();

    if( $fecha_inicio == '' ) {
        showmessage('Para realizar el filtro, debe seleccionar la fecha inicio.');
        return;
    }

    if( $fecha_fin == '' ) {
        showmessage('Para realizar el filtro, debe seleccionar la fecha fin.');
        return;
    }

    if( $fecha_fin < $fecha_inicio ) {
        showmessage('La fecha inicio debe ser menor a la fecha fin.');
        return;
    }
    load_planillas($url+'/'+$fecha_inicio+'/'+$fecha_fin);
}

function modal_close() {
    $modal_planilla.modal('hide');
    var $fecha_inicio = $('#fecha_inicio');
    var $hora_inicio = $('#hora_inicio');
    var $fecha_fin = $('#fecha_fin');
    var $hora_fin = $('#hora_fin');

    $fecha_inicio.val('');
    $fecha_fin.val('');
    $hora_inicio.timepicker('setTime', new Date());
    $hora_fin.timepicker('setTime', new Date());
}

function row_fecha_inicio($fecha,$hora) {
    var $row_fecha_inicio = $('#row_fecha_inicio');
    var $to_append;
    $row_fecha_inicio.html('');

    $to_append =
        '<div class="col-md-12">'+
        '<div class="col-md-6">'+
        '<label for="">Fecha inicio</label>'+
        '<input type="date" name="fecha_inicio" id="fecha_inicio" value="'+$fecha+'" class="form-control">'+
        '</div>'+
        '<div class="col-md-6">'+
        '<label for="">Hora inicio</label>'+
        '<div class="input-group bootstrap-timepicker timepicker">'+
        '<input name="hora_inicio" id="hora_inicio"   value="'+$hora+'" type="text" class="form-control input-small">'+
        '<span class="input-group-addon" style="background:#5bc0de"><i class="glyphicon glyphicon-time" style="color: white"></i></span>'+
        '</div>'+
        '</div>'+
        '</div>'
        ;
    $row_fecha_inicio.append($to_append);
}

function row_dia_fecha_inicio($dia) {
    var $row_dia_fecha_inicio= $('#row_dia_fecha_inicio');
    var $to_append;
    var $texto = '<label for="">La fecha INICIO es: '+$dia+'</label>';

    $row_dia_fecha_inicio.html('');
    if( $dia == '')
        $texto = '<label for="">Fecha inicio no seleccionada</label>';

    $to_append =
        '<div class="col-md-12">'+
        '<div class="col-md-6">'+
        $texto+
        '</div>'+
        '<div class="col-md-6"></div>'+
        '</div>'
        ;
    $row_dia_fecha_inicio.append($to_append);
}

function row_fecha_fin($fecha,$hora) {
    var $row_fecha_fin = $('#row_fecha_fin');
    var $to_append;
    $row_fecha_fin.html('');

    $to_append =
        '<div class="col-md-12">'+
        '<div class="col-md-6">'+
        '<label for="">Fecha fin</label>'+
        '<input type="date" name="fecha_fin" id="fecha_fin" value="'+$fecha+'" class="form-control">'+
        '</div>'+
        '<div class="col-md-6">'+
        '<label for="">Hora fin</label>'+
        '<div class="input-group bootstrap-timepicker timepicker">'+
        '<input name="hora_fin" id="hora_fin" value="'+$hora+'" type="text" class="form-control input-small">'+
        '<span class="input-group-addon" style="background:#5bc0de"><i class="glyphicon glyphicon-time" style="color: white"></i></span>'+
        '</div>'+
        '</div>'+
        '</div>'
        ;
    $row_fecha_fin.append($to_append);
}

function row_dia_fecha_fin( $dia ) {
    var $row_dia_fecha_fin = $('#row_dia_fecha_fin');
    var $to_append;
    var $texto = '<label for="">La fecha FIN es: '+$dia+'</label>';

    $row_dia_fecha_fin.html('');
    if( $dia == '')
        $texto = '<label for="">Fecha fin no seleccionada</label>';

    $to_append =
        '<div class="col-md-12">'+
        '<div class="col-md-6">'+
        $texto+
        '</div>'+
        '<div class="col-md-6"></div>'+
        '</div>'
    ;
    $row_dia_fecha_fin.append($to_append);
}

function row_mensaje_eliminar( $inicio,$fin ) {
    var $row_mensaje_eliminar = $('#row_mensaje_eliminar');
    var $to_append;

    $row_mensaje_eliminar.html('');
    $to_append =
        '<div class="col-md-12">'+
        '<label>Está seguro que desea eliminar la siguiente planilla?</label>'+
        '<input type="text" value="INICIO: '+$inicio+'   FIN: '+$fin+'" readonly class="form-control">'+
        '</div>'
        ;
    $row_mensaje_eliminar.append($to_append);
}

function start_timers() {
    var $hora_inicio = $('#hora_inicio');
    var $hora_fin    = $('#hora_fin');
    $hora_inicio.timepicker('setTime', new Date());
    $hora_fin.timepicker('setTime', new Date());
}

function clear_rows( $row ) {
    var $row_fecha_inicio     = $('#row_fecha_inicio ');
    var $row_dia_fecha_inicio = $('#row_dia_fecha_inicio ');
    var $row_fecha_fin        = $('#row_fecha_fin ');
    var $row_dia_fecha_fin    = $('#row_dia_fecha_fin ');
    var $row_mensaje_eliminar = $('#row_mensaje_eliminar');

    if( $row == 'row_fecha_inicio' )
        $row_fecha_inicio.html('');
    else if( $row == 'row_dia_fecha_inicio' )
        $row_dia_fecha_inicio.html('');
    else if( $row == 'row_fecha_fin' )
        $row_fecha_fin.html('');
    else if( $row == 'row_dia_fecha_fin' )
        $row_dia_fecha_fin.html('');
    else if( $row == 'row_mensaje_eliminar' )
        $row_mensaje_eliminar.html('');
    else{
        $row_fecha_inicio.html('');
        $row_dia_fecha_inicio.html('');
        $row_fecha_fin.html('');
        $row_dia_fecha_fin.html('');
        $row_mensaje_eliminar.html('');
    }
}

function fecha_inicio_changed() {
    var $fecha_inicio = $(this).val();
    var $day_name     =  get_date_day_name($fecha_inicio);
    row_dia_fecha_inicio($day_name);
}

function fecha_fin_changed() {
    var $fecha_fin = $(this).val();
    var $day_name  =  get_date_day_name($fecha_fin);
    row_dia_fecha_fin($day_name);
}

function get_date_day_name( $date_string ) {
    var weekday = ["Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado"];
    var $d = $date_string.split('-');
    var $date = new Date($d[0]+'/'+$d[1]+'/'+$d[2]);

    return weekday[$date.getDay()];
}

function american_date_format($date) {
    var $d = $date.split('-');

    return $d[2]+'-'+$d[1]+'-'+$d[0];
}

function modal_create() {
    row_fecha_inicio('','');
    row_dia_fecha_inicio('');
    row_fecha_fin('','');
    row_dia_fecha_fin('');
    start_timers();
    $modal_title.html('Nueva planilla');
    $modal_number = 1;

    $modal_planilla.modal('show');
}

function modal_edit() {
    $modal_title.html('Editar planilla');
    var $edit         = $(this).data('edit');
    var $fecha_inicio = $(this).data('fecha_inicio');
    var $fecha_fin    = $(this).data('fecha_fin');
    var $fecha_i;
    var $fecha_f;
    var $hora_i;
    var $hora_f;
    var $hora_inicio;
    var $hora_fin;

    $fecha_inicio = $fecha_inicio.split(' ');
    $fecha_i      = american_date_format($fecha_inicio[0]);
    $hora_i       = $fecha_inicio[1]+' '+$fecha_inicio[2];

    $fecha_fin = $fecha_fin.split(' ');
    $fecha_f   = american_date_format($fecha_fin[0]);
    $hora_f    = $fecha_fin[1]+' '+$fecha_fin[2];

    clear_rows('');
    row_fecha_inicio($fecha_i,$hora_i);
    row_dia_fecha_inicio(get_date_day_name($fecha_i));
    row_fecha_fin($fecha_f,$hora_f);
    row_dia_fecha_fin(get_date_day_name($fecha_f));

    $hora_inicio = $('#hora_inicio');
    $hora_fin    = $('#hora_fin');
    $hora_inicio.timepicker();
    $hora_fin.timepicker();
    $modal_planilla.find('[name=planilla_id]').val($edit);
    $modal_number = 2;

    $modal_planilla.modal('show');
}

function modal_delete() {
    $modal_title.html('Eliminar planilla');
    var $delete = $(this).data('delete');
    var $fecha_inicio = $(this).data('fecha_inicio');
    var $fecha_fin    = $(this).data('fecha_fin');

    clear_rows('');
    row_mensaje_eliminar($fecha_inicio,$fecha_fin);
    $modal_planilla.find('[name=planilla_id]').val($delete);
    $modal_number = 3;

    $modal_planilla.modal('show');
}

function form_planilla() {
    event.preventDefault();
    var $url_planillas_create = $('#url_planillas_create').val();
    var $url_planillas_edit = $('#url_planillas_edit').val();
    var $url_planillas_delete = $('#url_planillas_delete').val();
    var $method = $(this).attr('method');
    var $url;

    var $fecha_inicio = $('#fecha_inicio').val();
    var $fecha_fin    = $('#fecha_fin').val();

    if( $fecha_inicio == '' ) {
        showmessage('Seleccione la fecha inicio.');
        return;
    }

    if( $fecha_fin == '' ) {
        showmessage('Seleccione la fecha fin.');
        return;
    }

    if( $fecha_fin < $fecha_inicio ) {
        showmessage('La fecha inicio debe ser nemor a la fecha fin.');
        return;
    }

    if( $modal_number == 1 )
        $url = $url_planillas_create;
    else if( $modal_number == 2 )
        $url = $url_planillas_edit;
    else if( $modal_number == 3 )
        $url = $url_planillas_delete;

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
            modal_close();
            load_planillas('');
        } else {
            showmessage(data.message, 0);
        }
    });
}

function redirect_planilla()
{
    if( $(event.target).parent()[0] == (this) ) {
        $planilla_id = $(this).data('trow');
        var $url = $('#url_planillas_subareas_menores').val();
        location.href = $url+'/'+$planilla_id;
    }
}