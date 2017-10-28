var products;
var packages;
var dataset;
var items = [];
var igv = 0;

// Temporary variables
var selectedProduct;

var checkeado = false;

$(document).on('ready', function () {
    
    $('#product').on('blur', handleBlurProduct);
    $('#btnAdd').on('click', addRow);
    $(document).on('click', '[data-delete]', deleteItem);
    $(document).on('click', '[data-look]', lookDetails);
    $('#btnAccept').on('click', addItemsSeries);
    $('#form').on('submit', registerRental);
    $(document).on('click', '[data-igvserie]', updateSubtotal);
    $('#envioigv').on('click', envioIGV);

    var url_products ='../productos/names';
    var url_packages = '../paquetes/disponibles';

    $.ajax({
        url: url_products,
        method: 'GET'
    }).done(function(datos) {

        products = datos.products;
        if( Object.prototype.toString.call(products) === '[object Array]'
        && Object.prototype.toString.call(packages) === '[object Array]' )
        {
            dataset = products.concat(packages);
            loadAutoCompleteProducts(dataset);
        }

    });

    $.ajax({
        url: url_packages
    }).done(function(datos) {
        packages = datos.packages;
        if( Object.prototype.toString.call(products) === '[object Array]'
            && Object.prototype.toString.call(packages) === '[object Array]' )
        {
            dataset = products.concat(packages);
            loadAutoCompleteProducts(dataset);
        }
    });

});

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
    var serie = $(this).data('igvserie');
    var price;
    var precio = $(this).parent().next().next().text();
    console.log(precio);
    if( $(this).is(':checked'))
    {
        // precio = $(this).parent().prev().text();
        price = Math.round((precio*1.18)*100)/100;
        $(this).parent().next().html( Math.round((precio*0.18)*100)/100 );
        $(this).parent().next().next().html(price);
        console.log("Precio cambiado"+price);
        for (var i = 0; i<items.length; ++i) {
            console.log('Entre');
            if (items[i].series == serie) {
                console.log('Entre if');
                items[i].price = price;
                console.log("Item "+items[i]);
            }
        }
        igv += (Math.round(price*100)/100)-(Math.round(precio*100)/100);
        //igv += (price)
        console.log("IGV: "+igv);
        updateTotal();
    }else{
        price = Math.round((precio*100/118)*100)/100;
        $(this).parent().next().html(Math.round(0)/100);
        $(this).parent().next().next().html(price);
        for (var i = 0; i<items.length; ++i)
            if (items[i].series == serie)
                items[i].price = price;
        igv -= (Math.round(precio*100)/100)-(Math.round(price*100)/100);
        console.log("IGV: "+igv);
        updateTotal();
    }
}

function prevMonth(date_string) {
    var slash = date_string.indexOf('-'); // first slash
    var input_month = date_string.substr(slash+1);
    slash = input_month.indexOf('-'); // last slash
    input_month = input_month.substr(0, slash);

    var current_month = new Date().getMonth() +1; // January is 0 for Date objects

    return parseInt(input_month) < current_month;
}

function registerRental() {
    event.preventDefault();
    var totalguardar = $('#total').val();
    var costoEnvio = $('#costenvio').val();
    var totalIgv = $('#igv').val();
    var type_doc = $('input:radio[name=documento]:checked').val();
    console.log(costoEnvio);
    
    // Validate invoice number
    var invoice = $('#invoice').val();
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
    var url_alquiler = '../alquiler/registrar';
    data.push({name: 'items', value: JSON.stringify(items)});
    data.push({name: 'igv', value: Math.round(totalIgv*100)/100});
    data.push({name: 'total', value: Math.round(totalguardar*100)/100});
    data.push({name: 'envio', value: Math.round(costoEnvio*100)/100});
    data.push({name: 'type_doc', value: type_doc});
    console.log(data);
    $.ajax({
        url: url_alquiler,
        data: data, 
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': _token }

    })
        .done(function( response ) {
            if(response.error)
                alert(response.message);
            else{
                alert('Alquiler registrado correctamente.');
                location.reload();
            }

        });
}

function lookDetails() {
    var id = $(this).data('look');
    $.ajax({
        url: '../paquete/detalles/'+id,
        method: 'GET'
    }).done(function(datos) {
        $('#table-details').html('');
        for (var i = 0; i<datos.length; ++i)
        {
            renderTemplateDetails(datos[i].product.name, datos[i].series, datos[i].product.price);
        }

        $('#modalDetails').modal('show');
    });
}

function addRow() {
    // Validate the product name
    var name = $('#product').val();
    if (!name) return;

    var customer = $('#cliente').val();
    if (!customer) return;

    // Validate quantity products
    var _quantity = $('#cantidad').val();
    var quantity = parseInt(_quantity);
    if (!quantity || quantity < 1)
        return;

    // Validate price products
    var _price = $('#precio').val();
    var price = parseFloat(_price);
    if (!price || price <= 0)
        return;

    var search = $('#product').val();

    if ( packages.indexOf(search) != -1 )
    {
        $.ajax({
                url: '../paquete/buscar/' + name
            })
            .done(function( data ) {
                if (data) {
                    items.push({id: data.id, series: data.code, quantity: 1, price:price, originalprice:price, type:'paq'})
                    renderTemplatePackage(data.id, data.code, 1, price, price);
                    updateTotal();
                } else {
                    alert('Paquete no existe');
                }
            });
    }else {
        $.ajax({
                url: '../producto/buscar/' + name
            })
            .done(function( data ) {
                if (data) {
                    // if require series

                    $('#bodySeries').html('');
                    for (var i = 0; i<quantity; ++i) {
                        renderTemplateSeries();
                    }

                    loadAutoCompleteItems(data);

                    // Temporary variables
                    selectedProduct = { id: data.id, name: name, price: price };

                    $('#modalSeries').modal('show');

                } else {
                    alert('Producto no existe');
                }
            });
    }
    
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

function handleBlurProduct() {
    var $quantity = $('#cantidad');
    var name = $('#product').val();

    // When a package is selected
    if ( packages.indexOf(name) > -1 )
    {   // Quantity always is 1
        $quantity.val(1);
        $quantity.prop('readonly', true);
        setPackagePrice(name);
    } else { // When a product is selected
        // Quantity input is available
        $quantity.val('');
        $quantity.prop('readonly', false);
        setProductPrice(name);
    }
    /*$('#cantidad').val("");
    $('#cantidad').prop('readonly', false);
    var search = $('#product').val();

    if ( packages.indexOf(search) != -1 )
    {
        $('#cantidad').val(1);
        $('#cantidad').prop('readonly', true);
    }*/

}

function setProductPrice(product_name) {
    $.getJSON('../producto/buscar/'+product_name, function (data) {
        $('#precio').val( data.price );
    });
}

function setPackagePrice(package_name) {
    $.getJSON('../paquete/buscar/'+package_name, function (data) {
        $('#precio').val( data.price );
    });
}

function addItemsSeries() {
    var series_array = [];
    $('#bodySeries').find('input').each(function (i, element) {
        var series = $(element).val();
        if(series != "")
            series_array.push(series);
    });

    if( dontRepeat(series_array) ) {
        for ( var i=0; i<series_array.length; ++i) {
            items.push({ id: selectedProduct.id, series: series_array[i], quantity: 1, price: selectedProduct.price, originalprice:selectedProduct.price, type:'prod' });
            renderTemplateItem(selectedProduct.id, selectedProduct.name, series_array[i], 1, selectedProduct.price, selectedProduct.price);
        }

        updateTotal();
        $('#modalSeries').modal('hide');

    } else {
        alert('Existen series repetidas.');
    }
}

function dontRepeat(series_array) {

    var series_total = series_array.slice(0);
    for (var i = 0; i<items.length; ++i)
        series_total.push(items[i].series);

    for (var i = 0; i<series_array.length; ++i) {
        for (var j = i+1; j<series_total.length; ++j)
            if (series_array[i] == series_total[j])
                return false;
    }
    return true;
}

function deleteItem() {
    var $tr = $(this).parents('tr');
    var id = $(this).data('delete');
    var precio = $(this).parent().prev().text();
    var series = $tr.find('[data-series]').text();
    itemDelete(id, series, precio);
    $tr.remove();
}

function itemDelete(id, series, precio) {
    var price;
    for (var i = 0; i<items.length; ++i) {
        if (items[i].id == id && items[i].series == series) {
            items.splice(i, 1);
            price = precio*100/118;
            igv -= precio-price;
            updateTotal();
            return;
        }
    }
}

function updateTotal() {
    var costoEnvio = $('#costenvio').val();
    var total = 0;
    for (var i=0; i<items.length; ++i)
        total += items[i].price * items[i].quantity;
    if( $('#envioigv').is(':checked')) {
        total += Math.round((costoEnvio * 1.18) * 100) / 100;
    }else {
        total += Math.round(costoEnvio*100)/100
    }
    console.log(Math.round((costoEnvio*100/118)*100)/100);
    $('#igv').val(Math.round(igv*100)/100);
    $('#total').val(Math.round(total*100)/100);
}

// Funciones relacionadas al template HTML5
function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

function renderTemplatePackage(id, code, quantity, price, sub) {
    var clone = activateTemplate('#template-package');

    clone.querySelector("[data-name]").innerHTML = 'Paquete';
    clone.querySelector("[data-series]").innerHTML = code;
    clone.querySelector("[data-quantity]").innerHTML = quantity;
    clone.querySelector("[data-price]").innerHTML = price;
    clone.querySelector("[data-igvserie]").setAttribute('data-igvserie', code);
    clone.querySelector("[data-sub]").innerHTML = sub;
    clone.querySelector("[data-look]").setAttribute('data-look', id);
    clone.querySelector("[data-delete]").setAttribute('data-delete', id);

    $('#table-items').append(clone);
}

function renderTemplateDetails(name, series, price) {
    var clone = activateTemplate('#template-details');

    clone.querySelector("[data-name]").innerHTML = name;
    clone.querySelector("[data-series]").innerHTML = series;
    clone.querySelector("[data-price]").innerHTML = price;

    $('#table-details').append(clone);
}

function renderTemplateItem(id, name, series, quantity, price, sub) {
    var clone = activateTemplate('#template-item');

    clone.querySelector("[data-name]").innerHTML = name;
    clone.querySelector("[data-series]").innerHTML = series;
    clone.querySelector("[data-quantity]").innerHTML = quantity;
    clone.querySelector("[data-price]").innerHTML = price;
    clone.querySelector("[data-igvserie]").setAttribute('data-igvserie', series);
    clone.querySelector("[data-sub]").innerHTML = sub;

    clone.querySelector("[data-delete]").setAttribute('data-delete', id);

    $('#table-items').append(clone);
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

function renderTemplateSeries() {

    var clone = activateTemplate('#template-series');

    $('#bodySeries').append(clone);
}