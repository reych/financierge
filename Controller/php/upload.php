<?php

// Use get method to determine which function to call
$funcName = $_GET['funcToCall'];
$accountName = $_GET['accName'];

if ($funcName == "uploadCSV") {
    uploadCSV();
} elseif ($funcName == "getTrans") {
    if ($accountName == "checking") {
        echo "Checking
        2016-03-29_Subway_-6.65_1_
        2016-03-28_Venmo_-1.00_2_
        2016-03-23_ATM Deposit_40.00_4_
        2016-03-22_Venmo_-10.54_9_
        2016-03-18_Venmo_-4.65_9_
        2016-03-16_So Cal Gas_-23.00_3_
        2016-03-15_Deposited Check #1236_500.00_4_
        2016-03-12_Robinhood_-30.00_7_
        2016-03-11_COCA COLA LOS ANGELES_-5.00_1_
        2016-03-10_LADWP_-47.86_3_
        2016-03-09_RALPHS #0249_-45.80_1_
        2016-03-08_LA Live_-15.99_5_
        2016-03-06_Aeropostale Online_-20.00_9_
        2016-03-05_Uber Ride Los Angeles_-4.99_6_
        2016-03-04_AT&T _-3.00_1_
        ";
    } elseif ($accountName == "credit_card") {
        echo "Credit Card
        2016-03-03_JACK IN THE BOX_-7.29_1_
        2016-03-02_USC Fee_-10.75_8_
        2016-03-01_Deposited Check #1234_500.00_4_
        2016-02-28_COCA COLA LOS ANGELES_-5.00_3_
        2016-02-27_Deposited Check #1236_500.00_4_
        2016-02-26_Robinhood_-30.00_7_
        2016-02-25_USC Fee_-10.75_8_
        2016-02-24_AT&T _-3.00_1_
        2016-02-23_LADWP_-47.86_3_
        2016-02-20_LA Live_-15.99_5_
        2016-02-19_Aeropostale Online_-20.00_9_
        2016-02-17_Uber Ride Los Angeles_-4.99_6_
        2016-02-16_COCA COLA LOS ANGELES_-5.00_1_
        2016-02-15_JACK IN THE BOX_-7.29_1_
        2016-02-14_RALPHS #0249_-45.80_1_
        2016-02-13_So Cal Gas_22.00_3_
        2016-02-12_Deposited Check #1234_500.00_4_
        2016-02-11_Robinhood_-30.00_7_
        2016-02-10_USC Fee_-10.75_8_
        2016-02-09_AT&T _-46.99_1_
        2016-02-08_LADWP_-47.86_3_
        2016-02-07_LA Live_-15.99_5_
        2016-02-06_Aeropostale Online_-20.00_9_
        2016-02-05_Uber Technologies Inc_-3.20_6_
        2016-02-04_USC Bookstores_-59.99_9_
        ";
    } elseif ($accountName == "loan") {
        echo "Loan
        2016-02-03_Unicef _-50.00_2_
        2016-02-02_RALPHS #0249_-55.23_1_
        2016-02-01_COCA COLA LOS ANGELES_-5.00_3_
        2016-01-31_USC Fee_-20.00_8_
        2016-01-28_RALPHS #0229_-65.45_1_
        2016-01-27_Venmo_10.00_3_
        2016-01-25_Deposited Check #1232_500.00_4_
        2016-01-24_Starbucks_-3.76_1_
        2016-01-22_Trojan Barber Shop_-16.00_9_
        2016-01-20_ATM Withdrawal_-40.00_2_
        2016-01-17_Subway_-6.76_1_
        2016-01-15_So Cal Gas_-21.00_3_
        2016-01-14_Venmo_1.00_5_
        2016-01-12_Deposited Check #1235_500.00_4_
        2016-01-10_LADWP_-40.03_3_
        2016-01-07_Venmo_2.00_5_
        2016-01-06_AT&T _-46.99_3_
        2016-01-04_Online Transfer  Conf#ab342_-35.00_5_
        2016-01-01_Amazon - AmazonPrime_-49.99_5_
        ";
    }


} else {
    echo "no such account";
}

function uploadCSV(){
    //get file from temporary direcory where it is stored
    $target_dir = sys_get_temp_dir();
    //complete file path
    $target_file = $target_dir . "/" . basename($_FILES["file"]["name"]);
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
                $result .= PHP_EOL;
                // $result = '';
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
