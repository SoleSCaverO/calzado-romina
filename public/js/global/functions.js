function start_selectpicker($select_id) {
    $select_id.selectpicker();
    $select_id.selectpicker('val',' ');
    $(".selectpicker li").removeClass("selected");
}

function start_datatable($id_table) {
    var dom = "<'row'<'col-sm-6'f><'col-sm-6'l>>" +
              "<'row'<'col-sm-12'tr>>" +
              "<'row'<'col-sm-5'i><'col-sm-7'p>>";
    $id_table.DataTable({
        "dom":dom,
        "lengthMenu": [ 4,10, 25, 50, 100 ],
        "pageLength": 4,
        "language":{
            "decimal":        "",
            "emptyTable":     "No existen datos",
            "info":           "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty":      "Mostrando 0 a 0 de 0 registros",
            "infoFiltered":   "(Filtrando de _MAX_ registros totales)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Mostrar _MENU_ registros",
            "loadingRecords": "Cargando ...",
            "processing":     "Processando ...",
            "search":         "Buscar: ",
            //"searchPlaceholder":"Buscar ...",
            "zeroRecords":    "No existen datos",
            "paginate": {
                "first":      "Primero",
                "last":       "Último",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "aria": {
                "sortAscending":  ": activate to sort column ascending",
                "sortDescending": ": activate to sort column descending"
            }
        }
    });
}

function order_filter_and_lenght($id_table_name) {
    $('#'+$id_table_name+'_filter').addClass('pull-left');
    $('#'+$id_table_name+'_length').addClass('pull-right');
}

function preview($input_file_id,$previewer_id) {
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $($previewer_id).attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    $($input_file_id).change(function () {
        readURL(this);
    });
}

function image_extension( $image_value ) {
    return $image_value.split('.').pop();
}

function image_permitted_extension( $extension ) {
    return ( $extension =='jpg' || $extension == 'png' || $extension =='jpeg' || $extension =='JPG' || $extension =='PNG' || $extension =='JPEG' );
}

function select_table_row($row) {
    $($row).addClass('success').siblings().removeClass('success');
}

function showmessage( message, success )
{
    swal('',message,(success==1)?"success":"error" );
}