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
    $staff_id = $_POST['staff_id'];
    $f_name = $_POST["f_name"];
    $m_name = $_POST["m_name"];
    $l_name = $_POST["l_name"]; 
    $email = $_POST["email"];
    $mob_no = $_POST["mob_no"];
    $city = $_POST["city"];
    $country = $_POST["country"];
    $state = $_POST["state"];
    $pin_code = $_POST["pin_code"];
    $gender = $_POST["gender"];
    $dob = $_POST["dob"];
    $qualification = $_POST['qualification'];
    $work_exp = $_POST['work_exp'];

    $queryCheckStaff = "SELECT * from `staff` where `staff_id` = $staff_id";
    $resultCheckStaff = mysqli_query($conn, $queryCheckStaff);
    if(mysqli_num_rows($resultCheckStaff)==0){
        echo "<script>window.location.href='manageStaff';</script>";
        exit;
    }

    $queryUpdateStaff = "UPDATE `staff` SET `f_name` = '$f_name', `m_name` = '$m_name', `l_name` = '$l_name', `email` = '$email', `mob_no` = '$mob_no', `city` = '$city', `state` = '$state', `country` = '$country', `pin_code` = '$pin_code', `gender` = '$gender', `dob` = '$dob', `qualification` = '$qualification', `work_exp` = '$work_exp' WHERE `staff_id` = '$staff_id'";

    $resultUpdateStaff = mysqli_query($conn, $queryUpdateStaff);

    if($resultUpdateStaff){
        echo "<script>window.location.href='manageStaff?success=true';</script>";
    }
    else{
        echo "<script>window.location.href='manageStaff?success=false';</script>";
    }
    
}

?>

<?php

if(isset($_GET['s_id'])){
    $staff_id = $_GET['s_id'];
    $queryStaff = "SELECT * from `staff` where `staff_id` = $staff_id";
    $resultStaff = mysqli_query($conn, $queryStaff);
    if(mysqli_num_rows($resultStaff)==0){
        echo "<script>window.location.href='manageStaff';</script>";
        exit;
    }

    $staff = mysqli_fetch_assoc($resultStaff);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff</title>
</head>
<body class="body-own">

    <h1 class="page-heading">Edit Staff</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <p class="panel-heading">Personal Details</p>
        <div class="panel-own">
            <div class="form-grp">
                <center>
                <img src="/e-college/staff_profile_pic/<?php echo $staff['photo'] ?>" alt="" class="img-thumbnail">
                </center>
            </div>
            <div class="form-grp">
                <label for="First Name" class="form-label-own">First Name</label>
                <input type="text" name="f_name" class="form-input" value="<?php echo $staff['f_name']; ?>" required>
            </div>
            <div class="form-grp">
                <label for="Middle Name" class="form-label-own">Middle Name</label>
                <input type="text" name="m_name" class="form-input" value="<?php echo $staff['m_name']; ?>" required>
            </div>
            <div class="form-grp">
                <label for="Last Name" class="form-label-own">Last Name</label>
                <input type="text" name="l_name" class="form-input" value="<?php echo $staff['l_name']; ?>" required>
            </div>
            <div class="form-grp">
                <label for="Mobile Number" class="form-label-own">Mobile No</label>
                <input type="number" name="mob_no" class="form-input" value="<?php echo $staff['mob_no']; ?>" required>
            </div>
            <div class="form-grp">
                <label for="Email Id" class="form-label-own">Email Id</label>
                <input type="email" name="email" class="form-input" value="<?php echo $staff['email']; ?>" required>
            </div>
            <div class="form-grp">
                <label for="DOB" class="form-label-own">Date Of Birth</label>
                <input type="date" name="dob" class="form-input" value="<?php echo $staff['dob']; ?>" required>
            </div>
            <div class="form-grp">
                <label for="Gender" class="form-label-own">Gender</label>
                <select name="gender" class="form-input" required>
                    <option value="Male" <?php echo ($staff['gender']=="Female" ? "selected":""); ?>>Male</option>
                    <option value="Female" <?php echo ($staff['gender']=="Female" ? "selected":""); ?>>Female</option>
                </select>
            </div>
        </div>
        
        <p class="panel-heading">Address Details</p>
        <div class="panel-own">
            <div class="form-grp">
                <label for="City" class="form-label-own">City</label>
                <input type="text" name="city" class="form-input" value="<?php echo $staff['city']; ?>" required>
            </div>
            <div class="form-grp">
                <label for="State" class="form-label-own">State</label>
                <input type="text" name="state" class="form-input" value="<?php echo $staff['state']; ?>" required>
            </div>
            <div class="form-grp">
                <label for="Country" class="form-label-own">Country</label>
                <input type="text" name="country" class="form-input" value="<?php echo $staff['country']; ?>" required>
            </div>
            <div class="form-grp">
                <label for="Pin Code" class="form-label-own">Pin Code</label>
                <input type="number" name="pin_code" class="form-input" maxlength="6" value="<?php echo $staff['pin_code']; ?>" required>
            </div>
        </div>

        <p class="panel-heading">Qualification Details</p>
        <div class="panel-own">
            <div class="form-grp">
                <label for="Qualification" class="form-label-own">Qualification</label>
                <input type="text" name="qualification" class="form-input" value="<?php echo $staff['qualification']; ?>" required>
            </div>
            <div class="form-grp">
                <label for="Work Experience" class="form-label-own">Work Experience</label>
                <input type="number" name="work_exp" class="form-input" value="<?php echo $staff['work_exp']; ?>" required>
            </div>
        </div>
        <input type="text" name="staff_id" value="<?php echo $_GET['s_id']; ?>" hidden>

        <input type="submit" value="Save" class="btn-full-width">
    </form>
    
</body>
</html>

<?php
}

?>
