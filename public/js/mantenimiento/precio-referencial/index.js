$(document).on('ready',main);

function main() {
    custom_datatable();
    var $modelo_id    = $('#modelo_id');
    var $input_select = $('.input-block-level');
    var $dynamic_table_modelos_filter = $('#dynamic_table_modelos_filter');
    var $form = $('#form_prices');
    var $body = $('body');

    $dynamic_table_modelos_filter.addClass('hide_it');
    start_selectpicker($modelo_id);
    $input_select.on('change',modelos);
    $modelo_id.on('change',select_modelo);
    $body.on('click','[data-code]',modal_price);
    $body.on('click','[data-modal_close]',modal_close);
    $form.on('submit',submit_form);
}

function custom_datatable() {
    var $dynamic_table_modelos = $('#dynamic_table_modelos');
    $dynamic_table_modelos.DataTable(
        {
            'dom':"<'col-sm-6'f>"+
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            'pageLength': 8,
            "language": {
                "emptyTable": "No existen datos",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                "infoFiltered":   "(Filtrando de _MAX_ registros totales)",
                "zeroRecords": "No existen datos",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            }
        }
    );
}

function modelos() {
    var $modelo = $(this).val();
    $('#dynamic_table_modelos_filter label input[type=search]').val($modelo).trigger($.Event("keyup", { keyCode: 13 }));
}

function select_modelo() {
    var $modelo = $('#modelo_id option:selected').text();
    $('#dynamic_table_modelos_filter label input[type=search]').val($modelo).trigger($.Event("keyup", { keyCode: 13 }));
}

function modal_close() {
    $modal = $('#referentialPriceModal');
    $modal.find('[name=price]').val('');
    $modal.modal('hide');
}
function modal_price() {
    $modal = $('#referentialPriceModal');
    $id = $(this).attr('data-code');
    $price = $(this).attr('data-price');

    $modal.find('[name=id]').val($id);
    $modal.find('[name=price]').val($price);
    $modal.modal();
}

function submit_form(event) {
    event.preventDefault();
    $url = $(this).attr('action');
    $type = $(this).attr('method');
    $id = $('#id').val();
    $price = $('#price').val();

    $data = {
        'id' : $id,
        'price' : $price
    };

    if( !$price ){
        showmessage('Ingrese el precio referencial',0);
        return  false;
    }

    $.ajax({
        url  : $url,
        type : $type,
        data : $data,
        headers :{
            'X-CSRF-TOKEN':$('#_token').val()
        }
    }).done(function (data) {
        if(data.success){
            showmessage(data.message,1);
            setTimeout(function () {
                location.reload();
            },1000);
        }
    });
}