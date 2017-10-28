$(document).on('ready',main);

var $modal_inicio_eliminar;

function main() {
    $body = $('body');
    $modal_inicio_eliminar = $('#modal_inicio_eliminar');
    var $form_inicio_eliminar = $('#form_inicio_eliminar ');
    $body.on('click','[data-delete_start_work]',delete_start_work);
    $form_inicio_eliminar.on('submit',form_inicio_eliminar);
}

function delete_start_work () {
    var $id = $(this).data('delete_start_work');
    var $trabajador = $(this).data('trabajador');
    var $orden = $(this).data('orden');

    $modal_inicio_eliminar.find('[name=id]').val($id);
    $modal_inicio_eliminar.find('[name=trabajador]').val($trabajador);
    $modal_inicio_eliminar.find('[name=orden]').val($orden);
    $modal_inicio_eliminar.modal('show');
}

function form_inicio_eliminar() {
    event.preventDefault();
    var $url  = $(this).attr('action');
    var $type = $(this).attr('method');
    $.ajax({
        url:$url,
        type:$type,
        data:new FormData(this),
        dataType:'Json',
        processData: false,
        contentType:false
    }).done(function (data) {
        if( data.success=='true'){
            showmessage(data.message,1);
            setTimeout(function () {
                location.reload();
            },500);
        }else
            showmessage(data.message,0)
    });
}
