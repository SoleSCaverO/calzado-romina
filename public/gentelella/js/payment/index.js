var payments=[];
var invoice="";

$(document).on('ready', function () {
    $('#btnPago').on('click', traerPagos);
    $('#btnAddPay').on('click', mostrarRegistrarPay);
    $('#form').on('submit', registerPayment);

    $modalRegistarPay = $('#modalAddPayment');
});

var $modalRegistarPay;

function registerPayment() {
    event.preventDefault();
    var _token = $(this).find('[name=_token]').val();

    var data = $(this).serializeArray();
    $.ajax({
        url: 'pagos/save',
        data: data,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': _token }
    }).done(function( response ) {
        if(response.error){
            console.log(response.message);
            alert(response.message);
        }

        else{
            console.log("aasd");
            alert('Pago registrada correctamente.');
            location.reload();
        }

    });
}

function mostrarRegistrarPay() {
    var invoice = $('#facturas').val();
    if (! invoice) {
        alert('Ingrese el número de factura.');
        return;
    }
    $modalRegistarPay.find('[name="factura"]').val(invoice);

    $modalRegistarPay.modal('show');
}

function traerPagos() {
    invoice = $('#facturas').val();

    if (! invoice) {
        alert('Ingrese el número de factura.');
        return;
    }

    $.ajax({
        url: 'pagos/search/' + invoice
    })
        .done(function( data ) {
            var pagado = 0;
            console.log(data);
            $('#table-payments').html('');
            for (var i = 0; i<data.payments.length; ++i)
            {
                pagado = pagado + parseFloat(data.payments[i].payment);
                renderTemplatePayment(data.payments[i].invoice, data.payments[i].payment, data.payments[i].type, data.payments[i].operation, data.payments[i].updated_at);
                console.log(data.payments[i].invoice);
            }
            var deuda = parseFloat(data.output[0].total)-pagado;
            renderTemplateSummary(data.output[0].invoice, data.output[0].total, pagado, deuda);
            
        });
}
function renderTemplateSummary(invoice, total, pagado, deuda) {

    var clone = activateTemplate('#template-summary');

    clone.querySelector("[data-invoice]").innerHTML = invoice;
    clone.querySelector("[data-montototal]").innerHTML = total;
    clone.querySelector("[data-montopagado]").innerHTML = Math.round(pagado*100)/100;
    clone.querySelector("[data-montodeuda]").innerHTML = Math.round(deuda*100)/100;
    $('#table-summary').append(clone);
}

function renderTemplatePayment(invoice, payment, type, operation, updated_at) {

    var clone = activateTemplate('#template-payment');

    clone.querySelector("[data-factura]").innerHTML = invoice;
    clone.querySelector("[data-monto]").innerHTML = payment;
    clone.querySelector("[data-tipo]").innerHTML = type;
    clone.querySelector("[data-operacion]").innerHTML = operation;
    clone.querySelector("[data-fecha]").innerHTML = updated_at;
    clone.querySelector("[data-delete]").setAttribute('data-delete', invoice);
    console.log(invoice);
    $('#table-payments').append(clone);
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}