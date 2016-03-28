<?php
session_start();
include 'vendor/autoload.php';
use Parse\ParseClient;
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseException;
use Parse\ParseUser;
date_default_timezone_set('America/Los_Angeles');
ParseClient::initialize('9DwkUswTSJOLVi7dkRJxDQNbwHSDlQx3NTdXz5B0', '6HFMDcw8aRr9O7TJ3Pw8YOWbecrdiMuAPEL3OXia', 'IdmvCVEBYygkFTRmxOwUvSxtnXwlaGDF9ndq5URq');

class Network {

	function signupUser($username, $password) {
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

	function loginUser($username, $password) {
		try {
			$user = ParseUser::logIn($username, $password);
			return $user;
		} catch (ParseException $error) { 
			return NULL;
		}
	}

	function currentUser() {
		return ParseUser::getCurrentUser();
	}

	function logoutUser() {
		ParseUser::logOut();
	}

	function addAccount($name, $isAsset) {
		// return true or false
	}

	function deleteAccount($name) {
		// return true or false
	}

	function addTransactionToAccount($date, $principle, $amount, $category, $account) {
		// return true or false 
	}

	function getTransactionsForAccount($account) {
		// return array
	}

	function getTransactionsForAccountBetweenDates($startDate, $endDate, $account) {
		// return array (inclusive)
	}
}
?>