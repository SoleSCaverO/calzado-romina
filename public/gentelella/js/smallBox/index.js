$(document).on('ready', principal);

//First section



//Second section
var $btnExcel;
var $btnPDF;
var $selectYear;
var $selectMonth;

function principal()
{
    $('#form').on('submit', registerConcept);

    // Export buttons
    $btnExcel = $('#btnExcel');
    $btnPDF = $('#btnPDF');
    // Export params
    $selectYear = $('#selectYear');
    $selectMonth = $('#selectMonth');
    // Export click events
    $btnExcel.on('click', exportExcelOrPdf);
    $btnPDF.on('click', exportExcelOrPdf);
}

function exportExcelOrPdf()
{
    event.preventDefault();

    var url = $(this).attr('href');
    url += '?year=' + $selectYear.val() + '&month=' + $selectMonth.val();

    location.href = url;
}

function registerConcept()
{
    event.preventDefault();
    var _token = $(this).find('[name=_token]');
    var data = new FormData(this);
    console.log(data);
    $.ajax({
        url: 'cajachica/save',
        data: new FormData(this),
        dataType: "JSON",
        processData: false,
        contentType: false,
        method: 'POST'
    })
        .done(function( response ) {
            if(response.error)
                alert(response.message);
            else{
                alert(response.message);
                setTimeout(function(){
                    location.reload();
                }, 2000);
            }
        });
}

function renderTemplateItem(id, name, series, location, quantity, price, sub) {

    var clone = activateTemplate('#template-item');

    clone.querySelector("[data-name]").innerHTML = name;
    clone.querySelector("[data-series]").innerHTML = series;
    clone.querySelector("[data-ubication]").innerHTML = location;
    clone.querySelector("[data-quantity]").innerHTML = quantity;
    clone.querySelector("[data-price]").innerHTML = price;
    clone.querySelector("[data-sub]").innerHTML = sub;

    clone.querySelector("[data-delete]").setAttribute('data-delete', id);

    $('#table-items').append(clone);
}