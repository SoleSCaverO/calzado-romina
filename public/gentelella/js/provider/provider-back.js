$(document).on('ready', principal);

function principal()
{
    $modalRetornar = $('#modalRetornar');
    $('[data-back]').on('click', mostrarRetornar);
}

//Create
var $modalRetornar;
function mostrarRetornar() {
    if( access_denied ){
        alert('Usted no tiene permisos para esta acción');
        return;
    }

    var id = $(this).data('back');
    $modalRetornar.find('[name="id"]').val(id);

    var name = $(this).data('name');
    $modalRetornar.find('[name="nombreRetornar"]').val(name);
    $modalRetornar.modal('show');
}