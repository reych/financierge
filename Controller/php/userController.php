<?php

include '../../model/network.php';
use Parse\ParseException;
use Parse\ParseObject;

$network = null;
$userModel = null;

// Use get method to determine which function to call
$funcName = $_GET['funcToCall'];
if ($funcName == "uploadCSV") {
    uploadCSV();
}

function login($username, $password){
	//call parse class login function
	//get the model
	$network = new Network();
	$userModel = $network->login($username, $password);

	if($userModel){
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

		while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
			foreach ($filedata as $data) {
				if(count($data) == 2){
					//account
					$accountName = $data[0];
					$isAsset = $data[1];

					// if the account was not successfully added
					if(!$userModel.addAccount($name, $isAsset)){
					}

				} else {
					//transaction
					$accountName = $data[0];
					$date = $data[1] . "T00:00:00Z"; //added time to match parse format
					$principle = $data[2];
					$amount = $data[3];
					$category = $data[4];

					$added = $userModel.addTransactionToAccount($accountName, $date, $principle, $amount, $category);

					if(!$added){
						//add to an array of all the transactions not uploaded
					
					]
				}
			}

			//check if array is emtpy, if not, echo all the transactions not uploaded, with a small message
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



}

/*
For $sort
1: Sort by date
2: Sort by amount
3: Sort by category
4: Sort by alphabetical ordey by principle
*/

function getAccountNamesForList(){

	$result = "";
	$accounts = $userModel->getAccounts();
	//$accounts should be an array of strings

	foreach ($accounts as $key => $account) {
		$result .= $account->get("name") . '\n';
	}
	echo $result;
	/* 
	returning a string in the format
	account1Name
	account2Name
	...
	*/
}

function getTransactionsForList($accountName, $sort, $startDate, $endDate){

	$rawTransactions = $model.getTransactionsForAccountWithinDates($startDate, $endDate, $accountName, $sort);
	$result = "";

	foreach ($rawTransactions as $key => $rawTrans) {
		$date = $rawTrans->get("date");
		$principle = $rawTrans->get("principle");
		$amount = $rawTrans->get("amount");
		$category = $rawTrans->get("category");

		$result .= $date . "_" . $principle . "_" . $amount . "_" . $category . "\n";
	}

	echo $result;

}



?>
