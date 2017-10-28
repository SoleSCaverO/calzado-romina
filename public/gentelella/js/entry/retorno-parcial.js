function showDevolutionDetails(id) {
    $.ajax({
        url: 'retorno/'+id,
        dataType: 'json',
        cache: false,
        success: function(data) {
            var items = data.items;
            var packages = data.packages;

            for (var i=0; i<items.length; ++i)
                renderTemplateDetail(items[i].item.product_name, items[i].item.series, items[i].price, items[i].returned);

            for (var i=0; i<packages.length; ++i)
                renderTemplateDetail('Paquete', packages[i].package.name, packages[i].price, packages[i].returned);
        },
        error: function(xhr, status, err) {
            console.error(id, status, err.toString());
        }
    });
}

// Template HTML5
function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

function renderTemplateDetail(name, code, price, returned) {
    var clone = activateTemplate('#template-detail');

    clone.querySelector("[data-name]").innerHTML = name;
    clone.querySelector("[data-code]").innerHTML = code;
    clone.querySelector("[data-price]").innerHTML = price;
    if (returned) {
        // Checkbox readonly
        clone.querySelector("input").disabled = true;
        clone.querySelector("input").checked = true;
    } else {
        // Set checkbox data
        if (name=='Paquete')
            clone.querySelector("input").name = 'packages[]';
        else
            clone.querySelector("input").name = 'items[]';

        clone.querySelector("input").value = code;
    }


    $('#devolution-details').append(clone);
}
