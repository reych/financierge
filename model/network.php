<?php
/*

-----------------
NETWORK CLASS API
-----------------

Network::signupUser(username, password) -> true if signup was successful, false if otherwise

Network::loginUser(username, password) -> user object if login was successful, NULL if otherwise

Network::getCurrentUser() -> user object if user is logged in, NULL is otherwise

Network::logoutUser() -> nothing

Network::addAccount(name, isAsset) -> true if account was added sucessfully, false if otherwise

Network::deleteAccount(name) -> true if account was deleted sucessfully, false if otherwise

Network::addTransactionToAccount(date, principle, amount, category, name) -> true if transaction was added sucessfully, false if otherwise

Network::getTransactionsForAccount(name) -> array of transactions if fetch was successful, NULL if otherwise

Network::getTransactionsForAccountWithinDates(startDate, endDate, name) -> array of transactions if fetch was successful, NULL if otherwise

*/

include("vendor/autoload.php");
use Parse\ParseClient;
use Parse\ParseException;
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseUser;

session_start();
date_default_timezone_set("America/Los_Angeles");
ParseClient::initialize("9DwkUswTSJOLVi7dkRJxDQNbwHSDlQx3NTdXz5B0", "6HFMDcw8aRr9O7TJ3Pw8YOWbecrdiMuAPEL3OXia", "IdmvCVEBYygkFTRmxOwUvSxtnXwlaGDF9ndq5URq");

class Network {

	// tested
	static function signupUser($username, $password) {
		try {
			$user = new ParseUser();
			$user->set("username", $username);
			$user->set("password", $password);
			$user->signUp();
			return true;
		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return false;
	}

	// tested 
	static function loginUser($username, $password) {
		try {
			$user = ParseUser::logIn($username, $password);
			return $user;
		} catch (ParseException $error) { 
			echo $error->getMessage();
		}
		return NULL;
	}

	// tested 
	static function getCurrentUser() {
		return ParseUser::getCurrentUser();
	}

	// tested 
	static function logoutUser() {
		ParseUser::logOut();
	}

	// tested 
	static function addAccount($name, $isAsset) {
		try {
			// create account and save it in Account table
			$account = new ParseObject("Account");
			$account->set("name", $name);
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

	// tested 
	static function getAccounts() {
		try {
			$currentUser = ParseUser::getCurrentUser();
			if ($currentUser) {
				$accounts = $currentUser->get("accounts");
				for ($i = 0; $i < count($accounts); $i++) {
					$accounts[$i]->fetch();
					echo $accounts[$i]->get("name") . "\n"; // used for testing purposes only
				}
				return $accounts;
			}
		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return NULL;
	}

	// tested
	static function deleteAccount($name) {
		try {
			$currentUser = ParseUser::getCurrentUser();
			if ($currentUser) {
				$accounts = $currentUser->get("accounts");
				for ($i = 0; $i < count($accounts); $i++) {
					$accounts[$i]->fetch();
					if (strcmp($accounts[$i]->get("name"), $name) == 0) {
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

	// not tested
	static function addTransactionToAccount($date, $principle, $amount, $category, $name) {
		try {
			$transaction = new ParseObject("Transaction");
			$transaction->set("date", $date);
			$transaction->set("principle", $principle);
			$transaction->set("amount", $amount);
			$transaction->set("category", $category);
			$transaction->save();
			$accountQuery = new ParseQuery("Account");
			$account = $accountQuery->get($accountID);
			$transactions = $account->get("transactions");
			$transactions[] = $transaction->getObjectId();
			$account->setArray("transactions", $transactions);
			$account->save();
			return true;
		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return false;
	}

	// not tested
	static function getTransactionsForAccount($name) {
		try {
			$accountQuery = new ParseQuery("Account");
			$account = $accountQuery->get($id);
			$transactionIDs = $account->get("transactions");
			$transactions = [];
			foreach ($transactionIDs as $transactionID) {
				$transactionQuery = new ParseQuery("Transaction");
				$transaction = $transactionQuery->get($transactionID);
				$transactions[] = $transaction;
			}
			return $transactions;
		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return NULL;
	}

	// not tested
	static function getTransactionsForAccountWithinDates($startDate, $endDate, $name) {
		try {
		
		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return NULL;
	}
}

?>