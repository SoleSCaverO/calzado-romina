$(document).on('ready', principal);

var id;
var concepto;
var monto;
var tipo;

function principal()
{
    //Events
    $('.editar').on('click', updateConcept);
    $('#form').on('submit',sendRow)

}/**
 * Created by Juarez on 10/10/2016.
 */

function updateConcept()
{
    id = $(this).attr('data-id');
    concepto = $(this).attr('data-desc');
    monto = $(this).attr('data-amount');
    tipo = $(this).attr('data-type');
    $('#idModal').val(id);
    $('#conceptModal').val(concepto);
    $('#amountModal').val(monto);
    $('#typeConcept').val(tipo);
    $('#modalConcept').modal('show');
}

function sendRow()
{
    event.preventDefault();

    var _token = $(this).find('[name=_token]');

    $.ajax({
        url: 'editrow',
        data: new FormData(this),
        dataType: "JSON",
        processData: false,
        contentType: false,
        method: 'POST'
    })
        .done(function( response ) {
            if(response.error)
                alert(response.message);
            else{
                alert(response.message);
                    location.reload();
            }
        });
}

function limpiarCampos()
{
    $('#conceptModal').val('');
    $('#amountModal').val('');
    $('#modalConcept').modal('toggle');
}
