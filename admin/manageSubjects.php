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

    $sub_code = $_POST['sub_code'];
    $sub_name = $_POST['sub_name'];
    $sub_abbr = $_POST['sub_abbr'];
    $course_id = $_POST['course_id'];
    $semester = $_POST['semester'];
    $staff_id = $_POST['staff_id'];

    $query = "INSERT INTO `subject` (`sub_code`, `sub_name`, `sub_abbr`, `course_id`, `semester`, `staff_id`) VALUES ('$sub_code', '$sub_name', '$sub_abbr', '$course_id', '$semester', '$staff_id')";
    $result = mysqli_query($conn, $query);
    
    if($result){
        $success=true;
    }
    else{
        $showAlert = "Something went wrong";
    }
}

$query = "SELECT * from `subject`";
$result = mysqli_query($conn, $query);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subjects</title>
</head>
<body class="body-own">

    <?php
        if($success){
    ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success! </strong>Subject has been added succesfully..
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
            <strong>Success! </strong>Subject has been updated successfully..
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
    <?php
            }
            else {
    ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Something went wrong! </strong>Subject not updated successfully..
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
    <?php
        }
    }
    ?>

    <?php
        // Getting Courses List
        $queryCourse = "SELECT * from `course`";
        $resultCourse = mysqli_query($conn, $queryCourse);

        // Getting Staff List
        $queryStaff = "SELECT * from `staff`";
        $resultStaff = mysqli_query($conn, $queryStaff);
    ?>

    <h1 class="page-heading">Manage Subjects</h1>
    <h1 class="panel-heading">Add Subjects</h1>
    <div class="panel-own">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-grp">
                <label class="form-label-own">Subject Name</label>
                <input type="text" class="form-input" name="sub_name" required>
            </div>
            <div class="form-grp">
                <label class="form-label-own">Subject Code</label>
                <input type="number" class="form-input" name="sub_code" required>
            </div>
            <div class="form-grp">
                <label class="form-label-own">Subject Abbrevation</label>
                <input type="text" class="form-input" name="sub_abbr" required>
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
                    <option value="<?php echo $courseID; ?>"><?php echo $courseID. " - ". $course_name; ?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
            <div class="form-grp">
                <label class="form-label-own">Semester</label>
                <input type="number" class="form-input" name="semester" required>
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
                    <option value="<?php echo $staffID; ?>"><?php echo $staffID. " - ". $f_name. " ". $l_name; ?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
            <input type="submit" value="Add" class="btn-full-width">
        </form>
    </div>


    <h1 class="panel-heading">Subject List</h1>
    <div class="panel-transparent">

        <?php
            if(mysqli_num_rows($result)==0){
        ?>
            <p class="content-light">No Subjects</p>
        <?php
            }
            else{
        ?>
        <table class="table-responsive table-hover table">
            <thead>
                <tr>
                    <th class="content-bold">Subject Code</th>
                    <th class="content-bold">Subject Abbrevation</th>
                    <th class="content-bold">Course</th>
                    <th class="content-bold">Semester</th>
                    <th class="content-bold">Staff</th>
                    <th class="content-bold">Edit</th>
                </tr>
            </thead>
            <tbody>

            <?php
                while($row=mysqli_fetch_assoc($result)){
            ?>
                <tr>
                    <td class="content-light"><?php echo $row['sub_code']; ?></td>
                    <td class="content-light"><?php echo $row['sub_abbr']; ?></td>
                    <td class="content-light"><?php echo $row['course_id']; ?></td>
                    <td class="content-light"><?php echo $row['semester']; ?></td>
                    <td class="content-light"><?php echo $row['staff_id']; ?></td>
                    <td class="content-light"><i class="fa fa-pencil-square-o" aria-hidden="true" onclick="window.location.href='editSubject?s_id=<?php echo $row['sub_code']; ?>';"></i></td>
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