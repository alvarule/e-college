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
// func to resize image
function imageResize($imageSrc,$imageWidth,$imageHeight) {
    $newImageWidth=250;
    $newImageHeight=250;
    
    $newImageLayer=imagecreatetruecolor($newImageWidth,$newImageHeight);
    imagecopyresampled($newImageLayer,$imageSrc,0,0,0,0,$newImageWidth,$newImageHeight,$imageWidth,$imageHeight);
    
    return $newImageLayer;
}
?>

<?php
// Getting Courses List
$queryCourse = "SELECT * from `course`";
$resultCourse = mysqli_query($conn, $queryCourse);
?>


<?php
$success = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST'){

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
    $admission_year = date("Y");
    $course_id = $_POST["course_id"];
    $semester = $_POST["semester"];
    $quota = $_POST["quota"];
    $caste = $_POST["caste"];
    $category = $_POST["category"];

    // getting profile pic values
    $filename = $_FILES["profile"]["name"];
    $tempname = $_FILES["profile"]["tmp_name"];
    $ext = pathinfo($filename, PATHINFO_EXTENSION); // get file extension

    //acceptable file types
    $acceptable_types = array(
        'image/jpeg',
        'image/jpg'
    );
    
    //acceptable file extensions
    $acceptable_ext = array(
        'jpg',
        'jpeg'
    );

    // check whether staff is already registered?
    $existsResult = "SELECT * from `student` where `email` = '$email'";
    $existsResult = mysqli_query($conn, $existsResult);
    if(mysqli_num_rows($existsResult) > 0){
        $showAlert = "Student already exists!";
    }
    // check if file type/ext is valid
    elseif(!in_array($_FILES['profile']['type'], $acceptable_types) || !in_array($ext, $acceptable_ext))
    {
        $showAlert = "File Format Not Supported\nIt should be .jpg or .jpeg";
    }

    
    else{
        // to get the total no of registered students of same course and same year so as to create unique Student ID
        $listQuery = "SELECT * from `student` where `course_id` = '$course_id' and `admission_year` = '$admission_year'";
        $listResult = mysqli_query($conn, $listQuery);

        // generating Student ID
        $no_of_student = mysqli_num_rows($listResult);
        $student_id = $admission_year.$course_id.sprintf('%03d', $no_of_student+1);

        $uploadname = $student_id. ".". $ext; // upload name for student profile pic
        $upload = "../student_profile_pic/". $uploadname; // upload location for student profile pic

        // query to insert data into `student` table
        $insertQuery = "INSERT INTO `student` (`stud_id`, `photo`, `f_name`, `m_name`, `l_name`, `email`, `stud_mob_no`, `parent_mob_no`, `city`, `state`, `country`, `pin_code`, `gender`, `dob`, `semester`, `course_id`, `admission_year`, `quota`, `caste`, `category`) VALUES ('$student_id', '$uploadname', '$f_name', '$m_name', '$l_name', '$email', '$stud_mob_no', '$parent_mob_no', '$city', '$state', '$country', '$pin_code', '$gender', '$dob', $semester, '$course_id', '$admission_year', '$quota', '$caste', '$category')";

        // password generation
        $combination = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); 
        $combinationLen = strlen($combination) - 1; 
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $combinationLen);
            $pass[] = $combination[$n];
        }
        $password = implode($pass);
        // $password = "pass"; // demo purpose
        $passhash = password_hash($password , PASSWORD_DEFAULT);

        // query to insert data into `stud_login` table
        $loginEntryQuery = "INSERT into `stud_login` (`stud_id`, `password`) VALUES ('$student_id', '$passhash')";
        
        // setting student profile pic size
        $sourceProperties=getimagesize($tempname);
        $imageType=$sourceProperties[2];

        $imageSrc= imagecreatefromjpeg($tempname); 
        $tmp= imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1]);
        imagejpeg($tmp,$tempname);
        
        // code to send email for successful registration of student
        $data = [
            'email' => $email,
            'subject' => "Login Credentials for E-College",
            'message' => "Dear $f_name $m_name $l_name,<br>You have been successfully registered on E-College. Now you can login to the system using the credentials given below<br><b>Student ID:</b> $student_id<br><b>Password:</b> $password"
        ];
        $postdata = http_build_query($data);
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, 'http://localhost/e-college/partials/smtp/index.php');
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        

        // executing queries, uploading student profile pic and sending post request to http://localhost/e-college/partials/smtp/index.php for sending email and simultaneously checking if execution is successful
        if(curl_exec($ch) && mysqli_query($conn, $insertQuery) && mysqli_query($conn, $loginEntryQuery) && move_uploaded_file($tempname, $upload)){
            $success=true;                
        }

        else{
            $showAlert = "Something went wrong! Please try again";
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
    <title>Student Registration</title>
</head>
<body class="body-own">
    <?php
        if($success){
    ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success! </strong>Student has been successfully registered..
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
    <?php
        }
      
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

    <h1 class="page-heading">Student Registration</h1>
    <form action="\e-college\admin\studentRegistration.php" method="POST" enctype="multipart/form-data">
        <p class="panel-heading">Personal Details</p>
        <div class="panel-own">
            <div class="form-grp">
                <label class="form-label-own">Profile</label>
                <input type="file" name="profile" id="photo" class="form-input-file" required>
            </div>
            <div class="form-grp">
                <label for="First Name" class="form-label-own">First Name</label>
                <input type="text" name="f_name" id="f_name" class="form-input" value="<?php echo (isset($_POST['f_name']) ? $_POST['f_name'] : ''); ?>" required>
            </div>
            <div class="form-grp">
                <label for="Middle Name" class="form-label-own">Middle Name</label>
                <input type="text" name="m_name" id="m_name" class="form-input" value="<?php echo (isset($_POST['m_name']) ? $_POST['m_name'] : ''); ?>" required>
            </div>
            <div class="form-grp">
                <label for="Last Name" class="form-label-own">Last Name</label>
                <input type="text" name="l_name" id="l_name" class="form-input" value="<?php echo (isset($_POST['l_name']) ? $_POST['l_name'] : ''); ?>" required>
            </div>
            <div class="form-grp">
                <label for="email" class="form-label-own">Email</label>
                <input type="email" name="email" id="email" class="form-input" value="<?php echo (isset($_POST['email']) ? $_POST['email'] : ''); ?>" required>
            </div>
            
            <div class="form-grp">
                <label for="Mobile Number" class="form-label-own">Student's Mobile No</label>
                <input type="number" name="stud_mob_no" id="stud_mob_no" class="form-input" minlength="10" maxlength="10" value="<?php echo (isset($_POST['stud_mob_no']) ? $_POST['stud_mob_no'] : ''); ?>" required>
            </div>
            <div class="form-grp">
                <label for="Mobile Number" class="form-label-own">Parent's Mobile No</label>
                <input type="number" name="parent_mob_no" id="parent_mob_no" class="form-input" minlength="10" maxlength="10" value="<?php echo (isset($_POST['parent_mob_no']) ? $_POST['parent_mob_no'] : ''); ?>" required>
            </div>
            
            <div class="form-grp">
                <label for="DOB" class="form-label-own">Date Of Birth</label>
                <input type="date" name="dob" id="dob" class="form-input" value="<?php echo (isset($_POST['dob']) ? $_POST['dob'] : ''); ?>" required>
            </div>
            <div class="form-grp">
                <label for="Gender" id="gender" class="form-label-own" >Gender</label>
                <select name="gender"  class="form-input" required>
                    <option disabled selected value="">--Choose--</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="form-grp">
                <label for="caste" class="form-label-own">Caste</label>
                <input type="text" name="caste" id="caste" class="form-input" value="<?php echo (isset($_POST['caste']) ? $_POST['caste'] : ''); ?>" required>
            </div>

            <div class="form-grp">
                <label for="Category" class="form-label-own" id="category">Category</label>
                <select name="category" class="form-input" required>
                    <option disabled selected value="">--Choose--</option>
                    <option value="Open">Open</option>
                    <option value="OBC">OBC</option>
                    <option value="BC">BC</option>
                    <option value="SC">SC</option>
                    <option value="VJNT">VJNT</option>
                    <option value="EBC">EBC</option>
                </select>
            </div>
        </div>
        
        <p class="panel-heading">Address Details</p>
        <div class="panel-own">
            <div class="form-grp">
                <label for="City" class="form-label-own">City</label>
                <input type="text" name="city" id="city" class="form-input" value="<?php echo (isset($_POST['city']) ? $_POST['city'] : ''); ?>" required>
            </div>
            <div class="form-grp">
                <label for="State" class="form-label-own">State</label>
                <input type="text" name="state" id="state" class="form-input" value="<?php echo (isset($_POST['state']) ? $_POST['state'] : ''); ?>" required>
            </div>
            <div class="form-grp">
                <label for="Country" class="form-label-own">Country</label>
                <input type="text" name="country" id="country" class="form-input" value="<?php echo (isset($_POST['country']) ? $_POST['country'] : ''); ?>" required>
            </div>
            <div class="form-grp">
                <label for="Pin Code" class="form-label-own">Pin Code</label>
                <input type="number" name="pin_code" id="pin-code" class="form-input" value="<?php echo (isset($_POST['pin_code']) ? $_POST['pin_code'] : ''); ?>" required>
            </div>
        </div>

        <p class="panel-heading">Academics Details</p>
        <div class="panel-own">
            <div class="form-grp">
                <label for="Course Code" class="form-label-own">Course</label>
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
                <label for="Semester" class="form-label-own">Semester</label>
                <input type="number" name="semester" id="semester" class="form-input" value="<?php echo (isset($_POST['semester']) ? $_POST['semester'] : '1'); ?>" required>
            </div>
            <div class="form-grp">
                <label for="Quota" class="form-label-own">Quota</label>
                <input type="text" name="quota" id="quota" class="form-input" value="<?php echo (isset($_POST['quota']) ? $_POST['quota'] : ''); ?>" required>
            </div>
        </div>

        <input type="submit" value="Register" class="btn-full-width">
    </form>
<?php
    
      ?>
</body>
</html>