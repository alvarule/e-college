<?php
session_start();

// check whether user is student
if($_SESSION['end_user']!="student"){
    header("Location: /e-college/index");
    exit;
}

include '../partials/_nav.php';

// for working with spreadsheets (read and write)
require '../partials/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; 

?>

<?php
if(isset($_GET['t_id'])){

    $testID = $_GET['t_id'];
    // getting test details
    $queryTestDetails = "SELECT * FROM `test_attempt` NATURAL JOIN `test_detail` NATURAL JOIN `subject` WHERE `t_id` = '$testID' and `test_status` = 'in progress'";
    $resultTestDetails = mysqli_query($conn, $queryTestDetails);
    // check whether test is valid and is ready to attempt
    if(mysqli_num_rows($resultTestDetails)<1){
        echo "
        <script type='text/javascript'>
            window.location.href = 'test?success=0';
        </script>
        ";
        exit;
    }
    $test = mysqli_fetch_assoc($resultTestDetails);
    $testID = $test['t_id'];

    // query to check validity of the student accessing the test
    $checkValidityQuery = "SELECT * from `test_attempt` where `t_id` = '$testID'  AND `stud_id` = '$studentID'";
    $checkValidityResult = mysqli_query($conn, $checkValidityQuery);

    // query to check whether test is already attempted?
    $checkTestStatusQuery = "SELECT * from `test_attempt` where `t_id` = '$testID' and `stud_id` = '$studentID' and `attempt_status` = 'not completed'";
    $checkTestStatusResult = mysqli_query($conn, $checkTestStatusQuery);

    // checking validity of the student accessing the test
    if(mysqli_num_rows($checkValidityResult)==0){
        $showAlert = "<strong>Oops! </strong>You don't have permission to access this test!";
    }
    
    // checking whether test is already attempted?
    elseif(mysqli_num_rows($checkTestStatusResult)==0){
        $showAlert = "<strong>Oops! </strong>Test already attempted!";
    }

    
    if($_SERVER['REQUEST_METHOD']=="POST"){
        // getting test questions
        $queryQuestions = "SELECT * from `test_question` where `t_id` = '$testID'";
        $resultQuestions = mysqli_query($conn, $queryQuestions);


        $attempt = [date("Y-m-d h:i:s A"), "$studentID"];
        $q_no = 1;
        $score = 0;

        while($que = mysqli_fetch_assoc($resultQuestions)){
            $ansByStud = $_POST["ans$q_no"];
            $correctAns = $que['correct_option'];
            if($ansByStud == $correctAns){
                $score++;
            }
            array_push($attempt,"$ansByStud");
            $q_no++; // incrementing the question no
        }

        array_splice($attempt,2,0,$score);
        
        // reading the results file
        $reader = PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $sub = $test['sub_code'];
        $date = $test['date'];
        $resultFile = $testID. "_". $sub. "_". $date. ".xlsx";

        // Code to insert data into spreadsheet file
        // Loads the Result Spreadsheet 
        $spreadsheet = $reader->load("../test_result/$resultFile");
        
        $d=$spreadsheet->getSheet(0)->toArray();
        $rowNo = count($d); // get the total rows
        $rowNo++;
        
        
        // Retrieve the current active worksheet 
        $sheet = $spreadsheet->getActiveSheet(); 

        $j=1;
        foreach($attempt as $x_value) {
            $sheet->setCellValueByColumnAndRow($j,$rowNo,$x_value);
            $j=$j+1;
        }
        
        // Write an .xlsx file  
        $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        
        // Save .xlsx file 
        $writer->save("../test_result/$resultFile"); 

        $queryUpdateTestAttempt = "UPDATE `test_attempt` SET `score` = '$score', `attempt_status` = 'completed' WHERE `stud_id` = '$studentID' and `t_id` = '$testID'";
        $resultUpdateTestAttempt = mysqli_query($conn, $queryUpdateTestAttempt);

        if(!$resultUpdateTestAttempt){
            $showAlert = "<strong>Something went wrong! </strong>Please Try Again!";
        }
        else{
            // redirecting to test.php page after successful submission of the test
            echo "
            <script type='text/javascript'>
                window.location.href = 'test?success=1';
            </script>
            ";
        }
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Test</title>
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

    <p class="page-heading"><?php echo $test['title']; ?></p>
    <hr>
    
    <div class="panel-transparent">
        <div class="row">
            <span class="col-sm-6 content-bold">Subject: <?php echo $test['sub_code']. " - ". $test['sub_abbr']; ?></span>
            <span class="col-sm-6 content-bold">Total Marks: <?php echo $test['total_marks']; ?></span>
        </div>
    </div>

    <?php
        // getting test questions
        $queryQuestions = "SELECT * from `test_question` where `t_id` = '$testID'";
        $resultQuestions = mysqli_query($conn, $queryQuestions);

        if(mysqli_num_rows($resultQuestions)<1){
    ?>
        <div class="panel-transparent">
            <p class="content-light" style="text-align:center;">No Questions! Ask the staff to look into this</p>
        </div>
    <?php
        }
        else{
            $q_no = 1; // incrementing variable for question no in the test
    ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>?t_id=<?php echo $testID; ?>" method="post">
    <?php
            while($que = mysqli_fetch_assoc($resultQuestions)){
    ?>

            <div class="panel-own">

                <label class="form-label-own content-bold" style="margin-bottom:20px">Q.<?php echo $q_no. " - ". $que['question']; ?></label>

                <div class="form-check">
                    <input value="<?php echo $que["option1"] ?>" class="form-check-input" type="radio" name="ans<?php echo $q_no ?>" id="op<?php echo $q_no ?>1" required>
                    <label class="form-check-label content-light" for="op<?php echo $q_no ?>1" style="font-size:14pt;"><?php echo $que["option1"] ?></label>
                </div>

                <div class="form-check">
                    <input value="<?php echo $que["option2"] ?>" class="form-check-input" type="radio" name="ans<?php echo $q_no ?>" id="op<?php echo $q_no ?>2">
                    <label class="form-check-label content-light" for="op<?php echo $q_no ?>2" style="font-size:14pt;"><?php echo $que["option2"] ?></label>
                </div>

                <div class="form-check">
                    <input value="<?php echo $que["option3"] ?>" class="form-check-input" type="radio" name="ans<?php echo $q_no ?>" id="op<?php echo $q_no ?>3">
                    <label class="form-check-label content-light" for="op<?php echo $q_no ?>3" style="font-size:14pt;"><?php echo $que["option3"] ?></label>
                </div>

                <div class="form-check">
                    <input value="<?php echo $que["option4"] ?>" class="form-check-input" type="radio" name="ans<?php echo $q_no ?>" id="op<?php echo $q_no ?>4">
                    <label class="form-check-label content-light" for="op<?php echo $q_no ?>4" style="font-size:14pt;"><?php echo $que["option4"] ?></label>
                </div>
                
            </div>
            
            
            <?php
            $q_no++;
        }
        ?>
            <input type="submit" value="Submit" class="btn-full-width">
        </form>
        
        <?php
        }
        ?>
   
</body>
</html>


<?php
}
?>