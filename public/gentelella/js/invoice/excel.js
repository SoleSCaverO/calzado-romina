$(document).on('ready',principal);

var url;
function principal()
{
    $('#formYear').on('submit',invoices_year);
    $('#formMonth').on('submit',invoices_month);
    $('#formDate').on('submit',invoices_date);
}

function invoices_year()
{
    event.preventDefault();
    url = $(this).attr("action");

    var formData = new FormData(this);
    var year = formData.get('year');
    var pay  = formData.get('pay_year');
    var wait = formData.get('wait_year');

    if(pay == null && wait ==null ){
        alert('Seleccione el estado de los documentos');
        return;
    }

    if( pay == null )
        pay=-1;
    if( wait == null )
        wait=-1;

    $.ajax({
        url: '../public/facturas-annio/'+year+'/'+pay+'/'+wait,
        method: 'GET'
    }).done(function (data) {

        if( data.error )
            alert(data.message);
        else
            location.href = '../public/facturas-annio-excel/'+year+'/'+pay+'/'+wait;
    });
}

function invoices_month()
{
    event.preventDefault();
    url = $(this).attr("action");

    var formData = new FormData(this);
    var month = formData.get('month');
    var pay  = formData.get('pay_month');
    var wait = formData.get('wait_month');

    if(pay == null && wait ==null ){
        alert('Seleccione el estado de los documentos');
        return;
    }

    if( pay == null )
        pay=-1;
    if( wait == null )
        wait=-1;

    $.ajax({
        url: '../public/facturas-mes/'+month+'/'+pay+'/'+wait,
        method: 'GET'
    }).done(function (data) {

        if( data.error )
            alert(data.message);
        else
            location.href = '../public/facturas-mes-excel/'+month+'/'+pay+'/'+wait;
    });
}

function invoices_date()
{
    event.preventDefault();
    url = $(this).attr("action");

    var formData = new FormData(this);
    var start = formData.get('start');
    var end = formData.get('end');
    var pay  = formData.get('pay_date');
    var wait = formData.get('wait_date');

    var _start = new Date(start); var _end = new Date(end);

    if( _start.getTime() > _end.getTime()   ) {
        alert('La fecha de inicio no debe ser mayor a la fecha final');
        return;
    }

    if(pay == null && wait ==null ){
        alert('Seleccione el estado de los documentos');
        return;
    }

    if( pay == null )
        pay=-1;
    if( wait == null )
        wait=-1;

    $.ajax({
        url: '../public/facturas-fecha/'+start+'/'+end+'/'+pay+'/'+wait,
        method: 'GET'
    }).done(function (data) {

        if( data.error )
            alert(data.message);
        else
            location.href = '../public/facturas-fecha-excel/'+start+'/'+end+'/'+pay+'/'+wait;
    });

}
