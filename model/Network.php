<?php
include("vendor/autoload.php");
include("Transaction.php");
use Parse\ParseClient;
use Parse\ParseException;
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseUser;

date_default_timezone_set("America/Los_Angeles");
session_start();
ParseClient::initialize("9DwkUswTSJOLVi7dkRJxDQNbwHSDlQx3NTdXz5B0", "6HFMDcw8aRr9O7TJ3Pw8YOWbecrdiMuAPEL3OXia", "IdmvCVEBYygkFTRmxOwUvSxtnXwlaGDF9ndq5URq");

class Network {

	static function loginUser($username, $password) {
		try {
			$user = ParseUser::logIn($username, $password);
			return $user;
		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return NULL;
	}

	static function getCurrentUser() {
		return ParseUser::getCurrentUser();
	}

	static function logoutUser() {
		ParseUser::logOut();
	}

	static function addAccount($name, $isAsset) {
		try {
			// create account and save it in Account table
			$account = new ParseObject("Account");
			$account->set("name", $name);
			$account->set("balance", 0);
			$account->set("isAsset", $isAsset);
			$account->save();
			// add account to accounts array for current user and save it in User table
			$currentUser = ParseUser::getCurrentUser();
			if ($currentUser) {
				$accounts = $currentUser->get("accounts");
				$accounts[] = $account;
				$currentUser->setArray("accounts", $accounts);
				$currentUser->save();
				return true;
			}
		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return false;
	}

	static function getAccounts() {
		try {
			$currentUser = ParseUser::getCurrentUser();
			if ($currentUser) {
				$accounts = $currentUser->get("accounts");
				for ($i = 0; $i < count($accounts); $i++) {
					$accounts[$i]->fetch();
					//echo $accounts[$i]->get("name") . "\n"; // used for testing purposes only
				}
				return $accounts;
			}
		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return NULL;
	}

	static function deleteAccount($name) {
		try {
			$currentUser = ParseUser::getCurrentUser();
			if ($currentUser) {
				$accounts = $currentUser->get("accounts");
				for ($i = 0; $i < count($accounts); $i++) {
					$accounts[$i]->fetch();
					if (strcmp($accounts[$i]->get("name"), $name) == 0) {
						$transactions = $accounts[$i]->get("transactions");
						if($transactions){
							foreach ($transactions as $transaction) {
								$transaction->destroy();
							}
						}
						$accounts[$i]->destroy();
						unset($accounts[$i]);
						$currentUser->setArray("accounts", $accounts);
						$currentUser->save();
						return true;
					}
				}
			}
		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return false;
	}

	static function addTransactionToAccount($name, $date, $principle, $amount, $category) {
		try {
			// create transaction and save it in Transaction table
			$transaction = new ParseObject("Transaction");
			$transaction->set("date", $date);
			$transaction->set("principle", $principle);
			$transaction->set("amount", $amount);
			$transaction->set("category", $category);
			$transaction->save();
			// add transaction to specified account in accounts array for current user and save it in User table
			$currentUser = ParseUser::getCurrentUser();
			if ($currentUser) {
				$accounts = $currentUser->get("accounts");
				for ($i = 0; $i < count($accounts); $i++) {
					$accounts[$i]->fetch();
					if (strcmp($accounts[$i]->get("name"), $name) == 0) {
						$transactions = $accounts[$i]->get("transactions");
						$transactions[] = $transaction;
						$accounts[$i]->setArray("transactions", $transactions);
						$currentUser->setArray("accounts", $accounts);
						$currentUser->save();
						return true;
					}
				}
			}
		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return false;
	}

	// take in an associative array with account names as keys and all transactions to be added to that particular account as an array as the value
	static function addTransactionsToAccounts($newTransactions) {

		try {
			
			// add transaction to specified account in accounts array for current user and save it in User table
			$currentUser = ParseUser::getCurrentUser();
			if ($currentUser) {
				// get all accounts for current user from parse
				$accounts = $currentUser->get("accounts");

				// for each account that the user has
				for ($i = 0; $i < count($accounts); $i++) {
					$accountQuery = new ParseQuery("Account");
					// get the actual account (just had reference, not actual object)
					// $actualAccount = $accountQuery->get($accounts[$i]);//->fetch();
					$accounts[$i]->fetch();

					// get the name of this particular account
					$thisAccountName = $accounts[$i]->get("name");
					// $thisAccountName = $actualAccount->get("name");

					// if the account name matches a key in newTransactions, then we have some transactions to add to this account
					if (array_key_exists($thisAccountName, $newTransactions)) {
						// an array of new transaction objects (from model file) for this particular account
						$newTransForAccount = $newTransactions[$thisAccountName];

						$currentAccountTransactions = $accounts[$i]->get("transactions");
						// $currentAccountTransactions = $actualAccount->get("transactions")

						// for each new transaction for this account, add it to the account
						for ($j = 0; $j < count($newTransForAccount); $j++) {

							// echo "Trying to add transaction: ".$newTransForAccount[$j]->$principle.PHP_EOL;

							// create new parse Transaction object
							$transaction = new ParseObject("Transaction");
							$transaction->set("date", $newTransForAccount[$j]->date);
							$transaction->set("principle", $newTransForAccount[$j]->principle);
							$transaction->set("amount", $newTransForAccount[$j]->amount);
							$transaction->set("category", $newTransForAccount[$j]->category);
							$transaction->save();

							$currentAccountTransactions[] = $transaction;
						}
						

						$accounts[$i]->setArray("transactions", $currentAccountTransactions);
						// $actualAccount->setArray("transactions", $currentAccountTransactions);
					}
				}
				$currentUser->setArray("accounts", $accounts);
				$currentUser->save();
			}
			return true;

		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return false;
	}

	static function getTransactionsForAccount($name) {
		try {
			$currentUser = ParseUser::getCurrentUser();
			if ($currentUser) {
				$accounts = $currentUser->get("accounts");
				for ($i = 0; $i < count($accounts); $i++) {
					$accounts[$i]->fetch();
					if (strcmp($accounts[$i]->get("name"), $name) == 0) {
						$transactions = $accounts[$i]->get("transactions");
						for ($k = 0; $k < count($transactions); $k++) {
							$transactions[$k]->fetch();
							// echo $transactions[$k]->get("principle") . " -- " . $transactions[$k]->getObjectId() . "\n"; // used for testing purposes only
						}
						return $transactions;
					}
				}
			}
		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return NULL;
	}

	static function getTransactionsForAccountWithinDates($name, $start, $end, $sort) {
		try {
			$currentUser = ParseUser::getCurrentUser();
			if ($currentUser) {
				$accounts = $currentUser->get("accounts");
				for ($i = 0; $i < count($accounts); $i++) {
					$accounts[$i]->fetch();
					if (strcmp($accounts[$i]->get("name"), $name) == 0) {
						$transactions = $accounts[$i]->get("transactions");
						$transactionIDs = [];
						for ($k = 0; $k < count($transactions); $k++) {
							$transactionIDs[] = $transactions[$k]->getObjectId();
						}
						$transactionQuery = new ParseQuery("Transaction");
						$transactionQuery->greaterThanOrEqualTo("date", $end);
						$transactionQuery->lessThanOrEqualTo("date", $start);
						$transactionQuery->containedIn("objectId", $transactionIDs);

						$amt = strlen("amount");
						$dt = strlen("date");
    					if ((substr($sort, 0, $amt) === "amount") || (substr($sort, 0, $dt) === "date")) {
    						$transactionQuery->descending($sort);
    					} else {
							$transactionQuery->ascending($sort);
						}

						$transactions = $transactionQuery->find();
						// for ($k = 0; $k < count($transactions); $k++) { // used for testing purposes only
						// 	echo $transactions[$k]->get("principle") . " -- " . $transactions[$k]->getObjectId() . "\n";
						// }
						return $transactions;
					}
				}
			}
		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return NULL;
	}
}

// test for fetching transactions within certain dates
// Network::loginUser("christdv@usc.edu", "christdv");
// // $date = new DateTime("2016-03-15");
// // Network::addTransactionToAccount("Christian's Checking Acct", $date, "USC", 1024.95, "Food");
// $start = new DateTime("2016-03-10");
// $end = new DateTime("2016-03-23");
// Network::getTransactionsForAccountWithinDates("Checking", $start, $end, "date");
// Network::logoutUser();
?>
