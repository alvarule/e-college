<?php
session_start();
include '../partials/_nav.php';

// check whether user is student
if($_SESSION['end_user']!="student"){
    header("Location: /e-college/index");
    exit;
}
?>

<?php

// getting fees installments for current student
$queryFees = "SELECT * from `fees_stud` NATURAL JOIN `fees_detail` where `stud_id` = '$studentID'";
$resultFees = mysqli_query($conn, $queryFees);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fees</title>

</head>
<body class="body-own">


<?php
        if(isset($_GET['success'])){
            if($_GET['success']){
    ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success! </strong>Payment Successful..
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
    <?php
            }
            else{
    ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Sorry! </strong>Something went wrong. Please try again.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
    <?php
            }
        }
    ?>

    <h1 class="page-heading">My Fees</h1>
    <div class="panel-transparent">

    <?php
        if(mysqli_num_rows($resultFees)==0){
    ?>
            <p class="content-light">No fees found</p>
    <?php
        }
        else{
    ?>
            <table class="table table-responsive table-hover table-sm" id="feesTbl" >
                <thead>
                    <tr>
                        <th class="content-bold">Status</th>
                        <th class="content-bold">Reciept</th>
                        <th class="content-bold">Transaction Status</th>
                        <th class="content-bold">Amount</th>
                        <th class="content-bold">Transaction ID</th>
                        <th class="content-bold">Transaction Date</th>
                    </tr>
                </thead>
                <tbody>
    <?php
            while($fees = mysqli_fetch_assoc($resultFees)){
    ?>
                <tr>
                    <td class="content-light">
                        <?php 
                        if($fees['trans_status']!="success"){ 
                        ?>
                           <button class="btn-normal" onclick="window.location.href='../partials/razorpay/pay.php?f_id=<?php echo $fees['f_id']; ?>'">Pay Now</button>
                        <?php 
                        }
                        else{ 
                            echo $fees['status'];
                        } 
                        ?>
                    </td>

                    <td class="content-light"><?php echo ($fees['receipt']=="" ? "Fees not paid!":"<i class='fa fa-download' data-toggle='modal' data-target='#f". $fees['f_id']."'></i>"); ?></td>
                    <td class="content-light"><?php echo $fees['trans_status']; ?></td>
                    <td class="content-light"><?php echo $fees['amount']; ?></td>
                    <td class="content-light"><?php echo $fees['trans_id']; ?></td>
                    <td class="content-light"><?php echo ($fees['trans_date'] == "0000-00-00" ? "--": $fees['trans_date']); ?></td>
                </tr>

                <div class='container'>
                <!-- Modal -->
                <div id="f<?php echo $fees['f_id']; ?>" class='modal fade' role='dialog'>
                    <div class='modal-dialog modal-lg'>

                        <!-- Modal content-->
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                <h4 class='modal-title'>Fees Receipt</h4>
                            </div>
                            <div class='modal-body'>

                                <embed src='/e-college/fee_receipt/<?php echo $fees['receipt']; ?>' frameborder='0' width='100%' height='400px'>

                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                </div>                
    <?php
            }
    ?>
                </tbody>
            </table>  
    <?php
        }
    ?>
    
    </div>
</body>
</html>