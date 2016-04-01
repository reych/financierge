<?php

include 'userController.php';

/*
This testing is using..
is for...
*/
class userControllerTest extends PHPUnit_Framework_TestCase {

	public function testCoverage(){
		$result = login();
		$this->assertEquals($result, "FAIL");
	}


?>