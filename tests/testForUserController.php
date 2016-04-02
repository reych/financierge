<?php

include '../Controller/php/userController.php';
include("../../model/vendor/autoload.php");
use Parse\ParseClient;
use Parse\ParseException;
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseUser;

session_start();
date_default_timezone_set("America/Los_Angeles");
ParseClient::initialize("9DwkUswTSJOLVi7dkRJxDQNbwHSDlQx3NTdXz5B0", "6HFMDcw8aRr9O7TJ3Pw8YOWbecrdiMuAPEL3OXia", "IdmvCVEBYygkFTRmxOwUvSxtnXwlaGDF9ndq5URq");


class userControllerTest extends PHPUnit_Framework_TestCase {

	public function testLogin(){
		$result = login("asd", "asdf");
		$resultt= login("christdv@usc.edu", "christdv");
		$this->assertEquals($result, "FAIL");
		$this->assertEquals($resultt, "SUCCESS");
	}

	public function testGetAccountNamesForList(){
		//first test with a user that actually contains accounts
		//in the database: "christdv"
		$result = getAccountNamesForList();
		$this->assertEquals($result, "SUCCESS");

		//now test with user that does NOT contain accounts
		//in the database. "edgarlug"
		logout();
		login("edgarlug@usc.edu", "ed");
		$result = getAccountNamesForList();
		$this->assertEquals($result, "FAIL");
	}

	public function testGetTransactionsForList(){
		/*
		first test with a user that does NOT have accounts
		in the database: "edgarlug"
		this follows from loggin in last test.
		Expected failure on both tests
		*/
		$result = getTransactionsForList("Checking", NULL, NULL, "date");
		$resultt = getTransactionsForList("asf", NULL, NULL, "date");
		$this->assertEquals($result, "FAIL");
		$this->assertEquals($resultt, "FAIL");
		/*
		now test with a user that actually has an account
		in the database: "christdv"
		Expected succes on real name "Checking" and 
		failure on "asf"
		*/
		logout();
		login("christdv@usc.edu", "christdv");
		$result = getTransactionsForList("Checking", NULL, NULL, "date");
		$resultt = getTransactionsForList("asf", NULL, NULL, "date");
		$this->assertEquals($result, "SUCCESS");
		$this->assertEquals($resultt, "FAIL");
	}

	public function testUploadCSV(){


		//first try with any random string
		$file_path = "/Asdf/dgf.csv";
		$result = uploadCSV($file_path);
		$this->assertEquals($result, "FAIL");

		//second try with real file for deletion
		$file_path = "/home/teamh/financierge/resources/jeffsdelete.csv";
		$result = uploadCSV($file_path);
		$this->assertEquals($result, "SUCCESS");

		//third try with real file for importing
		$file_path = "/home/teamh/financierge/resources/data.csv";
		$result = uploadCSV($file_path);
		$this->assertEquals($result, "SUCCESS");

		//fourth try importing accounts twice
		$file_path = "/home/teamh/financierge/resources/twoAccounts.csv";
		$result = uploadCSV($file_path);
		$this->assertEquals($result, "SUCCESS");
	}

	public function testUserLoggedIn(){
		$result = userLoggedIn();
		$this->assertEquals($result, "TRUE");
	}

	public function testLogout() {
		$result = logout();
		$this->assertEquals($result, "Logged out");
	}

	public function testUserLoggedInAfterLogout(){
		$result = userLoggedIn();
		$this->assertEquals($result, "FALSE");
	}

}

//phpunit --coverage-html report testForUserController.php --whitelist userController.php

?>