$(document).on('ready', function (){
    $('#btnShowOutputs').on('click', showOutputs);
    $('#bodyOutput').on('click', '[data-details]', showDetails);

    $modalAnular = $('#modalAnular');
    $('[data-anular]').on('click', mostrarAnular);

    $modalDetraction = $('#modalDetraction');
    $('[data-detraction]').on('click', showDetractionModal);
    $formDetraction = $('#formDetraction');
    $formDetraction.on('submit', submitDetraction);

    $('[data-invoice]').on('click', verInvoice);
});

function verInvoice() {
    var ruta = $(this).data('url');
    var invoice = $(this).data('invoice');
    var route = ruta+"/"+invoice;
    console.log(ruta);
    window.open(route, '_blank');
}

var $modalDetraction, $formDetraction;
function showDetractionModal() {
    var id = $(this).data('detraction');
    $modalDetraction.find('[name="id"]').val(id);

    $.get(detraction_url+'/'+id, function (data) {
        if (data) {
            if (data == -1) {
                alert('No es posible asignar detracción cuando la venta excede 1750.');
                return;
            }

            $modalDetraction.find('[name="detraction"]').val(data.value);
            $modalDetraction.find('[name="detraction_date"]').val(data.detraction_date);
            $modalDetraction.find('[name="voucher"]').val(data.voucher);
        } else {
            $modalDetraction.find('[name="detraction"]').val(0);
            $modalDetraction.find('[name="detraction_date"]').val('');
            $modalDetraction.find('[name="voucher"]').val('');
        }

        $modalDetraction.modal('show');
    });
}
function submitDetraction() {
    event.preventDefault();

    $.post(detraction_url, $(this).serialize(), function (data) {
        if (data.success)
            $modalDetraction.modal('hide');
        else alert(data.message);
    });
}

var $modalAnular;
function mostrarAnular() {
    if( access_denied ){
        alert('Usted no tiene permisos para esta acción');
        return;
    }

    var id = $(this).data('anular');
    $modalAnular.find('[name="id"]').val(id);
    $modalAnular.modal('show');
}

function showOutputs() {
    var cliente = $('#clientes').val();
    var inicio = $('#inicio').val();
    var fin = $('#fin').val();

    if (!cliente || !inicio || !fin)
        return;

    var url = $(this).data('href');
    url = url.replace('{cliente}',cliente);
    url = url.replace('{inicio}',inicio);
    url = url.replace('{fin}',fin);
    location.href = url;
}

function showDetails() {
    var id = $(this).data('details');
    var url = $('#bodyOutput').data('href').replace('{id}', id);
    // console.log(url);
    $.ajax({
            url: url
        })
        .done(function( data ) {
            if (data) {
                $('#bodyDetails').html('');
                $(data.items).each(function(i, e) {
                    renderTemplateDetail(e.name, e.series, 1, e.price, e.price, e.location, -1);
                });
                $(data.packages).each(function(i, e) {
                    renderTemplateDetail(e.name, e.code, 1, e.price, e.price, e.location, e.package_id);
                });

            } else {
                alert('Compra no encontrada');
            }
        });
}

// Funciones relacionadas al template HTML5
function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

function renderTemplateDetail(name, series, quantity, price, sub, location) {

    var clone = activateTemplate('#template-detail');

    clone.querySelector("[data-name]").innerHTML = name;
    clone.querySelector("[data-series]").innerHTML = series;
    clone.querySelector("[data-quantity]").innerHTML = quantity;
    clone.querySelector("[data-price]").innerHTML = price;
    clone.querySelector("[data-sub]").innerHTML = sub;
    clone.querySelector("[data-location]").innerHTML = location;

    $('#bodyDetails').append(clone);
}
