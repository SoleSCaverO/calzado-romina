/**
 * Created by SunS on 28/06/2016.
 */

$(document).on('ready', principal);

function principal()
{
    var randomScalingFactor = function() {
        return Math.round(Math.random() * 100);
    };

    // Variables
    var PAGE_NAMES = ["Page 0", "Page 1", "Page 2", "Page 3", "Page 4", "Page 5", "Page 6"];
    var USER_LABELS = ["Items"];

    // Random color
    var randomColorFactor = function() {
        return Math.round(Math.random() * 255);
    };
    var randomColor = function(opacity) {
        return 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',' + (opacity || '.3') + ')';
    };

    var config = {
        type: 'horizontalBar',
        data: {
            labels: PAGE_NAMES,
            datasets: [{
                label: USER_LABELS[0],
                data: [], // [65, 59, 80, 81, 56, 55, 40],
                fill: false,
                borderDash: [5, 5]
            }]
        },
        options: {
            title:{
                display: true,
                text: "Los productos con más items"
            },
            animation: {
                duration: 2000
            },
            tooltips: {
                mode: 'label'
            },
            scales: {
                xAxes: [{
                    scaleLabel: {
                        show: true,
                        labelString: 'Pages',
                    },
                    ticks: {
                        suggestedMin: 0 // minimum will be 0, unless there is a lower value.
                    }
                }],
                yAxes: [{
                    scaleLabel: {
                        show: true,
                        labelString: 'Value'
                    },
                    ticks: {
                        suggestedMin: 0 // minimum will be 0, unless there is a lower value.
                    }
                }]
            }
        }
    };

    $.each(config.data.datasets, function(i, dataset) {
        dataset.borderColor = randomColor(0.4);
        dataset.backgroundColor = randomColor(0.5);
        dataset.pointBorderColor = randomColor(0.7);
        dataset.pointBackgroundColor = randomColor(0.5);
        dataset.pointBorderWidth = 1;
    });

    window.onload = function() {
        var ctx = document.getElementById("canvas").getContext("2d");
        window.myLine = new Chart(ctx, config);

        loadMonths();
    };

    $('#graficar').click(function (event) {
        event.preventDefault();

        var year = $('#anio').val();
        var month  = $('#mes').val();

        $('#loading').show();
        $('#canvas').slideUp();

        var pageDataSet = {
            label: USER_LABELS[0],
            borderColor: randomColor(0.4),
            backgroundColor: randomColor(0.5),
            pointBorderColor: randomColor(0.7),
            pointBackgroundColor: randomColor(0.5),
            pointBorderWidth: 1,
            data: []
        };

        $.getJSON('data_bar/'+year+'/'+month, function(data) {

            $('#loading').hide();
            $('#canvas').slideDown();

            config.data.datasets = [];
            config.data.labels = data.name;
            pageDataSet.data = data.quantity;
            config.data.datasets.push(pageDataSet);

            window.myLine.update();
        });
    })
}

function loadMonths()
{
    $('#anio').change(  function(){
        $year = $(this).val();

        $.getJSON('month/'+$year, function(data)
        {
            $('#mes').html('');

            $.each(data.name,function(key,value)
            {
                var month = convert_month_number(value);
                $("#mes").append('<option value="'+month+'">'+value+'</option>');
            });
        });
    });
}

function convert_month_number($month_name )
{
    switch( $month_name ) {
        case 'Enero':
            return 1;
        case 'Febrero':
            return 2;
        case 'Marzo':
            return 3;
        case 'Abril':
            return 4;
        case 'Mayo':
            return 5;
        case  'Junio':
            return 6;
        case  'Julio':
            return 7;
        case  'Agosto':
            return 8;
        case  'Setiembre':
            return 9;
        case 'Octubre':
            return 10;
        case  'Noviembre':
            return 11;
        case 'Diciembre':
            return 12;
    }
}