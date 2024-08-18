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
    $studentID = $_SESSION['stud_id'];
    $queryGetAttendance = "SELECT * from `lecture` where `l_id` = $l_id";
    $resultGetAttendance = mysqli_query($conn, $queryGetAttendance);
    if(!$resultGetAttendance){
        exit;
    }
    else{
        $row = mysqli_fetch_assoc($resultGetAttendance);
        if($row['attendance_act']){
            // check whether attendance is already marked
            $queryCheckAttendance = "SELECT * FROM `lecture_attendance` where `lect_id` = '$l_id' and `stud_id` = '$studentID'";
            $resultCheckAttendance = mysqli_query($conn, $queryCheckAttendance);
            if(mysqli_num_rows($resultCheckAttendance)==0){
                echo "
                <form action='' onsubmit='return markAttendance();'>
                <input type='submit' class='btn-full-width' value='Mark Attendance'>
                </form>"
                ;
            }
        }
    }
}
?>