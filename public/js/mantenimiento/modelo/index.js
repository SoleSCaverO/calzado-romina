$(document).on('ready', main);

var $div_button_imagen_crear;
var $modal_modelo_imagen_mostrar;
var $modal_modelo_imagen_crear;
var $modal_modelo_imagen_editar;
var $modal_modelo_imagen_eliminar;

var $control_ajax_call = 0;

function main() {
    // Global
    var $dynamic_table = $('#dynamic-table-modelos');
    var $dynamic_table_id_name = $dynamic_table.attr('id');

    start_datatable($dynamic_table );
    order_filter_and_lenght($dynamic_table_id_name);
    $div_button_imagen_crear      = $('#div-button-imagen-crear');
    $model_description            = $('#model_description');
    $modal_modelo_imagen_galeria  = $('#modal-modelo-imagen-galeria');
    $modal_modelo_imagen_mostrar  = $('#modal-modelo-imagen-mostrar');
    $modal_modelo_imagen_crear    = $('#modal-modelo-imagen-crear');
    $modal_modelo_imagen_editar   = $('#modal-modelo-imagen-editar');
    $modal_modelo_imagen_eliminar = $('#modal-modelo-imagen-eliminar');

    $model_description.on('input',model_description);

    // Modelos
    $('#table-modelo-imagenes').on('click', 'tr', imagenes);

     // Imágenes
    var $body = $('body');
    $body.on('click','[data-imagen]',modal_modelo_imagen_mostrar);
    $body.on('click','[data-galeria_modelo_id]',modal_modelo_imagen_galeria);
    $body.on('click','[data-imagen_crear]',modal_modelo_imagen_crear);
    $body.on('click','[data-imagen_editar]',modal_modelo_imagen_editar);
    $body.on('click','[data-imagen_eliminar]',modal_modelo_imagen_eliminar);

    // FORMS - SUBMIT
    $('#form-modelo-imagen-crear').on('submit',form_modelo_imagen_crear);
    $('#form-modelo-imagen-editar').on('submit',form_modelo_imagen_editar);
    $('#form-modelo-imagen-eliminar').on('submit',form_modelo_imagen_eliminar);
}

// Global
function modal_modelo_imagen_galeria() {
    var $url = $('#modelo-url').val();
    var $galeria_modelo_id = $(this).data('galeria_modelo_id');
    var $galeria_lista    = $('#galeria-lista');
    var $galeria_imagenes = $('#galeria-imagenes');

    $.ajax({
        url: $url+'/'+$galeria_modelo_id,
        type:'get',
        dataType:'Json'
    }).done(function (data) {
        $galeria_lista.html('');
        $galeria_imagenes.html('');

        var $to_append_lista = '';
        var $to_append_imagenes = '';
        var $primer_registro = 1;
        var $counter = 1;
        var $images_folder = $('#images-folder').val();

        if (data.success == 'true') {
            var $numero_imagenes   = data.number_images;
            $.each(data.data,function (k,v) {
                if( $numero_imagenes == 1 )
                {
                    $to_append_lista += '<li data-target="#galleria" data-slide-to="0" class="active"></li>';
                    $to_append_imagenes += '<div class="item active carousel_image text-center">'+
                        '<div class="carousel_image text-center">'+
                        '<img src="'+$images_folder+'/'+v.imgDescripcion+'" class="img-fluid" style="max-width: 100%; max-height: 100%;">'+
                        '</div>'+
                        '</div>';
                }else{
                    if( $primer_registro == 1 )
                    {
                        $to_append_lista += '<li data-target="#galleria" data-slide-to="0" class="active"></li>';
                        $to_append_imagenes += '<div class="item active">'+
                            '<div class="carousel_image text-center">'+
                            '<img src="'+$images_folder+'/'+v.imgDescripcion+'" class="img-fluid" style="max-width: 100%; max-height: 100%;">'+
                            '</div>'+
                            '</div>';
                        $primer_registro = 0;
                    }else
                    {
                        $to_append_lista += '<li data-target="#galleria" data-slide-to="'+$counter+'"></li>';
                        $to_append_imagenes += '<div class="item carousel_image text-center">'+
                            '<div class="carousel_image text-center">'+
                            '<img src="'+$images_folder+'/'+v.imgDescripcion+'" class="img-fluid" style="max-width: 100%; max-height: 100%;">'+
                            '</div>'+
                            '</div>';
                        $counter++;
                    }
                }
            });
            $galeria_lista.append($to_append_lista);
            $galeria_imagenes.append($to_append_imagenes);
            $modal_modelo_imagen_galeria.modal('show');
        }
    });
}

function model_description() {
    var $model_description = $(this).val();
    var $url = $('#modelo-descripcion').val();
    var $table_modelo_imagenes = $('#table-modelo-imagenes');

    $.ajax({
        url: $url+'/'+$model_description,
        type:'get',
        dataType:'Json'
    }).done(function (data) {
        $table_modelo_imagenes.html('');
        if (data.success == 'true') {
            var $to_append = '';
            $.each(data.data,function (k,v) {
                $to_append +=
                    '<tr data-modelo_id="'+ v.modId +'">'+
                    '<td>'+ v.modDescripcion +'</td>'+
                    '<td>'+ v.genero +'</td>'+
                    '<td>'+ v.linea  +'</td>'+
                    '</tr>';
            });
            $table_modelo_imagenes.append($to_append);
        }
    });
}

// Modelos
function imagenes() {
    select_table_row(this);
    var $modelo_id = $(this).attr('data-modelo_id');
    $div_button_imagen_crear.html('');
    $div_button_imagen_crear.append('<button class="btn btn-success btn-sm" data-imagen_crear="'+$modelo_id+'"> <i class="fa fa-plus-circle"> </i> Nueva imagen </button> ');
    load_images($modelo_id);
}

function modal_modelo_imagen_mostrar() {
    var $imagen = $(this).data('imagen');
    var $mostrar_imagen = $('#mostrar-imagen');
    var $images_folder = $('#images-folder').val();

    $mostrar_imagen.html('');
    $mostrar_imagen.append('<img src="'+$images_folder+'/'+$imagen+'" style="width: 100%;height: 100%"/>');
    $modal_modelo_imagen_mostrar.modal('show');
}

// Imágenes
function modal_modelo_imagen_crear() {
    $control_ajax_call = 1;
    var $modelo_imagen = $('#modelo-imagen');
    var $modelo_imagen_preview =  $('#modelo-imagen-preview');
    $modelo_imagen.val('');
    $modelo_imagen_preview.attr('src','');

    var $modelo_id = $('[data-imagen_crear]').data('imagen_crear');
    $modal_modelo_imagen_crear.find('[name=modelo_id]').val($modelo_id);
    $modal_modelo_imagen_crear.modal('show');
    preview($modelo_imagen,$modelo_imagen_preview);
}

function modal_modelo_imagen_editar() {
    var $modelo_imagen = $('#modelo-imagen-edit');
    var $modelo_imagen_preview =  $('#modelo-imagen-preview-edit');
    $modelo_imagen.val('');
    $modelo_imagen_preview.attr('src','');
    var $imagen_id = $(this).data('imagen_editar');
    var $imagen_description = $(this).data('imagen_description');
    var $modelo_id = $(this).data('modelo_id');
    var $imagen_estado = $(this).data('imagen_estado');
    var $image_estado_check = $('#image_estado_check');
    var $images_folder = $('#images-folder').val();
    $image_estado_check.html('');

    $modal_modelo_imagen_editar.find('[name=imagen_id]').val($imagen_id);
    $modal_modelo_imagen_editar.find('[name=modelo_id]').val($modelo_id);
    $modal_modelo_imagen_editar.find('[name=modelo_imagen_preview_edit]').attr('src',$images_folder+'/'+$imagen_description);
    if( $imagen_estado)
        $image_estado_check.append('<input type="checkbox"  name="imagen_estado" class="form-control" checked>');
    else
        $image_estado_check.append('<input type="checkbox"  name="imagen_estado" class="form-control">');

    $modal_modelo_imagen_editar.modal('show');
    preview($modelo_imagen,$modelo_imagen_preview);
}

function modal_modelo_imagen_eliminar() {
    var $modelo_imagen = $('#modelo-imagen-edit');
    var $modelo_imagen_preview =  $('#modelo-imagen-preview-delete');
    $modelo_imagen.val('');
    $modelo_imagen_preview.attr('src','');
    var $imagen_id = $(this).data('imagen_eliminar');
    var $imagen_description = $(this).data('imagen_description');
    var $modelo_id = $(this).data('modelo_id');
    var $images_folder = $('#images-folder').val();
    $modal_modelo_imagen_eliminar.find('[name=imagen_id]').val($imagen_id);
    $modal_modelo_imagen_eliminar.find('[name=modelo_id]').val($modelo_id);
    $modal_modelo_imagen_eliminar.find('[name=modelo_imagen_preview_delete]').attr('src',$images_folder+'/'+$imagen_description);
    $modal_modelo_imagen_eliminar.modal('show');
    preview($modelo_imagen,$modelo_imagen_preview);
}

function modal_close($modal) {
    $modal.modal('hide');
    $('#modelo-imagen').val('');
    $('#modelo-imagen-preview').attr('src', '');
}

function load_images($modelo_id){
    var $url = $('#modelo-url').val();
    $.ajax({
        url: $url+'/'+$modelo_id,
        type:'get',
        dataType:'Json'
    }).done(function (data) {
        var $images_table = $('#images-table');
        $images_table.html('');
        if(  data.success =='true' ){
            var $images_folder = $('#images-folder').val();
            var $to_append = '';
            $.each(data.data,function (k,v) {
                $to_append +=
                    '<tr>'+
                    '<td><img data-imagen="'+v.imgDescripcion+'" src="'+$images_folder+'/'+v.imgDescripcion+'" alt="" style="width:40px;height: 50px"></td>'+
                    '<td>'+((v.imgEstado==1)?'Mostrar':'No mostrar')+'</td>'+
                    '<td>'+
                    '<button class="btn btn-info btn-sm" data-imagen_editar="'+v.imgId+'"' +
                    'data-imagen_description="'+v.imgDescripcion+'"  data-imagen_estado="'+v.imgEstado+'"' +
                    'data-modelo_id="'+v.modId+'" data-imagen_estado="'+v.imgEstado+'" >'+
                    '<i class="fa fa-edit"></i> Editar'+
                    '</button>'+
                    '<button class="btn btn-danger btn-sm" data-imagen_eliminar="'+v.imgId+'" data-imagen_description="'+v.imgDescripcion+'" data-modelo_id="'+v.modId+'">'+
                    '<i class="fa fa-trash-o"></i> Eliminar'+
                    '</button>'+
                    '</td>'+
                    '</tr>';
            });
            $images_table.append($to_append);
        }
    });
}

function form_modelo_imagen_crear() {
    event.preventDefault();

    var $modelo_imagen = $('#modelo-imagen').val();
    var $image_extension = image_extension($modelo_imagen);

    if (!image_permitted_extension($image_extension)) {
        showmessage('Se permiten imágenes con extensión .jpg, .png.', 0);
        return;
    }
    var $modelo_id = $modal_modelo_imagen_crear.find('[name=modelo_id]').val();
    var $url = $(this).attr('action');
    var $method = $(this).attr('method');
    if ($control_ajax_call == 1){
        $control_ajax_call = 0;
        $.ajax({
            url: $url,
            method: $method,
            data: new FormData(this),
            dataType: "JSON",
            processData: false,
            contentType: false
        }).done(function (data) {
            if (data.success == 'true') {

                var $show_button_gallery = $('#show_button_gallery'+$modelo_id);
                var $to_append =
                    '<button class="btn btn-info btn-sm" data-galeria_modelo_id="'+ $modelo_id +'">'+
                    '<i class="fa fa-picture-o"></i> Imágenes'+
                    '</button>';

                $show_button_gallery.html('');
                $show_button_gallery.append($to_append);
                $control_ajax_call = 0;
                showmessage(data.message, 1);
                load_images(data.modelo_id);
                setTimeout(function () {
                    modal_close($modal_modelo_imagen_crear);
                }, 1000);
            }
            else {
                $control_ajax_call = 1;
                showmessage(data.message, 0);
            }
        });
    }
}

function form_modelo_imagen_editar() {
    event.preventDefault();

    var $modelo_imagen = $('#modelo-imagen-edit').val();
    var $image_extension = image_extension($modelo_imagen);
    if( $modelo_imagen.length>0 ) {
        if (!image_permitted_extension($image_extension)) {
            showmessage('Se permiten imágenes con extensión .jpg, .png.', 0);
            return;
        }
    }

    var $url = $(this).attr('action');
    var $method = $(this).attr('method');
    $.ajax({
        url: $url,
        method: $method,
        data: new FormData(this),
        dataType: "JSON",
        processData: false,
        contentType: false
    }).done(function (data) {
        if( data.success=='true' ) {
            showmessage(data.message, 1);
            load_images(data.modelo_id);
            setTimeout(function(){
                modal_close($modal_modelo_imagen_editar);
            },1000);
        }
        else
            showmessage(data.message,0);
    });
}

function form_modelo_imagen_eliminar() {
    event.preventDefault();

    var $url = $(this).attr('action');
    var $method = $(this).attr('method');
    $.ajax({
        url: $url,
        method: $method,
        data: new FormData(this),
        dataType: "JSON",
        processData: false,
        contentType: false
    }).done(function (data) {
        if( data.success=='true' ) {
            showmessage(data.message, 1);
            load_images(data.modelo_id);
            setTimeout(function(){
                modal_close($modal_modelo_imagen_eliminar);
            },1000);
        }
        else
            showmessage(data.message,0);
    });
}