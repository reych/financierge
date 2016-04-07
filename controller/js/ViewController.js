var loggedIn = phpRequest('userLoggedIn','');
if (loggedIn == 'TRUE') {
    accountListController();
} else {
    if (window.location.href != "http://localhost/login.html") {
        window.location = "login.html";
    }
}


var loginCounter = 0;
var past;
var oneMin = 1000 * 5;

function checkIfOneMinHadPassed() {


    var isPast = (new Date().getTime() - past < oneMin)?false:true;
    return isPast;
}

function changeAlertText(alertContent) {
    document.getElementById('loginResult').innerHTML = alertContent;
}

function logoutController() {
    phpRequest('logout', '');
    window.location = "login.html";
}

function logInController() {
    if (loginCounter == 4) {
        if (checkIfOneMinHadPassed()) {
            loginCounter = 0;
        } else {
            changeAlertText('Please wait at least 1 minute beofre next try!');
            return;
        }
    }

    var usrName = document.getElementById('login-username').value;
    var passWrd = document.getElementById('login-password').value;

    var result = phpRequest('login', '', usrName, passWrd);
    // alert(result);
    if (result == 'SUCCESS') {
        window.location = "index.html";
        // alert("login success");
    } else {
        loginCounter++;
        if (loginCounter == 4) {
            past = new Date().getTime();
        }
        // alert("asdfadsf");
        document.getElementById('login-username').value = '';
        document.getElementById('login-password').value = '';
        changeAlertText('Wrong username or password!');
    }
}

function accountListController() {
    var result = phpRequest('getAccountNamesForList','');
    // alert(result);
    displayAccounts(result);
}

function transactionsController(accountClicked) {
    var arguments = '&accName=' + accountClicked.id + '&sortType=' + 'date'+'&startDate=&endDate=';
    // alert(arguments);
    var result = phpRequest('getTransactionsForList', arguments);
    // alert(result);
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
        url: '../controller/php/UserController.php?funcToCall=' + funcName + arguments,
        data: {username: usrName, password: passWrd},
        async: false,
        success: function(phpResponse){
        }
    }).responseText;
}
