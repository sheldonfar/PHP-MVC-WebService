google.load('visualization', '1.0', {'packages':['corechart']});
google.setOnLoadCallback(drawChart);
function drawChart() {

    var data = [];
    for(var z = 0; z < 6; z++) {
        data[z] = new google.visualization.DataTable();
        data[z].addColumn('string', 'Topping');
        data[z].addColumn('number', 'Slices');
    }

    var count = 0;

    $('.cat').each(function() {
        count++;
    });

    var arr = [];
    for (var i = 0; i < count; i++)
        arr[i] = [];

    var j = 0;
    for(var i = 0; i < count; i++){
        $('table tr td:nth-child('+(i+3)+')').each(function() {
            arr[i][j] = parseFloat($(this).text()).toFixed(2)-0;
            j++;
        });
        j = 0;
    }
    var options = [];
    options[0] = {'title':'Product count', 'width':400,'height':300, chartArea:{top:20, width:'140%',height:'100%'}};
    options[1] = {'title':'Maximum price', 'width':400,'height':300, chartArea:{top:20, width:'140%',height:'100%'}};
    options[2] = {'title':'Minimum price', 'width':400,'height':300, chartArea:{top:20, width:'140%',height:'100%'}};
    options[3] = {'title':'Average price', 'width':400,'height':300, chartArea:{top:20, width:'140%',height:'100%'}};
    options[4] = {'title':'Sum of prices', 'width':400,'height':300, chartArea:{top:20, width:'140%',height:'100%'}};
    options[5] = {'title':'Longest product name', 'width':400,'height':300, chartArea:{top:20, width:'140%',height:'100%'}};


    var chart = [];
    for(var k = 0; k < 6; k++) {
        data[k].addRows([
            ['Product #1', arr[0][k]],
            ['Product #2', arr[1][k]],
            ['Product #3', arr[2][k]]
        ]);

        chart[k] = new google.visualization.PieChart(document.getElementById('chart_div_' + k));
        chart[k].draw(data[k], options[k]);
    }

}