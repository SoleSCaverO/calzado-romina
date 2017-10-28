$(document).on('ready',principal);

let order;

function principal() {
    search();
    $('#search').on('input',search);
    $('#orders').on('click', 'tr', showDetails);
    $('#filter').on('click', filter);
}

function search() {
    order = $('#search').val();
    if( order.length == 0 )
        order = 'z';

    const url = '../../ingreso/listar/orden_compra/'+order;
    $.ajax({
            url: url,
            method: 'GET'
        })
        .done(function( orders ) {
            $('#details').html('');
            $('#orders').html('');
            $.each(orders,function(key,order){
                const document = (order.type_doc=='F')?'Factura':'Boleta';
                let create_entry_option = '';
                if (order.attended) {
                    create_entry_option = '<button type="button" class="btn btn-success btn-sm" title="Orden ya atendida"><span class="glyphicon glyphicon-check"></span></button>';
                } else {
                    create_entry_option = '<a href="almacen/'+order.id+'" class="btn btn-primary btn-sm" title="Ingreso a almacén"><span class="glyphicon glyphicon-log-in"></span></a>';
                }
                const toAppend =
                    '<tr data-order="'+order.id+'">' +
                        '<td>'+order.provider.name+'</td>' +
                        '<td>'+order.currency+'</td>' +
                        '<td>'+order.igv+'</td>' +
                        '<td>'+order.total+'</td>' +
                        '<td>'+order.shipping+'</td>' +
                        '<td>'+order.invoice+'</td>' +
                        '<td>'+document+'</td>' +
                        '<td>'+order.invoice_date+'</td>' +
                        '<td>'+create_entry_option+'</td>' +
                    '</tr>';
                $('#orders').append(toAppend);
            });
            $('.pagination').html('');
            paginate();
        });
}

function showDetails() {
    const order = $(this).data('order');

    const url = '../../ingreso/listar/orden_compra/detalles/'+order;
    $.ajax({
            url: url,
            method: 'GET'
        })
        .done(function( details ) {
            $('#details').html('');
            $.each(details,function(key,detail){
                const toAppend =
                    '<tr>' +
                        '<td>'+detail.product.name+'</td>' +
                        '<td>'+detail.quantity+'</td>' +
                        '<td>'+detail.originalprice+'</td>' +
                        '<td>'+detail.igv+'</td>' +
                        '<td>'+detail.subtotal+'</td>' +
                    '</tr>';
                $('#details').append(toAppend);
            });
        });
}

function filter()
{
    const start = $('#start').val();
    const end = $('#end').val();

    const _start = new Date(start);
    const _end = new Date(end);

    if( _start.getTime() > _end.getTime()  ) {
        alert('Rango de fechas inválido');
        return;
    }

    const url = '../../ingreso/listar/orden_compra/fechas/'+start+'/'+end;
    $.ajax({
            url: url,
            method: 'GET'
        })
        .done(function( orders ) {
            $('#details').html('');
            $('#orders').html('');
            $.each(orders,function(key,order){
                const document = (order.type_doc=='F')?'Factura':'Boleta';
                let create_entry_option = '';
                if (order.attended) {
                    create_entry_option = '<button type="button" class="btn btn-success btn-sm" title="Orden ya atendida"><span class="glyphicon glyphicon-check"></span></button>';
                } else {
                    create_entry_option = '<a href="almacen/'+order.id+'" class="btn btn-primary btn-sm" title="Ingreso a almacén"><span class="glyphicon glyphicon-log-in"></span></a>';
                }
                let toAppend =
                    '<tr data-order="'+order.id+'">' +
                    '<td>'+order.provider.name+'</td>' +
                    '<td>'+order.currency+'</td>' +
                    '<td>'+order.igv+'</td>' +
                    '<td>'+order.total+'</td>' +
                    '<td>'+order.shipping+'</td>' +
                    '<td>'+order.invoice+'</td>' +
                    '<td>'+document+'</td>' +
                    '<td>'+order.invoice_date+'</td>' +
                    '<td>'+create_entry_option+'</td>' +
                    '</tr>';
                $('#orders').append(toAppend);
            });
            $('.pagination').html('');
            paginate();
        });
}
