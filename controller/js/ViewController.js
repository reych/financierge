/*
 This file has controller functions with name XXXController
 These functions are directly called from HTML by the onclick attributes of
 individual tags.
 Controller funcitons then setup corrent arguments and gather necessary info
 either from funciton parameter or HTML page directly by using document.getXXX()
 and then calls phpRequest to make request to backend and get result from php
 if necessary then calls seperate function to display result from php.

 Most of these controller funcitons are like middle man between actual JS that
 is changeing the page and backend PHP.
*/


// this part of the code is executed every time the js file is loaded and check
// if the user is loggedin by calling userLoggedIn funciton throught phpRequest
// if the user is not loggedin, it will redirect the page to login.html
// if the user is loggedin, it will try to load account list
var loggedIn = phpRequest('userLoggedIn','');
if (loggedIn == 'TRUE') {
    accountsController();
    // graphController();
} else {
    if (window.location.href != "https://localhost/login.html") {
        window.location = "https://localhost/login.html";
    }
    //use the if statement below if your computer is not setup to use https
    // if (window.location.href != "http://localhost/login.html") {
    //     window.location = "http://localhost/login.html";
    // }
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

var accountList;

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

    // alert(result);

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

// this function is called when the index.html is loaded
// this function calls phpRequest for account list and then calls
// displayAccounts fucntion to display accounts in a table
// see details in in displayAccounts funciton
function accountsController() {
    var result = phpRequest('getAccountNamesForList','');

    displayAccounts(result);
    accountList = document.getElementById('account-list');
}

// this funciton is called when user clicks on an account. then it tries to
// display transactions for that acount by calling createTab funciton
// accountClicked is the actual <td> tag that is clicked and the id for each
// account tag is its own account name. Thus we can get the account name by
// getting tag's id.
function transactionsController(accountClicked) {
    var arguments = '&accName=' + accountClicked.id + '&sortType='
                    + 'date' + '&startDate=&endDate=';
    var result = phpRequest('getTransactionsForList', arguments);
    createTab(result);

    //var accountSelected = accountClicked.id;

    // var arguments2 = '&accName=' + accountClicked.id;
    // var result = phpRequest('getIndividualGraphData',arguments2);
    // addOrUpdateAccount(result);
}

// this funciton is called when user clicks on the header of transactions table
// in order to sort transactions by desired sorting type.
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

function getBudget() {


    var catName = document.getElementById('category-input').value;
    var monthYear = document.getElementById('month-input').value;

    var arguments = '&category_input=' + catName + '&month_input=' + monthYear;
    var result = phpRequest('getBudgetInformation', arguments);

    // alert(result);

    var resultInArr = result.split('_');

    // var resultInArr = [100,100];

    if (resultInArr.length == 2) {
        document.getElementById('category').innerHTML = catName;
        document.getElementById('amount-spent').innerHTML = resultInArr[1];
        document.getElementById('budget').innerHTML = resultInArr[0];
        console.log('new budget: '+resultInArr[0] +'\n');
        console.log('amount spent: '+resultInArr[1] +'\n');

        if (resultInArr[0] > resultInArr[1] + 10) {
            document.getElementById('amount-spent').setAttribute("style", "color:#32CD32");
            document.getElementById('budget').setAttribute("style", "color:#32CD32");
        } else if (resultInArr[0] > resultInArr[1]) {
            document.getElementById('amount-spent').setAttribute("style", "color:#FDFF00");
            document.getElementById('budget').setAttribute("style", "color:#FDFF00");
        } else {
            document.getElementById('amount-spent').setAttribute("style", "color:#DC143C");
            document.getElementById('budget').setAttribute("style", "color:#DC143C");
        }
        document.getElementById('set-budget-btn').disabled = false;
    }
}

function setBudget() {

    var newBudget = document.getElementById('set-budget').value;
    document.getElementById('set-budget').value = "";
    var amountSpent = document.getElementById('amount-spent').innerHTML;

    document.getElementById('budget').innerHTML = newBudget;


    if (newBudget > amountSpent + 10) {
        document.getElementById('amount-spent').setAttribute("style", "color:#32CD32");
        document.getElementById('budget').setAttribute("style", "color:#32CD32");
    } else if (newBudget >= amountSpent) {
        document.getElementById('amount-spent').setAttribute("style", "color:#FDFF00");
        document.getElementById('budget').setAttribute("style", "color:#FDFF00");
        // document.getElementById('amount-spent').setAttribute("style", "color:#DC143C");
        // document.getElementById('budget').setAttribute("style", "color:#DC143C");
    } else {
        document.getElementById('amount-spent').setAttribute("style", "color:#DC143C");
        document.getElementById('budget').setAttribute("style", "color:#DC143C");
        // document.getElementById('amount-spent').setAttribute("style", "color:#FDFF00");
        // document.getElementById('budget').setAttribute("style", "color:#FDFF00");
    }

    var catName = document.getElementById('category-input').value;
    var monthYear = document.getElementById('month-input').value;
    var arguments = '&category_input=' + catName + '&month_input=' + monthYear + '&newBudget=' + newBudget;


    var result = phpRequest("setBudget", arguments); // var chumble =
    // document.getElementById('category').innerHTML = result;
}

// ajax func to handel all call to php
function phpRequest(funcName, arguments, usrName, passWrd) {
    return jQuery.ajax({
        // using post method to pass data to php for security of username and
        // password
        type:'post',
        // the url points to the php file to call and I used get method to pass
        // function name that I want to call along with arguments needed
        url: '../controller/php/UserController.php?funcToCall=' + funcName + arguments,
        // this data is passed to php using post method defined above for
        // security resaons
        data: {username: usrName, password: passWrd},
        // set async to false in order to make funciton return the actual
        // responde from php instead of just returning a blank string immdeately
        async: false,
    }).responseText;
}
