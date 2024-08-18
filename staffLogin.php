<!-- 
  Note:
  1- 
  2*- After successful validation of login, run a SQL query on 'student' table and fetch all the details of the student who is logging in and store them inside $stud_details variable using mysqli_fetch_assoc()
-->

<?php
include 'partials/_dbconnect.php';
session_start();

if(isset($_SESSION['end_user'])){
  if($_SESSION['end_user']=="staff"){
    header("Location: staff/index");
  }
  elseif($_SESSION['end_user']=="student"){
    header("Location: index");
  }
  elseif($_SESSION['end_user']=="admin"){
    header("Location: index");
  }
}
else{
  $success = true;
  if($_SERVER['REQUEST_METHOD'] == "POST"){

    $staffID = $_POST['staffID'];
    $password = $_POST['password'];

    $query = "SELECT * from `staff_login` where `staff_id`='$staffID'";
    $result = mysqli_query($conn, $query);
    $num = mysqli_num_rows($result);


    if($num==1){
        $row = mysqli_fetch_assoc($result);
        if(password_verify($password, $row['password'])){
          if($row['logged']>0){
            // if already a session is there
          }
          elseif($row['activation']!=1){
            // if account is active or not
            $showAlert = "Your Account has been disabled";
          }
          else{
            $_SESSION['staff_id'] = $staffID;
            $_SESSION['end_user'] = "staff";
            header("Location: staff/index");
          }
        }
        else{
            $success = false;
        }
    }
    else{
        $success = false;
    }
    
}
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Staff Login</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css"/>
    <link rel="icon" type="image/png" href="/e-college/partials/logo.png">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

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
      .link{
          font-family: 'outfit';
          font-size: 14pt;
          color: #005E85;
          font-weight: 300;
      }
      .link:hover{
          text-decoration: none;
          color: #0079ad;
      }
      .link:focus{
        text-decoration: none;
      }
      .form-control{
        border: 1px solid #888;
      }
      .eye-symbol{
        float: right;
        margin-right: 6px;
        margin-top: -40px;
        position: relative;
        z-index: 2;
      }
    </style>
  </head>

  <body>
    <?php 
    if(!$success){ 
    ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Sorry</strong> Wrong Credentials!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
    <?php 
    }
    ?>
    
    <?php 
    if(isset($showAlert)){ 
    ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong><?php echo $showAlert; ?></strong>
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
            <center><div class="panel-heading">Staff Login</div></center>
            
          <!-- form  -->
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-group">
              <label class="content-light">Staff ID</label>
              <input type="text" class="form-control" name="staffID" id="staffID" value="<?php echo (isset($_POST['staffID']) ? $_POST['staffID'] : ''); ?>" required>
            </div>
            <div class="form-group">
              <label class="content-light">Password</label>
              <input type="password" class="form-control" name="password" id="password" value="<?php echo (isset($_POST['password']) ? $_POST['password'] : ''); ?>" required>
              <i class="bi bi-eye-slash eye-symbol" id="togglePassword"></i>
            </div>
            
            <div class="checkbox">
              <label>
                <input type="checkbox"><span class="content-light"> Remember me</span>
              </label>
            </div>
            <button type="submit" class="btn btn-normal">Login</button>
            <div> <a href="forgotPassword" class="link">Forgot Password?</a> </div>
          </form>
        </div>
      </div>
      
      <script>
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password");

        togglePassword.addEventListener("click", function () {
            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            
            // toggle the icon
            this.classList.toggle("bi-eye");
        });

    </script>
      
    

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
  </body>
</html>



















