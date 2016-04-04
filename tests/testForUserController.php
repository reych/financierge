<?php

include '../controller/php/UserController.php';

class userControllerTest extends PHPUnit_Framework_TestCase {

	public function testLogin(){
		$result = login("asd", "asdf");
		$resultt= login("test1", "t1");
		$this->assertEquals($result, "FAIL");
		$this->assertEquals($resultt, "SUCCESS");
	}

	public function testUploadCSV(){

		//second try with real file for importing
		$file_path = "../resources/Data.csv";
		$result = uploadCSV($file_path);
		$this->assertTrue($result);
/*
		//third try importing accounts twice
		$file_path = "../resources/TwoAccounts.csv";
		$result = uploadCSV($file_path);
		$this->assertEquals($result, "SUCCESS");

		//fourth try with real file for deletion
		$file_path = "../resources/DeleteData.csv";
		$result = uploadCSV($file_path);
		$this->assertEquals($result, "SUCCESS");

		//this upload is just for testing
		$file_path = "../resources/Data.csv";
		$result = uploadCSV($file_path);
		$this->assertEquals($result, "SUCCESS");

/*
		//first try with any random string
		$file_path = "/Asdf/dgf.csv";
		$result = uploadCSV($file_path);
		$this->assertEquals($result, "FAIL");
		*/
	}

	public function testGetAccountNamesForList(){
		//first test with a user that actually contains accounts
		//in the database: "christdv"
		$result = getAccountNamesForList();
		$this->assertEquals($result, "SUCCESS");

		//now test with user that does NOT contain accounts
		//in the database. "edgarlug"
		logout();
		login("test2", "t2");
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
		login("test1", "t1");
		$result = getTransactionsForList("Checking", NULL, NULL, "date");
		$resultt = getTransactionsForList("asf", NULL, NULL, "date");
		$this->assertEquals($result, "SUCCESS");
		$this->assertEquals($resultt, "FAIL");
	}

	public function testUserLoggedIn(){
		$result = userLoggedIn();
		$this->assertEquals($result, "TRUE");
	}

	public function testLogout() {
/*
		//clear database before login out, for testing purposes
		$file_path = "/home/teamh/financierge/resources/DeleteData.csv";
		$result = uploadCSV($file_path);
*/
		$result = logout();
		$this->assertEquals($result, "Logged out");
	}

	public function testUserLoggedInAfterLogout(){
		$result = userLoggedIn();
		$this->assertEquals($result, "FALSE");
	}

}

/*
The following command is used to run this test:
phpunit --coverage-html report testForUserController.php --whitelist ../Controller/php/userController.php
*/

?>