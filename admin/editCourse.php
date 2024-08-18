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

if($_SERVER['REQUEST_METHOD']=="POST"){
    $course_id = $_POST['course_id'];
    $course_name = $_POST['course_name'];
    $duration = $_POST['duration'];

    $queryCheckCourse = "SELECT * from `course` where `course_id` = $course_id";
    $resultCheckCourse = mysqli_query($conn, $queryCheckCourse);
    if(mysqli_num_rows($resultCheckCourse)==0){
        echo "<script>window.location.href='manageCourses';</script>";
        exit;
    }

    $queryUpdateCourse = "UPDATE `course` SET `course_id` = '$course_id', `course_name` = '$course_name', `duration_sem` = '$duration' WHERE `course_id` = '$course_id'";

    $resultUpdateCourse = mysqli_query($conn, $queryUpdateCourse);

    if($resultUpdateCourse){
        echo "<script>window.location.href='manageCourses?success=true';</script>";
    }
    else{
        echo "<script>window.location.href='manageCourses?success=false';</script>";
    }

}

?>

<?php

if(isset($_GET['c_id'])){
    $course_id = $_GET['c_id'];
    $queryCourse = "SELECT * from `course` where `course_id` = $course_id";
    $resultCourse = mysqli_query($conn, $queryCourse);
    if(mysqli_num_rows($resultCourse)==0){
        echo "<script>window.location.href='manageCourses';</script>";
        exit;
    }

    $course = mysqli_fetch_assoc($resultCourse);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
</head>
<body class="body-own">
    
    <p class="page-heading">Edit Course</p>
    <div class="panel-own">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-grp">
                <label class="form-label-own">Course Name</label>
                <input type="text" class="form-input" name="course_name" value="<?php echo $course['course_name']; ?>" required>
            </div>
            <div class="form-grp" hidden>
                <label class="form-label-own">Course ID</label>
                <input type="text" class="form-input" name="course_id" value="<?php echo $course['course_id']; ?>" required>
            </div>
            <div class="form-grp">
                <label class="form-label-own">Duration</label>
                <input type="number" class="form-input" name="duration" value="<?php echo $course['duration_sem']; ?>" required>
            </div>
            <input type="submit" value="Save" class="btn-full-width">
        </form>
    </div>

</body>
</html>

<?php
}
?>
