<?php

include '../controller/php/UserController.php';

class UserControllerTest extends PHPUnit_Framework_TestCase {

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
		echo "importing termianted" . "\n";
		//third try importing accounts twice
		$file_path = "../resources/TwoAccounts.csv";
		$result = uploadCSV($file_path);
		$this->assertTrue($result);
		echo "adding same accounts terminated" . "\n";
		//fourth try with real file for deletion
		$file_path = "../resources/DeleteData.csv";
		$result = uploadCSV($file_path);
		$this->assertTrue($result);
		echo "delete data terminated" . "\n";
		//this upload is just for testing
		$file_path = "../resources/Data.csv";
		$result = uploadCSV($file_path);
		$this->assertTrue($result);
		echo "importing again temrianted" . "\n";
		/*
		//first try with any random string
		$file_path = "/Asdf/dgf.csv";
		$result = uploadCSV($file_path);
		$this->assertNotTrue($result);
		echo "bad file import terminated" . "\n";
		*/
	}

	public function testGetAccountNamesForList(){
		//first test with a user that actually contains accounts
		//in the database: "christdv"
		$result = getAccountNamesForList();
		$this->assertEquals($result, "SUCCESS");

		//now test with user that does NOT contain accounts
		//in the database. ""
		logout();
		login("test2", "t2");
		$result = getAccountNamesForList();
		$this->assertEquals($result, "FAIL");
	}

	public function testGetTransactionsForList(){
		/*
		first test with a user that does NOT have accounts
		in the database: "test2"
		this follows from loggin in last test.
		Expected failure on both tests
		*/
		$result = getTransactionsForList("Checking", NULL, NULL, "date");
		$resultt = getTransactionsForList("asf", NULL, NULL, "date");
		$this->assertEquals($result, "FAIL");
		$this->assertEquals($resultt, "FAIL");
		/*
		now test with a user that actually has an account
		in the database: "test1"
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

	public function testGetBaseDataForGraph(){
		logout();
		login("test5", "t5");
		//first test case where user have no accounts
		// $file_path = "../resources/deleteall.csv";
		// uploadCSV($file_path);
		$result = getBaseDataForGraph();
		$this->assertEquals($result, "SUCCESS");
		//second test case where the user have both assets and liabilities
		$file_path1 = "../resources/Data.csv";
		uploadCSV($file_path1);
		$result = getBaseDataForGraph();
		$this->assertEquals($result, "SUCCESS");
		//third test case where user only have assets
		$file_path2 = "../resources/DeleteVisa.csv";
		uploadCSV($file_path2);
		$result = getBaseDataForGraph();
		$this->assertEquals($result, "SUCCESS");
		//forth test case where the user only have liabilities
		$file_path3 = "../resources/Data.csv";
		uploadCSV($file_path3);
		$file_path4 = "../resources/DeleteAssetsAccounts.csv";
		uploadCSV($file_path4);
		$result = getBaseDataForGraph();
		$this->assertEquals($result, "SUCCESS");
		//clear the accounts afte this test
		$file_path5 = "../resources/DeleteVisa.csv";
		uploadCSV($file_path5);
		//fifth test case to reach our code
		$file_path9 = "../resources/DataSecond.csv";
		uploadCSV($file_path9);
		$result = getBaseDataForGraph();
		$this->assertEquals($result, "SUCCESS");
		//clear contents
		$file_path10 = "../resources/deleteall.csv";
		uploadCSV($file_path10);
		logout();
	}


	public function testGetBudgetInformation() {
		login("test4", "t4");
		$file_path11 = "home/teamh/financierge/resources/JeffsData.csv";
		uploadCSV($file_path11);
		$result = getBudgetInformation("test", "2016-01");
		$this->assertEquals($result, "FAIL");
	}

	public function testSetBudget(){
		setBudget("food", "2016-03", 50);
	}

	public function testGetBudgetAfterSet(){
		$result = getBudgetInformation("food", "2016-03");
		$this->assertEquals($result, "SUCCESS");
	}


	public function testLogout() {

		//clear database before login out, for testing purposes
		$file_path12 = "/home/teamh/financierge/resources/deleteall.csv";
		$result = uploadCSV($file_path12);
		echo "database cleared" . "\n";
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
phpunit --coverage-html report testForUserController.php --whitelist ../controller/php/UserController.php
*/

?>
