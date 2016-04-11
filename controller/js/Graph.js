
// the code in this file is called at the beginning of login

function updateGraph(values) {
    AmCharts.ready(function() {
        var chartData = [];
        var values = "assets|2016-04-10_10|2016-04-11_11|2016-04-12_12|2016-04-13_13|2016-04-14_14|2016-04-15_15"; //\nliabilities|2016-04-10_15|2016-04-11_16|2016-04-12_17|2016-04-13_18|2016-04-14_19|2016-04-15_20\nnetworth|2016-04-10_2|2016-04-11_3|2016-04-12_4|2016-04-13_5|2016-04-14_6|2016-04-15_7";
        var lines = values.split('\n');

        // nodes for assets line
        var nodes = lines[0].split('|');
        var name = nodes[0];
        for (k = 1; k < nodes.length; k++) {
            var values = nodes[k].split('_');
            var date = Date.parse(values[0]);
            var value = parseFloat(values[1]);
            chartData.push({
                date: values[0],
                assets: value
            });
        }

        // // nodes for liabilities line
        // var nodes = lines[1].split('|');
        // var name = nodes[0];
        // for (k = 1; k < nodes.length; k++) {
        //     var values = nodes[k].split('_');
        //     var date = Date.parse(values[0]);
        //     var value = parseFloat(values[1]);
        //     chartData.push({
        //         date: date,
        //         liabilities: value
        //     });
        // }

        // // nodes for net worth line
        // var nodes = lines[2].split('|');
        // var name = nodes[0];
        // for (k = 1; k < nodes.length; k++) {
        //     var values = nodes[k].split('_');
        //     var date = Date.parse(values[0]);
        //     var value = parseFloat(values[1]);
        //     chartData.push({
        //         date: date,
        //         networth: value
        //     });
        // }

        var chart = new AmCharts.AmSerialChart();
        chart.marginTop = 0;
        chart.marginRight = 10;
        chart.dataProvider = chartData;
        chart.categoryField = "date";

        var categoryAxis = chart.categoryAxis;
        categoryAxis.parseDates = true;
        categoryAxis.minPeriod = "YYYY-mm-dd";

        var graph = new AmCharts.AmGraph();
        graph.title = "red line";
        graph.valueField = "assets";
        graph.bullet = "round";
        graph.bulletBorderColor = "#FFFFFF";
        graph.bulletBorderThickness = 2;
        graph.lineThickness = 2;
        graph.lineColor = "#b5030d";
        graph.negativeLineColor = "#0352b5";
        graph.hideBulletsCount = 50; // this makes the chart to hide bullets when there are more than 50 series in selection
        chart.addGraph(graph);

        // var graph = new AmCharts.AmGraph();
        // graph.title = "Liabilities";
        // graph.valueField = "liabilities";
        // graph.bullet = "round";
        // graph.bulletBorderColor = "#FFFFFF";
        // graph.bulletBorderThickness = 2;
        // graph.lineThickness = 2;
        // graph.lineColor = "#b5030d";
        // chart.addGraph(graph);

        // var graph = new AmCharts.AmGraph();
        // graph.title = "Net Worth";
        // graph.valueField = "networth";
        // graph.bullet = "round";
        // graph.bulletBorderColor = "#FFFFFF";
        // graph.bulletBorderThickness = 2;
        // graph.lineThickness = 2;
        // graph.lineColor = "#b5030d";
        // chart.addGraph(graph);

        var chartCursor = new AmCharts.ChartCursor();
        chartCursor.cursorPosition = "mouse";
        chart.addChartCursor(chartCursor);

        var chartScrollbar = new AmCharts.ChartScrollbar();
        chartScrollbar.graph = graph;
        chartScrollbar.scrollbarHeight = 40;
        chartScrollbar.color = "#FFFFFF";
        chartScrollbar.autoGridCount = true;
        chart.addChartScrollbar(chartScrollbar);

        // chart.addListener("rendered", zoomChart);
        // if(chart.zoomChart){
        //     chart.zoomChart();
        // }

        chart.write("chartdiv");
    });

    // var chart = AmCharts.makeChart("chartdiv", {

    //     "chartScrollbar": {
    //         "graph":"g1",
    //         "gridAlpha":0,
    //         "color":"#888888",
    //         "scrollbarHeight":55,
    //         "backgroundAlpha":0,
    //         "selectedBackgroundAlpha":0.1,
    //         "selectedBackgroundColor":"#888888",
    //         "graphFillAlpha":0,
    //         "autoGridCount":true,
    //         "selectedGraphFillAlpha":0,
    //         "graphLineAlpha":0.2,
    //         "graphLineColor":"#c2c2c2",
    //         "selectedGraphLineColor":"#888888",
    //         "selectedGraphLineAlpha":1

    //     },
}

function zoomChart(){
    // chart.zoomToIndexes(Math.round(chart.dataProvider.length * 0.4), Math.round(chart.dataProvider.length * 0.55));
}
