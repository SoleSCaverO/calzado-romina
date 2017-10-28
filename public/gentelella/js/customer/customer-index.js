$(document).on('ready', principal);

function principal()
{
    $modalEditar = $('#modalEditar');
    $modalEliminar = $('#modalEliminar');

    $('[data-id]').on('click', mostrarEditar);
    $('[data-delete]').on('click', mostrarEliminar);
}

//Create
var $modalEditar;
var $modalEliminar;

function mostrarEditar() {
    $('[data-clase]').each( function () {
            $(this).removeClass('active');

    });

    var id = $(this).data('id');
    $modalEditar.find('[name="id"]').val(id);

    var name = $(this).data('name');
    $modalEditar.find('[name="name"]').val(name);

    var document = $(this).data('document');
    $modalEditar.find('[name="document"]').val(document);

    var address = $(this).data('address');
    $modalEditar.find('[name="address"]').val(address);

    var phone = $(this).data('phone');
    $modalEditar.find('[name="phone"]').val(phone);

    var persona = $(this).data('persona');
    console.log(persona);
    $('#'+persona).prop('checked', true);
    $('#'+persona).parent().addClass('active');

    var typeid = $(this).data('typeid');
    //console.log(typeid)
    $('#'+typeid).prop('selected', true);

    $modalEditar.modal('show');
}

function mostrarEliminar() {
    if( access_denied ){
        alert('Usted no tiene permisos para esta acción');
        return;
    }

    var id = $(this).data('delete');
    $modalEliminar.find('[name="id"]').val(id);

    var name = $(this).data('name');
    $modalEliminar.find('[name="nombreEliminar"]').val(name);
    $modalEliminar.modal('show');
}