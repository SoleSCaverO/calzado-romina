function paginate()
{
    $('#data').after('<div class="pagination"><ul id="nav" class="pagination"><li class="disabled"><span><<</span></li></ul></div>');
    var rowsShown = 2;
    var rowsTotal = $('#data tbody tr').length;
    var numPages = rowsTotal/rowsShown;
    for(i = 0;i < numPages;i++) {
        var pageNum = i + 1;
        $('#nav').append('<li><a href="#" rel="'+i+'">'+pageNum+'</a></li>');
    }
    $('#nav').append('<li class="disabled"><span>>></span></li>');

    $('#data tbody tr').hide();
    $('#data tbody tr').slice(0, rowsShown).show();
    $('#nav li:nth-child(2)').addClass('active');
    $('#nav a').bind('click', function(){

        $('#nav li').removeClass('active');
        $(this).parent().addClass('active');
        var currPage = $(this).attr('rel');
        var startItem = currPage * rowsShown;
        var endItem = startItem + rowsShown;
        $('#data tbody tr').css('opacity','0.0').hide().slice(startItem, endItem).
        css('display','table-row').animate({opacity:1}, 300);
    });
}
