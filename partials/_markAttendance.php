<?php

session_start();
include '_dbconnect.php';

// check whether user is student
if($_SESSION['end_user']!="student"){
    header("Location: /e-college/index");
    exit;
}

if(isset($_GET['l_id'])){
    $l_id = $_GET['l_id'];
    $stud_id = $_SESSION['stud_id'];

    // query to check if attendance is already marked
    $queryCheckAttendance = "SELECT * FROM `lecture_attendance` where `lect_id` = '$l_id' and `stud_id` = '$stud_id'";
    $resultCheckAttendance = mysqli_query($conn, $queryCheckAttendance);

    // if it is not marked
    if(mysqli_num_rows($resultCheckAttendance)<1){     
        // query to mark attendance   
        $queryMarkAttendance = "INSERT INTO `lecture_attendance` (`lect_id`, `stud_id`) VALUES ('$l_id', '$stud_id')";
        $resultMarkAttendance = mysqli_query($conn, $queryMarkAttendance);
    }
}

?>