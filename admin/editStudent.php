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
// Getting Courses List
$queryCourse = "SELECT * from `course`";
$resultCourse = mysqli_query($conn, $queryCourse);
?>

<?php

if($_SERVER['REQUEST_METHOD']=="POST"){
    $stud_id = $_POST['stud_id'];
    $f_name = $_POST["f_name"];
    $m_name = $_POST["m_name"];
    $l_name = $_POST["l_name"]; 
    $email = $_POST["email"];
    $stud_mob_no = $_POST["stud_mob_no"];
    $parent_mob_no = $_POST["parent_mob_no"];
    $city = $_POST["city"];
    $country = $_POST["country"];
    $state = $_POST["state"];
    $pin_code = $_POST["pin_code"];
    $gender = $_POST["gender"];
    $dob = $_POST["dob"];
    $roll_no = $_POST['roll_no'];
    $course_id = $_POST["course_id"];
    $semester = $_POST["semester"];
    $quota = $_POST["quota"];
    $caste = $_POST["caste"];
    $category = $_POST["category"];

    $queryCheckStud = "SELECT * from `student` where `stud_id` = $stud_id";
    $resultCheckStud = mysqli_query($conn, $queryCheckStud);
    if(mysqli_num_rows($resultCheckStud)==0){
        echo "<script>window.location.href='manageStudent';</script>";
        exit;
    }

    $queryUpdateStud = "UPDATE `student` SET `f_name` = '$f_name', `m_name` = '$m_name', `l_name` = '$l_name', `email` = '$email', `stud_mob_no` = '$stud_mob_no', `parent_mob_no` = '$parent_mob_no', `city` = '$city', `state` = '$state', `country` = '$country', `pin_code` = '$pin_code', `gender` = '$gender', `dob` = '$dob', `roll_no` = '$roll_no', `course_id` = '$course_id', `quota` = '$quota', `caste` = '$caste', `category` = '$category' WHERE `stud_id` = '$stud_id'";

    $resultUpdateStud = mysqli_query($conn, $queryUpdateStud);

    if($resultUpdateStud){
        echo "<script>window.location.href='manageStudent?success=true';</script>";
    }
    else{
        echo "<script>window.location.href='manageStudent?success=false';</script>";
    }
    
}

?>

<?php

if(isset($_GET['s_id'])){
    $stud_id = $_GET['s_id'];
    $queryStud = "SELECT * from `student` where `stud_id` = $stud_id";
    $resultStud = mysqli_query($conn, $queryStud);
    if(mysqli_num_rows($resultStud)==0){
        echo "<script>window.location.href='manageStudent';</script>";
        exit;
    }

    $student = mysqli_fetch_assoc($resultStud);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
</head>
<body class="body-own">

<h1 class="page-heading">Edit Student Details</h1>
    <form action="\e-college\admin\editStudent.php" method="POST">
        <p class="panel-heading">Personal Details</p>
        <div class="panel-own">
            <div class="form-grp">
                <center>
                <img src="/e-college/student_profile_pic/<?php echo $student['photo'] ?>" alt="" class="img-thumbnail">
                </center>
            </div>            
            <div class="form-grp">
                <label for="First Name" class="form-label-own">First Name</label>
                <input type="text" name="f_name" id="f_name" class="form-input" value="<?php echo $student['f_name']; ?>" required>
            </div>
            <div class="form-grp">
                <label for="Middle Name" class="form-label-own">Middle Name</label>
                <input type="text" name="m_name" id="m_name" class="form-input" value="<?php echo $student['m_name']; ?>" required>
            </div>
            <div class="form-grp">
                <label for="Last Name" class="form-label-own">Last Name</label>
                <input type="text" name="l_name" id="l_name" class="form-input" value="<?php echo $student['l_name']; ?>" required>
            </div>
            <div class="form-grp">
                <label for="email" class="form-label-own">Email</label>
                <input type="email" name="email" id="email" class="form-input" value="<?php echo $student['email']; ?>" required>
            </div>
            
            <div class="form-grp">
                <label for="Mobile Number" class="form-label-own">Student's Mobile No</label>
                <input type="number" name="stud_mob_no" id="stud_mob_no" class="form-input" minlength="10" maxlength="10" value="<?php echo $student['stud_mob_no']; ?>" required>
            </div>
            <div class="form-grp">
                <label for="Mobile Number" class="form-label-own">Parent's Mobile No</label>
                <input type="number" name="parent_mob_no" id="parent_mob_no" class="form-input" minlength="10" maxlength="10" value="<?php echo $student['parent_mob_no']; ?>" required>
            </div>
            
            <div class="form-grp">
                <label for="DOB" class="form-label-own">Date Of Birth</label>
                <input type="date" name="dob" id="dob" class="form-input" value="<?php echo $student['dob']; ?>" required>
            </div>
            <div class="form-grp">
                <label for="Gender" id="gender" class="form-label-own" >Gender</label>
                <select name="gender"  class="form-input" required>
                    <option value="Male" <?php echo ($student['gender']=="Male" ? "selected":""); ?>>Male</option>
                    <option value="Female" <?php echo ($student['gender']=="Female" ? "selected":""); ?>>Female</option>
                </select>
            </div>
            <div class="form-grp">
                <label for="caste" class="form-label-own">Caste</label>
                <input type="text" name="caste" id="caste" class="form-input" value="<?php echo $student['caste']; ?>" required>
            </div>

            <div class="form-grp">
                <label for="Category" class="form-label-own" id="category">Category</label>
                <select name="category" class="form-input" required>
                    <option value="Open" <?php echo ($student['category']=="Open" ? "selected":""); ?>>Open</option>
                    <option value="OBC" <?php echo ($student['category']=="OBC" ? "selected":""); ?>>OBC</option>
                    <option value="BC" <?php echo ($student['category']=="BC" ? "selected":""); ?>>BC</option>
                    <option value="SC" <?php echo ($student['category']=="SC" ? "selected":""); ?>>SC</option>
                    <option value="VJNT" <?php echo ($student['category']=="VJNT" ? "selected":""); ?>>VJNT</option>
                    <option value="EBC" <?php echo ($student['category']=="EBC" ? "selected":""); ?>>EBC</option>
                </select>
            </div>
        </div>
        
        <p class="panel-heading">Address Details</p>
        <div class="panel-own">
            <div class="form-grp">
                <label for="City" class="form-label-own">City</label>
                <input type="text" name="city" id="city" class="form-input" value="<?php echo $student['city']; ?>" required>
            </div>
            <div class="form-grp">
                <label for="State" class="form-label-own">State</label>
                <input type="text" name="state" id="state" class="form-input" value="<?php echo $student['state']; ?>" required>
            </div>
            <div class="form-grp">
                <label for="Country" class="form-label-own">Country</label>
                <input type="text" name="country" id="country" class="form-input" value="<?php echo $student['country']; ?>" required>
            </div>
            <div class="form-grp">
                <label for="Pin Code" class="form-label-own">Pin Code</label>
                <input type="number" name="pin_code" id="pin-code" class="form-input" value="<?php echo $student['pin_code']; ?>" required>
            </div>
        </div>

        <p class="panel-heading">Academics Details</p>
        <div class="panel-own">
            <div class="form-grp">
                <label for="Quota" class="form-label-own">Quota</label>
                <input type="text" name="quota" id="quota" class="form-input" value="<?php echo $student['quota']; ?>" required>
            </div>
            <div class="form-grp">
                <label for="Course Code" class="form-label-own">Course</label>
                <select name="course_id" class="form-input" required>
                    <?php
                        while($course = mysqli_fetch_assoc($resultCourse)){
                            $courseID = $course['course_id'];
                            $course_name = $course['course_name'];
                    ?>
                    <option value="<?php echo $courseID; ?>" <?php echo ($student['course_id']=="$courseID" ? "selected":""); ?>><?php echo $courseID. " - ". $course_name; ?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
            <div class="form-grp">
                <label for="Semester" class="form-label-own">Semester</label>
                <input type="number" name="semester" id="semester" class="form-input" value="<?php echo $student['semester']; ?>" required>
            </div>
            <div class="form-grp">
                <label for="roll_no" class="form-label-own">Roll No</label>
                <input type="number" name="roll_no" id="roll_no" class="form-input" value="<?php echo $student['roll_no']; ?>" required>
            </div>
        </div>
        <input type="text" value="<?php echo $_GET['s_id']; ?>" name="stud_id" hidden>

        <input type="submit" value="Save" class="btn-full-width">
    </form>
        
</body>
</html>

<?php
}
?>
