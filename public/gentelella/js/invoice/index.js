$(document).on('ready',principal);

var $modalQuitar;
var invoices = [];

function principal()
{
    populate_table_invoices();
    $modalQuitar = $('#modalQuitar');
    $('[data-invoice]').on('click',modalQuitar);
    $('#accept').on('click',quitarElemento);
    $('#ir').on('click',ir);
    $('#igv').on('click',igv);

    //Filtering data
    paginate();

    $('#filter_month').on('click',invoicesMonth);
    $('#filter_date').on('click',invoicesDate);
}

function populate_table_invoices(){
    var table_invoices = document.getElementById('invoices').children;

    for (var i=0; i<table_invoices.length; i++)
        invoices.push(table_invoices[i].getAttribute('data-id'));
}

function modalQuitar(){
    var invoice = $(this).data('invoice');
    $modalQuitar.find('[name=nombreQuitar]').val(invoice);
    $modalQuitar.modal('show');
}

function quitarElemento(){
    event.preventDefault();
    var invoice = $modalQuitar.find('[name=nombreQuitar]').val();
    $modalQuitar.modal('hide');

    var table_invoices = document.getElementById('invoices').children;

    for (var i=0; i<table_invoices.length; i++) {
        if (table_invoices[i].getAttribute('data-id') == invoice) {
            table_invoices[i].remove();
            delete_element(invoices, invoice);
        }
    }
}

function delete_element(  array, element ){
    var pos = 0;
    for( var i=0; i<array.length;i++ )
        if( array[i] == element )
            pos = i;

    array.splice(pos,1);
}

function ir(){
    event.preventDefault();
    var _token = $('#_token').val();
    var formData = new FormData();
    formData.append( 'ir',JSON.stringify(invoices) );

    $.ajax({
            url: 'listar-facturas-declarar-ir',
            data: formData,
            dataType: "JSON",
            processData: false,
            contentType: false,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': _token }
        })
        .done(function( response ) {
            if(response.error)
                alert(response.message);
            else{
                alert(response.message);
                setTimeout(function(){
                    location.reload();
                }, 500);
            }
        });
}
function igv(){
    event.preventDefault();

    var _token = $('#_token').val();
    var formData = new FormData();
    formData.append( 'ir',JSON.stringify(invoices) );

    $.ajax({
            url: 'listar-facturas-declarar-igv',
            data: formData,
            dataType: "JSON",
            processData: false,
            contentType: false,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': _token }
        })
        .done(function( response ) {
            if(response.error)
                alert(response.message);
            else{
                alert(response.message);
                setTimeout(function(){
                    location.reload();
                }, 500);
            }
        });
}

//Filtering data
function invoicesMonth()
{
    var month = $('#month').val();

    $.ajax({
        url: '../public/listar-facturas-declarar/'+month,
        method: 'GET'
    }).done(function (data) {

        if( data.error )
            alert(data.message);
        else
        {
            invoices = [];
            $('#invoices').html('');
            $.each(data,function(key,value){
                invoices.push(value.invoice);
                var type = (value.type_doc=='F')?'Factura':'Boleta';
                var buttons = '';
                if(  value.income_tax_date== null && value.general_sales_tax_date==null )
                    buttons ='<button type="button" class="btn btn-danger" data-invoice="'+value.invoice+'">'+
                        '<i class="fa fa-trash"></i> Quitar'+
                        '</button>'
                else
                {
                    if( value.income_tax_date != null )
                        buttons = '<button class="btn btn-primary ">IR</button>';
                    if( value.general_sales_tax_date != null )
                        buttons = '<button class="btn btn-success ">SUNAT</button>';
                }

                $('#invoices').append(
                    '<tr data-id="'+value.invoice+'">'+
                    '<td>'+value.invoice+'</td>' +
                    '<td>'+type+'</td>' +
                    '<td>'+value.invoice_date+'</td>' +
                    '<td>'+buttons+'</td>' +
                    '</tr>');
            });
        }
        $('[data-invoice]').on('click',modalQuitar);
    });
}

function invoicesDate()
{
    var inicio = $('#inicio').val();
    var fin = $('#fin').val();
    var _inicio = new Date(inicio);
    var _fin = new Date(fin);

    if( _inicio.getTime()> _fin.getTime() ){
        alert('La fecha de inicio no debe ser mayor a la fecha final');
        return;
    }

    $.ajax({
        url: '../public/listar-facturas-declarar/'+inicio+'/'+fin,
        method: 'GET'
    }).done(function (data) {
        if( data.error )
            alert(data.message);
        else
        {
            invoices = [];
            $('#invoices').html('');
            $.each(data,function(key,value){
                invoices.push(value.invoice);
                var type = (value.type_doc=='F')?'Factura':'Boleta';
                var buttons = '';
                if(  value.income_tax_date== null && value.general_sales_tax_date==null )
                    buttons ='<button type="button" class="btn btn-danger" data-invoice="'+value.invoice+'">'+
                        '<i class="fa fa-trash"></i> Quitar'+
                        '</button>'
                else
                {
                    if( value.income_tax_date != null )
                        buttons = '<button class="btn btn-primary ">IR</button>';
                    if( value.general_sales_tax_date != null )
                        buttons = '<button class="btn btn-success ">SUNAT</button>';
                }

                $('#invoices').append(
                    '<tr data-id="'+value.invoice+'">'+
                    '<td>'+value.invoice+'</td>' +
                    '<td>'+type+'</td>' +
                    '<td>'+value.invoice_date+'</td>' +
                    '<td>'+buttons+'</td>' +
                    '</tr>');
            });
        }
        $('[data-invoice]').on('click',modalQuitar);
    });
}
