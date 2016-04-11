
var namesToDataArrays = new Map();
var dataSets = [];

generateChartData();


function generateChartData() {

    var values = "assets|2016-04-10_10|2016-04-11_11|2016-04-12_12|2016-04-13_13|2016-04-14_14|2016-04-15_15\nliabilities|2016-04-10_15|2016-04-11_16|2016-04-12_17|2016-04-13_18|2016-04-14_19|2016-04-15_20\nnetworth|2016-04-10_2|2016-04-11_3|2016-04-12_4|2016-04-13_5|2016-04-14_6|2016-04-15_7";
    var lines = values.split('\n');


    for (i = 0; i < lines.length; i++) {
        var nodes = lines[i].split('|');
        var name = nodes[0];
        var dataArr = [];

        for(j = 1; j < nodes.length; j++) {

            var dateValues = nodes[j].split('_');
            var dateArr = dateValues[0].split('-');

            var newDate = new Date(parseInt(dateArr[0]), parseInt(dateArr[1]), parseInt(dateArr[2]));

            var newValue = parseFloat(dateValues[1]);

            dataArr.push({
                    "date" : newDate,
                    "value" : newValue
            });
        }

        dataSets.push({
                  "title": name,
          "fieldMappings": [{"fromField": "value", "toField": "value"}],
           "dataProvider": dataArr,
          "categoryField": "date"

            });
        namesToDataArrays.set(name, dataArr);        
    }
}

var chart = AmCharts.makeChart( "chartdiv", {
  "type": "stock",
  "theme": "light",

  "dataSets": dataSets,

  "panels": [ {
    "showCategoryAxis": false,
    "title": "Value",
    "percentHeight": 70,
    "stockGraphs": [ {
      "id": "g1",
      "valueField": "value",
      "comparable": true,
      "compareField": "value",
      "balloonText": "[[title]]:<b>[[value]]</b>",
      "compareGraphBalloonText": "[[title]]:<b>[[value]]</b>"
    } ]
  }],

  "chartScrollbarSettings": {
    "graph": ""
  },

  "chartCursorSettings": {
    "valueBalloonsEnabled": true,
    "fullWidth": true,
    "cursorAlpha": 0.1,
    "valueLineBalloonEnabled": true,
    "valueLineEnabled": true,
    "valueLineAlpha": 0.5
  },

  "periodSelector": {
    "position": "left",
    "periods": [ {
      "period": "MM",
      "selected": true,
      "count": 1,
      "label": "1 month"
    }, {
      "period": "YYYY",
      "count": 1,
      "label": "1 year"
    }, {
      "period": "YTD",
      "label": "YTD"
    }, {
      "period": "MAX",
      "label": "MAX"
    } ]
  },

  "dataSetSelector": {
    "position": "left"
  },

  "export": {
    "enabled": true
  }
} );

function testThisChart() {

   //  var graph = new AmCharts.AmGraph();
   //  graph.valueField = "savings";
   //  graph.type = "line";
   //  graph.id = "newOne";
   // // chart.removeGraph(graph);
   //  chart.addGraph(graph);
    chart.dataSets = [];
    chart.dataSets = [{
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