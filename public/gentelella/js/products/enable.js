$(document).on('ready', principal);

var $modalHabilitar;
function principal() {
    $modalHabilitar = $('#modalHabilitar');
    $('[data-habilitar]').on('click', mostrarHabilitar);
}

function mostrarHabilitar() {
    if( access_denied ){
        alert('Usted no tiene permisos para esta acción');
        return;
    }

    var id = $(this).data('habilitar');
    $modalHabilitar.find('[name="id"]').val(id);

    var name = $(this).data('name');
    $modalHabilitar.find('[name="nombreHabilitar"]').val(name);
    $modalHabilitar.modal('show');
}