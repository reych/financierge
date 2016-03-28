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

Network::deleteAccount(id) -> true if account was deleted sucessfully, false if otherwise

Network::addTransactionToAccount(date, principle, amount, category, id) -> true if transaction was added sucessfully, false if otherwise

Network::getTransactionsForAccount(id) -> array of transactions if fetch was successful, NULL if otherwise

Network::getTransactionsForAccountWithinDates(startDate, endDate, id) -> array of transactions if fetch was successful, NULL if otherwise

*/

include("vendor/autoload.php");
use Parse\ParseClient;
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseException;
use Parse\ParseUser;

session_start();
date_default_timezone_set("America/Los_Angeles");
ParseClient::initialize("9DwkUswTSJOLVi7dkRJxDQNbwHSDlQx3NTdXz5B0", "6HFMDcw8aRr9O7TJ3Pw8YOWbecrdiMuAPEL3OXia", "IdmvCVEBYygkFTRmxOwUvSxtnXwlaGDF9ndq5URq");

class Network {

	static function signupUser($username, $password) {
		$user = new ParseUser();
		$user->set("username", $username);
		$user->set("password", $password);
		try {
			$user->signUp();
			return true;
		} catch (ParseException $error) {
			return false;
		}
	}

	static function loginUser($username, $password) {
		try {
			$user = ParseUser::logIn($username, $password);
			return $user;
		} catch (ParseException $error) { 
			return NULL;
		}
	}

	static function getCurrentUser() {
		return ParseUser::getCurrentUser();
	}

	static function logoutUser() {
		ParseUser::logOut();
	}

	static function addAccount($name, $isAsset) {
		// return true or false
	}

	static function deleteAccount($id) {
		// return true or false
	}

	static function addTransactionToAccount($date, $principle, $amount, $category, $id) {
		// return true or false 
	}

	static function getTransactionsForAccount($id) {
		// return array
	}

	static function getTransactionsForAccountWithinDates($startDate, $endDate, $id) {
		// return array (inclusive)
	}
}

?>