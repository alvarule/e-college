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
    $sub_code = $_POST['sub_code'];
    $sub_name = $_POST['sub_name'];
    $sub_abbr = $_POST['sub_abbr'];
    $course_id = $_POST['course_id'];
    $semester = $_POST['semester'];
    $staff_id = $_POST['staff_id'];

    $queryCheckSubject = "SELECT * from `subject` where `sub_code` = $sub_code";
    $resultCheckSubject = mysqli_query($conn, $queryCheckSubject);
    if(mysqli_num_rows($resultCheckSubject)==0){
        echo "<script>window.location.href='manageSubjects';</script>";
        exit;
    }

    $queryUpdateSubject = "UPDATE `subject` SET `sub_code` = '$sub_code', `sub_name` = '$sub_name', `sub_abbr` = '$sub_abbr', `course_id` = '$course_id', `semester` = '$semester', `staff_id` = '$staff_id' WHERE `sub_code` = '$sub_code'";

    $resultUpdateSubject = mysqli_query($conn, $queryUpdateSubject);

    if($resultUpdateSubject){
        echo "<script>window.location.href='manageSubjects?success=true';</script>";
    }
    else{
        echo "<script>window.location.href='manageSubjects?success=false';</script>";
    }

}

?>

<?php

if(isset($_GET['s_id'])){
    $sub_code = $_GET['s_id'];
    $querySubject = "SELECT * from `subject` where `sub_code` = $sub_code";
    $resultSubject = mysqli_query($conn, $querySubject);
    if(mysqli_num_rows($resultSubject)==0){
        echo "<script>window.location.href='manageSubjects';</script>";
        exit;
    }

    $subject = mysqli_fetch_assoc($resultSubject);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subject</title>
</head>
<body class="body-own">

    <?php
        // Getting Courses List
        $queryCourse = "SELECT * from `course`";
        $resultCourse = mysqli_query($conn, $queryCourse);

        // Getting Staff List
        $queryStaff = "SELECT * from `staff`";
        $resultStaff = mysqli_query($conn, $queryStaff);
    ?>

    <p class="page-heading">Edit Subject</p>
    <div class="panel-own">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-grp">
                <label class="form-label-own">Subject Name</label>
                <input type="text" class="form-input" name="sub_name" required value="<?php echo $subject['sub_name']; ?>">
            </div>
            <div class="form-grp" hidden>
                <label class="form-label-own">Subject Code</label>
                <input type="number" class="form-input" name="sub_code" required value="<?php echo $subject['sub_code']; ?>">
            </div>
            <div class="form-grp">
                <label class="form-label-own">Subject Abbrevation</label>
                <input type="text" class="form-input" name="sub_abbr" required value="<?php echo $subject['sub_abbr']; ?>">
            </div>
            <div class="form-grp">
                <label class="form-label-own">Course ID</label>
                <select name="course_id" class="form-input" required>
                    <option disabled selected value="">--Choose--</option>
                    <?php
                        while($course = mysqli_fetch_assoc($resultCourse)){
                            $courseID = $course['course_id'];
                            $course_name = $course['course_name'];
                    ?>
                    <option value="<?php echo $courseID; ?>" <?php echo ($subject['course_id']=="$courseID" ? "selected":""); ?>><?php echo $courseID. " - ". $course_name; ?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
            <div class="form-grp">
                <label class="form-label-own">Semester</label>
                <input type="number" class="form-input" name="semester" required value="<?php echo $subject['semester']; ?>">
            </div>
            <div class="form-grp">
                <label class="form-label-own">Staff</label>
                <select name="staff_id" class="form-input" required>
                    <option disabled selected value="">--Choose--</option>
                    <?php
                        while($staff = mysqli_fetch_assoc($resultStaff)){
                            $staffID = $staff['staff_id'];
                            $f_name = $staff['f_name'];
                            $l_name = $staff['l_name'];
                    ?>
                    <option value="<?php echo $staffID; ?>" <?php echo ($subject['staff_id']=="$staffID" ? "selected":""); ?>><?php echo $staffID. " - ". $f_name. " ". $l_name; ?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
            <input type="submit" value="Save" class="btn-full-width">
        </form>
    </div>
    
</body>
</html>

<?php
}
?>