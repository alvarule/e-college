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

$result = false;

if(isset($_GET['date'])){
    // Getting lecture history for the given date
    $course_id = $stud_details['course_id'];
    $semester = $stud_details['semester'];
    $date = $_GET['date'];
    $queryLectures = "SELECT * FROM `lecture` NATURAL JOIN `subject` NATURAL JOIN `staff` WHERE `course_id` = '$course_id' and `semester` = $semester and `date` = '$date' ORDER BY `lecture`.`start_time` ASC";
    $resultLectures = mysqli_query($conn, $queryLectures);
    $result = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecture History</title>

    <style>
        .form-grp{
            text-align: left;
        }
        .form-input{
            width: 50%;
        }
    </style>
    
</head>
<body class="body-own">
    
        <p class="page-heading">Lecture History</p>
        <div class="panel-transparent">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                <div class="row">
                    <div class="form-grp col-sm-6">
                        <label for="" class="form-label-own">Date</label>
                        <input type="date" class="form-input" name="date">
                    </div>
                    <div class="col-sm-4"></div>
                    <input type="submit" value="Search" class="btn-normal col-sm-2">
                </div>
            </form>
        </div>

        <?php

        if($result){
            if(mysqli_num_rows($resultLectures)==0){
        ?>
                <div class="panel-transparent">
                    <p class="content-light">No Lectures Found for <?php echo $date; ?></p>
                </div>
        <?php
            }
            else{
        ?>
                <p class="panel-heading">Date: <?php echo $date; ?></p>
        <?php
                while($lecture = mysqli_fetch_assoc($resultLectures)){
        ?>
                    <div class="panel-own"> 
                        <div class="row">
                            <p class="col-sm-3 content-bold"><?php echo $lecture['sub_code']. " - ". $lecture['sub_abbr']; ?></p>
                            <p class="col-sm-3 content-bold"><?php echo "Prof. ". $lecture['f_name']. " ". $lecture['l_name']; ?></p>
                            <p class="col-sm-3 content-bold"><?php echo $lecture['start_time']. " - ". $lecture['end_time']; ?></p>
                            <p class="col-sm-3 content-bold"><button class="btn-normal">Watch</button></p>
                        </div>
                    </div> 
        <?php
                }
            }
        }
        else{
        ?>
            <div class="panel-transparent">
                <p class="content-light">Please search for lectures using above form!</p>
            </div>            
        <?php
        }
        ?>
        
</body>
</html>