<?php
include '../partials/_dbconnect.php';

// check whether user is logged in
if(!isset($_SESSION['end_user'])){
    header("Location: /e-college/");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- For icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/png" href="/e-college/partials/logo.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Own CSS -->
    <link rel="stylesheet" href="/e-college/style.css">

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.css" rel="stylesheet" />

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.js"></script>

    <!-- Importing Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Marcellus+SC&family=Mitr:wght@300&family=Outfit:wght@300;500&display=swap" rel="stylesheet"> 
    
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    
    <!-- 
    CSS To apply fonts
        font-family: 'Marcellus', serif;

        font-family: 'Marcellus SC', serif;

        font-family: 'Mitr', sans-serif;

        font-family: 'Outfit', sans-serif;
    -->

    
    <style>

        body {
            font-family: 'outfit'sans-serif;
        } 
        * {
            transition: 0.25s;
        }

        .navbar-own {
            background-color: #005E85;
        }

        .navbar-brand {
            font-family: 'Marcellus SC', serif;
            font-size: 28pt;
        }

        .navbar-brand-logo {
            width: 60px;
            border-radius: 20px;
        }

        .dropdown-item{
            font-size: 12pt;
        }

        /* Fixed sidenav, full height */
        .sidenav {
            height: 100%;
            width: 280px;
            position: fixed;
            /* z-index: 1; */
            top: 0;
            left: 0;
            background-color: #D3E0EA;
            overflow-x: hidden;
            box-shadow: 2px 2px 3px gray;
            padding-top: 100px;
        }

        /* sidenav links and dropdown button */
        .sidenav button,
        .dropdown-btn {
            padding: 10px 8px;
            text-decoration: none;
            font-size: 18pt;
            color: black;
            display: block;
            border: none;
            background: none;
            width: 80%;
            text-align: left;
            cursor: pointer;
            outline: none;
            border-radius: 40px;
            margin-left: 19px;
            margin-bottom: 10px;
            padding-left: 12px;
        }

        /* On mouse-over */
        .sidenav button:hover,
        .dropdown-btn:hover {
            background-color: #005E85;
            color: #D3E0EA;
            width: 90%;
        }
        }

        /*  active class  */
        .active {
            /* background-color: #005E85;
            color: white;
            border-radius: 30px; */
        }

        /* Dropdown container  */
        .dropdown-container {
            display: none;
            background-color: #D3E0EA;
            padding-left: 20px;
        }
        .dropdown-container button{
            font-size: 14pt;
        }

        /* caret down icon */
        .fa-caret-down {
            float: right;
            padding-right: 5px;
        }

        .dropdown-btn:active{
            background-color: transparent;
            color: #000;
        }

        /*  media queries for responsiveness */
        @media screen and (max-height: 450px) {
            .sidenav {
                padding-top: 15px;
            }

            .sidenav button {
                font-size: 18px;
            }
        }
    </style>


</head>

<body>
    
    <?php
        
        // Getting the logged in user's name
        if($_SESSION['end_user']=="student"){
            $studentID = $_SESSION["stud_id"];
            $query = "SELECT * from student where `stud_id` = '$studentID'";
            $result = mysqli_query($conn, $query);
            $stud_details = mysqli_fetch_assoc($result);
            $fullname = $stud_details['f_name']. " ". $stud_details['l_name'];
        }
        elseif($_SESSION['end_user']=="staff"){
            $staffID = $_SESSION["staff_id"];
            $query = "SELECT * from staff where `staff_id` = '$staffID'";
            $result = mysqli_query($conn, $query);
            $staff_details = mysqli_fetch_assoc($result);
            $fullname = $staff_details['f_name']. " ". $staff_details['l_name'];
        }
        elseif($_SESSION['end_user']=="admin"){
            $adminID = $_SESSION["admin_id"];
            $fullname = "Admin";
        }
        
    ?>

    <div class="main_content">
        <div class="header">
            <nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-own" style="height: 80px;">
                <div class="container-fluid">
                    <a class="navbar-brand" href="/e-college/">
                        <img src="/e-college/partials/logo.png" alt="logo" class="navbar-brand navbar-brand-logo">
                        <a class="navbar-brand" href="/e-college/">E-College</a>
                    </a>

                    <div class="collapse navbar-collapse" id="navbarNavDropdown">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle navbar-studname" href="#" id="navbarDropdownMenuLink"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #fff;">
                                    <?php echo $fullname; ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end content-light" aria-labelledby="navbarDropdownMenuLink">
                                    <li><a class="dropdown-item" href="#">Mode Switch</a></li>
                                    <li><a class="dropdown-item" href="/e-college/logout.php">Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidenav navbar-studname">

    <?php
        if($_SESSION['end_user']=="admin"){
    ?>
        <button onclick="window.location.href='index';">Dashboard</button>

        <button class="dropdown-btn">Registration<i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-container">
            <hr>
            <button onclick="window.location.href='studentRegistration';">Student</button>
            <button onclick="window.location.href='staffRegistration';">Staff</button>
            <hr>
        </div>
        
        <button class="dropdown-btn">Manage<i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-container">
            <hr>
            <button onclick="window.location.href='manageStudent';">Student</button>
            <button onclick="window.location.href='manageStaff';">Staff</button>
            <button onclick="window.location.href='manageCourses';">Course</button>
            <button onclick="window.location.href='manageSubjects';">Subject</button>
            <button onclick="window.location.href='feesMgmt';">Fees</button>
            <hr>
        </div>
        <button onclick="window.location.href='postNotices';">Notices</button>
        <button onclick="window.location.href='/e-college/help';">Help</button>

    <?php
        }
        elseif($_SESSION['end_user']=="staff"){
    ?>
        <button onclick="window.location.href='index';">Dashboard</button>
        
        <button class="dropdown-btn">Lectures<i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-container">
            <hr>
            <button onclick="window.location.href='createLecture';">Today's Lectures</button>
            <button onclick="window.location.href='lectureHistory';">History</button>
            <hr>
        </div>

        <button onclick="window.location.href='provideNotes';">Notes</button>
        <button onclick="window.location.href='createTest';">Tests</button>
        <button onclick="window.location.href='postNotices';">Notices</button>
        <button onclick="window.location.href='/e-college/help';">Help</button>

    <?php
        }
        elseif($_SESSION['end_user']=="student"){
    ?>
        <button onclick="window.location.href='index';">Dashboard</button>
        <button onclick="window.location.href='myClass';">My Class</button>

        <button class="dropdown-btn">Learn Online<i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-container">
            <hr>
            <button onclick="window.location.href='todaysLecture';">Today's Lectures</button>
            <button onclick="window.location.href='lectureHistory';">History</button>
            <hr>
        </div>

        <button onclick="window.location.href='notes';">Notes</button>
        <button onclick="window.location.href='test';">Tests</button>
        <button onclick="window.location.href='practical';">Practical</button>
        <button onclick="window.location.href='payFees';">Fees</button>
        <button onclick="window.location.href='notices';">Notices</button>
        <button onclick="window.location.href='/e-college/help';">Help</button>
    <?php
        }
    ?>

    </div>

    <script>

        var dropdown = document.getElementsByClassName("dropdown-btn");
        var i;

        for (i = 0; i < dropdown.length; i++) {
            dropdown[i].addEventListener("click", function () {
                this.classList.toggle("active");
                var dropdownContent = this.nextElementSibling;
                if (dropdownContent.style.display === "block") {
                    dropdownContent.style.display = "none";
                } else {
                    dropdownContent.style.display = "block";
                }
            });
        }
    </script>
    


</body>

</html>