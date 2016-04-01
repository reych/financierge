// modify the default sumbit function
// $("#upload-button").on("click", function() {
//     event.preventDefault();
//
//     // get the file from input file tag
//     var file_data = $("#fileToUpload").prop("files")[0];
//     var form_data = new FormData();
//     form_data.append("file", file_data)
//     phpRequest('uploadCSV', form_data);
// });
// alert(window.location.href);

var loggedIn = phpRequest('userLoggedIn','');
if (loggedIn == 'TRUE') {
    accountListController();
    // logInController();
}
// else {
    // if (window.location.href != "http://localhost/login.html") {
        // window.location = "login.html";
    // }
// }

var loginCounter = 0;
var past;
var oneMin = 1000 * 60;

function checkIfOneMinHadPassed() {


    var isPast = (new Date().getTime() - past < oneMin)?false:true;
    return isPast;
}

function logInController() {






    if (loginCounter == 4) {
        if (checkIfOneMinHadPassed()) {
            loginCounter = 0;
        } else {
            alert('Please wait at least 1 minute beofre next try!');
            return;
        }
    }

    var usrName = document.getElementById('login-username').value;
    var passWrd = document.getElementById('login-password').value;


    // $.post( "../Controller/php/userController.php?funcToCall=login", { username: usrName, password: passWrd })
    //   .done(function( data ) {
    //     // alert( "Data Loaded: " + data );
    //     if (data == 'SUCCESS') {
    //         window.location = "index.html";
    //     } else {
    //         alert('login failed');
    //     }
    //   });

    var result = phpRequest('login', '', usrName, passWrd);
    // var result = phpRequest('login', '', 'christdv@usc.edu', 'christdv');
    // alert(result);
    if (result == 'SUCCESS') {
        window.location = "index.html";
        // alert("login success");
    } else {
        loginCounter++;
        if (loginCounter == 4) {
            alert('setting past time')
            past = new Date().getTime();
        }
        alert('login failed!' + loginCounter);
    }
}

function accountListController() {
    var result = phpRequest('getAccountNamesForList','');
    // alert(result);
    displayAccounts(result);
}

function uploadController() {
    event.preventDefault();
    alert('uploading');
    // get the file from input file tag
    var file_data = $("#fileToUpload").prop("files")[0];
    var form_data = new FormData();
    form_data.append("file", file_data)
    var result = phpRequest('uploadCSV', form_data);
    alert(result);
    accountListController();
}

function transactionsController(accountClicked) {
    var arguments = '&accName=' + accountClicked.id + '&sortType=' + 'date'+'&startDate=&endDate=';
    alert(arguments);
    var result = phpRequest('getTransactionsForList', arguments);
    alert(result);
    createTab(result);
}

function sortTransactions(sortType){
    var selectedTabs = document.getElementsByClassName('selected');
    if(selectedTabs[0] !== undefined && selectedTabs[0] != undefined) {
        var accountName = selectedTabs[0].id.substring(4);
        if(isValidSortType(sortType)){
            var arguments = '&accName=' + accountName + '&sortType=' + sortType;
            var result = phpRequest('getTransactionsForList', arguments);
            var contentID = 'content-'+accountName;
            var contentDiv = document.getElementById(contentID);
            result = result.substring(accountName.length);
            displayTransactions(contentID, result);
        }
    }

}

//helper function to check if sort type is valid
function isValidSortType(sortType) {
    if(sortType === 'date' || sortType === 'category' || sortType === 'amount') {
        return true;
    }
    return false;
}

// ajax func to handel all call to php
function phpRequest(funcName, data, usrName, passWrd) {
    var arguments = "";
    if (typeof data == 'string' ) {
        arguments = data;

    }
    var result;
    // alert(funcName);
    return jQuery.ajax({
        type:'post',
        // url: '../Controller/php/upload.php?funcToCall=' + funcName + arguments,
        url: '../Controller/php/userController.php?funcToCall=' + funcName + arguments,
        data: {username: usrName, password: passWrd},
        // THIS MUST BE DONE FOR FILE UPLOADING
        // contentType: false,
        // processData: false,
        async: false,
        success: function(phpResponse){
            //TODO add if statements to determine which js to call(ex, pupolate
            //  account list or populate transactions or graph)
            // alert(phpResponse);
            // return phpResponse;


            // createTab(phpResponse);
        }
    }).responseText;
}
