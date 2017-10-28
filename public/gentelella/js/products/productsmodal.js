$(document).on('ready', principal);

function principal()
{
    //FooTable
    $('.mytable').footable();

    $modalView = $('#modalView');
    $modalEditar = $('#modalEditar');
    $modalEliminar = $('#modalEliminar');

    $btnExcel = $('#btnExcel');

    $('[data-view]').on('click', showItemsModal);
    $('[data-id]').on('click', mostrarEditar);
    $('[data-delete]').on('click', mostrarEliminar);
}

// Modals
var $modalView;
var $modalEditar;
var $modalEliminar;

// Buttons
var $btnExcel;

function showItemsModal() {
    var $tr = $(this).parents('tr');
    var product_id = $tr.find('[data-id]').data('id');
    var product_name = $tr.find('[data-name]').data('name');
    $('#selected_product').text(product_name);

    $.getJSON('./producto/'+product_id+'/items', function (data) {
        $('#items_quantity').text(data.length);
        var item_rows = '';
        for (var i=0; i<data.length; ++i) {
            item_rows += '<tr><td>'+data[i].series+'</td><td>'+data[i].current_location+'</td></tr>';
        }
        replaceExcelProductId(product_id);
        $('#items_tbody').html(item_rows);
    });
    $modalView.modal('show');
}

function replaceExcelProductId(product_id) {
    var excel_url = $btnExcel.attr('href');
    var lastPosition = excel_url.lastIndexOf('/');
    excel_url = excel_url.substr(0, lastPosition+1) + product_id;
    $btnExcel.attr('href', excel_url);
}

function mostrarEditar() {
    var id = $(this).data('id');
    $modalEditar.find('[name="id"]').val(id);

    var name = $(this).data('name');
    $modalEditar.find('[name="name"]').val(name);

    var description = $(this).data('description');
    $modalEditar.find('[name="description"]').val(description);

    var price = $(this).data('price');
    $modalEditar.find('[name="price"]').val(price);

    var money = $(this).data('money');


    if( money ==1 )
        $("#soles").prop("checked", true);
    else
        $("#dollar").prop("checked", true);

    var series = $(this).data('series');

    if( series ==1 )
        $("#series").prop("checked", true);

    var brand = $(this).data('brand');
    $modalEditar.find('[name="brands"]').val(brand);

    var exemplar = $(this).data('exemplar');
    $modalEditar.find('[name="exemplars"]').val(exemplar);

    var part_number = $(this).data('part');
    $modalEditar.find('[name="part_number"]').val(part_number);

    var color = $(this).data('color');
    $modalEditar.find('[name="color"]').val(color);

    var category = $(this).data('category');
    $modalEditar.find('[name="categories"]').val(category);

    var subcategory = $(this).data('subcategory');
    $modalEditar.find('[name="subcategories"]').val(subcategory);

    var image = $(this).data('image');
    $modalEditar.find('[name="oldImage"]').val(image);
    var image_url = '../public/images/products/'+image;
    $("#oldImage").html('<img src="'+image_url+'" class="img-responsive image"> ');

    var comment = $(this).data('comment');
    $modalEditar.find('[name="comment"]').val(comment);

    $.getJSON("producto/categoria",function(data)
    {
        $("#categories").empty();
        $.each(data,function(key,value)
        {
            if( value.id == category )
                $("#categories").append(" <option value='" + value.id+"' selected='selected'>" + value.name  + "</option> ");
            else
                $("#categories").append(" <option value='" + value.id+"' >" + value.name  + "</option> ");
        });
    });

    $.getJSON("producto/subcategoria/"+category,function(data)
    {
        $("#subcategories").empty();
        $.each(data,function(key,value)
        {
            if( value.id == subcategory )
                $("#subcategories").append(" <option value='" + value.id+"' selected='selected'>" + value.name  + "</option> ");
            else
                $("#subcategories").append(" <option value='" + value.id+"' >" + value.name  + "</option> ");
        });
    });

    $.getJSON("producto/marca",function(data)
    {
        $("#brands").empty();
        $.each(data,function(key,value)
        {
            if( value.id == brand )
                $("#brands").append(" <option value='" + value.id+"' selected='selected'>" + value.name  + "</option> ");
            else
                $("#brands").append(" <option value='" + value.id+"' >" + value.name  + "</option> ");
        });
    });

    $.getJSON("producto/modelo/"+brand,function(data)
    {
        $("#exemplars").empty();
        $.each(data,function(key,value)
        {
            if( value.id == exemplar )
                $("#exemplars").append(" <option value='" + value.id+"' selected='selected'>" + value.name  + "</option> ");
            else
                $("#exemplars").append(" <option value='" + value.id+"' >" + value.name  + "</option> ");
        });
    });


    $('#categories').change( function(){
        var categoria = $(this).val();
        $("#subcategories").empty();
        $.getJSON('producto/subcategoria/'+categoria,function(data)
        {
            $.each(data,function(key,value)
            {
                $("#subcategories").append(" <option value='" + value.id+"'>" + value.name  + "</option> ");
            });
        });
    } );

    $('#brands').change( function(){
        var marca = $(this).val();
        $("#exemplars").empty();
        $.getJSON("producto/modelo/"+marca,function(data)
        {
            $.each(data,function(key,value)
            {
                $("#exemplars").append(" <option value='" + value.id+"'>" + value.name  + "</option> ");
            });
        });
    } );

    $modalEditar.modal('show');
}

function mostrarEliminar() {
    if( access_denied ){
        alert('Usted no tiene permisos para esta acción');
        return;
    }

    var id = $(this).data('delete');
    $modalEliminar.find('[name="id"]').val(id);

    var name = $(this).data('name');
    $modalEliminar.find('[name="nombreEliminar"]').val(name);
    $modalEliminar.modal('show');
}