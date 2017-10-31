$(document).on('ready',main);

var $modal_description_create;
var $modal_description_edit;
var $modal_description_delete;

var $modal_pieza;
var $modal_number;
var $requested = 0;
var $description_id = 0;

function main() {
    $body = $('body');
    $modal_description_create = $('#modal_description_create');
    $modal_description_edit   = $('#modal_description_edit');
    $modal_description_delete = $('#modal_description_delete');
    $modal_pieza = $('#modal_pieza');

    $body.on('click','[data-description_create]',modal_description_create);
    $body.on('click','[data-description_edit]',modal_description_edit);
    $body.on('click','[data-description_delete]',modal_description_delete);

    $('#form_description_create').on('submit',form_description_create);
    $('#form_description_edit').on('submit',form_description_edit);
    $('#form_description_delete').on('submit',form_description_delete);

    $('#table_descriptions').on('click', 'tr', load_piezas);

    $body.on('click','[data-pieza_crear]',modal_pieza_crear);
    $body.on('click','[data-pieza_editar]',modal_pieza_editar);
    $body.on('click','[data-pieza_eliminar]',modal_pieza_eliminar);
    $body.on('click','#pieza_consider',pieza_consider);
    $body.on('click','#pieza_infinito',pieza_infinito_on_check);
}

// DESCRIPTIONS

function load_descriptions() {
    $.ajax({
        url : $('#url_load_descriptions').val()
    }).done(function (data) {
        var $table_descriptions = $('#table_descriptions');
        $table_descriptions.html('');
        $to_append = '';
        $.each(data.data,function (k,v) {
            $to_append +=
                '<tr data-description_id="'+v.id+'">'+
                '<td>'+v.name+'</td>'+
                '<td>'+v.description+'</td>'+
                '<td>'+(v.state==1?"Activa":"Inactiva")+'</td>'+
                '<td>'+
                '<button class="btn btn-info btn-sm" data-description_edit="'+v.id+'"'+
                'data-description_nombre="'+v.name+'"'+
                'data-description_description="'+v.description+'"'+
                'data-description_estado="'+v.state+'">' +
                '<i class="fa fa-pencil"></i> Editar' +
                '</button>'+
                '<button class="btn btn-danger btn-sm" data-description_delete="'+v.id+'"'+
                'data-description_name="'+v.name+'">'+
                '<i class="fa fa-trash-o"></i> Eliminar'+
                '</button>'+
                '</td>'+
                '</tr>';
        });

        $table_descriptions.append($to_append);
    });
}
function modal_description_create() {
    $modal_description_create.modal();
}

function modal_description_edit() {
    var $data = $(this).data();

    var $description_edit = $data.description_edit;
    var $description_name = $data.description_name;
    var $description_description = $data.description_description;
    var $description_state = $data.description_state;

    $modal_description_edit.find('[name=description_id]').val($description_edit);
    $modal_description_edit.find('[name=description_name]').val($description_name);
    $modal_description_edit.find('[name=description_description]').val($description_description);
    $modal_description_edit.find('[name=description_state]').prop('checked',$description_state==1);
    $modal_description_edit.modal();
}

function modal_description_delete() {
    var $data = $(this).data();
    var $description_delete = $data.description_delete;
    var $description_name   = $data.description_name;

    $modal_description_delete.find('[name=description_id]').val($description_delete);
    $modal_description_delete.find('[name=description_name]').val($description_name);
    $modal_description_delete.modal();
}

function form_description_create() {
    event.preventDefault();

    var $btn_create_cancel = $('#btn_create_cancel');
    var $btn_create_accept = $('#btn_create_accept');
    $btn_create_cancel.prop('disabled',true);
    $btn_create_accept.prop('disabled',true);
    var data = new FormData(this);
    $.ajax({
     url: $(this).attr('action'),
     type: $(this).attr('method'),
     data: data,
     processData: false,
     contentType: false
    }).done(function (data) {
     if( data.success ){
         showmessage(data.message,1);
         load_descriptions();
         setTimeout(function () {
             $modal_description_create.modal('hide');
             $modal_description_create.find('[name=description_name]').val('');
             $modal_description_create.find('[name=description_description]').val('');
             $modal_description_create.find('[name=description_state]').prop('checked',true);

             $btn_create_cancel.prop('disabled',false);
             $btn_create_accept.prop('disabled',false);
         },500);
     }else{
         showmessage(data.message,0);
         $btn_create_cancel.prop('disabled',false);
         $btn_create_accept.prop('disabled',false);
     }
    });
}

function form_description_edit() {
    event.preventDefault();

    var $btn_edit_cancel = $('#btn_edit_cancel');
    var $btn_edit_accept = $('#btn_edit_accept');
    $btn_edit_cancel.prop('disabled',true);
    $btn_edit_accept.prop('disabled',true);
    var data = new FormData(this);
    $.ajax({
     url: $(this).attr('action'),
     type: $(this).attr('method'),
     data: data,
     processData: false,
     contentType: false
    }).done(function (data) {
     if( data.success ){
         showmessage(data.message,1);
         load_descriptions();
         setTimeout(function () {
             $modal_description_edit.modal('hide');
             $btn_edit_cancel.prop('disabled',false);
             $btn_edit_accept.prop('disabled',false);
         },500);
     }else{
         showmessage(data.message,0);
         $btn_edit_cancel.prop('disabled',false);
         $btn_edit_accept.prop('disabled',false);
     }
    });
}

function form_description_delete() {
    event.preventDefault();

    var $btn_delete_cancel = $('#btn_delete_cancel');
    var $btn_delete_accept = $('#btn_delete_accept');
    $btn_delete_cancel.prop('disabled',true);
    $btn_delete_accept.prop('disabled',true);
    var data = new FormData(this);
    $.ajax({
     url: $(this).attr('action'),
     type: $(this).attr('method'),
     data: data,
     processData: false,
     contentType: false
    }).done(function (data) {
     if( data.success ){
         showmessage(data.message,1);
         load_descriptions();
         setTimeout(function () {
             $modal_description_delete.modal('hide');
             $btn_delete_cancel.prop('disabled',false);
             $btn_delete_accept.prop('disabled',false);
         },500);
     }else{
         showmessage(data.message,0);
         $btn_delete_cancel.prop('disabled',false);
         $btn_delete_accept.prop('disabled',false);
     }
    });
}
// DESCRIPTIONS

function load_piezas() {
    // Global variable
    $description_id = $(this).data('description_id');
    var $btn_add_piece = $('#btn_add_piece');
    $btn_add_piece.html('');
    $btn_add_piece.append(
        '<button class="btn btn-success btn-sm" data-pieza_crear>'+
        '<i class="fa fa-plus-square"></i> Nueva pieza'+
        '</button>'
    );
    piezas();
}

function piezas() {
    var $table_piezas = $('#table_piezas');
    var $url = $('#url_pieza_listar').val()+'/'+$description_id;
    $table_piezas.html('');

    $.ajax({
        url:$url,
        type:'get'
    }).done(function (data) {
        var $to_append = '';
        if( data.success == 'true' ) {
            $.each(data.data,function (k,v) {
                $to_append += '' +
                    '<tr>'+
                    '<td>'+ v.pieTipo+ '</td>'+
                    '<td>'+ v.pieMultiplo+ '</td>'+
                    '<td>'+ ((v.pieFlag==0)?v.pieInicial:'') +'</td>'+
                    '<td>'+ ((v.pieFinal==99999)?'Infinito':(v.pieFinal?v.pieFinal:''))+ '</td>'+
                    '<td>'+ ((v.pieEstado==1)?'Activa':'Inactiva')+ '</td>'+
                    '<td>'+
                    '<button class="btn btn-info btn-sm" data-pieza_editar="'+v.pieId+'"'+
                    'data-pieza_tipo="'+ v.pieTipo+ '"'+
                    'data-pieza_multiplo="'+ v.pieMultiplo+ '"'+
                    'data-pieza_flag="'+ v.pieFlag+ '"'+
                    'data-pieza_inicio="'+ v.pieInicial+ '"'+
                    'data-pieza_fin="'+ v.pieFinal+ '"'+
                    'data-pieza_estado="'+ v.pieEstado+ '">'+
                    '<i class="fa fa-pencil"></i> Editar'+
                    '</button>'+
                    '<button class="btn btn-danger btn-sm" data-pieza_eliminar="'+ v.pieId +'"'+
                    'data-pieza_tipo="'+ v.pieTipo +'">'+
                    '<i class="fa fa-trash-o"></i> Eliminar'+
                    '</button>'+
                    '</td>'+
                    '</tr>';
            });
            $table_piezas.append($to_append);
        }
    });
}

function row_pieza_tipo() {
    var $row_pieza_tipo = $('#row_pieza_tipo');
    var $to_append =
        '<div class="col-md-12 form-group">'+
        '<label>Tipo</label>'+
        '<input type="text" name="pieza_tipo" id="pieza_tipo" class="form-control">'+
        '</div>';
    $row_pieza_tipo.html('');
    $row_pieza_tipo.append($to_append);
}

function row_pieza_multiplo() {
    var $row_pieza_multiplo = $('#row_pieza_multiplo');
    var $to_append =
        '<div class="col-md-6 form-group">'+
        '<label>Múltiplo</label>'+
        '<input type="number" min="0" step="any" name="pieza_multiplo" class="form-control">'+
        '</div>';
    $row_pieza_multiplo.html('');
    $row_pieza_multiplo.append($to_append);
}

function row_pieza_inicio() {
    var $row_pieza_inicio = $('#row_pieza_inicio');
    var $to_append =
        '<div class="col-md-6 form-group">'+
        '<label>Inicio</label>'+
        '<input type="number" min="0" name="pieza_inicio" class="form-control">'+
        '</div>';
    $row_pieza_inicio.html('');
    $row_pieza_inicio.append($to_append);
}

function row_pieza_fin() {
    var $row_pieza_fin = $('#row_pieza_fin');
    var $to_append =
        '<div class="col-md-6 form-group">'+
        '<label>Fin</label>'+
        '<input type="number" min="0" name="pieza_fin" id="pieza_fin" class="form-control">'+
        '</div>';
    $row_pieza_fin.html('');
    $row_pieza_fin.append($to_append);
}

function row_pieza_infinito_unchecked() {
    var $row_pieza_infinito = $('#row_pieza_infinito');
    var $to_append =
        '<div class="col-md-1 col-md-offset-1">'+
        '<input type="checkbox" name="pieza_infinito" id="pieza_infinito"  class="form-control pull-right">'+
        '</div>'+
        '<div class="col-md-4">'+
        '<label class="beside_check">Infinito</label>'+
        '</div>';
    $row_pieza_infinito.html('');
    $row_pieza_infinito.append($to_append);
}

function pieza_infinito_on_check(){
    var boxes = $(":checkbox:checked");
    $pieza = $('#pieza_fin');
    $pieza.prop('readonly',false);

    boxes.each(function () {
        if( this.id == "pieza_infinito" ){
            $pieza.val('');
            $pieza.prop('readonly',true);
        }
    });

}

function row_pieza_infinito_checked() {
    var $row_pieza_infinito = $('#row_pieza_infinito');
    var $to_append =
        '<div class="col-md-1 col-md-offset-1">'+
        '<input type="checkbox"  name="pieza_infinito" id="pieza_infinito" class="form-control pull-right" checked>'+
        '</div>'+
        '<div class="col-md-4">'+
        '<label class="beside_check">Infinito</label>'+
        '</div>';
    $row_pieza_infinito.html('');
    $row_pieza_infinito.append($to_append);
}

function row_pieza_consider_unchecked() {
    var $row_pieza_consider = $('#row_pieza_consider');
    var $to_append =
        '<div class="col-md-1">'+
        '<input type="checkbox"  name="pieza_consider" class="form-control" id="pieza_consider">'+
        '</div>'+
        '<div class="col-md-11">'+
        '<label class="beside_check">No considerar N° de Piezas</label>'+
        '</div>';
    $row_pieza_consider.html('');
    $row_pieza_consider.append($to_append);
}

function row_pieza_consider_checked() {
    var $row_pieza_consider = $('#row_pieza_consider');
    var $to_append =
        '<div class="col-md-1">'+
        '<input type="checkbox"  name="pieza_consider" class="form-control" id="pieza_consider" checked>'+
        '</div>'+
        '<div class="col-md-11">'+
        '<label class="beside_check">No considerar N° de Piezas</label>'+
        '</div>';
    $row_pieza_consider.html('');
    $row_pieza_consider.append($to_append);
}

function row_pieza_estado_unchecked() {
    var $row_pieza_estado = $('#row_pieza_estado');
    var $to_append =
        '<div class="col-md-1">'+
        '<input type="checkbox"  name="pieza_estado" class="form-control">'+
        '</div>'+
        '<div class="col-md-11">'+
        '<label class="beside_check">Estado</label>'+
        '</div>';
    $row_pieza_estado.html('');
    $row_pieza_estado.append($to_append);
}

function row_pieza_estado_checked() {
    var $row_pieza_estado = $('#row_pieza_estado');
    var $to_append =
        '<div class="col-md-1">'+
        '<input type="checkbox"  name="pieza_estado" class="form-control" checked>'+
        '</div>'+
        '<div class="col-md-11">'+
        '<label class="beside_check">Estado</label>'+
        '</div>';
    $row_pieza_estado.html('');
    $row_pieza_estado.append($to_append);
}

function row_pieza_mensaje_eliminar($pieza_tipo,attr) {
    var $mensaje_eliminar = $('#row_pieza_mensaje_eliminar');
    $mensaje_eliminar.html('');
    var $to_append = '<div class="form-group">'+
        '<label>Seguro que desea eliminar la siguiente pieza?</label>'+
        '<div class="row">'+
        '<div class="col-md-12">'+
        '<input type="text" class="form-control" value="'+$pieza_tipo+'"'+attr+'>'+
        '</div>'+
        '</div>'+
        '</div>';

    $mensaje_eliminar.append($to_append);
}

function clear_rows($row) {
    var $row_pieza_tipo = $('#row_pieza_tipo');
    var $row_pieza_multiplo = $('#row_pieza_multiplo');
    var $row_pieza_consider = $('#row_pieza_consider');
    var $row_pieza_inicio = $('#row_pieza_inicio');
    var $row_pieza_fin = $('#row_pieza_fin');
    var $row_pieza_infinito = $('#row_pieza_infinito');
    var $row_pieza_estado = $('#row_pieza_estado');
    var $row_pieza_mensaje_eliminar = $('#row_pieza_mensaje_eliminar');

    if( $row == 'row_pieza_tipo' )
        $row_pieza_tipo.html('');
    else if( $row == 'row_pieza_multiplo' )
        $row_pieza_multiplo.html('');
    else if( $row == 'row_pieza_consider' )
        $row_pieza_consider.html('');
    else if( $row == 'row_pieza_inicio' )
        $row_pieza_inicio.html('');
    else if( $row == 'row_pieza_fin' )
        $row_pieza_fin.html('');
    else if( $row == 'row_pieza_infinito' )
        $row_pieza_infinito.html('');
    else if( $row == 'row_pieza_estado' )
        $row_pieza_estado.html('');
    else if( $row == 'row_pieza_mensaje_eliminar' )
        $row_pieza_mensaje_eliminar.html('');
    else if($row == '' ){
        $row_pieza_tipo.html('');
        $row_pieza_multiplo.html('');
        $row_pieza_consider.html('');
        $row_pieza_inicio.html('');
        $row_pieza_fin.html('');
        $row_pieza_infinito.html('');
        $row_pieza_estado.html('');
        $row_pieza_mensaje_eliminar.html('');
    }
}

function close_modal() {
    $modal_pieza.modal('hide');
    clear_rows('');
}

function pieza_consider() {
    var boxes = $(":checkbox:checked");
    var $clear = 0;
    boxes.each(function () {
        if( this.name == "pieza_consider" ){
            clear_rows('row_pieza_inicio');
            clear_rows('row_pieza_fin');
            clear_rows('row_pieza_infinito');
            $clear = 1;
        }
    });

    if( $clear == 0 ) {
        row_pieza_inicio();
        row_pieza_fin();
        row_pieza_infinito_unchecked();
    }
}

function modal_pieza_crear() {
    var $pieza_title = $('#pieza_title');
    $pieza_title.html('Nueva pieza');
    $('#pieza_id').val('');

    clear_rows('');
    row_pieza_tipo();
    row_pieza_multiplo();
    row_pieza_consider_unchecked();
    row_pieza_inicio();
    row_pieza_fin();
    row_pieza_infinito_unchecked();
    row_pieza_estado_checked();
    $modal_number = 1;

    $modal_pieza.modal('show');
}

function modal_pieza_editar() {
    var $pieza_title       = $('#pieza_title');
    var $pieza_id          = $(this).data('pieza_editar');
    var $pieza_tipo        = $(this).data('pieza_tipo');
    var $pieza_multiplo    = $(this).data('pieza_multiplo');
    var $pieza_flag        = $(this).data('pieza_flag');
    var $pieza_inicio      = $(this).data('pieza_inicio');
    var $pieza_fin         = $(this).data('pieza_fin');
    var $pieza_estado      = $(this).data('pieza_estado');

    $pieza_title.html('Editar pieza');
    $modal_number = 2;
    clear_rows('');

    row_pieza_tipo();
    row_pieza_multiplo();

    $modal_pieza.find('[name=pieza_id]').val($pieza_id);
    $modal_pieza.find('[name=pieza_tipo]').val($pieza_tipo);
    $modal_pieza.find('[name=pieza_multiplo]').val($pieza_multiplo);

    if( $pieza_estado == 1  )
        row_pieza_estado_checked();
    else
        row_pieza_estado_unchecked();

    if( $pieza_flag == 1 )
        row_pieza_consider_checked(); // No consider
    else {
        row_pieza_consider_unchecked();
        row_pieza_inicio();
        row_pieza_fin();
        $modal_pieza.find('[name=pieza_inicio]').val($pieza_inicio);

        if( $pieza_fin == 99999 )
            row_pieza_infinito_checked();
        else {
            $modal_pieza.find('[name=pieza_fin]').val($pieza_fin);
            row_pieza_infinito_unchecked();
        }
    }

    var boxes = $(":checkbox:checked");
    $pieza = $('#pieza_fin');
    $pieza.prop('readonly',false);

    boxes.each(function () {
        if( this.id == "pieza_infinito" ){
            $pieza.val('');
            $pieza.prop('readonly',true);
        }
    });

    $modal_pieza.modal('show');
}

function modal_pieza_eliminar() {
    var $pieza_title       = $('#pieza_title');
    var $pieza_id          = $(this).data('pieza_eliminar');
    var $pieza_tipo        = $(this).data('pieza_tipo');
    $pieza_title.html('Eliminar pieza');
    $modal_number = 3;
    clear_rows('');
    row_pieza_mensaje_eliminar($pieza_tipo,'readonly');
    $modal_pieza.find('[name=pieza_id]').val($pieza_id);

    $modal_pieza.modal('show');
}


$('#form_pieza').validate({
    focusInvalid: true,
    rules:{
        pieza_tipo:{
            required : true,
            remote : {
                url: $('#validate_unique_name').val(),
                type: 'get',
                data :{
                    id : function(){
                        return $('#pieza_id').val();
                    },
                    description_id : function () {
                        return $description_id;
                    }
                }
            }
        },
        pieza_inicio : {        
            required : true,
            remote : {
                url: $('#validate_start').val(),
                type: 'get',
                data :{
                    id : function(){
                        return $('#pieza_id').val();
                    },
                    description_id : function () {
                        return $description_id;
                    }
                }
            }
        },
        pieza_fin : {
            required : function(){
                var boxes = $(":checkbox:checked");
                var $required = 1;
                boxes.each(function () {
                    if( this.id == "pieza_infinito" ){
                        $required = 0;
                    }
                });

                if( $required == 0 ){
                    return false
                }

                return true;
            },
            remote : {
                url: $('#validate_end').val(),
                type: 'get',
                data :{
                    id : function(){
                        return $('#pieza_id').val();
                    },
                    description_id : function () {
                        return $description_id;
                    }
                }
            }
        },
        pieza_multiplo:{
            required : true,
            min : 0,
            number : true
        }
    },
    messages: {
        pieza_tipo:{
            required : 'Ingrese un tipo de pieza.',
            remote: 'Ya existe un tipo de pieza con ese nombre.'
        },
        pieza_inicio:{
            required : 'Ingrese inicio de pieza.',
        },
        pieza_fin:{
            required : 'Ingrese fin de pieza.',
            remote: 'Ya existe una pieza con ese fin o el fin se cruza en un intervalo.'
        },
        pieza_multiplo:{
            required : 'Ingrese un múltiplo',
            min : 'El valor mínimo es 0',
            number : 'Ingrese un número'
        }
    },
    submitHandler : function(form) {
        var $CREAR    = 1;
        var $EDITAR   = 2;
        var $ELIMINAR = 3;
        var $method = 'post';
        var $url;
        var data = new FormData(form);
        if( $modal_number == $CREAR ) {
            $url = $('#url_pieza_crear').val();
            data.append('description_id',$description_id);
        }
        else if ( $modal_number == $EDITAR )
            $url = $('#url_pieza_editar').val();
        else if( $modal_number == $ELIMINAR )
            $url = $('#url_pieza_eliminar').val();

        if( $requested == 1 ){
            return;
        }

        $requested = 1;

        $.ajax({
            url: $url,
            method: $method,
            data: data,
            dataType: "JSON",
            processData: false,
            contentType: false
        }).done(function (data) {
            if (data.success == 'true') {
                piezas();
                showmessage(data.message, 1);
                setTimeout(function () {
                    close_modal();
                    $requested = 0;
                },500);
            } else {
                showmessage(data.message, 0);
                $requested = 0;
            }
        });
    }
});