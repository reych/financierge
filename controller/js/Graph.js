


var namesToDataArrays = new Map();
var dataSets = [];
// var allGraphs = [];

var chart = AmCharts.makeChart( "chartdiv", {
  "type": "stock",
  "theme": "light",

  "dataSets": dataSets,

  "panels": [ {
    "showCategoryAxis": false,
    "title": "Value",
    "percentHeight": 70,
    "stockGraphs": [{
      "id": "",
      "valueField": "value",
      "comparable": true,
      "compareField": "value",
      "balloonText": "[[title]]:<b>$[[value]]</b>",
      "compareGraphBalloonText": "[[title]]:<b>$[[value]]</b>"
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
  "stockLegend": {
            "periodValueTextComparing": "test",
            "periodValueTextRegular": "test"
        },

  "dataSetSelector": {
    "position": "left"
  },
  "panelsSettings":{"recalculateToPercents" : "never"},

  "export": {
    "enabled": true
  }
} );

generateChartData();


function generateChartData() {
    // var values = "Net Worth|2015-10-10_10|2016-01-11_11|2016-03-12_12|2016-04-13_13|2016-04-14_2\nLiabilities|2015-10-10_5|2016-01-11_2|2016-03-12_7|2016-04-13_8|2016-04-14_4\nAssets|2015-10-10_3|2016-01-11_-4|2016-03-12_9|2016-04-13_20|2016-04-14_-5\nChecking|2015-10-10_1|2016-01-11_20|2016-03-12_10|2016-04-13_5|2016-04-14_1\nSavings|2015-10-10_-11|2016-01-11_3|2016-03-12_16|2016-04-13_5|2016-04-14_7";
    var results = phpRequest('getBaseData', '');
    addOrUpdateAccount(results);
}     



function addOrUpdateAccount(accountData) {
  //var values = "assets|2015-10-10_10|2016-01-11_11|2016-03-12_12|2016-04-13_13|2016-04-14_2\nliabilities|2016-04-10_15|2016-04-11_16|2016-04-12_17|2016-04-13_12|2016-04-14_19|2016-04-15_20\nnetworth|2016-04-10_2|2016-04-11_3|2016-04-12_4|2016-04-13_5|2016-04-14_6|2016-04-15_7";
    var lines = accountData.split('\n');

    // for each line split from the string, which represents an account and its transactions
    for (i = 0; i < lines.length; i++) {
        var nodes = lines[i].split('|');
        var name = nodes[0];
        var dataArr = [];

        for(j = 1; j < nodes.length; j++) {

            var dateValues = nodes[j].split('_');
            var dateArr = dateValues[0].split('-');

            var newDate = new Date(parseInt(dateArr[0]), parseInt(dateArr[1]), 
                                   parseInt(dateArr[2]));

            var newValue = parseFloat(dateValues[1]);

            dataArr.push({
                    "date" : newDate,
                    "value" : newValue
            });
        }

        
        this.namesToDataArrays.set(name, dataArr);        
    }
    updateDataSets();

}

function updateDataSets() {
    this.dataSets = [];
    this.allGraphs = [];
    // var i = 0;
    for (var [name, dataArray] of this.namesToDataArrays) {

        if (name.localeCompare("Net Worth") === 0 
            || name.localeCompare("Assets") === 0
            || name.localeCompare("Liabilities") === 0) {

          this.dataSets.push({
                    "title": name,
            "fieldMappings": [{"fromField": "value", "toField": "value"}],
             "dataProvider": dataArray,
            "categoryField": "date",
                 "compared": true

              });
      } else {
          this.dataSets.push({
                    "title": name,
            "fieldMappings": [{"fromField": "value", "toField": "value"}],
             "dataProvider": dataArray,
            "categoryField": "date"

              });
      }


      // this.allGraphs.push({
      //   "id": name,
      // "valueField": "value",
      // "comparable": true,
      // "compareField": "value" + i,
      // "balloonText": "[[title]]:<b>$[[value]]</b>",
      // "compareGraphBalloonText": "[[title]]:<b>$[[value]]</b>"
      // });

      // i++;
    }

    //this.chart.panels.stockGraphs = this.allGraphs;
    this.chart.dataSets = this.dataSets;
    this.chart.validateNow();
    this.chart.validateData();
    
}

function testThisChart() {

   var values = "Jeffs Playtime Fun Money Bags|2015-10-10_-10|2016-01-11_-11|2016-03-12_-12|2016-04-13_13|2016-04-14_-2";
   addOrUpdateAccount(values);
    
}
