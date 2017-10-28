// Global variables
var items = [];
var products;
var packages;
var dataset;
var igv = 0;

// Temporary variables
var selectedProduct;

var checkeado = false;

$(document).on('ready', function () {

    // If the user change the currency, alert him !
    $('input[name=moneda]').on('change', onChangeCurrency);
    
    $('#product').on('blur', handleBlurProduct);
    $('#btnAdd').on('click', addRow);
    $(document).on('click', '[data-delete]', deleteItem);
    $('#form').on('submit', registerPuchaseOrder);
    $(document).on('click', '[data-igvserie]', updateSubtotal);
    $('#envioigv').on('click', envioIGV);

    var url_products ='../productos/names';

    $.ajax({
        url: url_products,
        method: 'GET'
    }).done(function(datos) {

        products = datos.products;
        if( Object.prototype.toString.call(products) === '[object Array]')
        {
            dataset = products;
            loadAutoCompleteProducts(dataset);
        }

    });
});

function onChangeCurrency() {
    var htmlAlertCurrency = '<div class="alert alert-success">' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
        '<p>Por favor tenga en cuenta que el tipo de moneda hace referencia a toda la venta, y significa que todos los productos y paquetes están expresados en esta moneda, al igual que el costo de envío.</p>' +
        '</div>';
    $('#form').before(htmlAlertCurrency);
}

function envioIGV() {
    var costoEnvio = $('#costenvio').val();
    console.log(costoEnvio);
    var addIgv = 0;
    if( $(this).is(':checked'))
    {
        addIgv = Math.round((parseFloat(costoEnvio)*0.18)*100)/100;
        console.log('AddIGV:  '+addIgv);
        igv += Math.round(addIgv*100)/100;
    }else{
        addIgv = Math.round((parseFloat(costoEnvio)*0.18)*100)/100;
        igv -= Math.round(addIgv*100)/100;
    }
    updateTotal();
}

function updateSubtotal() {
    var id = $(this).data('igvserie');
    var price;
    var precio = $(this).parent().next().next().text();
    console.log(precio);
    if( $(this).is(':checked'))
    {
        // precio = $(this).parent().prev().text();
        price = Math.round((precio*1.18)*100)/100;
        $(this).parent().next().html( Math.round((precio*0.18)*100)/100 );
        $(this).parent().next().next().html(price);
        for (var i = 0; i<items.length; ++i)
            if (items[i].id == id)
                items[i].subtotal = price;
        igv += (Math.round(price*100)/100)-(Math.round(precio*100)/100);
        //igv += (price)
        console.log("IGV: "+igv);
        updateTotal();
    }else{
        price = Math.round((precio*100/118)*100)/100;
        $(this).parent().next().html(Math.round(0)/100);
        $(this).parent().next().next().html(price);
        for (var i = 0; i<items.length; ++i)
            if (items[i].id == id)
                items[i].subtotal = price;
        igv -= (Math.round(precio*100)/100)-(Math.round(price*100)/100);
        console.log("IGV: "+igv);
        updateTotal();
    }
} // Cambiar esto en alquiler

function prevMonth(date_string) {
    var slash = date_string.indexOf('-'); // first slash
    var input_month = date_string.substr(slash+1);
    slash = input_month.indexOf('-'); // last slash
    input_month = input_month.substr(0, slash);

    var current_month = new Date().getMonth() +1; // January is 0 for Date objects

    return parseInt(input_month) < current_month;
}

function registerPuchaseOrder() {
    event.preventDefault();
    var totalguardar = $('#total').val();
    var costoEnvio = $('#costenvio').val();
    var totalIgv = $('#igv').val();
    console.log(costoEnvio);
    var type_doc = $('input:radio[name=documento]:checked').val();
    
    // Validate invoice number
    var invoice = $('#factura').val();
    if (! invoice) {
        alert('Ingrese el número de factura.');
        return;
    }

    // Validate date
    var invoice_date = $('#invoice_date').val();
    if (prevMonth(invoice_date)) {
        alert('Tenga en cuenta que ha seleccionado un mes pasado.');
    }

    var _token = $(this).find('[name=_token]');
    var data = $(this).serializeArray();
    data.push({name: 'items', value: JSON.stringify(items)});
    data.push({name: 'igv', value: Math.round(totalIgv*100)/100});
    data.push({name: 'total', value: Math.round(totalguardar*100)/100});
    data.push({name: 'envio', value: Math.round(costoEnvio*100)/100});
    data.push({name: 'type_doc', value: type_doc});

    console.log(data);

    $.ajax({
        url: 'purchase',
        data: data,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': _token }
    }).done(function( response ) {
        if(response.error)
            alert(response.message);
        else{
            alert('Orden de compra registrada correctamente.');
            //location.reload();
        }

    });
}

function addRow() {
    // Validate the product name
    var name = $('#product').val();
    if (!name) return;
    console.log(name);

    var proveedor = $('#proveedor').val();
    if (!proveedor) return;
    console.log(proveedor);

    // Validate quantity products
    var _quantity = $('#cantidad').val();
    var quantity = parseInt(_quantity);
    if (!quantity || quantity < 1)
        return;
    console.log(quantity);

    // Validate price products
    var _price = $('#precio').val();
    var price = parseFloat(_price);
    if (!price || price <= 0)
        return;
    console.log(price);

    var search = $('#product').val();
    console.log(search);

    $.ajax({
        url: '../producto/buscar/' + name
    })
        .done(function( data ) {
            if (data) {
                // Temporary variables
                selectedProduct = { id: data.id, name: name, price: price };
                console.log(selectedProduct);

                var names_array = [];
                console.log("Algo: ");
                console.log($('#table-items').find('tr>td.name').text());
                $('#table-items').find('tr > td.name').each(function (i, element) {
                    var name = $(element).text();
                    if(name != "")
                        names_array.push(name);
                });
                names_array.push(name);
                console.log("names_array");
                console.log(names_array);
                console.log(dontRepeat(names_array));
                if( dontRepeat(names_array) ) {
                    items.push({ nombre:selectedProduct.name, id: selectedProduct.id, quantity: quantity, subtotal: selectedProduct.price*quantity, originalprice: selectedProduct.price, type:'prod' });
                    renderTemplateItem(selectedProduct.id, selectedProduct.name, quantity, selectedProduct.price, selectedProduct.price*quantity);
                    updateTotal();

                } else {
                    alert('Existen nombres repetidos.');
                }
                console.log("items");
                console.log(items);

            } else {
                alert('Producto no existe');
            }
        });
}

function handleBlurProduct() {
    var $quantity = $('#cantidad');
    var name = $('#product').val();
    // When a product is selected
    // Quantity input is available
    $quantity.val('');
    $quantity.prop('readonly', false);
    setProductPrice(name);
}

function setProductPrice(product_name) {
    var currency = $('input[name=moneda]:checked').val(); // Selected currency
    $.getJSON('../producto/'+product_name+'/precio/'+currency, function (data) {
        $('#precio').val(data.price);
    });
}

function loadAutoCompleteItems(data) {
    $.ajax({
        url: '../items/producto/' + data.id

    })
        .done(function(datos){
            //console.log(JSON.parse(datos));
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

// function loadAutoCompleteLocations() {
//     console.log("Entre ubicacion");
//     $.ajax({
//         url: '../paquete/ubicaciones'
//
//     })
//         .done(function(datos){
//             console.log(datos);
//             $('[data-location]').typeahead(
//                 {
//                     hint: true,
//                     highlight: true,
//                     minLength: 1
//                 },
//                 {
//                     name: 'locations',
//                     source: substringMatcher(datos)
//                 }
//             );
//         });
// }

/*function addItemsSeries() {
    var series_array = [];
    $('#bodySeries').find('[name=serie]').each(function (i, element) {
        var series = $(element).val();
        if(series != "")
            series_array.push(series);
    });

    if( dontRepeat(series_array) ) {
        for ( var i=0; i<series_array.length; ++i) {
            items.push({ nombre:selectedProduct.name, id: selectedProduct.id, series: series_array[i], quantity: 1, price: selectedProduct.price, originalprice: selectedProduct.price, type:'prod' });
            renderTemplateItem(selectedProduct.id, selectedProduct.name, series_array[i], 1, selectedProduct.price, selectedProduct.price);
        }

        updateTotal();
        $('#modalSeries').modal('hide');

    } else {
        alert('Existen series repetidas.');
    }
    console.log(items);
}*/

function dontRepeat(names_array) {
    var names_total = names_array.slice(0);
    for (var i = 0; i<items.length; ++i)
        names_total.push(items[i].name);
    console.log(names_total);
    for (var i = 0; i<names_array.length; ++i) {
        for (var j = i+1; j<names_total.length; ++j)
            if (names_array[i] == names_total[j])
                return false;
    }
    return true;
}

function deleteItem() {
    var $tr = $(this).parents('tr');
    var id = $(this).data('delete');
    var precio = $(this).parent().prev().text();
    itemDelete(id, precio);
    $tr.remove();
}

function itemDelete(id, precio) {
    var price;
    for (var i = 0; i<items.length; ++i) {
        if (items[i].id == id) {
            items.splice(i, 1);
            price = precio*100/118;
            if (Math.round(igv*100)/100 != 0){
                igv -= precio-price;
            }
            updateTotal();
            return;
        }
    }
}

function updateTotal() {
    var costoEnvio = $('#costenvio').val();
    var total = 0;
    for (var i=0; i<items.length; ++i){
        total += items[i].subtotal;
    }

    if( $('#envioigv').is(':checked')) {
        total += Math.round((costoEnvio * 1.18) * 100) / 100;
    }else {
        total += Math.round(costoEnvio*100)/100
    }
    console.log(Math.round((costoEnvio*100/118)*100)/100);
    $('#igv').val(Math.round(igv*100)/100);
    $('#total').val(Math.round(total*100)/100);

}

function loadAutoCompleteProducts(data) {

    $('#product').typeahead(
        {
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            name: 'product',
            source: substringMatcher(data)
        }
    );

}

// Funciones relacionadas al template HTML5
function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
};

function renderTemplateItem(id, name, quantity, price, sub) {
    console.log("Entre a renderizar.");

    var clone = activateTemplate('#template-item');

    clone.querySelector("[data-name]").setAttribute('class', "name");
    clone.querySelector("[data-name]").innerHTML = name;
    clone.querySelector("[data-quantity]").innerHTML = quantity;
    clone.querySelector("[data-price]").innerHTML = price;
    clone.querySelector("[data-igvserie]").setAttribute('data-igvserie', id);
    clone.querySelector("[data-igvmonto]").innerHTML = 0;
    clone.querySelector("[data-sub]").innerHTML = sub;

    clone.querySelector("[data-delete]").setAttribute('data-delete', id);

    $('#table-items').append(clone);
}

function renderTemplateSeries() {

    var clone = activateTemplate('#template-series');

    $('#bodySeries').append(clone);
}
