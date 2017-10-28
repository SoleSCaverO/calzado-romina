
var $modalDescomponer;
var $modalEdit;

var products;
var packages;
var items = [];
var selectedProduct;

$(document).on('ready', function () {
    $('[data-look]').on('click', lookDetails);
    $('[data-decompose]').on('click', mostrarDescomponer);
    $('[data-edit]').on('click', mostrarEditar);
    $('#form').on('submit', registerPackage);

    $modalDescomponer = $('#modalDescomponer');
    $modalEdit = $('#modalEdit');

    $('#btnAdd').on('click', addRow);
    $(document).on('click', '[data-delete]', deleteItem);
    $('#btnAccept').on('click', addItemsSeries);

    var url_products ='../public/productos/names';
    var url_locations ='../public/paquete/ubicaciones';

    $.ajax({
        url: url_locations,
        method: 'GET'
    }).done(function(datos) {

        locations = datos;
        loadAutoCompleteLocations(locations);
    });

    $.ajax({
        url: url_products,
        method: 'GET'
    }).done(function(datos) {

        products = datos.products;
        loadAutoCompleteProducts(products);
    });
});

function mostrarDescomponer()
{
    if( access_denied ){
        alert('Usted no tiene permisos para esta acción');
        return;
    }

    var id = $(this).data('decompose');
    $modalDescomponer.find('[name="id"]').val(id);

    var name = $(this).data('name');
    $modalDescomponer.find('[name="name"]').val(name);
    $modalDescomponer.modal('show');

    $('#sayYes').click( function(event) {
        event.preventDefault();
        $.ajax({
            url: '../public/paquete/descomponer/'+id,
            method: 'GET'
        }).done(function (response) {
            alert(response.message);
            location.reload();
        });
    });
}

function mostrarEditar()
{
    var id = $(this).data('edit');
    $modalEdit.find('[name="id"]').val(id);

    var code = $(this).data('code');
    $modalEdit.find('[name="code"]').val(code);

    var name = $(this).data('name');
    $modalEdit.find('[name="name"]').val(name);

    var location = $(this).data('location');
    $modalEdit.find('[name="location"]').val(location);

    var description = $(this).data('description');
    $modalEdit.find('[name="description"]').val(description);

    $.ajax({
        url: '../public/paquete/detalles/'+id,
        method: 'GET'
    }).done(function(datos) {
        $('#table-details-edit').html('');
        for (var i = 0; i<datos.length; ++i)
        {
            renderTemplateDetails_edit(datos[i].product.name, datos[i].series, datos[i].product.price);
        }
        $modalEdit.modal('show');

    });
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

function lookDetails() {
    var id = $(this).data('look');

    $.ajax({
        url: '../public/paquete/detalles/'+id,
        method: 'GET'
    }).done(function(datos) {
        $('#table-details-look').html('');
        for (var i = 0; i<datos.length; ++i)
        {
            renderTemplateDetails_look(datos[i].product.name, datos[i].series, datos[i].product.price);
        }

        $('#modalDetails').modal('show');
    });
}

function renderTemplateDetails_look(name, series, price) {
    var clone = activateTemplate('#template-details-look');

    clone.querySelector("[data-name-look]").innerHTML = name;
    clone.querySelector("[data-series-look]").innerHTML = series;
    clone.querySelector("[data-price-look]").innerHTML = price;

    $('#table-details-look').append(clone);
}

function renderTemplateDetails_edit(name, series, price) {
    var clone = activateTemplate('#template-details-edit');

    clone.querySelector("[data-name-edit]").innerHTML = name;
    clone.querySelector("[data-series-edit]").innerHTML = series;
    clone.querySelector("[data-price-edit]").innerHTML = price;

    $('#table-details-edit').append(clone);
}

function loadAutoCompleteProducts(data) {
    $('#product').typeahead(
        {
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            name: 'products',
            source: substringMatcher(data)
        }
    );

}

function loadAutoCompleteLocations(data) {
    $('#location').typeahead(
        {
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            name: 'locations',
            source: substringMatcher(data)
        }
    );
}

function addRow() {
    // Validate the product name
    var code = $('#code').val();
    if (!code) {
        alert('Ingrese el código del paquete');
        return;
    }

    var name = $('#name').val();
    if (!name) {
        alert('Ingrese el nombre del paquete');
        return;
    }

    var location = $('#location').val();
    if (!location) {
        alert('Ingrese la localización del paquete');
        return;
    }

    var product = $('#product').val();
    if (!product){
        alert('Seleccione el nombre del producto'); // Not continue
        return;
    }

    $.ajax({
            url: '../public/producto/buscar/' + product
        })
        .done(function( data ) {
            if (data) {
                // if require series
                $('#bodySeries').html('');

                renderTemplateSeries();
                loadAutoCompleteItems(data);

                // Temporary variables
                selectedProduct = { id: data.id, name: data.name,price: data.price };

                $('#modalSeries').modal('show');

            } else {
                alert('Producto no existe');
            }
        });
}

function loadAutoCompleteItems(data) {
    $.ajax({
            url: '../public/items/producto/' + data.id
        })
        .done(function(datos){
            $('[data-search]').typeahead(
                {
                    hint: true,
                    highlight: true,
                    minLength: 1
                },
                {
                    name: 'items',
                    source: substringMatcher(JSON.parse(datos))
                }
            );
        });
}

function addItemsSeries() {
    var series_array = [];
    $('#bodySeries').find('input').each(function (i, element) {
        var series = $(element).val();
        if(series != "")
            series_array.push(series);
    });

    for ( var i=0; i<series_array.length; ++i) {
        items.push({ id: selectedProduct.id, series: series_array[i] });
        renderTemplateDetails_edit( selectedProduct.name, series_array[i], selectedProduct.price );
    }
    $('#modalSeries').modal('hide');
}

function deleteItem() {
    var $tr = $(this).parents('tr');
    var id = $(this).data('delete');
    var series = $tr.find('[data-series]').text();
    itemDelete(id, series);
    $tr.remove();
}

function itemDelete(id, series) {
    for (var i = 0; i<items.length; ++i) {
        if (items[i].id == id && items[i].series == series) {
            items.splice(i, 1);
            return;
        }
    }
}

function renderTemplateSeries() {

    var clone = activateTemplate('#template-series');

    $('#bodySeries').append(clone);
}

function repeat(series_array) {

    var series_total = series_array.slice(0);
    for (var i = 0; i<series_array.length; ++i) {
        for (var j = i+1; j<series_total.length; ++j)
            if (series_array[i] == series_total[j])
                return true;
    }
    return false;
}

function registerPackage() {
    event.preventDefault();
    var _token = $(this).find('[name=_token]');
    var data = $(this).serializeArray();

    var product =[];
    var series =[];
    var items =[];

    $("[data-name-edit]").each( function(){ product.push($(this).text()) });
    $("[data-series-edit]").each( function(){ series.push($(this).text()) });
    for (var i = 0; i < product.length; i++)
        items.push({ product: product[i], series: series[i] });

    if( repeat(series) )
    {
        alert('Existen series repetidas');
        return;
    }

    data.push({name: 'items', value: JSON.stringify(items)});
    var url_paquete = '../public/paquete/modificar';

    $.ajax({
            url: url_paquete,
            data: data,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': _token }

        })
        .done(function( response ) {
            if(response.error)
                alert(response.message);
            else{
                alert('Paquete modificado correctamente.');
                location.reload();
            }
        });
}