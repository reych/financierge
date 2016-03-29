<?php

include 'Network.php';

$network;
$userModel;


function login($username, $password){
	/*
	//call parse class login function
	//get the model
	$network = new Network();
	$userModel = $network->login($username, $password);

	if($userModel){
		//echo error message
	}
	else {
		//echo login successfull and go to main page
	}
	*/
}


function uploadCSV(){

	//get file from temporary direcory where it is stored
	$target_dir = sys_get_temp_dir();
	//complete file path
	$target_file = $target_dir . "/" . basename($_FILES["fileToUpload"]["name"]);
	$uploadOK = 1;
	$fileType = pathinfo($target_file,PATHINFO_EXTENSION);
	if($fileType != "csv"){
	    $uploadOK = 0;
	}
	if($uploadOK == 0){
	    echo '<script language="javascript">';
	    echo 'alert("You did not provide a CSV file");';
	    echo 'window.location.assign("index.php");';
	    echo '</script>';
	    exit();
	}
	else{
	    //move file to the temporary directory to process
	    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
	    //open file
	    if (($file = fopen($target_file, "r")) !== FALSE) {
	        //while
	        while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
	            foreach ($filedata as $data) {
					if(count($data) == 3){
					//account
						$accountName = $data[0];
						$balance = $data[1];
						$type = $data[2];

						// if the account was not successfully added
						if(!$userModel.addAccount($name, $balance, $type)){
							//add to an array of all the transactions not uploaded
						}

					} else {
					//transaction
						$accountName = $data[0];
						$date = $data[1];
						$principle = $data[2];
						$amount = $data[3];
						$category = $data[4];

						$added = $userModel.addTransaction($accountName, $date, $principle, $amount, $category);

						if(!$added){
							//add to an array of all the transactions not uploaded
						}
					}
	            }

	            //check if array is emtpy, if not, echo all the transactions not uploaded, with a small message
	        }

	    } else {
	        echo '<script language="javascript">';
	        echo 'alert("Error");';
	        echo 'window.location.assign("index.html");';
	        echo '</script>';
	    }
	}
	//delete file from temporary directory to avoid conflicts with future uploads
	unlink($target_file);
	// header("Location:index.html");
}

function formatTransactions(){



}

function prepareForGraph(){



}

/*
For $sort
1: Sort by date
2: Sort by amount
3: Sort by category
4: Sort by alphabetical ordey by principle
*/

function getTransactionsForList($accountName, $sort, $startDate, $endDate){

	// all transactions
	$transactions = $userModel.getTransactionsWithinDates();

	// sorted transactions



}

function sortTransactions(){


}


?>
