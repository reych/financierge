<?php

include("../../model/network.php");
include("../../model/vendor/autoload.php");
use Parse\ParseClient;
use Parse\ParseException;
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseUser;

session_start();
date_default_timezone_set("America/Los_Angeles");
ParseClient::initialize("9DwkUswTSJOLVi7dkRJxDQNbwHSDlQx3NTdXz5B0", "6HFMDcw8aRr9O7TJ3Pw8YOWbecrdiMuAPEL3OXia", "IdmvCVEBYygkFTRmxOwUvSxtnXwlaGDF9ndq5URq");

// Use get method to determine which function to call
$funcName = $_GET['funcToCall'];
if ($funcName == "uploadCSV") {
    uploadCSV();
} else if($funcName == "getAccountNamesForList") {
	getAccountNamesForList();
} else if($funcName == "getTransactionsForList") {
	getTransactionsForList();
} else if($funcName == "login"){
	login();
} else if($funcName == "userLoggedIn"){
	userLoggedIn();
} else if($funcName == 'logout') {
    logout();
}

function login(){

	$username = $_POST["username"];
	$password = $_POST["password"];
	//call parse class login function
	//login returns a user object or null
	$user = Network::loginUser($username, $password);

	if($user){
		// echo '<script language="javascript">';
		// echo 'window.location.assign("../../index.html");';
		// echo '</script>';
        echo "SUCCESS";
	}
	else {
		// echo '<script language="javascript">';
		// echo 'alert("Login unsuccesful! Please provide right credentials.");';
		// echo 'window.location.assign("../../login.html");';
		// echo '</script>';
        echo "FAIL";
	}
}

function logout(){
	Network::logoutUser();
}

function uploadCSV(){

	//get file from temporary direcory where it is stored
	$target_dir = sys_get_temp_dir();
	//complete file path
	$target_file = $target_dir . "/" . basename($_FILES["fileToUpload"]["name"]);
	//move file to the temporary directory to process
	move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
	//open file
	if (($file = fopen($target_file, "r")) !== FALSE) {
		//while

		//Network::loginUser("christdv@usc.edu", "christdv");
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

				// if the account was not successfully added
				if(!$AccountAlreadyExists && !Network::addAccount($accountName, $isAsset)){
					// indicate account could not be added? will this screw up the frontend protocol?
				}

			// if it's a three item line (delete or modify account)
			} else if (count($data) == 3) {
				if (strcmp(strtolower($data[0]), "delete") == 0) {
					$accountName = $data[1];

					// if the account was not successfully deleted
					if(!Network::deleteAccount($accountName)){
						// indicate account could not be deleted? will this screw up the frontend protocol?
					}
				}
			} else {
				//transaction
				$accountName = $data[0];
				$date = new DateTime($data[1]); //added time to match parse format
				$principle = $data[2];
				$amount = floatval($data[3]);
				$category = $data[4];

				if(!Network::addTransactionToAccount($accountName, $date, $principle, $amount, $category)){
					//add to an array of all the transactions not uploaded
				}
			}
		}

		//echo $tempStr;
		//echo $bool_val ? 'isAsset: true' : 'isAsset: false';//"Success";
        echo '<script language="javascript">';
        echo 'alert("Upload succeded!");';
        echo 'window.location.assign("../../index.html");';
        echo '</script>';
        exit();

	} else {
		// echo "Error";
        echo '<script language="javascript">';
        echo 'alert("Upload failed!");';
        echo 'window.location.assign("../../index.html");';
        echo '</script>';
	}
	//delete file from temporary directory to avoid conflicts with future uploads
	unlink($target_file);
    //Network::logoutUser();
}

function formatTransactions(){
	//TODO
}

function getAccountNamesForList(){
//Network::loginUser("christdv@usc.edu", "christdv");
	$result = "";
	$accounts = Network::getAccounts();
	//$accounts should be an array of strings

	$accountNameArray = array();
	foreach ($accounts as $account) {
		array_push($accountNameArray, $account->get("name"));
	}

	// sort the array
	sort($accountNameArray);

	for ($i= 0; $i < count($accountNameArray); $i++){
		$result .= $accountNameArray[$i] . PHP_EOL;
	}
	echo $result;
	/*
	returning a string in the format
	account1Name
	account2Name
	...
	*/
    //Network::logoutUser();
}

function getTransactionsForList(){
    //Network::loginUser("christdv@usc.edu", "christdv");

	//get all account names as such
	$accountName = $_GET["accName"];
	$sort = $_GET["sortType"];
	$startDate = $_GET["startDate"];
	$endDate = $_GET["endDate"];
	//echo ("<script>console.log('startDate is initially: ".$startDate."'); </script>");

	if ($startDate == NULL || $startDate == "") {
		//$date = date('m/d/Y h:i:s a', time());
		$startDate = new DateTime();
		//echo ("<script>console.log('startDate became: ".$startDate."'); </script>");
	} else {
		$startDate = new DateTime($startDate);
	}

	//echo ("<script>console.log('endDate is initially: ".$endDate."'); </script>");

	if ($endDate == NULL || $endDate == "") {

		$endDate = clone $startDate;
		$endDate->modify("-3 months");
		//echo ("<script>console.log('endDate became: ".$endDate."'); </script>");
	} else {
		$endDate = new DateTime($endDate);
	}

	//change code here to not use start and end date for sprint 1

    // echo $accountName;

    //echo userLoggedIn();
	$rawTransactions = Network::getTransactionsForAccountWithinDates($accountName, $startDate, $endDate, strtolower($sort));
	if($rawTransactions == NULL){
		echo 'No transactions for this account!';
		return;
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
    //Network::logoutUser();
}


function userLoggedIn() {
	if(Network::getCurrentUser()) {
		echo "TRUE";
	} else {echo "FALSE";}
}
?>
