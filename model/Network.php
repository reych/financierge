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

	// Network::loginUser(username: string, password: string)
	// returns ParseUser or NULL
	static function loginUser($username, $password) {
		try {
			$user = ParseUser::logIn($username, $password);
			return $user;
		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return NULL;
	}

	// Network::getCurrentUser() 
	// returns ParseUser or NULL
	static function getCurrentUser() {
		return ParseUser::getCurrentUser();
	}

	// Network::logoutUser() 
	// returns ParseUser or NULL
	static function logoutUser() {
		ParseUser::logOut();
	}

	// Network::addAccount(name: string, isAsset: bool)
	// returns true or false
	static function addAccount($name, $isAsset) {
		try {
			// creates an account and saves it in the Account table on Parse
			$account = new ParseObject("Account");
			$account->set("name", $name);
			$account->set("balance", 0);
			$account->set("isAsset", $isAsset);
			$account->save();
			// adds the account to the accounts array for the user and saves it in the User table on Parse
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

	// Network::getAccounts()
	// returns array of Parse objects or NULL
	static function getAccounts() {
		try {
			$currentUser = ParseUser::getCurrentUser();
			if ($currentUser) {
				$accounts = $currentUser->get("accounts");
				for ($i = 0; $i < count($accounts); $i++) {
					// must call fetch in order to convert the account from Parse pointer to Parse object
					$accounts[$i]->fetch();
					// uncomment the line below to see which accounts were fetched
					// echo $accounts[$i]->get("name") . "\n";
				}
				return $accounts;
			}
		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return NULL;
	}

	// Network::deleteAccount(name: string)
	// returns true or false
	static function deleteAccount($name) {
		try {
			$currentUser = ParseUser::getCurrentUser();
			if ($currentUser) {
				$accounts = $currentUser->get("accounts");
				for ($i = 0; $i < count($accounts); $i++) {
					// must call fetch in order to convert the account from Parse pointer to Parse object
					$accounts[$i]->fetch();
					if (strcmp($accounts[$i]->get("name"), $name) == 0) {
						$transactions = $accounts[$i]->get("transactions");
						for ($i = 0; $i < count($transactions); $i++) {
							// deletes each transaction for the account in the Transaction table on Parse
							$transactions[$i]->destroy();
						}
						// deletes the account in the Account table on Parse
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

	// Network::addTransactionToAccount(name: string, date: DateTime, principe: string, amount: number, category: string)
	// returns true or false
	static function addTransactionToAccount($name, $date, $principle, $amount, $category) {
		try {
			// creates a transaction and saves it in the Transaction table on Parse
			$transaction = new ParseObject("Transaction");
			$transaction->set("date", $date);
			$transaction->set("principle", $principle);
			$transaction->set("amount", $amount);
			$transaction->set("category", $category);
			$transaction->save();
			// adds the transaction to the account in the accounts array for the user and saves it in the User table on Parse
			$currentUser = ParseUser::getCurrentUser();
			if ($currentUser) {
				$accounts = $currentUser->get("accounts");
				for ($i = 0; $i < count($accounts); $i++) {
					// must call fetch in order to convert the account from Parse pointer to Parse object
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

	// Network::getTransactionsForAccount(name: string)
	// returns array of Parse objects or NULL
	static function getTransactionsForAccount($name) {
		try {
			$currentUser = ParseUser::getCurrentUser();
			if ($currentUser) {
				$accounts = $currentUser->get("accounts");
				for ($i = 0; $i < count($accounts); $i++) {
					// must call fetch in order to convert the account from Parse pointer to Parse object
					$accounts[$i]->fetch();
					if (strcmp($accounts[$i]->get("name"), $name) == 0) {
						$transactions = $accounts[$i]->get("transactions");
						for ($k = 0; $k < count($transactions); $k++) {
							// must call fetch in order to convert the transaction from Parse pointer to Parse object
							$transactions[$k]->fetch();
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

	// Network::getTransactionsForAccountWithinDates(name: string, start: DateTime, end: DateTime, sort: string)
	// returns array of Parse objects or NULL
	static function getTransactionsForAccountWithinDates($name, $start, $end, $sort) {
		try {
			$currentUser = ParseUser::getCurrentUser();
			if ($currentUser) {
				$accounts = $currentUser->get("accounts");
				for ($i = 0; $i < count($accounts); $i++) {
					// must call fetch in order to convert the account from Parse pointer to Parse object
					$accounts[$i]->fetch();
					if (strcmp($accounts[$i]->get("name"), $name) == 0) {
						$transactions = $accounts[$i]->get("transactions");
						$transactionIDs = [];
						// stores the object ID for each transaction
						for ($k = 0; $k < count($transactions); $k++) {
							$transactionIDs[] = $transactions[$k]->getObjectId();
						}
						// creates a transaction query for fetching transactions that match the criteria below
						$transactionQuery = new ParseQuery("Transaction");
						$transactionQuery->greaterThanOrEqualTo("date", $end);
						$transactionQuery->lessThanOrEqualTo("date", $start);
						$transactionQuery->containedIn("objectId", $transactionIDs);
						// determines how the transactions should be sorted once fetched
						$amt = strlen("amount");
						$dt = strlen("date");
    					if ((substr($sort, 0, $amt) === "amount") || (substr($sort, 0, $dt) === "date")) {
    						$transactionQuery->descending($sort);
    					} else {
							$transactionQuery->ascending($sort);
						}
						$transactions = $transactionQuery->find();
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
?>
