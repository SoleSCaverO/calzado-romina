$(document).on('ready', function (){
    $('#btnShowEntries').on('click', showEntries);
    $('#bodyEntries').on('click', 'tr', showDetails);
    $modalAnular = $('#modalAnular');
    $('[data-anular]').on('click', mostrarAnular);
});

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

function showEntries() {
    var inicio = $('#inicio').val();
    var fin = $('#fin').val();

    if (!inicio || !fin)
        return;

    var url = $(this).data('href');
    url = url.replace('{inicio}',inicio);
    url = url.replace('{fin}',fin);
    location.href = url;
}

function showDetails() {
    var id = $(this).find('[data-id]').data('id');
    var url = $('#bodyEntries').data('href').replace('{id}', id);
    $.ajax({
            url: url
        })
        .done(function( data ) {
            console.log(data);
            if (data) {
                $('#bodyDetails').html('');
                $(data).each(function(i, e) {
                    renderTemplateDetail(e.name, e.series, e.quantity, e.price, e.quantity * e.price, e.location);
                });

            } else {
                alert('Reutilización no encontrada');
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
