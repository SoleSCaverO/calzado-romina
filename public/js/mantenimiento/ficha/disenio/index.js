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

    $('#add_checkboxes').on('click', add_checkboxes);
    $body.on('click', '[data-remove_checkbox]', remove_checkbox);
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

    // Cuero
    var stringType1Cuero ='<div class="material">' +
        '<div class="form-group col-md-4">'+
        '<input type="text" class="form-control" id="material">'+
        '</div>'+
        '<div class="form-group col-md-3">'+
        '<input type="text" class="form-control" id="material">'+
        '</div>'+
        '<div class="form-group col-md-3">'+
        '<input type="text" class="form-control" id="piezas">'+
        '</div>' +
        '<div class="col-md-2">'+
        '<button type="button" class="btn btn-danger" data-remove_material>-</button>'+
        '</div>'+
        '</div>';

    // Perfilado
    var stringType2Perfilado = '<div class="material">'+
        '<div class="form-group col-md-4">'+
        '<input type="text" class="form-control" id="material">'+
        '</div>'+
        '<div class="form-group col-md-3">'+
        '<input type="text" class="form-control" id="material">'+
        '</div>'+
        '<div class="form-group col-md-3">'+
        '<input type="text" class="form-control" id="material">'+
        '</div>'+
        '<div class="col-md-2">'+
        '<button type="button" class="btn btn-danger" data-remove_material>-</button>'+
        '</div>'+
        '</div>';

    // Perfilado Hilo
    var stringType2PerfiladoHilo = '<div class="material">'+
        '<div class="form-group col-md-10">'+
        '<input type="text" class="form-control" id="material">'+
        '</div>'+
        '<div class="col-md-2">'+
        '<button type="button" class="btn btn-danger" data-remove_material>-</button>'+
        '</div>'+
        '</div>';

    // Cosido vena
    var stringType2CosidoVena = '<div class="material">'+
        '<div class="form-group col-md-10">'+
        '<input type="text" class="form-control" id="material">'+
        '</div>'+
        '<div class="col-md-2">'+
        '<button type="button" class="btn btn-danger" data-remove_material>-</button>'+
        '</div>'+
        '</div>';

    // Pegado
    var stringType2Pegado = '<div class="material">'+
        '<div class="col-md-5">'+
        '<input type="text" class="form-control" placeholder="Tipo">'+
        '</div>'+
        '<div class="col-md-5">'+
        '<input type="text" class="form-control" placeholder="Color">'+
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
        if($container.data('material') === 1 ){
            $container.append(stringType1Cuero);
        }else {
            $container.append(stringType1);
        }
    }
    else{
        if($container.data('material') === 4 ) {
            if ($button.data('thread')) {
                $container.append(stringType2PerfiladoHilo);
            } else {
                $container.append(stringType2Perfilado);
            }
        }else if($container.data('material') === 5 ){
            $container.append(stringType2CosidoVena);
        }else if($container.data('material') === 6 ){
            $container.append(stringType2Pegado);
        }else {
            $container.append(stringType2);
        }
    }
}

function remove_material() {
    var $button = $(this);
    var $containerButton = $button.parent();

    $containerButton.parent().remove();
}

function add_checkboxes() {
    var $otros = $('#otros');
    var checkbox = '<div class="col-md-6 otros-checkboxes">'+
        '<div class="col-md-8">'+
        '<input type="text" class="form-control">'+
        '</div>'+
        '<div class="col-md-2">'+
        '<input type="checkbox" class="checkboxes">'+
        '</div>'+
        '<div class="col-md-2">'+
        '<button type="button" class="btn btn-danger" data-remove_checkbox>-</button>'+
        '</div>'+
        '</div>';

    $otros.append(checkbox);
}

function remove_checkbox() {
    $(this).parent().parent().remove();
}

function save_materials(event) {
    event.preventDefault();

    var $form = $(this);

    $form.validate({
        rules: {
            genero: {
                required: true
            },
            marca: {
                required: true
            },
            modelista: {
                required: true
            },
            talla: {
                required: true
            },
            fecha: {
                required: true
            },
            caja: {
                required: true
            },
            sello_especificaion:{
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
        }
    });

    if( $form.valid() ){
        /*Other validations*/

        var error = false;
        var $dataMaterial = $('[data-material]');
        $dataMaterial.each(function (k,v) {
            var areaId = $(v).attr('data-material');
            var area = $(v).attr('data-area');
            var tipoMaterial = $(v).attr('data-type_material');
            var counter = 0;

            if( tipoMaterial === '1'){
                $(v).children().each(function (key,value) { // materials
                    counter++;
                    material = $(value).children().children().val();
                    color = $($(value).children()[1]).children().val();
                    material_text = 'MATERIAL';

                    if( areaId === '1' ) {
                        pieza = $($(value).children()[2]).children().val();
                        material_text = 'TIPO';
                    }else{
                        pieza = color;
                    }

                    if( material.trim().length === 0 ){
                        error = true;
                        showmessage('El campo '+ material_text+' es requerido para el área '+ area+' en la fila '+counter,0);
                        return false;
                    }

                    if( areaId === '1' ) {
                        if( color.trim().length === 0 ) {
                            error = true;
                            showmessage('El campo COLOR es requerido para el área ' + area + ' en la fila ' + counter, 0);
                            return false;
                        }
                    }

                    if( areaId !== '3' ){
                        if( pieza.trim().length === 0 ) {
                            error = true;
                            showmessage('El campo PIEZAS es requerido para el área ' + area + ' en la fila ' + counter, 0);
                            return false;
                        }
                    }
                });
            }else{
                $(v).children().each(function (key,value) { // materials
                    counter++;
                    if( areaId === '4' ){
                        material = $(value).children().children().val();
                        button = $($(value).children()[1]).children().prop('tagName');
                        material_text = 'MATERIAL';
                        if( button === 'BUTTON'){
                            material_text = 'HILO';

                            var aguja = $('#aguja').val();
                            var hilo_forro = $('#hilo_forro').val();

                            if( aguja.trim().length === 0 ){
                                error = true;
                                showmessage('El campo AGUJA es requerido para el área '+ area,0);
                                return false;
                            }
                            if( hilo_forro.trim().length === 0 ){
                                error = true;
                                showmessage('El campo HILO FORRO es requerido para el área '+ area,0);
                                return false;
                            }
                        }
                        if( material.trim().length === 0 ){
                            error = true;
                            showmessage('El campo '+material_text+' es requerido para el área '+ area+' en la fila '+counter,0);
                            return false;
                        }
                    }else if( areaId === '5' ){
                        if( key === 0 ){
                            tipo_cosido = $($(value).children()[1]).children().val();
                            if( tipo_cosido.trim().length === 0 ){
                                error = true;
                                showmessage('El campo TIPO DE COSIDO es requerido para el área '+ area,0);
                                return false;
                            }
                        }else{
                            tipo_cosido = $(value).children().children().val();
                            if( tipo_cosido.trim().length === 0 ){
                                error = true;
                                showmessage('El campo VALOR DE COSIDO VENA es requerido para el área '+ area+' en la fila '+(counter-1),0);
                                return false;
                            }
                        }
                    }else if( areaId === '6' ){
                        pivot = $($(value).children()[0]).children().val();
                        tipo = $($(value).children()[1]).children().val();

                        if( key === 0 || key === 1 ){
                            color = $($(value).children()[2]).children().val();
                        }else{
                            color = tipo;
                            tipo = pivot;
                        }

                        if( key === 0 ){
                            texto = 'PLANTA';
                        }else if( key === 1 ){
                            texto = 'HILO LATERAL';
                        }
                        if( key === 0 ||  key === 1 ){
                            if( tipo.trim().length === 0 ){
                                error = true;
                                showmessage('El campo TIPO '+texto+' es requerido para el área '+ area,0);
                                return false;
                            }

                            if( color.trim().length === 0 ){
                                error = true;
                                showmessage('El campo COLOR '+texto+' es requerido para el área '+ area,0);
                                return false;
                            }
                        }else{
                            if( tipo.trim().length === 0 ){
                                error = true;
                                showmessage('El campo TIPO es requerido para el área '+ area+' en la fila '+(counter-2),0);
                                return false;
                            }

                            if( color.trim().length === 0 ){
                                error = true;
                                showmessage('El campo COLOR es requerido para el área '+ area+' en la fila '+(counter-2),0);
                                return false;
                            }
                        }

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

        $dataMaterial.each(function (k,v) {
            var areaId = $(v).attr('data-material');
            var tipoMaterial = $(v).attr('data-type_material');
            var area = {};
            var materials = [];
            if( tipoMaterial === '1'){
                $(v).children().each(function (key,value) {
                    var material = {};
                    materialName = $(value).children().children().val().trim();
                    color = $($(value).children()[1]).children().val().trim();
                    pieza = null;
                    
                    if( areaId === '1' ) {
                        pieza = $($(value).children()[2]).children().val().trim();
                    }else {
                       pieza = color;
                    }

                    material.material = materialName;
                    material.color = color;
                    material.pieza = pieza;

                    materials.push(material);
                });
            }else {
                $(v).children().each(function (key, value) { // materials
                    var material = {};
                    if (areaId === '4') {
                        materialName = $(value).children().children().val();
                        button = $($(value).children()[1]).children().prop('tagName');
                        if (button === 'BUTTON') {
                            material.material = materialName;
                            material.extra_perfilado = 1;
                        }else{
                            color = $($(value).children()[1]).children();
                            cantidad = $($(value).children()[2]).children();
                            material.material = materialName;
                            material.color = color;
                            material.cantidad = pieza;
                        }

                        materials.push(material);
                    } else if (areaId === '5') {
                        if (key === 0) {
                            tipo_cosido = $($(value).children()[1]).children().val();
                            if (tipo_cosido.trim().length === 0) {
                                error = true;
                                showmessage('El campo TIPO DE COSIDO es requerido para el área ' + area, 0);
                                return false;
                            }
                        } else {
                            tipo_cosido = $(value).children().children().val();
                            if (tipo_cosido.trim().length === 0) {
                                error = true;
                                showmessage('El campo VALOR DE COSIDO VENA es requerido para el área ' + area + ' en la fila ' + (counter - 1), 0);
                                return false;
                            }
                        }
                    } else if (areaId === '6') {
                        pivot = $($(value).children()[0]).children().val();
                        tipo = $($(value).children()[1]).children().val();

                        if (key === 0 || key === 1) {
                            color = $($(value).children()[2]).children().val();
                        } else {
                            color = tipo;
                            tipo = pivot;
                        }

                        if (key === 0) {
                            texto = 'PLANTA';
                        } else if (key === 1) {
                            texto = 'HILO LATERAL';
                        }
                        if (key === 0 || key === 1) {
                            if (tipo.trim().length === 0) {
                                error = true;
                                showmessage('El campo TIPO ' + texto + ' es requerido para el área ' + area, 0);
                                return false;
                            }

                            if (color.trim().length === 0) {
                                error = true;
                                showmessage('El campo COLOR ' + texto + ' es requerido para el área ' + area, 0);
                                return false;
                            }
                        } else {
                            if (tipo.trim().length === 0) {
                                error = true;
                                showmessage('El campo TIPO es requerido para el área ' + area + ' en la fila ' + (counter - 2), 0);
                                return false;
                            }

                            if (color.trim().length === 0) {
                                error = true;
                                showmessage('El campo COLOR es requerido para el área ' + area + ' en la fila ' + (counter - 2), 0);
                                return false;
                            }
                        }

                    }
                });
            }
        });

        counter = 0 ;
        error = false;
        $('#otros').children().each(function (k,v) {
            counter++;
            input = $(v).children().children().val();
            if( input.trim().length === 0 ){
                error = true;
                showmessage('El campo  para el CHECKBOX es requerido para el área HAB. PLANTILLA en el elemento  '+counter,0);
                return false;
            }
        });

        if( error ){
            return false;
        }

        var areas = [];
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