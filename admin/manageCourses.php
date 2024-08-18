<?php
session_start();

// check whether user is admin
if($_SESSION['end_user']!="admin"){
    header("Location: /e-college/index");
    exit;
}

include '../partials/_nav.php';
?>

<?php

$success = false;
if($_SERVER['REQUEST_METHOD'] == "POST"){

    $course_id = $_POST['course_id'];
    $course_name = $_POST['course_name'];
    $duration = $_POST['duration'];

    $queryCheckCourse = "SELECT * from `course` where `course_id` = $course_id";
    $resultCheckCourse = mysqli_query($conn, $queryCheckCourse);

    if(mysqli_num_rows($resultCheckCourse)==1){
        $showAlert = "Course ID already exists!";
    }
    else{
        $query = "INSERT INTO `course` (`course_id`, `course_name`, `duration_sem`) VALUES ('$course_id', '$course_name', '$duration')";
        $result = mysqli_query($conn, $query);
        
        if($result){
            $success=true;
        }
        else{
            $showAlert = "Something went wrong";
        }
    }
}

$query = "SELECT * from `course`";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="body-own">

    <?php
        if($success){
    ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success! </strong>Course has been added succesfully..
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
    <?php
        }
    ?>

    <?php
        if(isset($showAlert)){
    ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Sorry! </strong><?php echo $showAlert; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
    <?php
        }
    ?>

    <?php
        if(isset($_GET['success'])){
            if($_GET['success']){
    ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success! </strong>Course has been updated successfully..
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
    <?php
            }
            else {
    ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Something went wrong! </strong>Course not updated successfully..
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
    <?php
        }
    }
    ?>
    
    <p class="page-heading">Manage Courses</p>
    <p class="panel-heading">Add Course</p>
    <div class="panel-own">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-grp">
                <label class="form-label-own">Course Name</label>
                <input type="text" class="form-input" name="course_name" required>
            </div>
            <div class="form-grp">
                <label class="form-label-own">Course ID</label>
                <input type="text" class="form-input" name="course_id" required>
            </div>
            <div class="form-grp">
                <label class="form-label-own">Duration</label>
                <input type="number" class="form-input" name="duration" required>
            </div>
            <input type="submit" value="Add" class="btn-full-width">
        </form>
    </div>

    <p class="panel-heading">Course List</p>
    <div class="panel-transparent">

    <?php
        if(mysqli_num_rows($result)==0){
    ?>
        <p class="content-light">No Courses</p>
    <?php
        }
        else{
    ?>
        <table class="table table-hover table-responsive">
            <thead>
                <tr>
                    <th class="content-bold">Course ID</th>
                    <th class="content-bold">Course Name</th>
                    <th class="content-bold">Duration</th>
                    <th class="content-bold">Edit</th>
                </tr>
            </thead>
            <tbody>

            <?php
                while($row=mysqli_fetch_assoc($result)){
            ?>
                
                <tr>
                    <td class="content-light"><?php echo $row['course_id']; ?></td>
                    <td class="content-light"><?php echo $row['course_name']; ?></td>
                    <td class="content-light"><?php echo $row['duration_sem']; ?> semesters</td>
                    <td class="content-light"><i class="fa fa-pencil-square-o" aria-hidden="true" onclick="window.location.href='editCourse?c_id=<?php echo $row['course_id']; ?>';"></i></td>
                </tr>

            <?php
                }
            ?>
                
            </tbody>
        </table>

    <?php
        }
    ?>

    </div>

</body>
</html>