
// this part of the code is executed every time the js file is loaded and check
// if the user is loggedin by calling userLoggedIn funciton throught phpRequest
// if the user is not loggedin, it will redirect the page to login.html
// if the user is loggedin, it will try to load account list
var loggedIn = phpRequest('userLoggedIn','');
if (loggedIn == 'TRUE') {
    accountListController();
} else {
    if (window.location.href != "http://localhost/login.html") {
        window.location = "login.html";
    }
}


/*
 this part of the code will handel login requests from user and keep track of
 number of times the user tried to login with wrong credentials and block any
 further login requests from user for 1 minute if 4 consecutive wrong
 credentials are provided
*/

// global variables to make the values persistent when the user in at login.html
// so that we will be able to block any further login attempt for 1 minute after
// 4 times of failed login
var loginCounter = 0;
var past;
var oneMin = 1000 * 5;

// check current time with the var past, which holds the time of the forth
// fail login and based on the time difference, set isPast to true or false
// then return isPast
function checkIfOneMinHadPassed() {
    var isPast = (new Date().getTime() - past < oneMin)?false:true;
    return isPast;
}

// this function will find a <p> tag in login.html that is empty by default
// and update its text content with the passed in string in order to show
// apporate login fail alerts.
// the color of this paragraph is set to red in the CSS file
function changeAlertText(alertContent) {
    document.getElementById('loginResult').innerHTML = alertContent;
}

// this funciton is linked to login button in login.html by setting the button's
// onclick attribute to this function. check login.html to see details.
// the actual function that handels the login request from user in front-end
// first check if # of failed login attempt. if failed login time is 4, check if
// 1 min has passed or not. if passed, reset loginCounter and procees, else
// change alert text on top of the login part in login.html
function logInController() {
    if (loginCounter == 4) {
        if (checkIfOneMinHadPassed()) {
            loginCounter = 0;
        } else {
            changeAlertText('Please wait at least 1 minute beofre next try!');
            return;
        }
    }

    // get username and password from html
    var usrName = document.getElementById('login-username').value;
    var passWrd = document.getElementById('login-password').value;

    // try to login with above credentils by calling login funtion in
    // UserController throught phpRequest and save the login result string to
    // var result
    var result = phpRequest('login', '', usrName, passWrd);

    // check content of result if success, redirect to index
    if (result == 'SUCCESS') {
        window.location = "index.html";
    } else {
        // else increment loginCounter
        loginCounter++;
        if (loginCounter == 4) {
            // if loginCounter is 4, get current time in order to check if 1 min
            // has passed later
            past = new Date().getTime();
        }

        // clear the input fields in login.html
        document.getElementById('login-username').value = '';
        document.getElementById('login-password').value = '';
        // display appoporate message in alert seciton in login.html
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

// call logout in UserController throught phpRequest and then redirect the
// window location to login.html
function logoutController() {
    phpRequest('logout', '');
    window.location = "login.html";
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
