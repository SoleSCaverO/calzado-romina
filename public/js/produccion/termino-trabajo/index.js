$(document).on('ready',main);

var $modal_fin_eliminar;

function main() {
    $body = $('body');
    $modal_fin_eliminar = $('#modal_fin_eliminar');
    var $form_fin_eliminar = $('#form_fin_eliminar ');
    $body.on('click','[data-delete_end_work]',delete_end_work);
    $form_fin_eliminar.on('submit',form_fin_eliminar);
}

function delete_end_work () {
    var $id = $(this).data('delete_end_work');
    var $trabajador = $(this).data('trabajador');
    var $orden = $(this).data('orden');

    $modal_fin_eliminar.find('[name=id]').val($id);
    $modal_fin_eliminar.find('[name=trabajador]').val($trabajador);
    $modal_fin_eliminar.find('[name=orden]').val($orden);
    $modal_fin_eliminar.modal('show');
}

function form_fin_eliminar() {
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
