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
}

function login(){

	$username = $_GET["username"];
	$password = $_GET["password"];
	//call parse class login function
	//login returns a user object or null
	$user = Network::login($username, $password);

	if($user){
		echo '<script language="javascript">';
		echo 'window.location.assign("../../index.html");';
		echo '</script>';
	}
	else {
		echo '<script language="javascript">';
		echo 'alert("Login unsuccesful! Please provide right credentials.");';
		echo 'window.location.assign("../../login.html");';
		echo '</script>';
	}
}

function uploadCSV(){

	//get file from temporary direcory where it is stored
	$target_dir = sys_get_temp_dir();
	//complete file path
	$target_file = $target_dir . "/" . basename($_FILES["file"]["name"]);
	//move file to the temporary directory to process
	move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
	//open file
	if (($file = fopen($target_file, "r")) !== FALSE) {
		//while

		Network::loginUser("christdv@usc.edu", "christdv");
		while (!feof($file)) {
			$line = fgets($file);
			$data = explode(",", $line);
			if (count($data) == 2) {
				$accountName = $data[0];
				$isAsset = (strcmp($data[1], "true") == 0) ? true : false;

				// if the account was not successfully added
				if(!Network::addAccount($accountName, $isAsset)){
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

		/***
		echo indication to javascript to request several pieces of info:
			- list of accounts
			- all data necessary for initial graph look
			- probably something else, tbd
			- NOT info for transactions (requested upon clicking list of accounts)
		***/

	} else {
		echo "Error: Cannot open file!";
	}
	//delete file from temporary directory to avoid conflicts with future uploads
	unlink($target_file);
}

function formatTransactions(){
	//TODO
}

function getAccountNamesForList(){

	$result = "";
	$accounts = Network::getAccounts();
	//$accounts should be an array of strings

	foreach ($accounts as $key => $account) {
		$result .= $account->get("name") . PHP_EOL;
	}
	echo $result;
	/* 
	returning a string in the format
	account1Name
	account2Name
	...
	*/
}

function getTransactionsForList(){

	//get all account names as such
	$accountName = $_GET["accName"];
	$sort = $_GET["sortType"];
	$startDate = $_GET["startDate"];
	$endDate = $_GET["endDate"];

	//echo ("<script>console.log('startDate is initially: ".$startDate."'); </script>");

	if ($startDate == NULL || $startDate == "") {
		//$date = date('m/d/Y h:i:s a', time());
		$startDate = new DateTime('Y-m-d');
		//echo ("<script>console.log('startDate became: ".$startDate."'); </script>");
	} else {
		$startDate = new DateTime($startDate);
	}

	//echo ("<script>console.log('endDate is initially: ".$endDate."'); </script>");

	if ($endDate == NULL || $startDate == "") {

		$endDate = clone $startDate;
		$endDate->modify("-3 months");
		//echo ("<script>console.log('endDate became: ".$endDate."'); </script>");
	} else {
		$endDate = new DateTime($endDate);
	}

	//change code here to not use start and end date for sprint 1

	$rawTransactions = Network::getTransactionsForAccountWithinDates($accountName, $startDate, $endDate, $sort);
	if($rawTransactions == NULL){
		echo 'No transactions for this account!';
		return;
	}
	$result = "";

	foreach ($rawTransactions as $key => $rawTrans){
		$date = $rawTrans->get("date");
		$principle = $rawTrans->get("principle");
		$amount = $rawTrans->get("amount");
		$category = $rawTrans->get("category");

		$result .= $date . "_" . $principle . "_" . $amount . "_" . $category . PHP_EOL;
	}

	echo $result;
}

?>
