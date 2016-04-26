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
		$result = Network::addTransactionToAccount("Savings", new DateTime("2016-03-31"), "Test Principle", 35.4, "Test Category", true);
		$this->assertTrue($result);
		$result = Network::addTransactionToAccount("asdf", new DateTime("2016-03-31"), "Test Principle", 35.4, "Test Category", true);
		$this->assertNotTrue($result);
		Network::logoutUser();
	}

	function testAddTransactionsToAccounts() {

		//set up data in required format using transaction objects
		//there will be two transactions
		Network::loginUser("test3", "t3");

		$newTrans1 = new Transaction();
		$newTrans1->accountName = "Savings";
		$newTrans1->date = new DateTime("2016/1/5");
		$newTrans1->principle = "Testtesttest1";
		$newTrans1->amount = floatval(3.565);
		$newTrans1->category = "food";
		$newTrans1->isAsset = true;

		$newTrans2 = new Transaction();
		$newTrans2->accountName = "Savings";
		$newTrans2->date = new DateTime("2016/2/2");
		$newTrans2->principle = "Testteest2";
		$newTrans2->amount = floatval(5.565);
		$newTrans2->category = "food";
		$newTrans2->isAsset = true;

		$tempArr = array();
		// add the first transaction to this array
		array_push($tempArr, $newTrans1);
		array_push($tempArr, $newTrans2);
		// add this array to the array holding all transactions
		$allNewTransactions["Savings"] = $tempArr;
		$result = Network::addTransactionsToAccounts($allNewTransactions);
		$this->assertTrue($result);


		$newTrans3 = new Transaction();
		$newTrans3->accountName = "Savings";
		$newTrans3->date = new DateTime("2016/2/2");
		$newTrans3->principle = "Testteest3";
		$newTrans3->amount = floatval(5.565);
		$newTrans3->category = "food";
		$newTrans3->isAsset = true;

		$newTrans4 = new Transaction();
		$newTrans4->accountName = "Savings";
		$newTrans4->date = new DateTime("2016/2/2");
		$newTrans4->principle = "Testteest4";
		$newTrans4->amount = floatval(5.565);
		$newTrans4->category = "work";
		$newTrans4->isAsset = true;
		// create a new array to hold all of the transactions for this particular account
		$Arr = array();
		// add the first transaction to this array
		array_push($Arr, $newTrans3);
		array_push($Arr, $newTrans4);
		// add this array to the array holding all transactions
		$newTransactions["Savings"] = $Arr;
		$result = Network::addTransactionsToAccounts($newTransactions);
		$this->assertTrue($result);
		Network::logoutUser();
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

	// tests if the user has transactions for a particular category
	// within date a range
	function testGetTransactionsForCategorytWithinDates(){

		Network::loginUser("test3", "t3");
		$start = new DateTime("2016-01-01");
		$end = new DateTime("2016-01-30");
		$result = Network::getTransactionsForCategoryWithinDates("food", $start, $end);
		$this->assertNotNull($result);
		$start = new DateTime("2016-02-01");
		$end = new DateTime("2016-02-30");
		$result = Network::getTransactionsForCategoryWithinDates("food", $start, $end);
		$this->assertNotNull($result);
		Network::logoutUser();
	}

	// tests if the user has a budget set for a particular category and month
	function testGetBudgetAmount(){
		Network::loginUser("test3", "t3");
		$monthYear = new DateTime("2016-02");
		$result = Network::getAmountForBudget("food", $monthYear);
		$this->assertEquals($result, 0);
		Network::logoutUser();
	}

	// tests if the user can set a new budget for a particular month and category
	function testSetBudget(){
		Network::loginUser("test3", "t3");
		$monthYear = new DateTime("2016-02");
		$result = Network::addBudget("food", $monthYear, 100);
		$this->assertTrue($result);
		Network::logoutUser();

	}

	function testGetBudgetAmountAfterSet(){
		Network::loginUser("test3", "t3");
		$monthYear = new DateTime("2016-02");
		$result = Network::getAmountForBudget("food", $monthYear);
		$this->assertEquals($result, 100);
		Network::logoutUser();

	}

	function testResetBudget(){
		Network::loginUser("test3", "t3");
		$monthYear = new DateTime("2016-02");
		$result = Network::addBudget("food", $monthYear, 150);
		$this->assertTrue($result);
		$result = Network::getAmountforBudget("food", $monthYear);
		$this->assertEquals($result, 150);
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
