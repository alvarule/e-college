<?php
session_start();

// check whether user is student
if($_SESSION['end_user']!="student"){
    header("Location: /e-college/index");
    exit;
}

include '../partials/_nav.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/e-college/style.css">
    <title>Student Dashboard</title>

</head>
<body class="body-own">

    <div class="panel-own">
        <table class="table table-responsive align-middle" style="text-align: left;">
            <tr>
                <td rowspan="7" style="text-align: center;">
                    <img src="/e-college/student_profile_pic/<?php echo $stud_details['photo']; ?>" alt="img" class="img-thumbnail img-responsive">
                </td>
                <td class="content-bold">Name :</td>
                <td class="content-light"><?php echo $stud_details['f_name']. " ". $stud_details['m_name']. " ". $stud_details['l_name']; ?></td>
            </tr>
            <tr>
                <td class="content-bold">Email :</td>
                <td class="content-light"><?php echo $stud_details['email']; ?></td>
            </tr>
            <tr>
                <td class="content-bold">DOB :</td>
                <td class="content-light"><?php echo $stud_details['dob']; ?></td>
            </tr>
            <tr>
                <td class="content-bold">Gender :</td>
                <td class="content-light"><?php echo $stud_details['gender']; ?></td>
            </tr>     
            <tr>
                <td class="content-bold">Category :</td>
                <td class="content-light"><?php echo $stud_details['category']; ?></td>
            </tr>
            <tr>
                <td class="content-bold">Admission Year :</td>
                <td class="content-light"><?php echo $stud_details['admission_year']; ?></td>
            </tr>
            <tr>
                <td class="content-bold">Student ID :</td>
                <td class="content-light"><?php echo $stud_details['stud_id']; ?></td>
            </tr>
        </table>
    </div>

    <p class="panel-heading">Today's Lectures</p>
    
    <?php
    // Getting Lectures for present day
        $course_id = $stud_details['course_id'];
        $semester = $stud_details['semester'];
        $date = date('Y-m-d');

        $query = "SELECT * FROM `lecture` NATURAL JOIN `subject` NATURAL JOIN `staff` WHERE `course_id` = '$course_id' and `semester` = $semester and `date` = '$date' ORDER BY `lecture`.`start_time` ASC";
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result)==0){
            echo "<div class='panel-transparent'><p class='content-light'>No Lecture for today</p></div>";
        }
        else{
            while($row = mysqli_fetch_assoc($result)){ ?>
            <div class="panel-own">
                <div class="row">
                    <p class="col-sm-3 content-bold"><?php echo $row['sub_code']. " - ". $row['sub_abbr']; ?></p>
                    <p class="col-sm-3 content-bold">Prof. <?php echo $row['f_name']. " ". $row['l_name']; ?></p>
                    <p class="col-sm-3 content-bold"><?php echo $row['start_time']. " - ". $row['end_time']; ?></p>

                    <?php
                        if($row['status']=='not completed'){
                    ?>
                            <p class="col-sm-3 content-bold"><button class="btn-normal" onclick="window.location.href='joinLecture?l_id=<?php echo $row['l_id']; ?>';">Join</button></p>
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