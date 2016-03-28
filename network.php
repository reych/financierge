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
	function loginUser($username, $password) {
		try {
			$user = ParseUser::logIn($username, $password);
			return $user;
		} catch (ParseException $error) { 
			return NULL;
		}
	}

	function logoutUser() {
		ParseUser::logOut();
	}

	function currentUser() {
		return ParseUser::getCurrentUser();
	}

	function addAccount($name, $balance, $isAsset) {

	}

	function addTransactionToAccount($date, $principle, $amount, $category, $account) {

	}

	function deleteAccount($name) {

	}

	function deleteTransactionFromAccount($transaction, $account) {
		// low priority
	}

	
}
?>