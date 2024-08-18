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

// deleting an installment
$deleted = false;
if(isset($_GET['del'])){
    $f_id = $_GET['del'];
    $queryFees = "SELECT * from `fees_detail` where `f_id` = $f_id";
    $resultFees = mysqli_query($conn, $queryFees);
    if(mysqli_num_rows($resultFees)>0){
        $queryDelFees = "DELETE FROM fees_detail WHERE `f_id` = $f_id";
        $resultDelFees = mysqli_query($conn, $queryDelFees);
        if($resultDelFees){
            $deleted = true;
        }
    }
}

?>

<?php
// Getting Courses List
$queryCourse = "SELECT * from `course`";
$resultCourse = mysqli_query($conn, $queryCourse);

// Getting Fees Details List
$queryFees = "SELECT * from `fees_detail` NATURAL JOIN `course`";
$resultFees = mysqli_query($conn, $queryFees);
?>

<?php

//creating an installment
$success = false;
if($_SERVER['REQUEST_METHOD']== 'POST'){
    $course_id = $_POST["course_id"];
    $amount = $_POST["amount"];
    $date = date("Y-m-d");

    // inserting data into `fees_detail` tbl
    $queryInsertFees = "INSERT INTO `fees_detail` (`f_id`, `course_id`, `amount`, `date`) VALUES (NULL, '$course_id', '$amount', '$date')";
    $resultInsertFees = mysqli_query($conn, $queryInsertFees);
    
    if($resultInsertFees){
        $success = true;
    
        // getting student list eligible for the installment
        $queryGetStuds = "SELECT * FROM `fees_detail` NATURAL JOIN `student` WHERE `course_id` = $course_id and `date` = '$date'";
        $resultGetStuds = mysqli_query($conn, $queryGetStuds);

        // looping through every student from student list got from previous query
        while($stud = mysqli_fetch_assoc($resultGetStuds)){
            $f_id = $stud['f_id'];
            $stud_id = $stud['stud_id'];

            // inserting fees installment into `fees_stud` for students
            $queryInsertFeesStud = "INSERT INTO `fees_stud` (`f_id`, `stud_id`, `trans_status`) VALUES ('$f_id', '$stud_id', '--') ";
            $resultInsertFeesStud = mysqli_query($conn, $queryInsertFeesStud);
            if(!$resultInsertFeesStud){
                $success = false;
            }
        }
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fees Management</title>
</head>
<body class="body-own">

<?php
        if($success){
    ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success! </strong>Installment created successfully..
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
    <?php
        }
        if($deleted){
    ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success! </strong>Installment deleted successfully..
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
    <?php
        }
    ?>   

    <h1 class="page-heading">Fees Management</h1>
    <h2 class="panel-heading">Create Installment</h2>
    <div class="panel-own">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-grp">
                <label class="form-label-own">Course</label>
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
                <label class="form-label-own">Amount</label>
                <input type="number" class="form-input" name="amount" required>
            </div>
            <input type="Submit" class="btn-full-width" value="Create Installment">
        </div>
    </form>
    <br>
    <h2 class="panel-heading">Installments</h2>

    <div class="panel-transparent">

    <?php
        if(mysqli_num_rows($resultFees)==0){
    ?>
            <p class="content-light">No Installments found</p>
    <?php
        }
        else{
    ?>
            <table class="table-responsive table table-hover">
                <thead>
                    <tr>
                        <th class="content-bold">Course</th>
                        <th class="content-bold">Amount</th>
                        <th class="content-bold">View</th>
                        <th class="content-bold">Delete</th>
                    </tr>
                </thead>
                <tbody>
    <?php
            while($fees = mysqli_fetch_assoc($resultFees)){
    ?>
                    <tr>
                        <td class="content-light"><?php echo $fees['course_id']. " - ". $fees['course_name']; ?></td>
                        <td class="content-light"><?php echo $fees['amount']; ?></td>
                        <td class="content-light"><i class="fa fa-eye" onclick="window.location.href='/e-college/admin/viewFees?id=<?php echo $fees['f_id']; ?>';"></td>
                        <td class="content-light"><i class="fa fa-trash" onclick="window.location.href='/e-college/admin/feesMgmt?del=<?php echo $fees['f_id']; ?>';"></td>
                    </tr>
                    <?php
            }
    ?>
            </tbody>
            </table>
    <?php
        }
    ?>
</body>
</html>