// Global variables
let items = [];
let purchase_order_id;

// Path to search one product by name
let product_search_url;
// Available locations
let locations_url;
// Post to register entry
let new_entry_url;

// Variables to render
let $bodySeries;
let $lastBtnAddClicked;

// Temporary variables
let selectedProduct;

$(document).on('ready', function () {
    // Get absolute paths from HTML
    purchase_order_id = location.href.substr(location.href.lastIndexOf('/')+1);
    product_search_url = $('meta[name="product_search_url"]').attr('content');
    locations_url = $('meta[name="package_locations_url"]').attr('content');
    new_entry_url = $('meta[name="new_entry_url"]').attr('content');

    // Get references
    $bodySeries = $('#bodySeries');

    $(document).on('click', '[data-add]', addItem);
    $(document).on('click', '[data-delete]', deleteItem);
    $('#btnAccept').on('click', addItemsSeries);
    $('#form').on('submit', registerEntry);
});

function registerEntry() {
    event.preventDefault();

    const _token = $(this).find('[name=_token]');
    let data = $(this).serializeArray();
    data.push({ name: 'items', value: JSON.stringify(items) });
    data.push({ name: 'purchase_order_id', value: purchase_order_id });
    $.ajax({
        url: new_entry_url,
        data: data,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': _token }
    })
    .done(function( response ) {
        if(response.error)
            alert(response.message);
        else{
            alert('Compra registrada correctamente.');
            location.reload();
        }
    });
}

function addItem() {
    $lastBtnAddClicked = $(this);

    const $tr = $(this).parents('tr');
    let $td = $tr.find('td').first();

    // Product name
    const product_name = $td.text();
    if (!product_name) return;

    // Quantity
    $td = $td.next();
    const _quantity = $td.text();
    const quantity = parseInt(_quantity);
    if (!quantity || quantity < 1)
        return;

    // Price
    $td = $td.next();
    let _price = $td.text();
    let price = parseFloat(_price);
    if (!price || price <= 0)
        return;

    // alert(product_name);
    // alert(quantity);
    // alert(price);

    // Validate product name in backend
    let url_path = product_search_url;
    url_path = url_path.replace('{name}', product_name);
    $.ajax({
        url: url_path
    }).done(function(data) {
        if (data) {
            $bodySeries.html('');
            for (let i = 0; i<quantity; ++i) {
                renderTemplateSeries();
            }

            // Temporary variables
            selectedProduct = { id: data.id, name: product_name, price: price };

            loadAutoCompleteLocations();
            $('#modalSeries').modal('show');
        } else {
            alert('Producto no encontrado en la base de datos');
        }
    });
}

function loadAutoCompleteLocations() {
    $.ajax({
        url: locations_url
    }).done(function (location_data) {
        console.log(location_data);
        $('[data-location]').typeahead(
            {
                hint: true,
                highlight: true,
                minLength: 1
            },
            {
                name: 'locations',
                source: substringMatcher(location_data)
            }
        );
    });
}

function addItemsSeries() {
    let locations_array = [];
    $bodySeries.find('[data-location]').each(function (i, element) {
        let locations = $(element).val();
        if(locations != "")
            locations_array.push(locations);
    });
    
    let series_array = [];
    $bodySeries.find('[data-serie]').each(function (i, element) {
        let series = $(element).val();
        if(series != "")
            series_array.push(series);
    });

    if( dontRepeat(series_array) ) {
        for (let i=0; i<series_array.length; ++i) {
            items.push({ id: selectedProduct.id, series: series_array[i], location: locations_array[i], quantity: 1, price: selectedProduct.price });
            renderTemplateItem(selectedProduct.id, selectedProduct.name, series_array[i], locations_array[i], 1, selectedProduct.price, selectedProduct.price);
        }

        updateTotal();
        $('#modalSeries').modal('hide');
        $lastBtnAddClicked.prop('disabled', true);
    } else {
        alert('Existen series repetidas.');
    }
}

function dontRepeat(series_array) {
    let series_total = series_array.slice(0);
    for (let i = 0; i<items.length; ++i)
        series_total.push(items[i].series);
    console.log(series_total);
    for (let i = 0; i<series_array.length; ++i) {
        for (let j = i+1; j<series_total.length; ++j)
            if (series_array[i] == series_total[j])
                return false;
    }
    return true;
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
            updateTotal();
            return;
        }
    }
}

function updateTotal() {
    var total = 0;
    for (var i=0; i<items.length; ++i)
        total += items[i].price * items[i].quantity;
    $('#total').val(total);
}


// Render HTML5 template functions
function activateTemplate(id) {
    let t = document.querySelector(id);
    return document.importNode(t.content, true);
}

function renderTemplateItem(id, name, series, location, quantity, price, sub) {

    let clone = activateTemplate('#template-item');
    clone.querySelector("[data-name]").innerHTML = name;
    clone.querySelector("[data-series]").innerHTML = series;
    clone.querySelector("[data-ubication]").innerHTML = location;
    clone.querySelector("[data-quantity]").innerHTML = quantity;
    clone.querySelector("[data-price]").innerHTML = price;
    clone.querySelector("[data-sub]").innerHTML = sub;

    clone.querySelector("[data-delete]").setAttribute('data-delete', id);

    $('#table-items').append(clone);
}

function renderTemplateSeries() {
    const clone = activateTemplate('#template-series');
    $bodySeries.append(clone);
}
