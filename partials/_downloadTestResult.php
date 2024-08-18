<?php
include '_dbconnect.php';

session_start();

// check whether user is staff
if($_SESSION['end_user']!="staff"){
    header("Location: /e-college/index");
    exit;
}

if(isset($_GET['t_id'])){

    $testID = $_GET['t_id'];
    $staffID = $_SESSION['staff_id'];
    $queryTest = "SELECT * from `test_detail` NATURAL JOIN `subject` WHERE `t_id` = $testID and `staff_id` = $staffID";
    $resultTest = mysqli_query($conn, $queryTest);
    if(mysqli_num_rows($resultTest)==0){
        echo "<h1>You Don't have permission here</h1>";
    }
    else{
        $test = mysqli_fetch_assoc($resultTest);
        $resultFile = $test['result']; 
        $file = "../test_result/$resultFile";       
    
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
            window.location.href = 'createTest';
        </script>
        ";        
    }
    
}

?>