var items;
var packages;
var dataset;

$(document).on('ready', function () {
    
    $('#btnBajar').on('click', mostrarDarBaja)

    var url = $('#searchProduct').data('url');
    var url_products = url+'/productos/disponibles';
    var url_packages = url+'/paquetes/disponibles';
    $('#divProduct').show();
    $('#divPackage').hide();
    $.ajax({
        url: url_products,
        method: 'GET'
    }).done(function(datos) {
        items = datos.products;
        loadAutoCompleteItems(items, 'items', '#searchProduct');
    });

    $.ajax({
        url: url_packages
    }).done(function(datos) {
        packages = datos.packages;
    });
    
    $("input[name=tipo]").change(function () {
        if( $('#product').is(':checked') )
        {
            $('#divProduct').show();
            $('#divPackage').hide();
            dataset = items;
            loadAutoCompleteItems(dataset, 'items', '#searchProduct');
        }

        if( $('#package').is(':checked') )
        {
            $('#divPackage').show();
            $('#divProduct').hide();
            dataset = packages;
            loadAutoCompleteItems(dataset, 'packages', '#searchPackage');
        }

    });

    $modalDarBaja = $('#modalDarBaja');
    dataset = "";
});

var $modalDarBaja;

function loadAutoCompleteItems(data, string, textbox) {
    console.log('La data');
    console.log(data);
    //console.log(string);
    $(textbox).typeahead(
        {
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            name: string,
            source: substringMatcher(data)
        }
    );

}

function mostrarDarBaja() {

    var tipo = $('input:radio[name=tipo]:checked').val();
    var codigoBaja = 'search'+tipo;
    var codigo = $('input[id='+codigoBaja+']').val();
    console.log(codigo);
    $modalDarBaja.find('[name="codigoDarBaja"]').val(codigo);
    $modalDarBaja.find('[name="tipo"]').val(tipo);
    $modalDarBaja.modal('show');
}
