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


    // if ( 0 < $_FILES['file']['error'] ) {
    //     echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    // }
    // else {
    //     move_uploaded_file($_FILES['file']['tmp_name'], sys_get_temp_dir() . $_FILES['file']['name']);
    // }


    //get file from temporary direcory where it is stored
    $target_dir = sys_get_temp_dir();
    //complete file path
    $target_file = $target_dir . '/' . basename($_FILES["file"]["name"]);

    echo "file name : " . $target_file;






    $uploadOK = 1;
    // $fileType = pathinfo($target_file,PATHINFO_EXTENSION);
    // if($fileType != "csv"){
    //     $uploadOK = 0;
    // }
    if($uploadOK == 0){
        echo '<script language="javascript">';
        echo 'alert("You did not provide a CSV file");';
        echo 'window.location.assign("../../index.html");';
        echo '</script>';
        exit();
    }
    else{




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
        }
    }

    //delete file from temporary directory to avoid conflicts with future uploads
    // unlink($target_file);
    // redirect to index after the upload
    // header("Location:../../index.html");

}

?>
