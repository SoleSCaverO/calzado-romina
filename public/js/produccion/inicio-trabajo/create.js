$(document).on('ready',main);

function main() {
    var $body = $('body');
    var $time = $('#time');
    $time.timepicker('setTime', new Date());
    $body.on('click','[data-search]',search);
    $body.on('keypress','#worker_code',search);
    $body.on('click','[data-change_type_work]',change_type_work);
    $body.on('click','[data-search_order]',search_order);
    $body.on('keypress','#search_order',search_order);
    $body.on('click','[data-save_start_work]',save_start_work);
}

function search(e) {
    if( e.which == 13 || e.which == 1 ) {
        var $worker_code = $('#worker_code').val();

        if ($worker_code.length == 0) {
            showmessage('Ingrese el código del trabajador.', 0);
            return;
        }

        var $url = $('#url_worker_data').val() + '/' + $worker_code;
        $.ajax({
            url: $url,
            type: 'get'
        }).done(function (data) {
            if (data.success == 'true') {
                var $area_id = $('#area_id');
                var $subarea_id = $('#subarea_id');
                var $type_work = $('#type_work');
                var $type_work_id = $('#type_work_id');
                var $nombres = $('#nombres');
                $area_id.val(data.data.area);
                $subarea_id.val(data.data.subarea);
                $type_work.val(data.data.type_work_name);
                $type_work_id.val(data.data.type_work_id);
                $nombres.val(data.data.nombres);

            } else
                showmessage(data.message, 0)
        });
    }
}

function change_type_work() {
    var $worker_code = $('#worker_code').val();
    var $type_work_id = $('#type_work_id').val();
    if( $type_work_id.length == 0  )
        showmessage('Primero busque un trabajador.','0');
    var $url = $('#url_change_type_work').val()+'/'+$worker_code+'/'+$type_work_id;
    $.ajax({
        url:$url,
        type:'get'
    }).done(function (data) {
        if( data.success=='true'){
            var $type_work_id = $('#type_work_id');
            var $type_work = $('#type_work');
            $type_work_id.val(data.data.type_id);
            $type_work.val(data.data.type_name);
        }else
            showmessage(data.message,0)
    });
}

function search_order(e) {
    if( e.which == 13 || e.which == 1 ) {
        var $search_order = $('#search_order').val();

        if ($search_order.length == 0) {
            showmessage('Ingrese el código de la orden.', 0);
            return;
        }

        var $url = $('#url_search_order').val() + '/' + $search_order;
        $.ajax({
            url: $url,
            type: 'get'
        }).done(function (data) {
            if (data.success == 'true') {
                var $pairs = $('#pairs');
                var $disponible = $('#disponible');
                $pairs.val(data.data);
                $disponible.val(data.data - data.cant_asignada);
            } else
                showmessage(data.message, 0)
        });
    }
}

function save_start_work() {
    var $data = {};
    var $pairs =  $('#pairs').val();
    $data.worker_code = $('#worker_code').val();
    $data.date = $('#date').val();
    $data.time = $('#time').val();
    $data.type_work_id = $('#type_work_id').val();
    $data.search_order = $('#search_order').val();
    $data.pairs_user = $('#pairs_user').val();
    $data.description = $('#description').val();
    var $url = $('#url_inicio_trabajo_store').val();

    if( $data.worker_code.length == 0 ) {
        showmessage('Debe buscar un trabajador.',0);
        return;
    }

    if( $data.date.length == 0 ) {
        showmessage('Seleccione la fecha de registro.',0);
        return;
    }

    if( $data.search_order.length == 0 ) {
        showmessage('Debe buscar una orden.',0);
        return;
    }

    if( $data.pairs_user.length == 0 ) {
        showmessage('Debe ingresar la cantidad de pares a trabajar en la orden.',0);
        return;
    }

    if( $data.pairs_user < 1 ) {
        showmessage('La cantidad de pares a trabajar, debe ser positiva.',0);
        return;
    }

    if( parseInt($data.pairs_user) > parseInt($pairs) ) {
        showmessage('La cantidad de pares a trabajar, no puede ser mayor a la cantidad de pares de la orden.',0);
        return;
    }

    $.ajax({
        url:$url,
        type:'post',
        data:$data,
        headers:{
            'X-CSRF-TOKEN': $('#_token').val()
        }
    }).done(function (data) {
        if( data.success=='true'){
            showmessage(data.message,1);
            setTimeout(function () {
                var $back_start = $('#back_start');
                location.href = $back_start.attr('href');
            },500);
        }else
            showmessage(data.message,0)
    });
}
