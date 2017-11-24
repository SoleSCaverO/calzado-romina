$(document).on('ready',main);

var $modal_precios;
var $green_button;

function main() {
    custom_datatable();
    var $modelo_id    = $('#modelo_id');
    var $input_select = $('.input-block-level');
    var $dynamic_table_modelos_filter = $('#dynamic_table_modelos_filter');
    var $form_precios = $('#form_precios');
    var $body = $('body');

    $modal_precios = $('#modal_precios');
    $dynamic_table_modelos_filter.addClass('hide_it');
    start_selectpicker($modelo_id);
    $input_select.on('change',modelos);
    $modelo_id.on('change',select_modelo);
    $body.on('click','[data-modelo_id]',modal_precios);
    $body.on('click','[data-modal_close]',modal_close);
    $form_precios.on('submit',form_precios)
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

function modal_precios() {
    var $modelo_id      = $(this).data('modelo_id');
    var $modelo_nombre  = $(this).data('modelo_nombre');
    var $subarea_id     = $(this).data('subarea_id');
    var $subarea_nombre = $(this).data('subarea_nombre');
    var $precio_checked = $(this).data('precio_checked');
    var $precio_pieza   = $(this).data('precio_pieza');

    var $perfilado = $('#perfilado').val();
    var $input_piezas = $('#input_piezas');
    var $modal_precios_title  = $('#modal_precios_title');
    var $precios = $('#precios'+$modelo_id+$subarea_id).val();

    $precios = JSON.parse($precios);
    $modal_precios_title.html( 'MODELO '+$modelo_nombre+' - '+$subarea_nombre );

    if( $perfilado.length > 0 ){
        var $to_append_ ;
        $input_piezas.html('');
        $to_append_ =
            '<div class="col-md-6 col-md-offset-1">' +
            '<label>Número de piezas</label>'+
            '<input type="number" min="1" name="numero_piezas" value="'+ $precio_pieza +'" class="form-control" required>' +
            '</div>';

        $input_piezas.append($to_append_);
        $green_button = $precio_checked.length;
        $modal_precios.find('[name=pivot]').val($precio_checked);
    }else {
        var $to_append = '<div class="row"><div class="col-md-offset-1"><label>Seleccione una descripción</label></div></div>';
        var $checkboxes = $('#checkboxes_precios');
        $checkboxes.html('');
        $.each($precios, function (k, v) {
            $to_append +=
                '<div class="row">' +
                '<div class="col-md-1 col-md-offset-1">' +
                '<input type="radio"  name="ddc_id" value="' + v.ddatcNombre + '" class="form-control"' + ($precio_checked == v.ddatcNombre ? ' checked' : '') + '>' +
                '</div>' +
                '<div class="col-md-10">' +
                '<label class="beside_check" style="margin-right: 5px; color:black">' + (v.ddatcNombre?('('+v.ddatcNombre+')'):'(-)')+'</label>'+'<label>'+v.ddatcDescripcion +'</label>' +
                '</div>' +
                '</div>'
            ;
        });

        $checkboxes.append($to_append);
    }
    $modal_precios.find('[name=modelo_id]').val($modelo_id);
    $modal_precios.find('[name=subarea_id]').val($subarea_id);
    $modal_precios.modal('show');
}

function modal_close() {
    var $checkboxes = $('#checkboxes_precios');
    $modal_precios.modal('hide');
    $checkboxes.html('');
}

function form_precios() {
    event.preventDefault();

    var $url    = $(this).attr('action');
    var $method = $(this).attr('method');
    var $almost_one_checked = 0;

    // PERFILADO
    var $perfilado = $('#perfilado').val();
    if( $perfilado.length>0 ){
        if( $green_button == 0 ){
            showmessage('Antes de asignar el número de piezas, asigne una descripción.',0);
            return;
        }
    }

    //
    var radios = $(':radio:checked');
    radios.each(function () {
        if( this.name == 'ddc_id')
            $almost_one_checked = 1;
    });

    if( $almost_one_checked == 0 && $perfilado.length == 0  ) {
        showmessage('Seleccione alguna descripción.', 0);
        return;
    }

    $.ajax({
            url: $url,
            method: $method,
            data: new FormData(this),
            dataType: "JSON",
            processData: false,
            contentType: false
        }).done(function (data) {
            if (data.success == 'true') {
                showmessage(data.message, 1);
                modal_close();
                setTimeout(function () {
                    location.reload();
                },200)
            } else {
                showmessage(data.message, 0);
            }
        });
}