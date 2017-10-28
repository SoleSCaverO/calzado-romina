$(document).on('ready', function (){
    // $('#btnShowOutputs').on('click', showOutputs); // ??

    $('[data-look]').on('click', showDetails);

    $modalAnular = $('#modalAnular');
    $('[data-anular]').on('click', mostrarAnular);

    $(document).on('click', '[data-package]', showPackageDetails);

    // Apply fooTable
    $('#outputsTable').footable();

    $modalDetraction = $('#modalDetraction');
    $('[data-detraction]').on('click', showDetractionModal);
    $formDetraction = $('#formDetraction');
    $formDetraction.on('submit', submitDetraction);

    $('[data-invoice]').on('click', verInvoice);
});

var $modalDetraction, $formDetraction;

function verInvoice() {

    var ruta = $(this).data('url');
    var invoice = $(this).data('invoice');
    var route = ruta+"/"+invoice;
    console.log(ruta);
    window.open(route, '_blank');
}

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

function showPackageDetails() {
    var id = $(this).data('package');

    $.ajax({
        url: '../../paquete/detalles/'+id,
        method: 'GET'
    }).done(function(datos) {
        $('#table-package-details').html('');
        for (var i = 0; i<datos.length; ++i)
        {
            renderTemplateDetails(datos[i].product.name, datos[i].series, datos[i].product.price);
        }

        $('#modalPackageDetails').modal('show');
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
    var id = $(this).data('look');
    var url = $('#bodyOutput').data('href').replace('{id}', id);
    console.log(url);
    $.ajax({
            url: url
        })
        .done(function( data ) {
            if (data) {
                console.log(data);
                $('#bodyDetails').html('');
                $(data.items).each(function(i, e) {
                    renderTemplateDetail(e.name, e.series, 1, e.price, e.price, e.location, -1);
                });
                $(data.packages).each(function(i, e) {
                    renderTemplateDetail(e.name, e.code, 1, e.price, e.price, e.location, e.package_id);
                });

            } else {
                alert('Alquiler no encontrada');
            }
        });
}

// Funciones relacionadas al template HTML5
function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

function renderTemplateDetail(name, series, quantity, price, sub, location, package_id) {

    var clone = activateTemplate('#template-detail');

    if (package_id > 0) {
        clone.querySelector("[data-package]").setAttribute('data-package', package_id);
    } else {
        clone.querySelector("[data-name]").innerText = name;
    }

    clone.querySelector("[data-series]").innerHTML = series;
    clone.querySelector("[data-quantity]").innerHTML = quantity;
    clone.querySelector("[data-price]").innerHTML = price;
    clone.querySelector("[data-sub]").innerHTML = sub;
    clone.querySelector("[data-location]").innerHTML = location;

    $('#bodyDetails').append(clone);
}

function renderTemplateDetails(name, series, price) {
    var clone = activateTemplate('#template-package-detail');

    clone.querySelector("[data-name]").innerHTML = name;
    clone.querySelector("[data-series]").innerHTML = series;
    clone.querySelector("[data-price]").innerHTML = price;

    $('#table-package-details').append(clone);
}