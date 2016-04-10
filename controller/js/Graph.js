
// the code in this file is called at the beginning of login

function updateGraph(values) {
    var chartData = [];


    values = "assets|2016-04-10_10|2016-04-11_11|2016-04-12_12|2016-04-13_13|2016-04-14_14|2016-04-15_15\nliabilities|2016-04-10_15|2016-04-11_16|2016-04-12_17|2016-04-13_18|2016-04-14_19|2016-04-15_20\nnetworth|2016-04-10_2|2016-04-11_3|2016-04-12_4|2016-04-13_5|2016-04-14_6|2016-04-15_7";
    var lines = values.split('\n');

    // nodes for assets line
    var nodes = lines[0].split('|');
    var name = nodes[0];
    for (k = 1; k < nodes.length; k++) {
        var values = nodes[k].split('_');
        var date = new Date(values[0]);
        var value = parseFloat(values[1]);
        chartData.push({
            date: date,
            assets: value
        });
    }

    // nodes for liabilities line
    var nodes = lines[1].split('|');
    var name = nodes[0];
    for (k = 1; k < nodes.length; k++) {
        var values = nodes[k].split('_');
        var date = new Date(values[0]);
        var value = parseFloat(values[1]);
        chartData.push({
            date: date,
            liabilities: value
        });
    }

    // nodes for net worth line
    var nodes = lines[2].split('|');
    var name = nodes[0];
    for (k = 1; k < nodes.length; k++) {
        var values = nodes[k].split('_');
        var date = new Date(values[0]);
        var value = parseFloat(values[1]);
        chartData.push({
            date: date,
            networth: value
        });
    }

    var chart = AmCharts.makeChart("chartdiv", {
        "type": "serial",
        "theme": "none",
        "marginTop": 0,
        "marginRight": 10,
        "dataProvider": chartData,
        "graphs": [{
            "id":"g1",
                "balloonText": "[[category]]<br><b><span style='font-size:14px;'>[[assets]]</span></b>",
                "bullet": "round",
                "bulletSize": 8,         
                "lineColor": "#d1655d",
                "lineThickness": 2,
                "negativeLineColor": "#d1655d",
                "type": "smoothedLine",
                "valueField": "assets"
        }, {
            "id":"g2",
                "balloonText": "[[category]]<br><b><span style='font-size:14px;'>[[liabilities]]</span></b>",
                "bullet": "round",
                "bulletSize": 8,         
                "lineColor": "#d1655d",
                "lineThickness": 2,
                "negativeLineColor": "#d1655d",
                "type": "smoothedLine",
                "valueField": "liabilities"
        }, {
            "id":"g3",
                "balloonText": "[[category]]<br><b><span style='font-size:14px;'>[[networth]]</span></b>",
                "bullet": "round",
                "bulletSize": 8,         
                "lineColor": "#d1655d",
                "lineThickness": 2,
                "negativeLineColor": "#d1655d",
                "type": "smoothedLine",
                "valueField": "networth"
        }],
        "chartScrollbar": {
            "graph":"g1",
            "gridAlpha":0,
            "color":"#888888",
            "scrollbarHeight":55,
            "backgroundAlpha":0,
            "selectedBackgroundAlpha":0.1,
            "selectedBackgroundColor":"#888888",
            "graphFillAlpha":0,
            "autoGridCount":true,
            "selectedGraphFillAlpha":0,
            "graphLineAlpha":0.2,
            "graphLineColor":"#c2c2c2",
            "selectedGraphLineColor":"#888888",
            "selectedGraphLineAlpha":1

        },
        "chartCursor": {
            "categoryBalloonDateFormat": "YYYY-mm-dd",
            "cursorAlpha": 0,
            "valueLineEnabled":true,
            "valueLineBalloonEnabled":true,
            "valueLineAlpha":0.5,
            "fullWidth":true
        },
        "dataDateFormat": "YYYY-mm-dd",
        "categoryField": "date",
        "categoryAxis": {
            "minPeriod": "YYYY-mm-dd",
            "parseDates": true,
            "minorGridAlpha": 0.1,
            "minorGridEnabled": true
        },
        "export": {
            "enabled": true
        }
    });

    chart.addListener("rendered", zoomChart);
    if(chart.zoomChart){
        chart.zoomChart();
    }
}

function zoomChart(){
    chart.zoomToIndexes(Math.round(chart.dataProvider.length * 0.4), Math.round(chart.dataProvider.length * 0.55));
}
