<?php
session_start();

// check whether user is staff
if($_SESSION['end_user']!="staff"){
    header("Location: /e-college/index");
    exit;
}

include '../partials/_nav.php';

// for working with spreadsheets
require '../partials/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
?>

<?php
// Getting Subjects List for the currently logged in staff
$querySubjects = "SELECT * from `subject` where `staff_id` = $staffID";
$resultSubjects = mysqli_query($conn, $querySubjects);

// Getting Proposed Tests for staff
$queryTestProposed = "SELECT * from `test_detail` NATURAL JOIN `subject` WHERE `staff_id` = '$staffID' and `test_status` = 'proposed' ORDER BY `test_detail`.`date`  DESC";
$resultTestProposed = mysqli_query($conn, $queryTestProposed);

// Getting In Progress Tests for staff
$queryTestInProgress = "SELECT * from `test_detail` NATURAL JOIN `subject` WHERE `staff_id` = '$staffID' and `test_status` = 'in progress' ORDER BY `test_detail`.`date`  DESC";
$resultTestInProgress = mysqli_query($conn, $queryTestInProgress);

// Getting Test History for staff
$queryTestHistory = "SELECT * from `test_detail` NATURAL JOIN `subject` WHERE `staff_id` = '$staffID' and `test_status` = 'completed' ORDER BY `test_detail`.`date`  DESC";
$resultTestHistory = mysqli_query($conn, $queryTestHistory);
?>

<?php
// Starting a Test
if((isset($_GET['t_id_to_start'])) or isset($_GET['t_id_to_end'])){
    if(isset($_GET['t_id_to_start'])){
        $testID = $_GET['t_id_to_start'];
    }
    else{
        $testID = $_GET['t_id_to_end'];
    }

    // getting test details
    $queryTestDetails = "SELECT * from `test_detail` NATURAL JOIN `subject` WHERE `t_id` = '$testID'";
    $resultTestDetails = mysqli_query($conn, $queryTestDetails);
    $test = mysqli_fetch_assoc($resultTestDetails);
    $testID = $test['t_id'];
    
    // query to check the validity of the staff accessing this page
    $checkValidityQuery = "SELECT * FROM `test_detail` NATURAL JOIN `subject` WHERE `staff_id` = $staffID AND `t_id` = $testID";
    $checkValidityResult = mysqli_query($conn, $checkValidityQuery);
    
    // check whether test exists
    if(mysqli_num_rows($resultTestDetails)<1){
        echo "
        <script type='text/javascript'>
            window.location.href = 'createTest';
        </script>
        ";
        exit;
    }
    // checking the validity of the staff accessing this page
    elseif(mysqli_num_rows($checkValidityResult)==0){
        $showAlert = "<strong>Oops! </strong>You don't have permission to this Test!";
    }
    // if all checks are clear then update the status as per requested by the staff
    else{
        // if request is made to start a test
        if(isset($_GET['t_id_to_start'])){
            $queryUpdateTestStatus = "UPDATE `test_detail` SET `test_status` = 'in progress' WHERE `t_id` = '$testID'";
            $resultUpdateTestStatus = mysqli_query($conn, $queryUpdateTestStatus);
            if($resultUpdateTestStatus){
                $success = "<strong>Success! </strong>Test Started..";
            }
        }
        // if request is made to end a test
        elseif(isset($_GET['t_id_to_end'])){
            $queryUpdateTestStatus = "UPDATE `test_detail` SET `test_status` = 'completed' WHERE `t_id` = '$testID'";
            $queryUpdateAttemptStatus = "UPDATE `test_attempt` SET `attempt_status` = 'absent' WHERE `t_id` = '$testID' and `attempt_status` = 'not completed'";
            
            if(mysqli_query($conn, $queryUpdateTestStatus) and mysqli_query($conn, $queryUpdateAttemptStatus)){
                $success = "<strong>Success! </strong>Test Ended..";
            }            
        }
        
    }

}

?>

<?php

if($_SERVER['REQUEST_METHOD']=="POST"){
    $title = $_POST['title'];
    $sub = $_POST['subject'];
    $total_questions = $_POST['total_questions'];
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    
    // query to insert data into test_detail table
    $queryInsertTestDetails = "INSERT INTO `test_detail` (`title`, `sub_code`, `total_questions`, `date`, `start_time`, `end_time`) VALUES ('$title', '$sub', '$total_questions', '$date', '$start_time', '$end_time');";
    $resultInsertTestDetails = mysqli_query($conn, $queryInsertTestDetails);

    // query to get t_id of the newly created test
    $queryForTestID = "SELECT `t_id` from `test_detail` where `sub_code` = '$sub' and `date` = '$date'";
    $resultForTestID = mysqli_query($conn, $queryForTestID);
    $test = mysqli_fetch_assoc($resultForTestID);
    $testID = $test['t_id'];

    $resultFile = $testID. "_". $sub. "_". $date. ".xlsx";

    // creating a spreadsheet to store the students responses for the test
    $spreadsheet = new Spreadsheet();
    $writer = new Xlsx($spreadsheet);
    $writer->save("../test_result/$resultFile");
    
    $queryUpdateTestResult = "UPDATE `test_detail` SET `result` = '$resultFile' WHERE `t_id` = $testID";
    $resultUpdateTestResult = mysqli_query($conn, $queryUpdateTestResult);

    // query to get the list of students eligible for the current test
    $query = "SELECT * FROM `student` NATURAL JOIN `subject` WHERE sub_code = '$sub'";
    $result = mysqli_query($conn, $query);

    // looping through every student obtained from above query
    while($stud = mysqli_fetch_assoc($result)){
        $studentID = $stud['stud_id'];

        // query to insert data into test_attempt for every eligible students
        $queryInsertTestAttempt = "INSERT INTO `test_attempt` (`stud_id`, `t_id`, `total_marks`, `attempt_status`) VALUES ('$studentID', '$testID', $total_questions, 'not completed')";
        $resultInsertTestAttempt = mysqli_query($conn, $queryInsertTestAttempt);
    }
    echo "
    <script type='text/javascript'>
        window.location.href = 'testQuestions.php?t_id=$testID';
    </script>
    ";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/e-college/style.css">
    <title>Tests</title>
</head>
<body class="body-own">

    <?php
        if(isset($success)){
    ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $success; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
    <?php
        }
    ?>

    <?php
        if(isset($showAlert)){
    ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $showAlert; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
    <?php
        }
    ?>


    <p class="page-heading">Tests</p>

    <!---------------------------Create Test Start----------------------------->

    <p class="panel-heading">Create Test</p>                        
    <div class="panel-own">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-grp">
                <label for="" class="form-label-own">Title</label>
                <input type="text" name="title" class="form-input" required>
            </div>
            <div class="form-grp">
                <label for="" class="form-label-own">Subject</label>  
                <select name="subject" class="form-input" required>
                    <option disabled selected value="">--</option>

                    <?php
                        while($subject = mysqli_fetch_assoc($resultSubjects)){
                    ?>
                        <option value="<?php echo $subject['sub_code']; ?>"><?php echo $subject['sub_code']. " - ". $subject['sub_abbr']; ?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
                
    
            <div class="form-grp">
                <label for="" class="form-label-own">Total Questions</label>
                <input type="number" name="total_questions" class="form-input" required>
            </div>
            <div class="form-grp">
                <label for="" class="form-label-own">Date</label>
                <input type="date" name="date" class="form-input" required>
            </div>
            <div class="form-grp">
                <label for="" class="form-label-own">Start Time</label>
                <input type="time" name="start_time" class="form-input" required>
            </div>
            <div class="form-grp">
                <label for="" class="form-label-own">End Time</label>
                <input type="time" name="end_time" class="form-input" required>
            </div>
            <input type="submit" value="Create" class="btn-full-width">
        </form>
    </div>

    <!---------------------------Create Test End----------------------------->

    <!---------------------------New (Proposed) Tests Start----------------------------->

    <?php
        if(mysqli_num_rows($resultTestProposed)!=0){
    ?>

        <p class="panel-heading">New Tests</p>

    <?php
            while($test = mysqli_fetch_assoc($resultTestProposed)){
    ?>

        <div class="panel-own">
            <div class="row">
                <p class="content-bold col-sm-12"><?php echo $test['title']; ?></p>
                <hr>
                <p class="content-bold col-sm-2"><?php echo $test['sub_code']. " - ". $test['sub_abbr']; ?></p>
                <p class="content-bold col-sm-3"><?php echo $test['date']; ?></p>
                <p class="content-bold col-sm-3"><?php echo $test['start_time']. " - ". $test['end_time']; ?></p>
                <p class="content-bold col-sm-2"><button class="btn-normal" onclick="window.location.href='testQuestions?t_id=<?php echo $test['t_id']; ?>';">Edit</button></p>
                <p class="content-bold col-sm-2"><button class="btn-normal" onclick="window.location.href='createTest?t_id_to_start=<?php echo $test['t_id']; ?>';">Start</button></p>
            </div>
        </div>
    
    <?php
            }
        }
    ?>
    
    <!---------------------------New (Proposed) Tests End----------------------------->

    
    <!---------------------------In Progress Test Start----------------------------->

    <?php
        if(mysqli_num_rows($resultTestInProgress)!=0){
    ?>

        <p class="panel-heading">Tests In Progress</p>

    <?php
            while($test = mysqli_fetch_assoc($resultTestInProgress)){
    ?>

        <div class="panel-own">
            <div class="row">
                <p class="content-bold col-sm-12"><?php echo $test['title']; ?></p>
                <hr>
                <p class="content-bold col-sm-2"><?php echo $test['sub_code']. " - ". $test['sub_abbr']; ?></p>
                <p class="content-bold col-sm-3"><?php echo $test['date']; ?></p>
                <p class="content-bold col-sm-3"><?php echo $test['start_time']. " - ". $test['end_time']; ?></p>
                <p class="content-bold col-sm-2"><button class="btn-normal" onclick="window.location.href='testQuestions?t_id=<?php echo $test['t_id']; ?>';">Edit</button></p>
                <p class="content-bold col-sm-2"><button class="btn-normal" onclick="window.location.href='createTest?t_id_to_end=<?php echo $test['t_id']; ?>';">End</button></p>
            </div>
        </div>
    
    <?php
            }
        }
    ?>

    <!---------------------------In Progress Test End----------------------------->

    
    <!---------------------------Test History Start----------------------------->

    <p class="panel-heading">Test History</p>               
    <?php
        if(mysqli_num_rows($resultTestHistory)==0){
    ?>
        <div class="panel-transparent">
            <p class="content-light">No Tests found</p>
        </div>
    <?php
        }
        else{
            while($test = mysqli_fetch_assoc($resultTestHistory)){
    ?>
                <div class="panel-own">
                    <div class="row">
                        <p class="col-sm-3 content-light"><?php echo $test['title']; ?></p>
                        <p class="col-sm-3 content-light"><?php echo $test['sub_code']. " - ". $test['sub_abbr']; ?></p>
                        <p class="col-sm-3 content-light"><?php echo $test['date']; ?></p>
                        <p class="col-sm-3 content-light"><i class="fa fa-download" onclick="window.location.href='/e-college/partials/_downloadTestResult?t_id=<?php echo $test['t_id']; ?>';"></i></p>
                    </div>
                </div>
    <?php
            }
        }
    ?>
    <!---------------------------Test History End----------------------------->

</body>
</html>