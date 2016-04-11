

// var allGraphs = 


var chart = AmCharts.makeChart("chartdiv", {
    "type": "serial",
    "theme": "none",
    "marginTop":0,
    "marginRight": 40,
    "dataProvider": [{"date": "1980-12-02",
    "checking" : .177},
    {
        "date": "1980-12-02",
        "value": 0.077
    }, {
        "date": "1981-12-02",
        "checking" : .177
    }, {
        "date": "1982-12-02",
        "checking" : .177
    }, {
        "date": "1983-12-02",
        "value": 0.177,
        "checking" : .177
    }, {
        "date": "1984-12-02",
        "value": -0.021,
        "checking" : .177
    }, {
        "date": "1985-12-02",
        "value": -0.037,
        "checking" : .177
    }, {
        "date": "1986-12-02",
        "value": 0.03,
        "checking" : .177
    }, {
        "date": "1987-12-02",
        "value": 0.179
    }, {
        "date": "1988-12-02",
        "value": 0.18
    }, {
        "date": "1989-12-02",
        "value": 0.104
    }, {
        "date": "1990-12-02",
        "value": 0.255
    }, {
        "date": "1991-12-02",
        "value": 0.2
    }, {
        "date": "1992-12-02",
        "value": 0.065
    }, {
        "date": "1993-12-02",
        "value": 0.11
    }, {
        "date": "1994-12-02",
        "value": 0.172
    }, {
        "date": "1995-12-02",
        "value": 0.269
    }, {
        "date": "1996-12-02",
        "value": 0.141
    }, {
        "date": "1997-12-02",
        "value": 0.353
    }, {
        "date": "1998-12-02",
        "value": 0.548
    }, {
        "date": "1999-12-02",
        "value": 0.298
    }, {
        "date": "2000-12-02",
        "value": 0.267
    }, {
        "date": "2001-12-02",
        "value": 0.411
    }, {
        "date": "2002-12-02",
        "value": 0.462
    }, {
        "date": "2003-12-02",
        "value": 0.47
    }, {
        "date": "2004-12-02",
        "value": 0.445
    }, {
        "date": "2005-12-02",
        "value": 0.47
    }],
    "valueAxes": [{
        "axisAlpha": 0,
        "position": "left"
    }],
    "graphs": [{
        "id":"g1",
        "balloonText": "[[category]]<br><b><span style='font-size:14px;'>[[value]]</span></b>",
        "bullet": "round",
        "lineColor": "#d1655e",
        // "type": "smoothedLine",
        "valueField": "value"
    },{
        "id":"g2",
        "balloonText": "[[category]]<br><b><span style='font-size:14px;'>[[value]]</span></b>",
        "bullet": "round",
        "bulletSize": 8,         
        "lineColor": "#d1655d",
        "type": "smoothedLine",
        "valueField": "checking"

    }],
    "chartScrollbar": {
        "graph":"",
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
        "categoryBalloonDateFormat": "YYYY-MM-DD",
        "cursorAlpha": 0,
        "valueLineEnabled":true,
        "valueLineBalloonEnabled":true,
        "valueLineAlpha":0.5,
        "fullWidth":true
    },
    "dataDateFormat": "YYYY-MM-DD",
    "categoryField": "date",
    "categoryAxis": {
        "minPeriod": "DD",
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

function zoomChart(){
    chart.zoomToIndexes(Math.round(chart.dataProvider.length * 0.4), Math.round(chart.dataProvider.length * 0.55));
}

function testThisChart() {

    var graph = new AmCharts.AmGraph();
    graph.valueField = "savings";
    graph.type = "line";
    graph.id = "newOne";
    chart.removeGraph(graph);
    chart.addGraph(graph);
    chart.dataProvider = [];
    chart.dataProvider = [{
        "date": "1990-12-02",
        "savings": 0.255
    }, {
        "date": "1991-12-02",
        "savings": 0.21
    }, {
        "date": "1992-12-02",
        "savings": 0.065
    }, {
        "date": "1993-12-02",
        "savings": 0.11
    }];

    chart.validateNow();
    chart.validateData();
}