$(document).on('ready', function () {
    $('#exportExcel').on('click', exportarExcel);
    $('#exportExcel2').on('click', exportarExcel2);
    $('#exportPDF').on('click', exportarPDF);
    $('#exportPDF2').on('click', exportarPDF2);
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!

    var yyyy = today.getFullYear();
    if(dd<10){dd='0'+dd}
    if(mm<10){mm='0'+mm}
    today = yyyy+'-'+mm+'-'+dd;

    $('#start').attr('value',today);
    $('#end').attr('value',today);
    $('#start2').attr('value',today);
    $('#end2').attr('value',today);
});

function exportarPDF() {
    var datestart = $('#start').val();
    var dateend = $('#end').val();
    if (datestart > dateend ) {
        alert('Orden de fechas incorrecta.');
        return;
    }

    var url = $('#exportPDF').data('url');
    var urlPDF = url+'/'+datestart+'/'+dateend;
    console.log(urlPDF);
    window.open(urlPDF, '_blank');
    //location.href = url+'/'+datestart+'/'+dateend;
}

function exportarPDF2() {
    var datestart = $('#start2').val();
    var dateend = $('#end2').val();
    var cliente = $('#clientes').val();
    console.log(cliente);
    if (datestart > dateend || cliente == "") {
        alert('Error en los datos enviados.');
        return;
    }
    console.log(cliente);

    var url = $('#exportPDF2').data('url');
    var urlPDF = url+'/'+datestart+'/'+dateend+'/'+cliente;
    console.log(urlPDF);
    window.open(urlPDF, '_blank');
    //location.href = url+'/'+datestart+'/'+dateend;
}

function exportarExcel() {
    var datestart = $('#start').val();
    var dateend = $('#end').val();
    if (datestart > dateend) {
        alert('Orden de fechas incorrecta.');
        return;
    }

    var url = $('#exportExcel').data('url');
    location.href = url+'/'+datestart+'/'+dateend;
}

function exportarExcel2() {
    var datestart = $('#start2').val();
    var dateend = $('#end2').val();
    var cliente = $('#clientes').val();
    if (datestart > dateend || cliente == "") {
        alert('Error en los datos ingresados.');
        return;
    }

    var url = $('#exportExcel').data('url');
    location.href = url+'/'+datestart+'/'+dateend+'/'+cliente;
}