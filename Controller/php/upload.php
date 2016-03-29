<?php

// Use get method to determine which function to call
$funcName = $_GET['funcToCall'];
if ($funcName == "uploadCSV") {
    uploadCSV();
}

function uploadCSV(){
    //get file from temporary direcory where it is stored
    $target_dir = sys_get_temp_dir();
    //complete file path
    $target_file = $target_dir . "/" . basename($_FILES["file"]["name"]);
    echo $target_file;
    echo "test!!!!";
    //move file to the temporary directory to process
    move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
    //open file
    if (($file = fopen($target_file, "r")) !== FALSE) {
        //while
        $result = "";
        $count = 0;
        while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
            if($count > 0) {
                foreach ($filedata as $data) {
                    // echo $data . "\n";
                    $result .= $data . '_';
                }
                $result .= '\n';
            }
            $count++;
        }
        echo $result;

    } else {

    }

    //delete file from temporary directory to avoid conflicts with future uploads
    unlink($target_file);

}
?>
