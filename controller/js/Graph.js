
document.querySelector("#go").addEventListener("mousedown", function () {

  var ifr = document.getElementById("iframed");
  var ifrDoc = ifr.contentDocument || ifr.contentWindow.document;
  var theFrom = ifrDoc.getElementById("from");
  
  var theTo = ifrDoc.getElementById("to");
  //var from = new Date(fromField);
  //var to = new Date(toField);
  var from = new Date(theFrom.value); //try .value if this doesn't work
  var to = new Date(theTo.value);

  

  chart.zoom(from, to);
});


jQuery(function($) {
//     var $j = jQuery.noConflict();
//     $j("#dob").datepicker();
    jQuery(".date-input").datepicker({
      changeMonth: true,
      changeYear: true
    });
});

var namesToDataArrays = new Map();
var dataSets = [];
// var allGraphs = [];

// amcharts chart with established parameters
var chart = AmCharts.makeChart( "chartdiv", {
    "type": "stock",
    "theme": "dark",

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

  // "stockLegend": {
  //           "periodValueTextComparing": "test",
  //           "periodValueTextRegular": "test"
  //       },

    "dataSetSelector": {
        "position": "left"
    },
    "panelsSettings":{
        "recalculateToPercents" : "never"
    },

    "export": {
        "enabled": true
    }
} );

generateChartData();


// This function requests data from the php backend and then populates the 
// graph. It is called once everytime a page is loaded.
function generateChartData() {
    var results = phpRequest('getBaseData', '');
    addOrUpdateAccount(results);
}

// Adds an account or group of accounts to the dataset for the graph.
// accountData accepts a properly formatted string for account info,
// beginning with the account name, followed by date/amount pairs of
// totals for that account as a net of each day. Example:
// "Account Name 1|2016-5-13_50|2016-6-14_31|Account Name 2|2016-6-14_31"
function addOrUpdateAccount(accountData) {
    this.chart.bulletField = "bullet";

    var lines = accountData.split('\n');

    // for each line split from the string, which represents an account and
    // its transactions
    for (i = 0; i < lines.length; i++) {
        var nodes = lines[i].split('|');
        var name = nodes[0];

        // if(namesToDataArrays.has(name)){
        //   namesToDataArrays.delete(name);
        //   continue;
        // }
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

// Resets the dataset for the graph to include all current accounts in the users
// profile as well as Net Worth, Assets, and Liabilities
function updateDataSets() {
    this.dataSets = [];
    // this.allGraphs = [];
    // var i = 0;

    // for each account, add it to the dataset for the graph so that it can
    // be displayed.
    for (var [name, dataArray] of this.namesToDataArrays) {
        if(name.length !== 0) {

            // if the account is one of our three primary totals, then make it
            // show when the graph is first loaded
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

            // else it is not a primary total and will not be displayed when
            // the graph is initially loaded
            } else {
              this.dataSets.push({
                        "title": name,
                "fieldMappings": [{"fromField": "value", "toField": "value"}],
                 "dataProvider": dataArray,
                "categoryField": "date"

                  });
            }
        }

    //   this.allGraphs.push({
    //     "id": name,
    //   "valueField": "value",
    //   "comparable": true,
    //   "compareField": "value" + i,
    //   "bullet": "round",
    //   "bulletBorderThickness": 1,
    //   "balloonText": "[[title]]:<b>$[[value]]</b>",
    //   "compareGraphBalloonText": "[[title]]:<b>$[[value]]</b>"
    //   });

    //   i++;
    }

    // this.chart.panels.stockGraphs = this.allGraphs;
    this.chart.dataSets = this.dataSets;
    this.chart.validateNow();
    this.chart.validateData();
}

// this function is used to test inputing data into our graph. It is now
// obsolete as the graph is functional
function testThisChart() {

   var values = "Test|2015-10-10_-10|2016-03-12_-12|2016-04-13_13|2016-04-14_-2";
   addOrUpdateAccount(values);
}

chart.addListener('rendered', function (event) {
    var dataProvider = chart.dataSets[0].dataProvider;
  $( ".amChartsPeriodSelector .amChartsInputField" ).datepicker({
      dateFormat: "dd-mm-yy",
      minDate: dataProvider[0].date,
      maxDate: dataProvider[dataProvider.length-1].date,
      onClose: function() {
          $( ".amChartsPeriodSelector .amChartsInputField" ).trigger('blur');
      }
  });
});
