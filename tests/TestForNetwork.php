<?php
include("../model/Network.php");

class NetworkClassTest extends PHPUnit_Framework_TestCase {

	// tests if the user can login
	function testLoginUser() {
		$user = Network::loginUser("asdf", "asd");
		$this->assertNull($user); 
		$user = Network::loginUser("test3", "t3");
		$this->assertNotNull($user); 
		Network::logoutUser();
	}

	// tests if the user can logout
	function testLogoutUser() {
		Network::loginUser("test3", "t3");
		$currentUser = Network::getCurrentUser();
		$this->assertNotNull($currentUser);
		Network::logoutUser();
		$currentUser = Network::getCurrentUser();
		$this->assertNull($currentUser);
	}

	// tests if the user can stay logged in
	function testGetCurrentUser() {
		$currentUser = Network::getCurrentUser();
		$this->assertNull($currentUser);
		Network::loginUser("test3", "t3");
		$currentUser = Network::getCurrentUser();
		$this->assertNotNull($currentUser);
		Network::logoutUser();
	}

	// tests if the user can add an account
	function testAddAccount() {
		Network::loginUser("test3", "t3");
		$result = Network::addAccount("Savings", true);
		$this->assertTrue($result);
		Network::logoutUser();
	}

	// tests if the accounts for the user can be fetched at once
	function testGetAccounts() {
		$accounts = Network::getAccounts();
		$this->assertNull($accounts);
		Network::loginUser("test3", "t3");
		$accounts = Network::getAccounts();
		$this->assertNotNull($accounts);
		Network::logoutUser();
	}

	// tests if transactions can be added to accounts
	function testAddTransactionToAccount() {
		Network::loginUser("test3", "t3");
		$result = Network::addTransactionToAccount("Savings", new DateTime("2016-03-31"), "Test Principle", 35.4, "Test Category");
		$this->assertTrue($result);
		$result = Network::addTransactionToAccount("asdf", new DateTime("2016-03-31"), "Test Principle", 35.4, "Test Category");
		$this->assertNotTrue($result);
		Network::logoutUser();
	}

	function testAddTransactionsToAccounts() {
		
		//set up data in required format using transaction objects
		//there will be two transactions
		$acntName = "Savings";
		$dt = new DateTime("2016/1/1");
		$princ = "Testtesttest";
		$amnt = floatval(3.565);
		$ctgry = "other";

		$newTrans1 = new Transaction();
		$newTrans1->accountName = $acntName;
		$newTrans1->date = $dt;
		$newTrans1->principle = $princ;
		$newTrans1->amount = $amnt;
		$newTrans1->category = $ctgry;

		$dt = new DateTime("2016/2/2");
		$princ = "Testteest";
		$amnt = floatval(5.565);

		$newTrans2 = new Transaction();
		$newTrans2->accountName = $acntName;
		$newTrans2->date = $dt;
		$newTrans2->principle = $princ;
		$newTrans2->amount = $amnt;
		$newTrans2->category = $ctgry;

		// create a new array to hold all of the transactions for this particular account
		$tempArr = array();
		// add the first transaction to this array
		array_push($tempArr, $newTrans1);
		array_push($tempArr, $newTrans2);
		// add this array to the array holding all transactions
		$allNewTransactions[$acntName] = $tempArr;

		$result = Network::addTransactionsToAccounts($allNewTransactions);
		$this->assertTrue($result);
	}

	// tests if transactions can be fetched for accounts
	function testGetTransactionsForAccounts() {
		Network::loginUser("test3", "t3");
		$result = Network::getTransactionsForAccount("Savings");
		$this->assertNotNull($result);
		$result = Network::getTransactionsForAccount("asdf");
		$this->assertNull($result);
		Network::logoutUser();
	}

	// tests if transactions can be fetched for accounts according to certain dates
	function testGetTransactionsForAccountWithinDates() {
		Network::loginUser("test3", "t3");
		$result = Network::getTransactionsForAccountWithinDates("Savings", new DateTime("2016-03-28"), new DateTime("2016-03-31"), "date");
		$this->assertNotNull($result);
		$result = Network::getTransactionsForAccountWithinDates("Savings", new DateTime("2016-03-28"), new DateTime("2016-03-31"), "amount"); // tests different sort key
		$this->assertNotNull($result);
		$result = Network::getTransactionsForAccountWithinDates("asdf", new DateTime("2016-03-28"), new DateTime("2016-03-31"), "amount"); // tests incorrect account name
		$this->assertNull($result);
		Network::logoutUser();
	}

	// tests if the user can delete an account
	function testDeleteAccount(){
		Network::loginUser("test3", "t3");
		$result = Network::deleteAccount("Savings");
		$this->assertTrue($result);
		$result = Network::deleteAccount("asdf");
		$this->assertNotTrue($result);
		Network::logoutUser();
	}
}
//phpunit --coverage-html report testForNetwork.php --whitelist ../model/Network.php
?>