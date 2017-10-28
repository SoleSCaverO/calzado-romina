$(document).on('ready',principal);

function principal()
{
    paginate();

    $('#filter_month').on('click',invoicesMonth);
    $('#filter_date').on('click',invoicesDate);
}

//Filtering data
function invoicesMonth()
{
    var month = $('#month').val();

    $.ajax({
        url: '../public/listar-facturas-declarar-historial/'+month,
        method: 'GET'
    }).done(function (data) {
        if( data.error )
            alert(data.message);
        else
        {
            $('#invoices').html('');
            $.each(data,function(key,value){
                var type = (value.type_doc=='F')?'Factura':'Boleta';

                $('#invoices').append(
                    '<tr>'+
                        '<td>'+value.invoice+'</td>' +
                        '<td>'+type+'</td>' +
                        '<td>'+value.invoice_date+'</td>' +
                        '<td>'+value.income_tax_date+'</td>' +
                        '<td>'+value.general_sales_tax_date+'</td>' +
                    '</tr>');
            });
        }
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
        url: '../public/listar-facturas-declarar-historial/'+inicio+'/'+fin,
        method: 'GET'
    }).done(function (data) {
        if( data.error )
            alert(data.message);
        else
        {
            $('#invoices').html('');
            $.each(data,function(key,value){
                var type = (value.type_doc=='F')?'Factura':'Boleta';

                $('#invoices').append(
                    '<tr>'+
                        '<td>'+value.invoice+'</td>' +
                        '<td>'+type+'</td>' +
                        '<td>'+value.invoice_date+'</td>' +
                        '<td>'+value.income_tax_date+'</td>' +
                        '<td>'+value.general_sales_tax_date+'</td>' +
                    '</tr>');
            });
        }
    });
}
