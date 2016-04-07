<?php
include '../model/Network.php';

class NetworkClassTest extends PHPUnit_Framework_TestCase {

	function testLoginUser(){
		$user = Network::loginUser("asdf", "asd");
		$this->assertNull($user);
		$user = Network::loginUser("test3", "t3");
		$this->assertNotNull($user);
	}

	function testLogout(){
		Network::logoutUser();
		$user = Network::getCurrentUser();
		$this->assertNull($user);
	}

	function testGetCurrentUser(){
		$user = Network::loginUser("test3", "t3");
		$getUser = Network::getCurrentUser();
		$this->assertNotNull($getUser);
	}

	function testAddAccount(){
		$result = Network::addAccount("Credit", false);
		$this->assertTrue($result);
		$result = Network::addAccount("Savings", true);
		$this->assertTrue($result);
	}

	function testGetAccounts(){
		$result = Network::getAccounts();
		$this->assertNotNull($result);

		Network::logoutUser();
		$result = Network::getAccounts();
		$this->assertNull($result);

		Network::loginUser("test3", "t3");
	}

	function testAddTransactionToAccount(){
		$result = Network::addTransactionToAccount("Savings", new DateTime("2016-03-31"), "testTest", 35.4, "other");
		$this->assertTrue($result);

		//changed account name
		$result = Network::addTransactionToAccount("Savis", new DateTime("2016-03-31"), "testTest", 35.4, "other");
		$this->assertNotTrue($result);
	}

	function testAddTransactionsToAccounts(){
		//for next sprint
	}


	function testGetTransactionsForAccounts(){
		$result = Network::getTransactionsForAccount("Savings");
		$this->assertNotNull($result);

		$result = Network::getTransactionsForAccount("asdf");
		$this->assertNull($result);
	}


	function testGetTransactionsForAccountWithinDates(){
		
		$result = Network::getTransactionsForAccountWithinDates("Savings", new DateTime("2016-03-28"), new DateTime("2016-03-31"), "date");
		$this->assertNotNull($result);

		//changed the sorting
		$result = Network::getTransactionsForAccountWithinDates("Savings", new DateTime("2016-03-28"), new DateTime("2016-03-31"), "amount");
		$this->assertNotNull($result);

		//changed the account name
		$result = Network::getTransactionsForAccountWithinDates("Savin", new DateTime("2016-03-28"), new DateTime("2016-03-31"), "amount");
		$this->assertNull($result);
	}

	function testDeleteAccount(){
		$result = Network::deleteAccount("Credit");
		$this->assertTrue($result);

		$result = Network::deleteAccount("Savings");
		$this->assertTrue($result);

		$result = Network::deleteAccount("Asd");
		$this->assertNotTrue($result);

		Network::logoutUser();
	}
}
//phpunit --coverage-html report testForNetwork.php --whitelist ../model/Network.php

?>