$(document).on('ready',main);

function main() {
    var $form_edit_inicio = $('#form_edit_inicio');

    $form_edit_inicio.on('submit',inicio);
}

function inicio() {
    event.preventDefault();

    var $pairs_user = $('#pairs_user').val();

    if( $pairs_user.length == 0 ) {
        showmessage('Debe ingresar la cantidad de pares a trabajar en la orden.',0);
        return;
    }

    if( $pairs_user < 1 ) {
        showmessage('La cantidad de pares a trabajar, debe ser positiva.',0);
        return;
    }

    var $url  = $(this).attr('action');
    var $type = $(this).attr('method');

    $.ajax({
        url:$url,
        type:$type,
        data: new FormData(this),
        dataType: "JSON",
        processData: false,
        contentType: false
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
