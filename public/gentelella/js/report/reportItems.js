var local;
var shelf;
var level;
var box;
$(document).on('ready', function () {

    local = $("#locals").val();
    shelf;
    level;
    box;
    $.getJSON('../locals/shelves/'+local,function(data)
    {
        shelf = data[0].name;
        $.each(data,function(key,value)
        {
            $("#shelves").append(" <option value='" + value.name+"'>" + value.name  + "</option> ");
        });
        $.getJSON("../shelves/levels/"+data[0].name,function(data)
        {
            $.each(data,function(key,value)
            {
                $("#levels").append(" <option value='" + value.name+"'>" + value.name  + "</option> ");
            });
            level = data[0].name;
            $.getJSON("../levels/boxes/"+data[0].name,function(data)
            {
                $.each(data,function(key,value)
                {
                    $("#boxes").append(" <option value='" + value.name+"'>" + value.name  + "</option> ");
                });
                console.log('caja   '+data[0].name);
                box = data[0].name;
                var full_name = local+'-'+shelf+'-'+level+'-'+box;
                console.log(full_name);
                $.getJSON("../boxes/items/"+full_name,function(data)
                {
                    $('#bodyItems').html('');
                    $.each(data,function(i,e)
                    {
                        renderTemplateItem(e.name, e.series, e.location, 1, e.state);
                    });
                });
            });

        });


    });


    $('#locals').on('change', loadShelves);
    $('#shelves').on('change', loadLevels);
    $('#levels').on('change', loadBoxes);
    $('#boxes').on('change', loadItems);
});

function loadShelves() {
    local = $(this).val();
    //loadItems();
    $("#shelves").empty();
    $.getJSON("../locals/shelves/"+local,function(data)
    {

        $.each(data,function(key,value)
        {
            $("#shelves").append(" <option value='" + value.name+"'>" + value.name  + "</option> ");
        });
    });
}

function loadLevels() {
    shelf = $(this).val();

    //loadItems();
    $("#levels").empty();
    $.getJSON("../shelves/levels/"+shelf,function(data)
    {
        $("#levels").append(" <option value=''>Elija un nivel </option> ");
        $.each(data,function(key,value)
        {
            $("#levels").append(" <option value='" + value.name+"'>" + value.name  + "</option> ");
        });
    });
}

function loadBoxes() {
    level = $(this).val();

    //loadItems();
    $("#boxes").empty();
    $.getJSON("../levels/boxes/"+level,function(data)
    {
        $("#boxes").append(" <option value=''>Elija una caja </option> ");
        $.each(data,function(key,value)
        {
            $("#boxes").append(" <option value='" + value.name+"'>" + value.name  + "</option> ");
        });
    });
}

function loadItems() {
    local = $('#locals').val();
    shelf = $('#shelves').val();
    level = $('#levels').val();
    box = $(this).val();
    var full_name = local+'-'+shelf+'-'+level+'-'+box;
    console.log('Buscado  '+full_name);
    //$("#boxes").empty();
    $.getJSON("../boxes/items/"+full_name,function(data)
    {
        $('#bodyItems').html('');
        $.each(data,function(i,e)
        {
            renderTemplateItem(e.name, e.series, e.location, 1, e.state);
        });
    });
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}
function renderTemplateItem(name, series, location, quantity, state) {

    var clone = activateTemplate('#template-item');

    clone.querySelector("[data-name]").innerHTML = name;
    clone.querySelector("[data-series]").innerHTML = series;
    clone.querySelector("[data-quantity]").innerHTML = quantity;
    clone.querySelector("[data-location]").innerHTML = location;
    clone.querySelector("[data-state]").innerHTML = state;

    $('#bodyItems').append(clone);
    
}