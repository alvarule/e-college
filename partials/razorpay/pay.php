<?php

require('config.php');
require('razorpay-php/Razorpay.php');

include '../_dbconnect.php';
use Razorpay\Api\Api;
session_start();

if(isset($_GET['f_id'])){

    $f_id = $_GET['f_id'];
    $stud_id = $_SESSION['stud_id'];

    $queryFees = "SELECT * FROM `fees_detail` NATURAL JOIN `fees_stud` NATURAL JOIN `student` WHERE `stud_id` = '$stud_id' and `f_id` = '$f_id'";
    $resultFees = mysqli_query($conn, $queryFees);
    $fees = mysqli_fetch_assoc($resultFees);
    
    // Create the Razorpay Order


    $api = new Api($keyId, $keySecret);

    //
    // We create an razorpay order using orders api
    // Docs: https://docs.razorpay.com/docs/orders
    //

    $orderData = [
        'receipt'         => 'rcptid_11',
        'amount'          => $fees['amount']*100,
        'currency'        => 'INR'
    ];

    $razorpayOrder = $api->order->create($orderData);
    
    $razorpayOrderId = $razorpayOrder['id'];
    
    $_SESSION['razorpay_order_id'] = $razorpayOrderId;
    
    $displayAmount = $amount = $orderData['amount'];
    
    if ($displayCurrency !== 'INR')
    {
        $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
        $exchange = json_decode(file_get_contents($url), true);
        
        $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
    }
    
    $checkout = 'automatic';
    
    if (isset($_GET['checkout']) and in_array($_GET['checkout'], ['automatic', 'manual'], true))
    {
        $checkout = $_GET['checkout'];
    }
    
    $data = [
        "key"               => $keyId,
        "amount"            => $amount,
        "shopping_order_id" => $fees['id'],
        "name"              => "E College",
        "description"       => "E College",
        "image"             => "../logo.png",
        "prefill"           => [
            "name"              => $fees['f_name']." ". $fees['l_name'],
            "email"             => $fees['email'],
            "contact"           => $fees['stud_mob_no'],
    ],
    "notes"             => [
    "address"           => "Address",
    "merchant_order_id" => "12312321",
    ],
    "theme"             => [
        "color"             => "#2986CC"
    ],
    "order_id"          => $razorpayOrderId,
    ];

    if ($displayCurrency !== 'INR')
    {
        $data['display_currency']  = $displayCurrency;
        $data['display_amount']    = $displayAmount;
    }

    $json = json_encode($data);

?>
    <title>Pay Fees</title>
    <link rel="icon" type="image/png" href="/e-college/partials/logo.png">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <button class="" id="modalbtn" data-toggle="modal" data-target="#modal"></button>
    <div class="container">
    <!-- Modal -->
    <div id="modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Pay Fees</h4>
                    <button type="button" class="close" data-dismiss="modal" onclick="window.location.href='/e-college/student/payFees';">&times;</button>
                </div>
                <div class="modal-body">

                    <style>
                        input[type="submit"]{
                            margin: 0;
                            padding: 5px 24px;
                            background-color: #005E85;
                            border: none;
                            border-radius: 10px;
                            font-family: "mitr";
                            font-size: 16pt;
                            color: #fff;
                        }
                    </style>
                    
                    <center>
                    <?php
                        require("checkout/{$checkout}.php");
                    ?>
                    </center>                                   


    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.href='/e-college/student/payFees';">Close</button>
    </div>
    </div>

    </div>
        </div>
    </div>
    </div>

    <script>
        document.getElementById("modalbtn").click();
    </script>

<?php
}

?>