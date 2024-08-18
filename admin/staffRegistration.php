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
$success = false; // for the alert on successful registration
if ($_SERVER['REQUEST_METHOD'] == "POST"){

    // getting input values
    $f_name = $_POST["f_name"];
    $m_name = $_POST["m_name"];
    $l_name = $_POST["l_name"]; 
    $dob = $_POST["dob"];
    $gender = $_POST["gender"];
    $mob_no = $_POST["mob_no"];
    $email = $_POST["email"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $country = $_POST["country"];
    $pin_code = $_POST["pin_code"];
    $work_exp = $_POST["work_exp"];
    $qualification = $_POST["qualification"];
    $joining_year = date("Y");
    
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
    $existsResult = "SELECT * from `staff` where `email` = '$email'";
    $existsResult = mysqli_query($conn, $existsResult);
    if(mysqli_num_rows($existsResult) > 0){
        $showAlert = "Staff already exists!";
    }
    // check if file type/ext is valid
    elseif(!in_array($_FILES['profile']['type'], $acceptable_types) || !in_array($ext, $acceptable_ext))
    {
        $showAlert = "File Format Not Supported\nIt should be .jpg or .jpeg";
    }

    else{
        // to get the total no of registered staffs so as to give create unique Staff ID
        $listQuery = "SELECT * from `staff`";
        $listResult = mysqli_query($conn, $listQuery);

        // generating Staff ID
        $no_of_staff = mysqli_num_rows($listResult);
        $month = date("m", strtotime($dob));
        $date = date("d", strtotime($dob));
        $staff_id = $date.$month.$no_of_staff+1;

        $uploadname = $staff_id. ".". $ext; // upload name for staff profile pic
        $upload = "../staff_profile_pic/". $uploadname; // upload location for staff profile pic

        // query to insert data into `staff` table
        $insertQuery = "INSERT INTO `staff` (`staff_id`, `photo`, `f_name`, `m_name`, `l_name`, `dob`, `gender`, `mob_no`, `email`, `city`, `state`, `country`, `pin_code`, `work_exp`, `qualification`, `joining_year`) VALUES ('$staff_id', '$uploadname', '$f_name', '$m_name', '$l_name', '$dob', '$gender', '$mob_no', '$email', '$city', '$state', '$country', '$pin_code', '$work_exp', '$qualification', '$joining_year');";

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

        // query to insert data into `staff_login` table
        $loginEntryQuery = "INSERT into `staff_login` (`staff_id`, `password`) VALUES ('$staff_id', '$passhash')";
        
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
            'message' => "Dear $f_name $m_name $l_name,<br>You have been successfully registered on E-College. Now you can login to the system using the credentials given below<br><b>Staff ID:</b> $staff_id<br><b>Password:</b> $password"
        ];
        $postdata = http_build_query($data);
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, 'http://localhost/e-college/partials/smtp/index.php');
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        
                    
        // executing queries, uploading staff profile pic and sending post request to http://localhost/e-college/partials/smtp/index.php for sending email and simultaneously checking if execution is successful
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
    <title>Staff Registration</title>
</head>
<body class="body-own">
    <?php
        if($success){
    ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success! </strong>Staff has been successfully Registered..
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

    <h1 class="page-heading">Staff Registration</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
        <p class="panel-heading">Personal Details</p>
        <div class="panel-own">
            <div class="form-grp">
                <label for="profile" class="form-label-own">Profile</label>
                <input type="file" id="profile" name="profile" class="form-input-file" required accept="image/*">
            </div>
            <div class="form-grp">
                <label for="First Name" class="form-label-own">First Name</label>
                <input type="text" name="f_name" class="form-input" value="<?php echo (isset($_POST['f_name']) ? $_POST['f_name'] : ''); ?>" required>
            </div>
            <div class="form-grp">
                <label for="Middle Name" class="form-label-own">Middle Name</label>
                <input type="text" name="m_name" class="form-input" value="<?php echo (isset($_POST['m_name']) ? $_POST['m_name'] : ''); ?>" required>
            </div>
            <div class="form-grp">
                <label for="Last Name" class="form-label-own">Last Name</label>
                <input type="text" name="l_name" class="form-input" value="<?php echo (isset($_POST['l_name']) ? $_POST['l_name'] : ''); ?>" required>
            </div>
            <div class="form-grp">
                <label for="Mobile Number" class="form-label-own">Mobile No</label>
                <input type="number" name="mob_no" class="form-input" value="<?php echo (isset($_POST['mob_no']) ? $_POST['mob_no'] : ''); ?>" required>
            </div>
            <div class="form-grp">
                <label for="Email Id" class="form-label-own">Email Id</label>
                <input type="email" name="email" class="form-input" value="<?php echo (isset($_POST['email']) ? $_POST['email'] : ''); ?>" required>
            </div>
            <div class="form-grp">
                <label for="DOB" class="form-label-own">Date Of Birth</label>
                <input type="date" name="dob" class="form-input" value="<?php echo (isset($_POST['dob']) ? $_POST['dob'] : ''); ?>" required>
            </div>
            <div class="form-grp">
                <label for="Gender" class="form-label-own">Gender</label>
                <select name="gender" class="form-input" required>
                    <option disabled selected value="">--Choose--</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
        </div>
        
        <p class="panel-heading">Address Details</p>
        <div class="panel-own">
            <div class="form-grp">
                <label for="City" class="form-label-own">City</label>
                <input type="text" name="city" class="form-input" value="<?php echo (isset($_POST['city']) ? $_POST['city'] : ''); ?>" required>
            </div>
            <div class="form-grp">
                <label for="State" class="form-label-own">State</label>
                <input type="text" name="state" class="form-input" value="<?php echo (isset($_POST['state']) ? $_POST['state'] : ''); ?>" required>
            </div>
            <div class="form-grp">
                <label for="Country" class="form-label-own">Country</label>
                <input type="text" name="country" class="form-input" value="<?php echo (isset($_POST['country']) ? $_POST['country'] : ''); ?>" required>
            </div>
            <div class="form-grp">
                <label for="Pin Code" class="form-label-own">Pin Code</label>
                <input type="number" name="pin_code" class="form-input" maxlength="6" value="<?php echo (isset($_POST['pin_code']) ? $_POST['pin_code'] : ''); ?>" required>
            </div>
        </div>

        <p class="panel-heading">Qualification Details</p>
        <div class="panel-own">
            <div class="form-grp">
                <label for="Qualification" class="form-label-own">Qualification</label>
                <input type="text" name="qualification" class="form-input" value="<?php echo (isset($_POST['qualification']) ? $_POST['qualification'] : ''); ?>" required>
            </div>
            <div class="form-grp">
                <label for="Work Experience" class="form-label-own">Work Experience</label>
                <input type="number" name="work_exp" class="form-input" value="<?php echo (isset($_POST['work_exp']) ? $_POST['work_exp'] : ''); ?>" required>
            </div>
        </div>

        <input type="submit" value="Register" class="btn-full-width">
    </form>
    
</body>
</html>