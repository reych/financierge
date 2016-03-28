<?php
$funcName = $_POST['funcToCall'];

if ($funcName == "testFunc"){
 test();
}

function test() {
    echo "Ajax test success!";
}

?>
