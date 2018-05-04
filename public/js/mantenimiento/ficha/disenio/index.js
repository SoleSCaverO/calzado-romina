$(document).on('ready', main);

function main() {
    var $body = $('body');
    $body.on('click', '[data-add_material]', add_material);
    $body.on('click', '[data-remove_material]', remove_material);
    $('#form_materials').on('submit', save_materials);

    var $imagen1 = $('#imagen1'); var $imagen2 = $('#imagen2');
    var $imagen3 = $('#imagen3'); var $imagen4 = $('#imagen4');

    var $imagen1_preview =  $('#imagen1-preview');
    var $imagen2_preview =  $('#imagen2-preview');
    var $imagen3_preview =  $('#imagen3-preview');
    var $imagen4_preview =  $('#imagen4-preview');

    $imagen1.val(''); $imagen2.val(''); $imagen3.val(''); $imagen4.val('');
    $imagen1_preview.attr('src',''); $imagen2_preview.attr('src','');
    $imagen3_preview.attr('src',''); $imagen4_preview.attr('src','');
    preview($imagen1,$imagen1_preview); preview($imagen2,$imagen2_preview);
    preview($imagen3,$imagen3_preview); preview($imagen4,$imagen4_preview);
}

function add_material() {
    var $button = $(this);
    var $container = $button.parent().parent().parent();

    var stringType1 = '<div class="material">' +
        '<div class="form-group col-md-7">'+
        '<input type="text" class="form-control" id="material">'+
        '</div>'+
        '<div class="form-group col-md-3">'+
        '<input type="text" class="form-control" id="piezas">'+
        '</div>'+
        '<div class="col-md-2">'+
        '<button type="button" class="btn btn-danger" data-remove_material>-</button>'+
        '</div>'+
        '</div>';

    var stringType2 = '<div class="material">' +
        '<div class="form-group col-md-7">'+
        '<input type="text" class="form-control" id="material">'+
        '</div>'+
        '<div class="col-md-5">'+
        '<button type="button" class="btn btn-danger" data-remove_material>-</button>'+
        '</div>'+
        '</div>';

    if( $container.data('type_material') === 1 ){
        $container.append(stringType1);
    }
    else{
        $container.append(stringType2);
    }
}

function remove_material() {
    var $button = $(this);
    var $containerButton = $button.parent();

    $containerButton.parent().remove();
}

function save_materials(event) {
    event.preventDefault();

    var $form = $(this);

    $form.validate({
        rules: {
            coleccion: {
                required: true
            },
            genero: {
                required: true
            },
            marca: {
                required: true
            },
            horma: {
                required: true
            },
            modelista: {
                required: true
            },
            talla: {
                required: true
            },
            cuero: {
                required: true
            },
            forro: {
                required: true
            },
            fecha: {
                required: true
            },
            falsa: {
                required: true
            },
            contrafuerte: {
                required: true
            },
            puntera: {
                required: true
            },
            talon: {
                required: true
            },
            caja: {
                required: true
            },
            papel: {
                required: true
            },
            hantan: {
                required: true
            },
            bolsa: {
                required: true
            },
            sello_pan_oro:{
                required: true
            },
            sello_especificaion:{
                required: true
            },
            troquel:{
                required: true
            },
            modelaje:{
                required: true
            },
            produccion:{
                required: true
            },
            gerencia:{
                required: true
            },
            imagen1:{
                required: true
            },
            imagen2:{
                required: true
            },
            imagen3:{
                required: true
            },
            imagen4:{
                required: true
            }
        },
        messages: {
        }
    });

    if( $form.valid() ){
        /*Other validations*/
        var error = false;
        var $dataMaterial = $('[data-material]');
        $dataMaterial.each(function (k,v) {
            var area = $(v).attr('data-area');
            var tipoMaterial = $(v).attr('data-type_material');
            var counter = 0;
            if( tipoMaterial === '1'){
                $(v).children().each(function (key,value) {
                    counter++;
                    material = $(value).children().children().val();
                    pieza = $($(value).children()[1]).children().val();
                    if( material.trim().length === 0 ){
                        error = true;
                        showmessage('El campo MATERIAL es requerido para el área '+ area+' en la fila '+counter,0);
                    }else if( pieza.trim().length === 0 ){
                        error = true;
                        showmessage('El campo PIEZAS es requerido para el área '+ area+' en la fila '+counter,0);
                    }
                });
            }else{
                $(v).children().each(function (key,value) {
                    counter++;
                    material = $(value).children().children().val();
                    if( material.trim().length === 0 ){
                        error = true;
                        showmessage('El campo MATERIAL es requerido para el área '+ area+' en la fila '+counter,0);
                    }
                });
            }

            if( error ){
                return false;
            }
        });

        if(error){
            return false;
        }

        var areas = [];
        $dataMaterial.each(function (k,v) {
            var areaId = $(v).attr('data-material');
            var tipoMaterial = $(v).attr('data-type_material');
            var area = {};
            var materials = [];
            if( tipoMaterial === '1'){
                $(v).children().each(function (key,value) {
                    var material = {};
                    materialName = $(value).children().children().val();
                    pieza = $($(value).children()[1]).children().val();
                    materialName = materialName.trim();
                    pieza = pieza.trim();

                    material.material = materialName;
                    material.pieza = pieza;

                    materials.push(material);
                });
            }else{
                $(v).children().each(function (key,value) {
                    var material = {};
                    materialName = $(value).children().children().val();
                    materialName = materialName.trim();

                    material.material = materialName;
                    material.pieza = '';

                    materials.push(material);
                });
            }

            if( error ){
                return false;
            }
            area.id = areaId;
            area.materials = materials;
            areas.push(area)
        });

        var data = new FormData($form[0]);
        var url  = $form.attr('action');
        var type = $form.attr('method');
        data.append('areas',JSON.stringify(areas));
        $('#btn_save').prop('disabled',true);

        $.ajax({
            url : url,
            type: type,
            data: data,
            processData: false,
            contentType: false,
            headers:{
                'X-CSRF-TOKEN': $('#_token').val()
            }
        }).done(function (data) {
            if( data.success ){
                showmessage(data.message,1);
                setTimeout(function () {
                    location.href = $('#ficha_tecnica').val();
                },2000);
            }else{
                showmessage(data.message,0);
                $('#btn_save').prop('disabled',false);
            }
        });
    }
}