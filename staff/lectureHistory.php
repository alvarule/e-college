<?php
session_start();

// check whether user is student
if($_SESSION['end_user']!="staff"){
    header("Location: /e-college/index");
    exit;
}

include '../partials/_nav.php';
?>

<?php

$result = false;

if(isset($_GET['from']) and isset($_GET['to'])){
    // Getting lecture history for the given date
    $staff_id = $_SESSION["staff_id"];
    $from = $_GET['from'];
    $to = $_GET['to'];
    $queryLectures = "SELECT * FROM `lecture` NATURAL JOIN `subject` NATURAL JOIN `course` NATURAL JOIN `staff` WHERE `staff_id` = '$staff_id' and `date` >= '$from' and `date` <= '$to' ORDER BY `lecture`.`date`, `lecture`.`start_time` ASC";
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
        .btn-full-width{
            margin: 0px;
            padding: 5px 24px;
        }
    </style>
    
</head>
<body class="body-own">
    
        <p class="page-heading">Lecture History</p>
        <div class="panel-transparent">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                <div class="row">
                    <div class="form-grp col-sm-4">
                        <label for="" class="form-label-own">From</label>
                        <input type="date" class="form-input" name="from">
                    </div>
                    <div class="form-grp col-sm-4">
                        <label for="" class="form-label-own">To</label>
                        <input type="date" class="form-input" name="to">
                    </div>
                    <div class="col-sm-2"></div>
                    <input type="submit" value="Search" class="btn-normal col-sm-2">
                </div>
            </form>
        </div>

        <?php

        if($result){
            if(mysqli_num_rows($resultLectures)==0){
        ?>
                <div class="panel-transparent">
                    <p class="content-light"><?php echo "No Lectures Found from ". $from. " to ". $to; ?></p>
                </div>
        <?php
            }
            else{
        ?>
                <div class="panel-transparent">
                    <p class="content-light"><b><?php echo $from; ?></b> to <b><?php echo $to; ?></b></p>
                </div>
        <?php
                while($lecture = mysqli_fetch_assoc($resultLectures)){
        ?>
                    <div class="panel-own"> 
                        <div class="row">
                            <p class="col-sm-3 content-bold"><?php echo $lecture['date']; ?></p>
                            <p class="col-sm-3 content-bold"><?php echo $lecture['sub_code']. " - ". $lecture['sub_abbr']; ?></p>
                            <p class="col-sm-3 content-bold"><?php echo $lecture['course_id']. " - ". $lecture['course_name']; ?></p>
                            <p class="col-sm-3 content-bold"><?php echo "Semester - ". $lecture['semester']; ?></p>
                            <hr><br>
                            <p class="col-sm-6 content-bold"><?php echo $lecture['start_time']. " - ". $lecture['end_time']; ?></p>
                            <p class="col-sm-3 content-bold"><button class="btn-full-width">Link</button></p>
                            <?php
                                if($lecture['attendance']!=""){
                            ?>
                                <p class="col-sm-3 content-bold"><button class="btn-full-width" onclick="window.location.href='/e-college/partials/_downloadAttendance?l_id=<?php echo $lecture['l_id']; ?>';">Attendance</button></p>
                            <?php
                                }
                            ?>
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