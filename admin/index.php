<?php
session_start();

// check whether user is admin
if($_SESSION['end_user']!="admin"){
    header("Location: /e-college/index");
    exit;
}

include '../partials/_nav.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/E-College/style.css">
    <title>Admin Dashboard</title>
    <style>
        .btn-full-width{
            padding: 10%;
            font-family: 'Outfit', sans-serif;
            font-size: 28px;
        }
    </style>
</head>
<center>
<body class="body-own">
    <div class="panel-own">
        <div class="row">
            <div class="col-sm-6">
                <button class="btn-full-width" onclick="window.location.href='studentRegistration';">Student Registration</button>
            </div>
            <div class="col-sm-6">
                <button class="btn-full-width" onclick="window.location.href='staffRegistration';">Staff Registration</button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <button class="btn-full-width" onclick="window.location.href='feesMgmt';">Fees Management</button>
            </div>
            <div class="col-sm-6">
                <button class="btn-full-width" onclick="window.location.href='postNotices';">Notices</button>
            </div>
        </div>
    </div>  
</body>
</center>
</html>