$(document).on('ready', principal);

function principal()
{
    var category = $("#categories").val();
    $.getJSON('../producto/subcategoria/'+category,function(data)
    {
        $.each(data,function(key,value)
        {
            $("#subcategories").append(" <option value='" + value.id+"'>" + value.name  + "</option> ");
        });
    });

    var brand = $("#brands").val();
    $.getJSON("../producto/modelo/"+brand,function(data)
    {
        $.each(data,function(key,value)
        {
            $("#exemplars").append(" <option value='" + value.id+"'>" + value.name  + "</option> ");
        });
    });

//Index
    $('#categories').change( function(){
        var categoria = $(this).val();
        $("#subcategories").empty();
        $.getJSON("../producto/subcategoria/"+categoria,function(data)
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
        $.getJSON("../producto/modelo/"+marca,function(data)
        {
            $.each(data,function(key,value)
            {
                $("#exemplars").append(" <option value='" + value.id+"'>" + value.name  + "</option> ");
            });
        });
    } );
}