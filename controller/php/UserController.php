<?php
//include("/home/teamh/financierge/model/Network.php");
include("../../model/Network.php");
include("../../model/vendor/autoload.php");
use Parse\ParseClient;
use Parse\ParseException;
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseUser;

date_default_timezone_set("America/Los_Angeles");
session_start();
ParseClient::initialize("9DwkUswTSJOLVi7dkRJxDQNbwHSDlQx3NTdXz5B0", "6HFMDcw8aRr9O7TJ3Pw8YOWbecrdiMuAPEL3OXia", "IdmvCVEBYygkFTRmxOwUvSxtnXwlaGDF9ndq5URq");

////////This section of the code will only be accessed when
//called from the HTML, this part handles the request form
//Javascript and passes the arguments to the methods.

// Use get method to determine which function the javascrip is requesting
$funcName = $_GET['funcToCall'];
if ($funcName == "uploadCSV") {
	//get file from temporary direcory where it is stored
	$target_dir = sys_get_temp_dir();
	//complete file path
	$target_file = $target_dir . "/" . basename($_FILES["fileToUpload"]["name"]);
	move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
    uploadCSV($target_file);

} else if($funcName == "getAccountNamesForList") {
	getAccountNamesForList();

} else if($funcName == "getTransactionsForList") {
	// get all account names as such
	$accountName = $_GET["accName"];
	$sort = $_GET["sortType"];
	$startDate = $_GET["startDate"];
	$endDate = $_GET["endDate"];
	getTransactionsForList($accountName, $startDate, $endDate, $sort);

} else if ($funcName == "login"){
	$username = $_POST["username"];
	$password = $_POST["password"];
	login($username, $password);

} else if($funcName == "userLoggedIn"){
	userLoggedIn();

} else if($funcName == "logout") {
    logout();
} else if($funcName == "getBaseData") {
	getBaseDataForGraph();
} else if($funcName == "getIndividualGraphData") {
	$acctName = $_GET["accName"];
	getIndividualDataForGraph($acctName);
}


function login($username, $password){
	// call parse class login function
	// login returns a user object or null
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

		// array mapping account names to arrays containing Transaction objects
		// array(accountName => arrayOfTransactionObjects)
		$allNewTransactions = array();

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
				if($accounts){
					foreach ($accounts as $account) {
						if (strcmp($account->get("name"), $accountName) == 0) {
							$AccountAlreadyExists = true;
						}
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
				$acntName = $data[0];
				$dt = new DateTime($data[1]); //added time to match parse format
				$princ = $data[2];
				$amnt = floatval($data[3]);
				$ctgry = $data[4];

				// echo $princ;
				$newTrans = new Transaction();
				$newTrans->accountName = $acntName;
				$newTrans->date = $dt;
				$newTrans->principle = $princ;
				$newTrans->amount = $amnt;
				$newTrans->category = $ctgry;
				//echo "Here's what should be after the :... ".$principle;
				//echo "Trying to add transaction: ".$newTrans->principle." to an array".PHP_EOL;

				// if the array contains the account name as a key already:
				if (array_key_exists($acntName, $allNewTransactions)) {
					// push the new Transaction object into the array held at the account name key
					array_push($allNewTransactions[$acntName], $newTrans);

				// the array doesn't yet have any transactions for this account to add
				} else {
					// create a new array to hold all of the transactions for this particular account
					$tempArr = array();
					// add the first transaction to this array
					array_push($tempArr, $newTrans);
					// add this array to the array holding all transactions
					$allNewTransactions[$acntName] = $tempArr;
				}

				// Network::addTransactionToAccount($acntName, $dt, $princ, $amnt, $ctgry);
			}
		}

		// call function to load up array of transactions
		Network::addTransactionsToAccounts($allNewTransactions);

        echo '<script language="javascript">';
        // echo 'alert("Upload succeded!");';
        echo 'window.location.assign("../../index.html");';
        echo '</script>';
        return true;
	}
	else {
        echo '<script language="javascript">';
        // echo 'alert("Upload failed!");';
        echo 'window.location.assign("../../index.html");';
        echo '</script>';
        return false;
	}
	//delete file from temporary directory to avoid conflicts with future uploads
	unlink($target_file);
	return true;
}

function getAccountNamesForList(){
	$result = "";
	$accounts = Network::getAccounts();
	if($accounts == NULL){
		echo ' ';
		return "FAIL";
	}
	$accountNameArray = [];
	foreach ($accounts as $account) {
		$accountNameArray[] = $account->get("name");
	}

	sort($accountNameArray);

	//result will be one string that will be echoed for the javascrip to pick up
	//PHP_EOL => PHP EndOfLine
	//Used instea of "\n" for parsing
	for ($i = 0; $i < count($accountNameArray); $i++){
		$result .= $accountNameArray[$i] . PHP_EOL;
	}
	echo $result;
	return "SUCCESS";
}

function getTransactionsForList($accountName, $startDate, $endDate, $sort){

	if ($startDate == NULL || $startDate == "") {
		//by calling new DateTime() with no arguments,
		//the date object is created with the current date
		$startDate = new DateTime();

	} else {
		$startDate = new DateTime($startDate);
	}

	if ($endDate == NULL || $endDate == "") {
		//the startDate time is then used to give the default 3 months range
		$endDate = clone $startDate;
		$endDate->modify("-3 months");

	} else {
		$endDate = new DateTime($endDate);
	}

	//change code here to not use start and end date for sprint 1

	$rawTransactions = Network::getTransactionsForAccountWithinDates($accountName,
						 $startDate, $endDate, strtolower($sort));

	if ($rawTransactions == NULL){
		echo 'No transactions for this account!';
		return "FAIL";
	}

	$result = $accountName . PHP_EOL;

	foreach ($rawTransactions as $rawTrans){

		$date = $rawTrans->get("date");
		$principle = $rawTrans->get("principle");
		$amount = $rawTrans->get("amount");
		$category = $rawTrans->get("category");

		$result .= $date->format('Y-m-d') . "_" . $principle . "_" . $amount . "_" . $category . PHP_EOL;
	}

	echo $result;
    // return "SUCCESS";
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

//will return nothing if the acocunt doesn't have any transactions
function getIndividualDataForGraph($acctName) {

	//Network::loginUser("zhongyag@usc.edu", "zg");
	$transactions = Network::getTransactionsForAccount($acctName);
	if($transactions == NULL) {
		return;
	}
	$compactTrans = calculateDailyValues($transactions);
	$cumulativeTrans = calculateCumulativeValues($compactTrans);
	$formattedTrans = formatGraphDataToString($acctName, $cumulativeTrans);

	echo $formattedTrans;
	//Network::logoutUser();
}

//will return nothing if the acocunt doesn't have any transactions
function formIndividualDataForGraph($acctName, $acctTrans) {

	if($acctTrans == NULL) {
		return;
	}
	$compactTrans = calculateDailyValues($acctTrans);
	$cumulativeTrans = calculateCumulativeValues($compactTrans);
	$formattedTrans = formatGraphDataToString($acctName, $cumulativeTrans);

	return $formattedTrans;
	//Network::logoutUser();
}

/* REFACTOR THIS LATER TO MAKE CLEANER OR MORE EFFICIENT */
function getBaseDataForGraph() {


	//get accounts
	$accounts = Network::getAccounts();

	// echo "Time after getting all accounts " . date("h:i:sa") . PHP_EOL;

	$accountAssets = array();
	$accountLiabilities = array();
	//split accounts into assets or liabilities
	for($i=0; $i<count($accounts); $i++){
		$accounts[$i]->fetch();
		if($accounts[$i]->get("isAsset")) {
			array_push($accountAssets, $accounts[$i]);
		} else {
			array_push($accountLiabilities, $accounts[$i]);

		}
	}

	// echo "Time after grouping accounts " . date("h:i:sa") . PHP_EOL;

	$transAssets = array();
	$transLiabilities = array();

	$accountGraphData = "";

	// function formIndividualDataForGraph($acctName, $acctTrans)
	//Getting liability and asset arrays for transactions
	foreach ($accountAssets as $singleAssetAccount) {
		$curName = $singleAssetAccount->get("name");
		$transactions = Network::getTransactionsForAccount($curName);
		$transAssets = array_merge($transAssets, $transactions); //add transactions to transAssets array
		$accountGraphData .= formIndividualDataForGraph($curName, $transactions);
	}

	foreach ($accountLiabilities as $singleLiabilityAccount) {
		$curName = $singleLiabilityAccount->get("name");
		$transactions = Network::getTransactionsForAccount($curName);
		$transLiabilities = array_merge($transLiabilities, $transactions); //add transactions to transLiabilities array
		$accountGraphData .= formIndividualDataForGraph($curName, $transactions);
	}

	// echo "Time after merging all trans " . date("h:i:sa") . PHP_EOL;

	//sort the transactions by date.
	// usort($transAssets, "cmp");
	// usort($transLiabilities, "cmp");
	$compactAssets = calculateDailyValues($transAssets);
	$compactLiabilities = calculateDailyValues($transLiabilities);

	$cumulativeAssets = calculateCumulativeValues($compactAssets);
	$cumulativaLiabilities = calculateCumulativeValues($compactLiabilities);

	// if there is no transactions at all, which means you don't have a life
	if (count($transAssets) == 0 && count($transLiabilities) == 0) {
		return;
	}

	// if you only have assets, which means you do not spend
	if (count($transAssets) > 0 && count($transLiabilities) == 0) {
		$formattedAssets = formatGraphDataToString("Assets", $cumulativeAssets);
		$formattedNetworth = formatGraphDataToString("Net Worth", $cumulativeAssets);
		// already added PHP_EOL in formatGraphDataToString so no need here
		echo $formattedNetworth . $formattedAssets . $accountGraphData;
		return;
	}
	// if you only have liabilities, which means you are going to be broke
	if (count($transLiabilities) > 0 && count($transAssets) == 0) {
		$formattedLiabilities = formatGraphDataToString("Liabilities", $cumulativaLiabilities);
		foreach ($cumulativaLiabilities as $key => $value) {
			$cumulativaLiabilities[$key] = $value * -1;
		}
		$formattedNetworth = formatGraphDataToString("Networth", $cumulativaLiabilities);
		echo $formattedNetworth . $formattedLiabilities . $accountGraphData;
		return;
	}

	// echo "Time after getting cumulative & daily values " . date("h:i:sa") . PHP_EOL;

	//CALCULATE NET WORTH
	//net worth array
	$netWorth;
	//set pointer to last element in array to get the earliest last date
	end($compactAssets);
	$assetEndDate = key($compactAssets);
	end($compactLiabilities);
	$liabilityEndDate = key($compactLiabilities);
	$endDate = returnLower($assetEndDate, $liabilityEndDate);
	//reset to set pointer to first element in array
	reset($compactAssets);
	reset($compactLiabilities);
	$assetCurrentDate = key($compactAssets);
	$liabilityCurrentDate = key($compactLiabilities);

	//set initial previous values
	$networthPrevVal = 0;

	//return first date
	$currDate = returnLower($assetCurrentDate, $liabilityCurrentDate);

	// echo "Time at start of first while " . date("h:i:sa") . PHP_EOL;

	//loop while current date is less than or equal to end date
	while((strcmp($currDate, $endDate)) < 1) {
	// $i = 0;
	// while($i < 17) {
		$compareResult = strcmp($assetCurrentDate, $liabilityCurrentDate);
		//add lower date to the networth array.
		//if values are the same, combine the values and then add to networth current date.
		if($compareResult < 0) {
			//if asset date is earlier
			$networth[$assetCurrentDate] = $compactAssets[$assetCurrentDate] + $networthPrevVal;
			$networthPrevVal = $networth[$assetCurrentDate]; //set last networth
			if (strcmp($currDate, $endDate) == 0) {
				break;
			}
			next($compactAssets); //increment to the next date
			$assetCurrentDate = key($compactAssets);
		} else if($compareResult > 0) {
			//if liability date is earlier
			$networth[$liabilityCurrentDate] = $networthPrevVal - $compactLiabilities[$liabilityCurrentDate];
			$networthPrevVal = $networth[$liabilityCurrentDate]; //set last networth
			if (strcmp($currDate, $endDate) == 0) {
				break;
			}
			next($compactLiabilities); //increment to the next date
			$liabilityCurrentDate = key($compactLiabilities);
		} else {
			//if dates are the same
			$toAdd = $compactAssets[$assetCurrentDate] - $compactLiabilities[$liabilityCurrentDate];
			$networth[$assetCurrentDate] = $networthPrevVal + $toAdd;
			$networthPrevVal = $networth[$assetCurrentDate];
			if (strcmp($currDate, $endDate) == 0) {
				break;
			}
			//increment pointers and get key
			next($compactLiabilities);
			next($compactAssets);
			$assetCurrentDate = key($compactAssets);
			$liabilityCurrentDate = key($compactLiabilities);

		}

		$currDate = returnLower($assetCurrentDate, $liabilityCurrentDate);
		// echo "dates for a&l " . $assetCurrentDate . " " . $liabilityCurrentDate . PHP_EOL;
		// echo "currDate: " . $currDate . PHP_EOL;
		// $i++;
	}

	// echo "Time at start of second while " . date("h:i:sa") . PHP_EOL;

	//still have some transactions left from the longer array.
	while(strcmp($assetCurrentDate, $assetEndDate) != 0) {
		$networth[$assetCurrentDate] = $compactAssets[$assetCurrentDate] + $networthPrevVal;
		$networthPrevVal = $networth[$assetCurrentDate]; //set last networth
		next($compactAssets); //increment to the next date
		$assetCurrentDate = key($compactAssets);
	}
	while(strcmp($liabilityCurrentDate, $liabilityEndDate) != 0) {
		$networth[$liabilityCurrentDate] = $networthPrevVal - $compactLiabilities[$liabilityCurrentDate];
			$networthPrevVal = $networth[$liabilityCurrentDate]; //set last networth
			next($compactLiabilities); //increment to the next date
			$liabilityCurrentDate = key($compactLiabilities);
	}

	//format assets, liabilities, and networth.
	$formattedAssets = formatGraphDataToString("Assets", $cumulativeAssets);
	$formattedLiabilities = formatGraphDataToString("Liabilities", $cumulativaLiabilities);
	$formattedNetworth = formatGraphDataToString("Net Worth", $networth);

	$baseDataString = $formattedNetworth . $formattedAssets . $formattedLiabilities . $accountGraphData;

	echo $baseDataString;


	// echo "Time at end of func " . date("h:i:sa") . PHP_EOL;

	// Network::logoutUser();

}

function calculateCumulativeValues($transactionsArray) {
	$cumulativeArray = array();
	$prevValue = 0;
	foreach($transactionsArray as $key => $transVal) {
		$cumulativeArray[$key] =  $prevValue + $transVal;
		$prevValue = $cumulativeArray[$key];
	}
	return $cumulativeArray;
}

//take in (sorted?) array and calculate the daily values
//Returns associative array of date->value mapping in sorted order by date
function calculateDailyValues($transactionsArray) {
	$compressedArray = array();
	foreach($transactionsArray as $trans) {
		$key = $trans->get("date")->format('Y-m-d');
		if(array_key_exists($key,$compressedArray)){
			$compressedArray[$key] += $trans->get("amount");
		} else {
			$compressedArray[$key] = $trans->get("amount");
		}
	}

	ksort($compressedArray);
	return $compressedArray;


}

//comparator for sorting by date
function cmp($trans1, $trans2){
	return ($trans1->get("date") > $trans2->get("date"));
}

//compares two strings, returns the lesser of the two
function returnLower($val1, $val2) {
	$val = strcmp($val1, $val2);

	if($val < 0){
		return $val1;
	}
	else {
		return $val2;
	}
}

//format daily values for $name. Corresponds to a line on the graph.
function formatGraphDataToString($name, $dailyValuesAssocArray){
	$resultString = $name;
	foreach($dailyValuesAssocArray as $date => $value) {
		$resultString .= "|" . $date . "_" . $value;
	}
	$resultString .= PHP_EOL;
	return $resultString;
}
?>
