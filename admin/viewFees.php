<?php

session_start();

// check whether user is admin
if($_SESSION['end_user']!="admin"){
    header("Location: /e-college/index");
    exit;
}

include '../partials/_nav.php';
?>

<?php
$success = false;

if(isset($_GET['accept']) && isset($_GET['id'])){
    $id = $_GET['accept'];
    $queryFeesInst = "SELECT * from `fees_stud` WHERE 'id' = '$id'";
    $resultFeesInst = mysqli_query($conn, $queryFeesInst);
    
    // $feesInst = mysqli_fetch_assoc($resultFeesInst);
    // code for validation

    $queryUpdateFeesInst = "UPDATE `fees_stud` SET `status` = 'received' WHERE `id` = $id";
    $resultUpdateFeesInst = mysqli_query($conn, $queryUpdateFeesInst);
    if($resultUpdateFeesInst){
        $success = true;
    }
}

if(isset($_GET['reject']) && isset($_GET['id'])){
    $id = $_GET['reject'];
    $queryFeesInst = "SELECT * from `fees_stud` WHERE 'id' = '$id'";
    $resultFeesInst = mysqli_query($conn, $queryFeesInst);
    
    // $feesInst = mysqli_fetch_assoc($resultFeesInst);
    // code for validation

    $queryUpdateFeesInst = "UPDATE `fees_stud` SET `status` = 'open', `trans_status` = '--', `trans_date` = '0000-00-00', `trans_id` = '--', `order_id` = '--', `receipt` = '' WHERE `id` = '$id'";
    $resultUpdateFeesInst = mysqli_query($conn, $queryUpdateFeesInst);
    if($resultUpdateFeesInst){
        $success = true;
    }
}

?>

<?php
$f_id = $_GET['id'];
$queryFees = "SELECT * from `fees_stud` where `f_id` = '$f_id'";
$resultFees = mysqli_query($conn, $queryFees);
?>

<?php

if(isset($_GET['id'])){
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Fees</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
      crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    
    <style>
        #myTable_filter label, #myTable_length label{
            margin: 0;
            position: static;
            left: 0px;
            /* width: 20%; */
            font-family: 'outfit';
            font-weight: 500;
            font-size: 14pt;
            box-sizing: border-box;
            color: #000;
        }

        #myTable_filter input, #myTable_length select{
            margin: 0;
            position: static;
            right: 0px;
            /* width: 79%; */
            padding: 5px 16px;
            font-family: 'outfit';
            font-size: 14pt;
            font-weight: 300;
            border-radius: 20px;
            outline: none;
            border: 2px solid #D3E0EA;
            background-color: #fff;
            box-sizing: border-box;            
        }

        #myTable_info{
            font-family: 'outfit';
            font-size: 14pt;
            color: #000;
        }
        .hidden{
            color: transparent;
        }
    </style>   

</head>
<body class='body-own'>

    <?php
        if($success){
    ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success! </strong>Fees status updated successfully..
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
    <?php
        }
    ?>

    <p class="page-heading">View Fees</p>

    <?php
        if(mysqli_num_rows($resultFees)==0){
    ?>
        <div class="panel-transparent">
            <p class="content-light">No Fees Installment Found</p>
        </div>
    <?php
        }
        else{
    ?>
    
    <div class="panel-transparent" style="overflow-x: auto">
        <table class="table table-hover table-responsive align-middle" id="myTable" style="width:auto">
            <thead>
                <tr>
                    <th class="content-bold">Stud ID</th>
                    <th class="content-bold"><span class="hidden">__</span>Status<span class="hidden">__</span></th>
                    <th class="content-bold">Transaction<span class="hidden">_</span>Status</th>
                    <th class="content-bold">Transaction<span class="hidden">_</span>Date</th>
                    <th class="content-bold">Transaction<span class="hidden">_</span>ID</th>
                    <th class="content-bold">Order<span class="hidden">_</span>ID</th>
                    <th class="content-bold">Receipt</th>
                    <th class="content-bold"><span class="hidden">___</span>Action<span class="hidden">___</span></th>
                </tr>
            </thead>

            <tbody>

            <?php
                while($fees = mysqli_fetch_assoc($resultFees)){
            ?>
                <tr>
                    <td class="content-light"><?php echo $fees['stud_id']; ?></td>
                    <td class="content-light"><?php echo $fees['status']; ?></td>
                    <td class="content-light"><?php echo $fees['trans_status']; ?></td>
                    <td class="content-light"><?php echo ($fees['trans_date'] == "0000-00-00" ? "--": $fees['trans_date']); ?></td>
                    <td class="content-light"><?php echo $fees['trans_id']; ?></td>
                    <td class="content-light"><?php echo $fees['order_id']; ?></td>
                    <td class="content-light"><?php echo ($fees['trans_date'] == "0000-00-00" ? "--": "<i class=\"fa fa-eye\" data-toggle='modal' data-target='#f". $fees['f_id']."'></i>"); ?></td>
                    <td class="content-light">
                        <?php 
                            if($fees['status']=="in progress"){ 
                        ?>  
                            <i class="fa fa-check btn btn-success" onclick="window.location.href='<?php echo $_SERVER['PHP_SELF'].'?id='. $fees['f_id'].'&accept='.$fees['id']; ?>';"></i>
                            <i class="fa fa-times btn btn-danger" onclick="window.location.href='<?php echo $_SERVER['PHP_SELF'].'?id='. $fees['f_id'].'&reject='.$fees['id']; ?>';"></i>
                        <?php 
                            }
                        ?>
                    </td>
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
    </div>

    <script>
        $(document).ready(function () {
        $('#myTable').DataTable();
        });
    </script>

    <?php
        }
    ?>
</body>
</html>

<?php
}

?>