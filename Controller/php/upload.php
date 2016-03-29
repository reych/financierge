<?php
// include '../../vendor/autoload.php';
// use Parse\ParseClient;
// use Parse\ParseObject;
// use Parse\ParseQuery;
// use Parse\ParseException;
// use Parse\ParseUser;
// date_default_timezone_set('America/Los_Angeles');
//
// if (session_status() == PHP_SESSION_NONE) {
// 	session_start();
// 	ParseClient::initialize('9DwkUswTSJOLVi7dkRJxDQNbwHSDlQx3NTdXz5B0', '6HFMDcw8aRr9O7TJ3Pw8YOWbecrdiMuAPEL3OXia', 'IdmvCVEBYygkFTRmxOwUvSxtnXwlaGDF9ndq5URq');
// }
//
// $test = ParseObject::create("Test");
// $test->set("foo", "bar");
// $test->save();
//
// //get current user from Parse for further query
// $currentUser = ParseUser::getCurrentUser();
// if (!$currentUser) {
//     echo '<script language="javascript">';
//     echo 'alert("Error getting current user or not logged in!");';
//     echo 'window.location.assign("mainpage.php");';
//     echo '</script>';
//     exit();
// }

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
        echo 'window.location.assign("../../index.html");';
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
                    if(count($data) == 4){
                        //account
                        $name = $data[0];
                        $dateEst = $data[1];
                        $balance = $data[2];
                        $type = $data[3];



                    } else {
                        //transaction
                        $name = $data[0];
                        $date = $data[1];
                        $principle = $data[2];
                        $amount = $data[3];
                        $category = $data[5];

                    }
                    
                }
            }

        } else {

            echo '<script language="javascript">';
            echo 'alert("Error");';
            echo 'window.location.assign("../../index.html");';
            echo '</script>';
        }
    }

    //delete file from temporary directory to avoid conflicts with future uploads
    unlink($target_file);
    // redirect to index after the upload
    header("Location:../../index.html");

}
?>