<?php
session_start();

// check whether user is staff
if($_SESSION['end_user']!="staff"){
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
    <title>Staff Dashboard</title>

</head>
<body class="body-own">

    <div class="panel-own">
        <table class="table table-responsive align-middle" style="text-align: left;">
            <tr>
                <td rowspan="7" style="text-align: center;">
                    <img src="/e-college/staff_profile_pic/<?php echo $staff_details['photo']; ?>" alt="img" class="img-thumbnail img-responsive">
                </td>
                <td class="content-bold">Name :</td>
                <td class="content-light"><?php echo $staff_details['f_name']. " ". $staff_details['m_name']. " ". $staff_details['l_name']; ?></td>
            </tr>
            <tr>
                <td class="content-bold">Email :</td>
                <td class="content-light"><?php echo $staff_details['email']; ?></td>
            </tr>
            <tr>
                <td class="content-bold">DOB :</td>
                <td class="content-light"><?php echo $staff_details['dob']; ?></td>
            </tr>
            <tr>
                <td class="content-bold">Gender :</td>
                <td class="content-light"><?php echo $staff_details['gender']; ?></td>
            </tr>     
            <tr>
                <td class="content-bold">Joining year :</td>
                <td class="content-light"><?php echo $staff_details['joining_year']; ?></td>
            </tr>
            <tr>
                <td class="content-bold">Staff ID :</td>
                <td class="content-light"><?php echo $staff_details['staff_id']; ?></td>
            </tr>
        </table>
    </div>

    <p class="panel-heading">Today's Lectures</p>

    <?php
        $date = date('Y-m-d');

        $query = "SELECT * from `lecture` NATURAL JOIN `subject` where `staff_id` = '$staffID' and `date` = '$date' and `status` = 'not completed' ORDER BY `lecture`.`start_time` ASC";
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result)==0){
            echo "<div class='panel-transparent'><p class='content-light'>No Lecture for today</p></div>";
        }
        else{
            while($row = mysqli_fetch_assoc($result)){ ?>
            <div class="panel-own">
                <div class="row">
                    <p class="col-sm-4 content-bold"><?php echo $row['sub_code']. " - ". $row['sub_abbr']; ?></p>
                    <p class="col-sm-4 content-bold"><?php echo $row['start_time']. " - ". $row['end_time']; ?></p>
                    <p class="col-sm-4 content-bold"><button class="btn-normal" onclick="window.location.href='conductLecture?l_id=<?php echo $row['l_id']; ?>';">Start Lecture</button></p>
                </div>
            </div>
    <?php
            }
        }
    ?>
    
</body>
</html>