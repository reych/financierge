<?php
include("/home/teamh/financierge/model/Network.php");
include("/home/teamh/financierge/model/vendor/autoload.php");
// include("../../model/Network.php");
// include("../../model/vendor/autoload.php");

////////This section of the code will only be accessed when
//called from the HTML, this part handles the request from
//Javascript and passes the arguments to the methods.

// Use get method to determine which function the javascrip is requesting
$funcName = $_GET['funcToCall'];
if ($funcName == "uploadCSV") {
	//get file from temporary direcory where it is stored
	$targerDir = sys_get_temp_dir();
	//complete file path
	$targetFile = $targerDir . "/" . basename($_FILES["fileToUpload"]["name"]);
	move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile);
    uploadCSV($targetFile);
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
} else if ($funcName == "getBudgetInformation"){
	$catName = $_GET["category_input"];
	$month = $_GET["month_input"];
	getBudgetInformation($catName, $month);
} else if ($funcName == "setBudget") {
	$catName = $_GET["category_input"];
	$month = $_GET["month_input"];
	$newBudget = $_GET["newBudget"];
	setBudget($catName, $month, $newBudget);
}

// logs user into our Parse database. Accepts a username and password as strings
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

// logs the presently logged in user out of Parse database
function logout(){
	Network::logoutUser();
	return "Logged out";
}

// uploads the specified file from a csv to the database, associating the
// data with the presently logged-in user. $fileName is a string that specifies
// the name of a properly formatted .csv file for upload
function uploadCSV($fileName){

	if(file_exists($fileName)){
		$file = fopen($fileName, "r");

		$allNewTransactions = array();
		$transactionsByCategory = array();

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
				$isAsst = $data[5];

				// echo $princ;
				$newTrans = new Transaction();
				$newTrans->accountName = $acntName;
				$newTrans->date = $dt;
				$newTrans->principle = $princ;
				$newTrans->amount = $amnt;
				$newTrans->category = $ctgry;
				if (substr($isAsst, 0, 4 ) === "true") {
					$newTrans->isAsset = true;

				} else {
					$newTrans->isAsset = false;
				}

				// if the array contains the account name as a key already:
				if (array_key_exists($acntName, $allNewTransactions)) {
					// push the new Transaction object into the
					// array held at the account name key
					array_push($allNewTransactions[$acntName], $newTrans);
				// the array doesn't yet have any transactions for
				// this account to add
				} else {
					// create a new array to hold all of the transactions
					// for this particular account
					$tempArr = array();
					// add the first transaction to this array
					array_push($tempArr, $newTrans);
					// add this array to the array holding all transactions
					$allNewTransactions[$acntName] = $tempArr;
				}
			}
		}
		Network::addTransactionsToAccounts($allNewTransactions);

        echo '<script language="javascript">';
        echo 'window.location.assign("../../index.html");';
        echo '</script>';
        return true;
	}
	else {
        echo '<script language="javascript">';
        echo 'window.location.assign("../../index.html");';
        echo '</script>';
        return false;
	}

	//delete file from temporary directory to avoid conflicts with future uploads
	unlink($targetFile);
	return true;
}

// echoes account names for the list of accounts. Additionally
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

// accepts an acount name ($accountName) a start and end date as string objects
// formatted in a YYYY-MM-DD fashion ($startDate and endDate respectively)
// and a string designating a sort parameter (date, value, or type). Echoes
// a properly formatter string that can be parsed by the frontend js
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
    return "SUCCESS";
}

// checks to see if a user is presently logged in. Returns and echos the strings
// "TRUE" or "FALSE" depending on the result.
function userLoggedIn() {
	if(Network::getCurrentUser()) {
		echo "TRUE";
		return "TRUE";
	} else {
		echo "FALSE";
		return "FALSE";
	}
}

// will return nothing if the acocunt doesn't have any transactions.
// $acctName takes a string as the account name and the $acctTrans
// takes an array of Parse transaction objects associated with the
// account specified by $acctName. Returns a properly formatted string
// containing data for a specific account. Example:
// "Account Name 1|2016-5-13_50|2016-6-14_31"
function formIndividualDataForGraph($acctName, $acctTrans) {
	$compactTrans = calculateDailyValues($acctTrans);
	$cumulativeTrans = calculateCumulativeValues($compactTrans);
	$formattedTrans = formatGraphDataToString($acctName, $cumulativeTrans);

	return $formattedTrans;
}

// Requests raw data about all accounts owned by the logged in user and echoes
// results to the front end javascript
function getBaseDataForGraph() {

	//get accounts
	$accounts = Network::getAccounts();

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

	$transAssets = array();
	$transLiabilities = array();

	writeToFile($transAssets, "assets");
	writeToFile($transLiabilities, "liabilities");

	$accountGraphData = "";

	//Getting liability and asset arrays for transactions
	foreach ($accountAssets as $singleAssetAccount) {
		$curName = $singleAssetAccount->get("name");
		$transactions = Network::getTransactionsForAccount($curName);
		if($transactions == NULL){
			continue;
		}
		$transAssets = array_merge($transAssets, $transactions); //add transactions to transAssets array
		$accountGraphData .= formIndividualDataForGraph($curName, $transactions);
	}

	foreach ($accountLiabilities as $singleLiabilityAccount) {
		$curName = $singleLiabilityAccount->get("name");
		$transactions = Network::getTransactionsForAccount($curName);
		if($transactions == NULL){
			continue;
		}
		$transLiabilities = array_merge($transLiabilities, $transactions); //add transactions to transLiabilities array
		$accountGraphData .= formIndividualDataForGraph($curName, $transactions);
	}

	$compactAssets = calculateDailyValues($transAssets);
	$compactLiabilities = calculateDailyValues($transLiabilities);

	$cumulativeAssets = calculateCumulativeValues($compactAssets);
	$cumulativaLiabilities = calculateCumulativeValues($compactLiabilities);

	// if there is no transactions at all, which means you don't have a life
	if (count($transAssets) == 0 && count($transLiabilities) == 0) {
		return "SUCCESS";
	}

	// if you only have assets, which means you do not spend
	if (count($transAssets) > 0 && count($transLiabilities) == 0) {
		$formattedAssets = formatGraphDataToString("Assets", $cumulativeAssets);
		$formattedNetworth = formatGraphDataToString("Net Worth",
							 $cumulativeAssets);
		// already added PHP_EOL in formatGraphDataToString so no need here
		echo $formattedNetworth . $formattedAssets . $accountGraphData;
		return "SUCCESS";
	}

	// if you only have liabilities, which means you are going to be broke
	if (count($transLiabilities) > 0 && count($transAssets) == 0) {
		$formattedLiabilities = formatGraphDataToString("Liabilities", $cumulativaLiabilities);
		foreach ($cumulativaLiabilities as $key => $value) {
			$cumulativaLiabilities[$key] = $value * -1;
		}
		$formattedNetworth = formatGraphDataToString("Networth", $cumulativaLiabilities);
		echo $formattedNetworth . $formattedLiabilities . $accountGraphData;
		return "SUCCESS";
	}

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

	//loop while current date is less than or equal to end date
	while((strcmp($currDate, $endDate)) < 1) {
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
	}

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
	writeToFile($transAssets, "assets");
	writeToFile($transLiabilities, "liabilities");
	return "SUCCESS";
}

// takes in an array of transaction values cumulated by the daily aggragate
// amount and returns a new array of dates and net amount for an account
// per day
function calculateCumulativeValues($transactionsArray) {
	$cumulativeArray = array();
	$prevValue = 0;
	foreach($transactionsArray as $key => $transVal) {
		$cumulativeArray[$key] =  $prevValue + $transVal;
		$prevValue = $cumulativeArray[$key];
	}
	return $cumulativeArray;
}

//take in array and calculate the daily values
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

function getBudgetInformation($categoryName, $monthYear){

	$success = "FAIL";
	if( ($categoryName == "" || $categoryName == NULL)||($monthYear == "" || $monthYear == NULL) ){
		$budgetAmount = 0;
		$amountSpent = 0;
		echo $budgetAmount . "_" . $amountSpent . PHP_EOL;
		return $success;
	}
	//get the first and last day of the month
	$startDate = new DateTime($monthYear . '-01');
	$endDate = clone $startDate;
	$endDate->modify("+1 month");
	$endDate->modify("-1 day");

	$budgetAmount = Network::getAmountForBudget($categoryName, $startDate);

	// echo $budgetAmount;
	// return;

	// $transactions = Network::getTransactionsForCategoryWithinDates($categoryName, $startDate, $endDate);
	/*
	$amountSpent = 0;
	if ($transactions != NULL) {
		foreach ($transactions as $transaction) {
			$isAsset = $transaction->get("isAsset");
			$amount = floatval($transaction->get("amount"));

			if($isAsset){
				$amountSpent -= $amount;
			} else {
				$amountSpent += $amount;
			}
		}
		$success = "SUCCESS";
	}
	*/
	$amountSpent = getAmountSpent($categoryName, $monthYear);

	echo $budgetAmount . "_" . $amountSpent . PHP_EOL;
	return $success;
}

function setBudget($categoryName, $monthYear, $newBudget) {

	$monthYear = new DateTime($monthYear . '-01');
	$result = $monthYear->format('Y-m-d H:i:s');
	// echo $categoryName." ".$result." ".$newBudget;
	echo Network::addBudget($categoryName, $monthYear, floatval($newBudget));
}

function writeToFile($arrayToWrite, $typeInStr) {
	$cacheStr = "";
	foreach ($arrayToWrite as $trans) {
		$cacheStr .= $typeInStr . "_" . $trans->get('date')->format("Y-m-d") . "_" . $trans->get("amount") . "_" . $trans->get("category") . "|";

	}
	$iv = "1234567812345678";
	$pass = 'guessmyPW';
	$method = 'aes256';
	file_put_contents("../../" . $typeInStr  . ".txt", openssl_encrypt($cacheStr, $method, $pass, true, $iv));
}

function getAmountSpent($categoryName, $monthYear) {
	$amountSpent = 0;
	$iv = "1234567812345678";
	$pass = 'guessmyPW';
	$method = 'aes256';

	$cachedStr = file_get_contents("../../assets.txt");
	$cachedTransArray = explode('|', openssl_decrypt($cachedStr, $method, $pass, true, $iv));
	$cachedStr = file_get_contents("../../liabilities.txt");
	$cachedTransArray = array_merge($cachedTransArray, explode('|', openssl_decrypt($cachedStr, $method, $pass, true, $iv)));
	foreach ($cachedTransArray as $cachedTrans) {
	    $cachedTransInfoArray = explode('_', $cachedTrans);
	    // assets_2016-03-28_-1_leisure
	    // --0--  -----1---- 2- ---3---
	    // echo $cachedTrans . "";
	    // echo $cachedTransInfoArray[3] . " ";
	    if (count($cachedTransInfoArray) > 1 && withInDateRange($cachedTransInfoArray[1], $monthYear) && $cachedTransInfoArray[3] == $categoryName) {
	        if($cachedTransInfoArray[0] == "assets"){
	            $amountSpent -= $cachedTransInfoArray[2];
	        } else {
	            $amountSpent += $cachedTransInfoArray[2];
	        }
	    }


	}
	// print_r($cachedTransArray);
	return $amountSpent;


}

function withInDateRange($transTime, $desiredTime) {
	 $transTime =  substr($transTime, 0, -3);
	//  echo $transTime . " " . $desiredTime;
	 return $transTime == $desiredTime;
}

?>
