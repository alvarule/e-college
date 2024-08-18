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

// Getting today's lectures for the student
$course_id = $stud_details['course_id'];
$semester = $stud_details['semester'];
$date = date("Y-m-d");
$queryLectures = "SELECT * FROM `lecture` NATURAL JOIN `subject` NATURAL JOIN `staff` WHERE `course_id` = '$course_id' and `semester` = $semester and `date` = '$date' ORDER BY `lecture`.`start_time` ASC";
$resultLectures = mysqli_query($conn, $queryLectures);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Today's Lecture</title>
</head>
<body class="body-own">
    <h1 class="page-heading">My Today's Lecture</h1>

    <?php
        if(mysqli_num_rows($resultLectures)==0){
    ?>
        <div class="panel-transparent">
            <p class="content-light">No Lectures for today!</p>
        </div>
    <?php
        }
        else{
            while($lecture = mysqli_fetch_assoc($resultLectures)){
    ?>

        <div class="panel-own">
            <div class="row">
                <p class="col-sm-3 content-bold"><?php echo $lecture['sub_code']. " - ". $lecture['sub_abbr']; ?></p>
                <p class="col-sm-3 content-bold">Prof. <?php echo $lecture['f_name']. " ". $lecture['l_name']; ?></p>
                <p class="col-sm-3 content-bold"><?php echo $lecture['start_time']. " - ". $lecture['end_time']; ?></p>

                <?php
                    if($lecture['status']=='not completed'){
                ?>
                        <p class="col-sm-3 content-bold"><button class="btn-normal" onclick="window.location.href='joinLecture?l_id=<?php echo $lecture['l_id']; ?>';">Join</button></p>
                <?php
                    }
                    else{
                ?>
                        <p class="col-sm-3 content-bold"><button class="btn-normal">Lecture Ended</button></p>
                <?php
                    }
                ?>
            </div>
        </div>   
    
    <?php
            }
        }
    ?>
    
</body>
</html>