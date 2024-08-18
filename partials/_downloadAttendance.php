<?php
include '_dbconnect.php';

session_start();

// check whether user is staff
if($_SESSION['end_user']!="staff"){
    header("Location: /e-college/index");
    exit;
}

if(isset($_GET['l_id'])){

    $lectID = $_GET['l_id'];
    $staffID = $_SESSION['staff_id'];
    $queryAttendance = "SELECT * from `lecture` NATURAL JOIN `subject` WHERE `l_id` = $lectID and `staff_id` = $staffID";
    $resultAttendance = mysqli_query($conn, $queryAttendance);
    if(mysqli_num_rows($resultAttendance)==0){
        echo "<h1>You Don't have permission here</h1>";
    }
    else{
        $lect = mysqli_fetch_assoc($resultAttendance);
        $resultFile = $lect['attendance']; 
        $file = "../lecture_attendance/$resultFile";       
    
        if(!file_exists($file)){ // file does not exist
            die('file not found');
        } 
        else {
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=". basename($file));
            header("Content-Type: ". mime_content_type($file));
            header("Content-Transfer-Encoding: binary");
        
            // read the file from disk
            readfile($file);
            echo "File Downloaded";
        }
        echo "
        <script type='text/javascript'>
            window.location.href = 'lectureHistory';
        </script>
        ";        
    }
    
}

?>