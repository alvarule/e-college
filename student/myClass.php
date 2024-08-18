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
// PHP Code to fetch required data 
$course_id = $stud_details['course_id'];
$semester = $stud_details['semester'];
$query = "SELECT photo, f_name, m_name, l_name, roll_no from student where course_id='$course_id' and semester='$semester'";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Class</title>
    <style>
        .img{
            width: 15%;
            height: 15%;
        }
    </style>
</head>
<body class="body-own">
    <h1 class="page-heading">My Class</h1>
    <div class="panel-transparent">
        <table class="table table-hover table-responsive">
            <thead>
                <tr class="row">
                    
                    <th class="content-bold col-sm-4">Profile</th>
                    <th class="content-bold col-sm-4">Roll No</th>
                    <th class="content-bold col-sm-4">Name</th>
                    
                </tr> 
            </thead>
            <tbody>
                <?php
                    while($row = mysqli_fetch_assoc($result)){
                ?>
                <tr class="row">
                    <td class="col-sm-4"><img src="/e-college/student_profile_pic/<?php echo $row['photo']; ?>" width="20%" style="border-radius:50%;"></td>
                    <td class="content-light col-sm-4"><?php echo $row['roll_no']; ?></td>
                    <td class="content-light col-sm-4"><?php echo $row['f_name']. " ". $row['m_name']. " ". $row['l_name']; ?></td>
                </tr>  
                <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>