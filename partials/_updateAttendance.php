<?php

session_start();
include '_dbconnect.php';

// check whether user is student
if($_SESSION['end_user']!="staff"){
    header("Location: /e-college/index");
    exit;
}

if(isset($_GET['l_id']) and isset($_GET['status'])){
    $l_id = $_GET['l_id'];
    $status = $_GET['status'];

    $queryGetAttendance = "SELECT * from `lecture` where `l_id` = $l_id";
    $resultGetAttendance = mysqli_query($conn, $queryGetAttendance);
    if(!$resultGetAttendance){
        exit;
    }
    else{
        $queryUpdate = "UPDATE `lecture` SET `attendance_act` = $status WHERE `l_id` = $l_id";
        $resultUpdate = mysqli_query($conn, $queryUpdate);
    }    
}

?>