<?php
session_start();

// check whether user is student
if($_SESSION['end_user']!="student"){
    header("Location: /e-college/index");
    exit;
}

include '../partials/_nav.php';
?>

<?php

// getting new tests' details for student
$queryTestAttempt = "SELECT * FROM `test_attempt` NATURAL JOIN `test_detail` NATURAL JOIN `subject` WHERE `stud_id` = '$studentID' AND `attempt_status` = 'not completed' ORDER BY `test_detail`.`date`  ASC";
$resultTestAttempt = mysqli_query($conn, $queryTestAttempt);

// getting test history of the student
$queryTestHistory = "SELECT * FROM `test_attempt` NATURAL JOIN `test_detail` NATURAL JOIN `subject` WHERE stud_id = '$studentID' AND (`attempt_status` = 'completed' OR `attempt_status` = 'absent') ORDER BY `test_detail`.`date`  DESC";
$resultTestHistory = mysqli_query($conn, $queryTestHistory);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
</head>
<body class="body-own">

    <?php
        if(isset($_GET['success'])){
            if($_GET['success']==1){
    ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo "<strong>Test Submitted Successfully!</strong>" ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
    <?php
            }
            elseif($_GET['success']==0){
    ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo "<strong>Test either doesn't exist or is not started yet!</strong>" ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
    <?php
            }
        }
    ?>
    
    <h1 class="page-heading">My Tests</h1>
    <h1 class="panel-heading">New Tests</h1>

    <?php
        if(mysqli_num_rows($resultTestAttempt)==0){
    ?>
        <div class="panel-transparent">
            <p class="content-light">No New Tests</p>
        </div>
    <?php
        }
        else{
            while($newTest = mysqli_fetch_assoc($resultTestAttempt)){
    ?>

        <div class="panel-own">
            <div class="row">
                <p class="col-sm-12 content-bold"><?php echo $newTest['title']; ?></p><hr>  <!--New Tests-->
                <p class="col-sm-3 content-bold"><?php echo $newTest['sub_code']. " - ". $newTest['sub_abbr']; ?></p>      
                <p class="col-sm-3 content-bold"><?php echo $newTest['date']; ?></p>
                <p class="col-sm-3 content-bold"><?php echo $newTest['start_time']. " - ". $newTest['end_time']; ?></p>

                <?php
                    if($newTest['test_status']=="proposed"){
                ?>
                    <p class="col-sm-3 content-bold"><button class="btn-normal">Not Yet Started</button></p>
                <?php
                    }
                    else{
                ?>
                    <p class="col-sm-3 content-bold"><button class="btn-normal" onclick="window.location.href='attendTest?t_id=<?php echo $newTest['t_id']; ?>';">Start</button></p>
                <?php
                    }
                ?>
            </div>
        </div>

    <?php
            }
    ?>


    <?php
        }
    ?>

    <br>
    
    <h1 class="panel-heading">Test History</h1>
    <div class="panel-transparent">

    <?php
        if(mysqli_num_rows($resultTestHistory)==0){
    ?>
        <p class="content-light">No Test History</p>
    <?php
        }
        else{
    ?>
            
        <table class="table table-responsive table-hover">  <!--Test history table-->
            <thead>
                <tr>
                    <th class="content-bold">Title</th>
                    <th class="content-bold">Subject</th>
                    <th class="content-bold">Date</th>
                    <th class="content-bold">Status</th>
                    <th class="content-bold">Score</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while($test = mysqli_fetch_assoc($resultTestHistory)){
                ?>
                    <tr>
                        <td class="content-light"><?php echo $test['title']; ?></td>
                        <td class="content-light"><?php echo $test['sub_code']. " - ". $test['sub_abbr']; ?></td>
                        <td class="content-light"><?php echo $test['date']; ?></td>
                        <td class="content-light"><?php echo $test['attempt_status']; ?></td>
                        <td class="content-light"><?php echo $test['score']. "/". $test['total_marks']; ?></td>
                    </tr>
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