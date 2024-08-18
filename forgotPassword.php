<?php

$success = false;

include 'partials/_dbconnect.php';
if($_SERVER['REQUEST_METHOD']=="POST"){
    $user = $_POST['user'];
    $id = $_POST['id'];
    $email = $_POST['email'];
    
    if($user == "staff"){
        $query = "SELECT * from `staff` where `staff_id` = '$id' and `email` = '$email'";
        $result = mysqli_query($conn, $query);
    }
    elseif($user == "student"){
        $query = "SELECT * from `student` where `stud_id` = '$id' and `email` = '$email'";
        $result = mysqli_query($conn, $query);
    }
    
    if(mysqli_num_rows($result)==0){
        $showAlert = "<strong>Sorry!</strong> Wrong credentials";
    }
    else{
        // new password generation
        $combination = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); 
        $combinationLen = strlen($combination) - 1; 
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $combinationLen);
            $pass[] = $combination[$n];
        }
        $newPass = implode($pass);
        // $newPass = "abcd"; // demo purpose
        $passhash = password_hash($newPass, PASSWORD_DEFAULT);

        // setting the query based on the user (staff/student)
        if($user == "staff"){
            $queryUpdate = "UPDATE `staff_login` SET `password` = '$passhash' WHERE `staff_id` = $id";
        }
        else{
            $queryUpdate = "UPDATE `stud_login` SET `password` = '$passhash' WHERE `stud_id` = $id";
        }

        // code to send email for successful password reset
        $user1 = ucfirst($user);
        $data = [
            'email' => $email,
            'subject' => "Your Password Reset is Successful",
            'message' => "Hey!<br>You've successfully reset your password for E-College<br>Your new login credentials are:<br>$user1 ID: <strong>$id</strong><br>Password: <strong>$newPass<strong>"
        ];
        $postdata = http_build_query($data);
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, 'http://localhost/e-college/partials/smtp/index.php');
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        if(curl_exec($ch) && mysqli_query($conn, $queryUpdate)){
            $success = true;
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
    <title>Forgot Password</title>
    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css"/>
    <link rel="icon" type="image/png" href="/e-college/partials/logo.png">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <!-- Importing Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Marcellus+SC&family=Mitr:wght@300&family=Outfit:wght@300;500&display=swap" rel="stylesheet"> 

    <style>
        *{
            transition: 0.25s;
        }
        body{
            margin: 0;
            padding: 0;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            width: 100vw;
        }
        .panel{
            width: 380px;
            height: 520px;
            background-color: #D3E0EA;
            border-radius: 14px;
            box-shadow: 2px 2px 3px gray;
            font-size: 16pt;
            top: 50%;
            left: 50%;
            position: absolute;
            transform: translate(-50%,-50%);
            box-sizing: border-box;
            padding: 100px 30px;
        }

        .content-light{
            margin: 0;
            font-family: 'outfit';
            font-size: 14pt;
            font-weight: 300;
            color: #000;
        }

        img{
            margin-top: -70px;
            height: 120px;
            width: 120px;
        }

        .panel-heading{
            font-family: "Marcellus";
            font-size: 22pt;
            color: #000;
        }
        .btn-normal{
            margin: 10px 0;
            padding: 5px 80px;
            width: 100%;
            background-color: #005E85;
            border: none;
            border-radius: 10px;
            font-family: "mitr";
            font-size: 16pt;
            color: #fff;
            margin-top: 8px;
        }
        .btn-normal:hover{
            background-color: #0079ad;
            color: #fff;
        }
        .btn-normal:focus{
            color: #fff;
        }
        .btn-normal:active{
            background-color: #0079ad;
        }
        .form-control{
            border: 1px solid #888;
        }
    </style>

</head>
<body>

    <?php
        if($success){
    ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success! </strong>Your password reset mail has been sent to your registered Email ID
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
    <?php
        }
      
        if(isset($showAlert)){
    ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $showAlert; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
    <?php
        }
    ?>

    <!-- panel  -->
    <div class="panel panel-default">
        <div class="panel-body">
            <center><img src="partials/logo.png" alt=""></center>
            <center><div class="panel-heading">Forgot Password</div></center>
            
            <!-- form  -->
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="form-group">
                    <label class="content-light">You are</label>
                    <select name="user" class="form-control" required>
                        <option value="" disabled selected>--Choose--</option>
                        <option value="staff">Staff</option>
                        <option value="student">Student</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="content-light">ID</label>
                    <input type="text" class="form-control" name="id" required>
                </div>
                <div class="form-group">
                    <label class="content-light">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                
                <button type="submit" class="btn btn-normal">Reset Password</button>
            </form>
        </div>
    </div>
    
</body>
</html>