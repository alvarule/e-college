<?php
session_start();

// check whether user is staff
if($_SESSION['end_user']!="staff"){
    header("Location: /e-college/index");
    exit;
}

include '../partials/_nav.php';

// for working with spreadsheets (writing purpose)
require '../partials/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet; 
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; 
?>

<?php
if(isset($_GET['t_id'])){
    
    $testID = $_GET['t_id'];
    // getting test details
    $queryTestDetails = "SELECT * from `test_detail` NATURAL JOIN `subject` WHERE `t_id` = '$testID'";
    $resultTestDetails = mysqli_query($conn, $queryTestDetails);
    // check whether test exists
    if(mysqli_num_rows($resultTestDetails)<1){
        echo "
        <script type='text/javascript'>
            window.location.href = 'createTest';
        </script>
        ";
        exit;
    }
    $test = mysqli_fetch_assoc($resultTestDetails);
    $testID = $test['t_id'];
    $total_questions = $test['total_questions'];

    // query to check the validity of the staff accessing this page
    $checkValidityQuery = "SELECT * FROM `test_detail` NATURAL JOIN `subject` WHERE `staff_id` = $staffID AND `t_id` = $testID";
    $checkValidityResult = mysqli_query($conn, $checkValidityQuery);
    
    // query to check whether the test questions are already created?
    $checkTestQuestionQuery = "SELECT * from `test_question` where `t_id` = '$testID'";
    $checkTestQuestionResult = mysqli_query($conn, $checkTestQuestionQuery);
    
    // checking the validity of the staff accessing this page
    if(mysqli_num_rows($checkValidityResult)==0){
        $showAlert = "<strong>Oops! </strong>You don't have permission to access this!";
    }
    // checking whether the test questions are already created?
    elseif(mysqli_num_rows($checkTestQuestionResult)>1){
        $showAlert = "<strong>Oops! </strong>Test already exists!";
    }
    // if all checks are clear then create the test
    if($_SERVER['REQUEST_METHOD']=="POST"){
        //set column header
        //set your own column header
        $column_header=["Timestamp","Student ID", "Score"];
                
        for($q = 1; $q <= $total_questions; $q++){
            // getting the question details
            $que = $_POST["que$q"];
            $op1 = $_POST["op".$q."1"];
            $op2 = $_POST["op".$q."2"];
            $op3 = $_POST["op".$q."3"];
            $op4 = $_POST["op".$q."4"];
            $ans = $_POST["ans$q"];
            $ans = $_POST[$ans];

            // query to insert question into `test_question`
            $queryInsertTestQuestion = "INSERT INTO `test_question` (`t_id`, `question`, `option1`, `option2`, `option3`, `option4`, `correct_option`) VALUES ('$testID', '$que', '$op1', '$op2', '$op3', '$op4', '$ans')";
            // inserting question into `test_question`
            $resultInsertTestQuestion = mysqli_query($conn, $queryInsertTestQuestion);

            if(!$resultInsertTestQuestion){
                $showAlert = "<strong>Something went wrong! </strong>Please try again";
            }

            // appending the 'question' to $column_header
            array_push($column_header,"$que");
        }

        // Code to insert data into spreadsheet file
        // Creates New Spreadsheet 
        $spreadsheet = new Spreadsheet(); 
        
        // Retrieve the current active worksheet 
        $sheet = $spreadsheet->getActiveSheet(); 

        $j=1;
        foreach($column_header as $x_value) {
            $sheet->setCellValueByColumnAndRow($j,1,$x_value);
            $j=$j+1;
        }
        
        // Write an .xlsx file  
        $writer = new Xlsx($spreadsheet); 
        
        // Save .xlsx file to the files directory 
        $sub = $test['sub_code'];
        $date = $test['date'];
        $resultFile = $testID. "_". $sub. "_". $date. ".xlsx";
        $writer->save("../test_result/$resultFile"); 

        // redirecting to createTest.php page
        echo "
        <script type='text/javascript'>
            window.location.href = 'createTest';
        </script>
        ";

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Test</title>

</head>
<body class="body-own">

    <?php
        if(isset($showAlert)){
    ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $showAlert; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
    <?php
        exit;
        }
    ?>
    
    <p class="page-heading">Create Test</p>

    <div class="panel-transparent content-bold">
        <div class="row">
            <span class="col-sm-6">Title: <?php echo $test['title']; ?></span>
            <span class="col-sm-6">Subject: <?php echo $test['sub_code']. " - ". $test['sub_abbr']; ?></span>
        </div>
    </div>
    
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>?t_id=<?php echo $testID; ?>" method="post">
    
    <?php
        for($q = 1; $q <= $total_questions; $q++){
    ?>

        <div class="panel-own">

            <label class="form-label-own">Q.<?php echo $q; ?></label>
            <textarea name="que<?php echo $q; ?>" class="form-input-full-width" placeholder="Question" rows="2" required><?php echo (isset($_POST["que$q"]) ? $_POST["que$q"] : ''); ?></textarea>

            <input type="text" name="op<?php echo $q; ?>1" value="<?php echo (isset($_POST["op$q"."1"]) ? $_POST["op$q"."1"] : ''); ?>" class="form-input" placeholder="Option 1" required>

            <input type="text" name="op<?php echo $q; ?>2" value="<?php echo (isset($_POST["op$q"."2"]) ? $_POST["op$q"."2"] : ''); ?>" class="form-input" placeholder="Option 2" required>

            <input type="text" name="op<?php echo $q; ?>3" value="<?php echo (isset($_POST["op$q"."3"]) ? $_POST["op$q"."3"] : ''); ?>" class="form-input" placeholder="Option 3" required>

            <input type="text" name="op<?php echo $q; ?>4" value="<?php echo (isset($_POST["op$q"."4"]) ? $_POST["op$q"."4"] : ''); ?>" class="form-input" placeholder="Option 4" required>

            
            <div class="form-grp">
                <label class="form-label-own">Correct Answer</label>
                <select name="ans<?php echo $q; ?>" class="form-input" style="width: 25%;" required>
                    <option disabled selected value="">--</option>

                    <option value="op<?php echo $q; ?>1">Option 1</option>
                    <option value="op<?php echo $q; ?>2">Option 2</option>
                    <option value="op<?php echo $q; ?>3">Option 3</option>
                    <option value="op<?php echo $q; ?>4">Option 4</option>
                </select>
            </div>
        </div>


    <?php
        }
    ?>

        <input type="submit" value="Create Test" class="btn-full-width">
    </form>
</body>
</html>  


<?php
}
?>

