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

// Getting today's lectures for the staff
$date = date("Y-m-d");
$queryLectures = "SELECT * FROM `lecture` NATURAL JOIN `subject` where `date`= '$date' and `staff_id` = $staffID and `status` = 'not completed' ORDER BY `lecture`.`start_time` ASC";
$resultLectures = mysqli_query($conn, $queryLectures);

$queryLecturesCompleted = "SELECT * FROM `lecture` NATURAL JOIN `subject` where `date`= '$date' and `staff_id` = $staffID and `status` = 'completed' ORDER BY `lecture`.`start_time` ASC";
$resultLecturesCompleted = mysqli_query($conn, $queryLecturesCompleted);

if(isset($_GET['del'])){
    $l_id = $_GET['del'];
    $queryDelete = "DELETE FROM `lecture` WHERE `l_id` = $l_id";
    $resultDelete = mysqli_query($conn, $queryDelete);
    if($resultDelete){
        $deleted = true;
    }
    else{
        $deleted = false;
    }
}

if(isset($_GET['end'])){
    $l_id = $_GET['end'];
    $queryLect = "SELECT * from `lecture` natural join `subject` where `l_id` = '$l_id'";
    $resultLect = mysqli_query($conn, $queryLect);
    $lect = mysqli_fetch_assoc($resultLect);

    $resultFile = $l_id. "_". $lect['sub_code']. "_". $lect['date']. ".xlsx";

    // creating a spreadsheet to store the students responses for the test
    $spreadsheet = new Spreadsheet();
    
    $queryAttendance = "SELECT * from `lecture_attendance` NATURAL JOIN `student` where `lect_id` = '$l_id'";
    $resultAttendance = mysqli_query($conn, $queryAttendance);
    
    $sheet = $spreadsheet->getActiveSheet();
    $header = "Attendance: ". $lect['sub_abbr']. ": ". $lect['date'];
    $sheet->setCellValueByColumnAndRow(1,1, $header);
    
    $sheet->setCellValueByColumnAndRow(1,3, "Student ID");
    $sheet->setCellValueByColumnAndRow(2,3, "Roll No");
    $sheet->setCellValueByColumnAndRow(3,3, "Full Name");

    $rowNo = 4;
    while($attendance = mysqli_fetch_assoc($resultAttendance)){
        $sheet->setCellValueByColumnAndRow(1, $rowNo, $attendance['stud_id']);
        $sheet->setCellValueByColumnAndRow(2, $rowNo, $attendance['roll_no']);
        $sheet->setCellValueByColumnAndRow(3, $rowNo, $attendance['l_name']. " ". $attendance['f_name']. " ". $attendance['m_name']);
        $rowNo = $rowNo + 1;
    }
    
    $writer = new Xlsx($spreadsheet);
    $writer->save("../lecture_attendance/$resultFile");
    
    $queryEnd = "UPDATE `lecture` SET `status` = 'completed', `attendance` = '$resultFile' WHERE `l_id` = $l_id";
    $resultEnd = mysqli_query($conn, $queryEnd);
    if($resultEnd){
        $ended = true;
    }
    else{
        $ended = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/e-college/style.css">
    <title>Lecture</title>
</head>
<body class="body-own">

<?php
if(isset($_GET['success'])){
    if($_GET['success']=="1"){
?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Lecture Created Successfully!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>

<?php
    }
    elseif($_GET['success']=="0"){
?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Something went wrong! </strong>Lecture Not Created
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>

<?php
    }
}

if(isset($deleted)){
    if($deleted){
?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Lecture Deleted Successfully!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>

<?php
    }
    else{
?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Something went wrong! </strong>Lecture Not Deleted
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>

<?php
    }
}

if(isset($ended)){
    if($ended){
?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Lecture Ended Successfully!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>

<?php
    }
    else{
?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Something went wrong! </strong>Lecture Not Ended
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>

<?php
    }
}
?>

    <p class="page-heading">Lectures</p>
    
    <p class="panel-heading">Create Lecture</p>                        <!--here create lecture starts.-->
    <div class="panel-own">
        
        <form action="/e-college/partials/zoom/create/index" method="POST">
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
                <label for="" class="form-label-own">Date</label>
                <input type="date" class="form-input" name="date" required>
            </div>
            <div class="form-grp">
                <label for="" class="form-label-own">Start Time</label>
                <input type="time" class="form-input" name="start_time" required>
            </div>
            <div class="form-grp">
                <label for="" class="form-label-own">End Time</label>
                <input type="time" class="form-input" name="end_time" required>
            </div>
            <input type="submit" value="Create" class="btn-full-width">
        </form>

    </div>

    <p class="panel-heading">Today's Lectures</p>
    
    <?php
    // check if lecture exist for today?
    if(mysqli_num_rows($resultLectures)==0){
    ?>
    
    <div class="panel-transparent">
        <p class="content-light">No lectures remaining for today</p>
    </div>

    <?php
    }
    else{
        // fetch details of lecture one by one
        while($lecture = mysqli_fetch_assoc($resultLectures)){
    ?>

        <div class="panel-own">
            <div class="row">
                <p class="col-sm-5 content-bold"><?php echo $lecture['sub_code']. " - ". $lecture['sub_abbr']; ?></p>
                <p class="col-sm-5 content-bold"><?php echo $lecture['start_time']. " - ". $lecture['end_time']; ?></p>
                <p class="col-sm-2 content-bold"><i class="fa fa-trash" onclick="window.location.href='createLecture?del=<?php echo $lecture['l_id']; ?>';"></i></p>
                <hr>

                <p class="col-sm-6 content-bold"><button type="submit" class="btn-full-width" onclick="window.location.href='conductLecture?l_id=<?php echo $lecture['l_id']; ?>';">Start</button></p>
                
                <p class="col-sm-6 content-bold"><button type="submit" class="btn-full-width" onclick="window.location.href='createLecture?end=<?php echo $lecture['l_id']; ?>';">End</button></p>

            </div>
        </div>

    <?php
        }
    }
    ?>

    <p class="panel-heading">Completed Lectures</p>

    <?php

    while($lecture = mysqli_fetch_assoc($resultLecturesCompleted)){
    ?>
        <div class="panel-own">
            <div class="row">
                <p class="col-sm-6 content-bold"><?php echo $lecture['sub_code']. " - ". $lecture['sub_abbr']; ?></p>
                <p class="col-sm-6 content-bold"><?php echo $lecture['start_time']. " - ". $lecture['end_time']; ?></p>

            </div>
        </div>

    <?php
    }
    ?>

</body>
</html> 