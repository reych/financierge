<?php
$funcName = $_GET['funcToCall'];

if ($funcName == "testFunc"){
    test();
} elseif ($funcName == "testFunc2") {
    echo "Ajax test 2 success!";
} elseif ($funcName == "uploadCSV") {
    uploadCSV();
}

function test() {
    echo "Ajax test success!";
}

function uploadCSV(){

    //get file from temporary direcory where it is stored
    $target_dir = sys_get_temp_dir();
    //complete file path
    $target_file = $target_dir . '/' . basename($_FILES["file"]["name"]);

    echo "file name : " . $target_file . "\n";

    //move file to the temporary directory to process
    move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
    //open file
    if (($file = fopen($target_file, "r")) !== FALSE) {
        //while
        while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
            foreach ($filedata as $singleData) {
                echo $singleData . "\n";
            }
        }

    } else {

        echo '<script language="javascript">';
        echo 'alert("Error");';
        echo 'window.location.assign("../../index.html");';
        echo '</script>';
        exit();

    }

    //delete file from temporary directory to avoid conflicts with future uploads
    unlink($target_file);


}

?>
