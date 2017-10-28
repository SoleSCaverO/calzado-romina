$(document).on('ready',main);
var $tallas = [];
var $count_rows = 0;
var $pares_totales=0;
var $tab = 0;
var $subarea_id = 0;
var $requested = 0;
function main() {
    var $body = $('body');
    $tab = $('#tabber').val();
    $subarea_id = $('#subarea_id').val();
    $pares_totales = $('#pares_totales').val();
    $body.on('click','[data-tabber]',tabber);
    $body.on('click','[data-agregar_orden]',agregar_orden);
    $body.on('click','[data-quitar_orden]',quitar_orden);
    $body.on('click','#guardar_ordenes'+$tab,guardar_orden);
    $body.on('click','[data-delete_order]',eliminar_orden);

    $body.on('click','[data-add_material]',add_material);
    $body.on('click','[data-take_out_material]',take_out_material);
    $body.on('click','[data-save_material]',save_material);
    $body.on('click','[data-delete_material]',delete_material);

    tallas();
    on_td_key_up_automatically();
}

function tabber() {
    $tab = $(this).data('tabber');
    tallas();
    on_td_key_up_automatically();
    on_td_key_up();

    on_td_key_up_row();
    var $body = $('body');
    $body.on('click','#guardar_ordenes'+$tab,guardar_orden);
}

function on_td_key_up_automatically() {
    var $table_ordenes = $('#table_ordenes'+$tab);
    $stop = $table_ordenes.children().length;
    $counter = 1;
    $table_ordenes.children().each(function () {
        if( $stop == $counter ){
            return false;
        }
        $(this).children().each(function () {
            var $parameter = $(this);
            $(this).trigger('keyup',sub_total_automatically($parameter));
        });
        $counter++;
    });
    total();
}

function sub_total_automatically( $parameter ) {
    var $data_id;
    var $counter = 0;
    var $values = [];
    for (var key in $parameter.data())
        $data_id = key;

    $('[data-'+$data_id+']').each(function (k,v) {
        var $value = $(v).html();
        $counter++;
        if( $value != '' )
            $values.push($value);
        else
            $values.push(0);

        if( $counter == $count_rows )
            return false;
    });

    var $sub_total = $('#sum_'+$data_id);
    var $sum = sum_elements($values);
    if( $values.length>0 ) {
        $sub_total.html('');
        $sub_total.html($sum);
    }
}

function on_td_key_up() {
    var $table_ordenes = $('#table_ordenes'+$tab);
    $stop = $table_ordenes.children().length;
    $counter = 1;
    $table_ordenes.children().each(function () {
        if( $stop == $counter ){
            return false;
        }
        $(this).children().each(function () {
            $(this).on('keyup',sub_total);
        });
        $counter++;
    })
}

function on_td_key_up_row() {
    var $table_ordenes = $('#table_ordenes'+$tab);
    $stop = $table_ordenes.children().length;
    $counter = 1;
    $table_ordenes.children().each(function () {
        if( $stop == $counter ){
            return false;
        }
        $(this).children().each(function () {
            $(this).on('keyup',sub_total_row);
        });
        $counter++;
    })
}

function tallas() {
    var $tallas_json = $('#tallas'+$tab);
    $tallas = [];
    $.each( JSON.parse($tallas_json.val()),function (k,v) {
        $tallas.push(v.mulDescripcion);
    })
}

function agregar_orden() {
    tallas();
    $tab = $(this).data('agregar_orden');
    var $table_ordenes = $('#table_ordenes'+$tab);
    var $number_zeros  = $('#number_zeros'+$tab).val();
    var $to_append;

    $count_rows = $table_ordenes.children().length;

    // LOGIC TO AVOID REPEATED NUMBER OF ROW
    $indexes = [];
    for( $i=0;$i<$count_rows;$i++ ){
        $indexes.push($i+1);
    }

    $index_in_table = [];
    $table_ordenes.children().each(function (k,v) {
        $index = $($(v).children()[0]).text();
        $index_in_table.push(parseInt($index));

        if( k == $count_rows-2 ){
            return false;
        }
    });

    $diff = $($indexes).not($index_in_table).get();
    // LOGIC TO AVOID REPEATED NUMBER OF ROW

    $to_append = '<tr><td>'+$diff[0]+'</td>'+ td_zeros($number_zeros) +
        '<td style="font-weight: bold">0</td>'+
        '<td><span title=""><button class="btn btn-warning btn-sm" data-quitar_orden="'+$.now()+'">' +
        '<i class="fa fa-close"></i> Quitar</button></span></td></tr>';
    if( $count_rows  == 1)
        $table_ordenes.prepend($to_append);
    else
        $('#'+$table_ordenes.attr('id')+' > tr').eq($count_rows-2).after($to_append);

    var $number_rows = $table_ordenes.children().length;
    $table_ordenes.children().each(function(k,v){
        if( k+1 == $number_rows ){
            return false;
        }

        $($(v).children()[0]).text(k+1);
    });
        
    on_td_key_up();
    on_td_key_up_row();
}

function quitar_orden() {
    var $row = $(this).parent().parent().parent();
    var $table_ordenes = $row.parent();

    $restar = [];
    for( var i=0; i<$tallas.length; i++ )
        $restar.push( parseInt( $( $row.children()[i+1] ).html() ) );
    
    for( var j=0; j<$tallas.length; j++ )
        $('#sum_talla_id'+$tallas[j]+$tab).each(function (k,v) {
            var $value = $(v).html();
            $(v).html(parseInt($value)- $restar[j]);
        });

    total();
    $row.remove();

    var $number_rows   = $table_ordenes.children().length;
    $table_ordenes.children().each(function(k,v){
        if( k+1 == $number_rows ){
            return false;
        }

        $($(v).children()[0]).text(k+1);
    });
}

function td_zeros($number_zeros) {
    var $to_append = '';
    for( var i=0; i<$number_zeros; i++ )
        $to_append += '<td contenteditable="true" data-talla_id'+$tallas[i]+$tab+'>0</td>';

    return $to_append;
}

function sub_total() {
    var $data_id;
    var $counter = 0;
    var $values = [];
    for (var key in $(this).data())
        $data_id = key;

    $('[data-'+$data_id+']').each(function (k,v) {
        var $value = $(v).html();
        $counter++;
        if( $value != '' )
            $values.push($value);
        else
            $values.push(0);

        if( $counter == $count_rows )
            return false;
    });

    var $sub_total = $('#sum_'+$data_id);
    var $sum = sum_elements($values);
    if( $values.length>0 ) {
        $sub_total.html('');
        $sub_total.html($sum);
    }
    total();
}

function sub_total_row(){
    $row = $(this).parent();
    $number_columns = $tallas.length;
    $acumulator = 0;
    $row.children().each(function(k,v){
        if( k != 0 && k != $number_columns+1 && k != $number_columns+2 ){
            $acumulator +=  $(v).text()?parseInt($(v).text()):0;
        }
    });

    $td_sumatoria = $($row.children()[$number_columns+1]).text($acumulator);
}

function total() {
    var $total_elemets = [];
    for( var i=0; i<$tallas.length; i++ )
    $('#sum_talla_id'+$tallas[i]+$tab).each(function (k,v) {
        var $value = $(v).html();
        $total_elemets.push($value);
    });

    var $total = $('#total'+$tab);
    $total.val(sum_elements($total_elemets));
}

function sum_elements($values) {
    var $sum = 0;
    for( var i=0; i<$values.length; i++ )
        $sum += parseInt($values[i]);

    return $sum;
}

// ORDERS
function guardar_orden() {
    var $total = $('#total'+$tab).val();
    var $table_ordenes = $('#table_ordenes'+$tab);
    var $filas = $table_ordenes.children();
    var $numero_filas = $table_ordenes.children().length;

    if(  $numero_filas == 1 ) {
        showmessage('Agregue por lo menos una órden.');
        return;
    }

    if(  $pares_totales != $total ) {
        showmessage('El total de pares debe ser: '+ $pares_totales+', para proceder a guardar.');
        return;
    }

    var $data_talla = $('#number_zeros'+$tab).val();
    var $ordenes = [];

    var $iterator = 0;
    $filas.each(function () {
        $iterator++;
        if( $iterator == $numero_filas)
            return false;
        var $orden = [];
        for( var i=1;i<=$data_talla;i++ ){
            $orden.push( $( $(this).children()[i]).text() );
        }
        $ordenes.push($orden);
    });

    var $data = {};
    var $id     = $('#id'+$tab).val();
    var $modelo = $('#modelo'+$tab).val();
    var $color  = $('#color'+$tab).val();
    var $url    = $('#url_order_create').val();

    $data.orders = $ordenes;
    $data.id     = $id;
    $data.model  = $modelo;
    $data.color = $color;
    var $button = $(this);
    $button.remove();
 
    if( $requested == 1 ){
        return;
    }
    $requested = 1;
        
    $.ajax({
        url:$url,
        type:'post',
        data:$data,
        headers:{
            'X-CSRF-TOKEN': $('#_token').val()
        }
    }).done(function (data) {
        if( data.success=='true' ){
            showmessage(data.message,1);
            setTimeout(function () {
                var $url = $('#url_programacion_details').val();
                location.href = $url+'/'+data.id;
            },500);
        }
    });
}

function eliminar_orden() {
    var $id  = $(this).data('delete_order');
    var $url = $('#url_delete_order').val();
    var $tr  = $(this).parent().parent();

    $data = {};
    $data.id = $id;

    $.ajax({
        url:$url,
        type:'post',
        data:$data,
        headers:{
            'X-CSRF-TOKEN': $('#_token').val()
        }
    }).done(function (data) {
        if( data.success=='true' ){
            showmessage(data.message,1);
            $tr.remove();
        }
    });
}

//MATERIALS
function add_material() {
    var $id  = $(this).data('add_material');
    $dprodId = $id.split('_')[1];
    $subaId   = $id.split('_')[2];
    var $table_body = $('#materiales_'+$dprodId+'_'+$subaId);

    var $to_append = '<tr>'+
        '<td>'+
        '<input type="text" class="form-control" data-mat_id="0" name="mat_nombre">'+
        '</td>'+
        '<td>'+
        '<input type="text" class="form-control" name="mat_descripcion">'+
        '</td>'+
        '<td>' +
        '<button class="btn btn-warning btn-sm" data-take_out_material="delete_'+$dprodId+'_'+$subaId+'"><i class="fa fa-close"></i> Quitar</button>'+
        '</td>'+
        '</tr>';
    $table_body.append($to_append);
}

function take_out_material() {
    $tr = $(this).parent().parent();
    $tr.remove();
}

function save_material() {
    var $id = $(this).data('save_material');
    $dprodId = $id.split('_')[1];
    $subaId   = $id.split('_')[2];

    var $table_body = $('#materiales_'+$dprodId+'_'+$subaId);
    var $description = $('#descripcion_'+$dprodId+'_'+$subaId).val();
    var $url = $('#url_material_create').val();
    var $modelo = $('#modelo'+$dprodId).val();
    var $color  = $('#color'+$dprodId).val();

    var $data = {};
    var $ids = [];
    var $names = [];
    var $descriptions = [];
    var $go_out = 0;
    $table_body.children().each(function (k,v) {
        var $name = $($(v).children()[0]).children();
        var $id   = $name.data('mat_id');
        $name     = $name.val();
        var $description = $($(v).children()[1]).children().val();

        if( $name.length == 0 ) {
            showmessage('Ingrese nombre de material.',0);
            $go_out = 1;
            return false;
        }

        $ids.push($id);
        $names.push($name);
        $descriptions.push($description);
    });

    if( $go_out == 1)
        return;

    $data.description = $description;
    $data.dprodId = $dprodId;
    $data.subaId  = $subaId;
    $data.modelo  = $modelo;
    $data.color   = $color;
    $data.ids     = $ids;
    $data.names   = $names;
    $data.descriptions = $descriptions;

    if( $requested == 1 ){
        return;
    }
    $requested = 1;

    $.ajax({
        url:$url,
        type:'post',
        data:$data,
        headers:{
            'X-CSRF-TOKEN': $('#_token').val()
        }
    }).done(function (data) {
        if( data.success=='true' ){
            showmessage(data.message,1);
            setTimeout(function () {
                var $url = $('#url_programacion_details').val();
                location.href = $url+'/'+data.id;
            },500);
        }
    });
}

function delete_material() {
    var $material_id = $(this).data('delete_material');
    $dprodId = $material_id.split('_')[0];
    $id   = $material_id.split('_')[1];
    var $url = $('#url_material_delete').val();
    $data = {};
    $data.dprodId = $dprodId;
    $data.id = $id;

    if( $requested == 1 ){
        return;
    }
    $requested = 1;

    $.ajax({
        url:$url,
        type:'post',
        data:$data,
        headers:{
            'X-CSRF-TOKEN': $('#_token').val()
        }
    }).done(function (data) {
        if( data.success=='true' ){
            showmessage(data.message,1);
            setTimeout(function () {
                var $url = $('#url_programacion_details').val();
                location.href = $url+'/'+data.id;
            },500);
        }
    });
}
