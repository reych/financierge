
var namesToDataArrays = new Map();
var dataSets = [];

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
  "panelsSettings":{"recalculateToPercents" : "never"},

  "export": {
    "enabled": true
  }
} );

generateChartData();


function generateChartData() {

    var values = "assets|2015-10-10_10|2016-01-11_11|2016-03-12_12|2016-04-13_13|2016-04-14_2\nliabilities|2016-04-10_15|2016-04-11_16|2016-04-12_17|2016-04-13_12|2016-04-14_19|2016-04-15_20\nnetworth|2016-04-10_2|2016-04-11_3|2016-04-12_4|2016-04-13_5|2016-04-14_6|2016-04-15_7";
    addOrUpdateAccount(values);

    /*
    var lines = values.split('\n');

    // for each line split from the string, which represents an account and its transactions
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

        this.dataSets.push({
                  "title": name,
          "fieldMappings": [{"fromField": "value", "toField": "value"}],
           "dataProvider": dataArr,
          "categoryField": "date"

            });
        this.namesToDataArrays.set(name, dataArr);        
    }
    */
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

            var newDate = new Date(parseInt(dateArr[0]), parseInt(dateArr[1]), parseInt(dateArr[2]));

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
    for (var [name, dataArray] of this.namesToDataArrays) {
        this.dataSets.push({
                  "title": name,
          "fieldMappings": [{"fromField": "value", "toField": "value"}],
           "dataProvider": dataArray,
          "categoryField": "date"

            });
    }

    this.chart.dataSets = this.dataSets;
    this.chart.validateNow();
    this.chart.validateData();
    
}

function testThisChart() {

   var values = "Jeffs Playtime Fun Money Bags|2015-10-10_-10|2016-01-11_-11|2016-03-12_-12|2016-04-13_13|2016-04-14_-2";
   addOrUpdateAccount(values);
    
}





// document.querySelector("#go").addEventListener("mousedown", function () {
//   var from = new Date(document.querySelector("#from").value);
//   var to = new Date(document.querySelector("#to").value);
  
//   chart.zoom(from, to);
// });

// function updateGraph($values) {


// var namesToDataArrays = new Map();
// var dataSets = [];

// var chart = AmCharts.makeChart( "chartdiv", {
//   "type": "stock",
//   "theme": "light",

//   "dataSets": dataSets,

//   "panels": [ {
//     "showCategoryAxis": false,
//     "title": "Value",
//     "percentHeight": 70,
//     "stockGraphs": [ {
//       "id": "g1",
//       "valueField": "value",
//       "comparable": true,
//       "compareField": "value",
//       "balloonText": "[[title]]:<b>[[value]]</b>",
//       "compareGraphBalloonText": "[[title]]:<b>[[value]]</b>"
//     } ]
//   }],

//   "chartScrollbarSettings": {
//     "graph": ""
//   },

//   "chartCursorSettings": {
//     "valueBalloonsEnabled": true,
//     "fullWidth": true,
//     "cursorAlpha": 0.1,
//     "valueLineBalloonEnabled": true,
//     "valueLineEnabled": true,
//     "valueLineAlpha": 0.5
//   },

//   "periodSelector": {
//     "position": "left",
//     "periods": [ {
//       "period": "MM",
//       "selected": true,
//       "count": 1,
//       "label": "1 month"
//     }, {
//       "period": "YYYY",
//       "count": 1,
//       "label": "1 year"
//     }, {
//       "period": "YTD",
//       "label": "YTD"
//     }, {
//       "period": "MAX",
//       "label": "MAX"
//     } ]
//   },

//   "dataSetSelector": {
//     "position": "left"
//   },
//   "panelsSettings":{"recalculateToPercents" : "never"},

//   "export": {
//     "enabled": true
//   }
// } );

// generateChartData();


// function generateChartData() {

//     var values = "assets|2015-10-10_10|2016-01-11_11|2016-03-12_12|2016-04-13_13|2016-04-14_2\nliabilities|2016-04-10_15|2016-04-11_16|2016-04-12_17|2016-04-13_12|2016-04-14_19|2016-04-15_20\nnetworth|2016-04-10_2|2016-04-11_3|2016-04-12_4|2016-04-13_5|2016-04-14_6|2016-04-15_7";
//     addOrUpdateAccount(values);

    
//     var lines = values.split('\n');

//     // for each line split from the string, which represents an account and its transactions
//     for (i = 0; i < lines.length; i++) {
//         var nodes = lines[i].split('|');
//         var name = nodes[0];
//         var dataArr = [];

//         for(j = 1; j < nodes.length; j++) {

//             var dateValues = nodes[j].split('_');
//             var dateArr = dateValues[0].split('-');

//             var newDate = new Date(parseInt(dateArr[0]), parseInt(dateArr[1]), parseInt(dateArr[2]));

//             var newValue = parseFloat(dateValues[1]);

//             dataArr.push({
//                     "date" : newDate,
//                     "value" : newValue
//             });
//         }

//         this.dataSets.push({
//                   "title": name,
//           "fieldMappings": [{"fromField": "value", "toField": "value"}],
//            "dataProvider": dataArr,
//           "categoryField": "date"

//             });
//         this.namesToDataArrays.set(name, dataArr);        
//     }
    
// }



// function addOrUpdateAccount(accountData) {
//   //var values = "assets|2015-10-10_10|2016-01-11_11|2016-03-12_12|2016-04-13_13|2016-04-14_2\nliabilities|2016-04-10_15|2016-04-11_16|2016-04-12_17|2016-04-13_12|2016-04-14_19|2016-04-15_20\nnetworth|2016-04-10_2|2016-04-11_3|2016-04-12_4|2016-04-13_5|2016-04-14_6|2016-04-15_7";
//     var lines = accountData.split('\n');

//     // for each line split from the string, which represents an account and its transactions
//     for (i = 0; i < lines.length; i++) {
//         var nodes = lines[i].split('|');
//         var name = nodes[0];
//         var dataArr = [];

//         for(j = 1; j < nodes.length; j++) {

//             var dateValues = nodes[j].split('_');
//             var dateArr = dateValues[0].split('-');

//             var newDate = new Date(parseInt(dateArr[0]), parseInt(dateArr[1]), parseInt(dateArr[2]));

//             var newValue = parseFloat(dateValues[1]);

//             dataArr.push({
//                     "date" : newDate,
//                     "value" : newValue
//             });
//         }

        
//         this.namesToDataArrays.set(name, dataArr);        
//     }
//     updateDataSets();

// }

// function updateDataSets() {
//     this.dataSets = [];
//     for (var [name, dataArray] of this.namesToDataArrays) {
//         this.dataSets.push({
//                   "title": name,
//           "fieldMappings": [{"fromField": "value", "toField": "value"}],
//            "dataProvider": dataArray,
//           "categoryField": "date"

//             });
//     }

//     this.chart.dataSets = this.dataSets;
//     this.chart.validateNow();
//     this.chart.validateData();
    
// }

// function testThisChart() {

//    var values = "Jeffs Playtime Fun Money Bags|2015-10-10_-10|2016-01-11_-11|2016-03-12_-12|2016-04-13_13|2016-04-14_-2";
//    addOrUpdateAccount(values);
    
// }