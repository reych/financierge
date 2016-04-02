<?php

include("/home/teamh/financierge/model/network.php");
include("/home/teamh/financierge/model/vendor/autoload.php");
use Parse\ParseClient;
use Parse\ParseException;
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseUser;

session_start();
date_default_timezone_set("America/Los_Angeles");
ParseClient::initialize("9DwkUswTSJOLVi7dkRJxDQNbwHSDlQx3NTdXz5B0", "6HFMDcw8aRr9O7TJ3Pw8YOWbecrdiMuAPEL3OXia", "IdmvCVEBYygkFTRmxOwUvSxtnXwlaGDF9ndq5URq");
// Jeff is testing
////////This section of the code will only be accessed when 
//called from the HTML, this part handles the request form
//Javascript and passes the arguments to the methods.

// Use get method to determine which function to call
$funcName = $_GET['funcToCall'];
if ($funcName == "uploadCSV") {
		//get file from temporary direcory where it is stored
	$target_dir = sys_get_temp_dir();
	//complete file path
	$target_file = $target_dir . "/" . basename($_FILES["fileToUpload"]["name"]);
	move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
    uploadCSV($target_file);
} 
else if($funcName == "getAccountNamesForList") {
	getAccountNamesForList();
} 
else if($funcName == "getTransactionsForList") {
	//get all account names as such
	$accountName = $_GET["accName"];
	$sort = $_GET["sortType"];
	$startDate = $_GET["startDate"];
	$endDate = $_GET["endDate"];
	getTransactionsForList($accountName, $startDate, $endDate, $sort);
} 
else if($funcName == "login"){
	$username = $_POST["username"];
	$password = $_POST["password"];
	login($username, $password);
} 
else if($funcName == "userLoggedIn"){
	userLoggedIn();
} 
else if($funcName == 'logout') {  
    logout();
}


function login($username, $password){
	//call parse class login function
	//login returns a user object or null
	$user = Network::loginUser($username, $password);

	if($user){
        echo "SUCCESS";
        return "SUCCESS";
	}
	else {
        echo "FAIL";
        return "FAIL";
	}
}

function logout(){
	Network::logoutUser();
	return "Logged out";
}

function uploadCSV($file_name){

	if(file_exists($file_name)){
		$file = fopen($file_name, "r");
		while (!feof($file)) {
			$line = fgets($file);
			$data = explode(",", $line);
			if (count($data) == 2) {
				$accountName = $data[0];

				$length = strlen("true");
    			$isAsset = (substr($data[1], 0, $length) === "true");

				// check to see if account already exists for this user
				$AccountAlreadyExists = false;
				$accounts = Network::getAccounts();
				foreach ($accounts as $account) {
					if (strcmp($account->get("name"), $accountName) == 0) {
						$AccountAlreadyExists = true;
					}
				}
				if(!$AccountAlreadyExists){
					Network::addAccount($accountName, $isAsset);
				}

			// if it's a three item line (delete or modify account)
			} else if (count($data) == 3) {
				if (strcmp(strtolower($data[0]), "delete") == 0) {
					$accountName = $data[1];
						Network::deleteAccount($accountName);
				}
			} else {
				//transaction
				$accountName = $data[0];
				$date = new DateTime($data[1]); //added time to match parse format
				$principle = $data[2];
				$amount = floatval($data[3]);
				$category = $data[4];
				Network::addTransactionToAccount($accountName, $date, $principle, $amount, $category);
			}
		}
        echo '<script language="javascript">';
        echo 'alert("Upload succeded!");';
        echo 'window.location.assign("../../index.html");';
        echo '</script>';
        return "SUCCESS";

	} else {
        echo '<script language="javascript">';
        echo 'alert("Upload failed!");';
        echo 'window.location.assign("../../index.html");';
        echo '</script>';
        return "FAIL";
	}
	//delete file from temporary directory to avoid conflicts with future uploads
	unlink($target_file);
}

function getAccountNamesForList(){
	$result = "";
	$accounts = Network::getAccounts();
	if($accounts == NULL){
		echo ' ';
		return "FAIL";
	}
	$accountNameArray = array();
	foreach ($accounts as $account) {
		array_push($accountNameArray, $account->get("name"));
	}

	sort($accountNameArray);

	for ($i= 0; $i < count($accountNameArray); $i++){
		$result .= $accountNameArray[$i] . PHP_EOL;
	}
	echo $result;
	return "SUCCESS";
}

function getTransactionsForList($accountName, $startDate, $endDate, $sort){

	if ($startDate == NULL || $startDate == "") {
		$startDate = new DateTime();
	} else {
		$startDate = new DateTime($startDate);
	}

	if ($endDate == NULL || $endDate == "") {

		$endDate = clone $startDate;
		$endDate->modify("-3 months");
	} else {
		$endDate = new DateTime($endDate);
	}

	//change code here to not use start and end date for sprint 1

	$rawTransactions = Network::getTransactionsForAccountWithinDates($accountName, $startDate, $endDate, strtolower($sort));
	if($rawTransactions == NULL){
		echo 'No transactions for this account!';
		return "FAIL";
	}
	$result = $accountName . PHP_EOL;

	foreach ($rawTransactions as $key => $rawTrans){

		$date = $rawTrans->get("date");
		$principle = $rawTrans->get("principle");
		$amount = $rawTrans->get("amount");
		$category = $rawTrans->get("category");

		$result .= $date->format('Y-m-d') . "_" . $principle . "_" . $amount . "_" . $category . PHP_EOL;
	}

	echo $result;
    return "SUCCESS";
}


function userLoggedIn() {
	if(Network::getCurrentUser()) {
		echo "TRUE";
		return "TRUE";
	} else {
		echo "FALSE";
		return "FALSE";
	}
}
?>
