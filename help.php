<?php

include 'partials/_dbconnect.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help</title>

    <!-- Own CSS -->
    <link rel="stylesheet" href="/e-college/style.css">
    <link rel="icon" type="image/png" href="/e-college/partials/logo.png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        body {
            font-family: 'outfit'sans-serif;
        } 
        * {
            transition: 0.25s;
        }

        .body-own{
            margin-left: 0px;
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

        .panel-transparent{
            padding: 0;
            margin: 0px;
            height: 420px;
        }
        .panel-own{
        }
        .col-sm-4{
            padding: 20px;
        }
    </style>
    
</head>
<body class="body-own">
    
    <div class="header">
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-own" style="height: 80px;">
            <div class="container-fluid">
                <a class="navbar-brand" href="/e-college/">
                    <img src="/e-college/partials/logo.png" alt="logo" class="navbar-brand navbar-brand-logo">
                    <a class="navbar-brand" href="/e-college/index">E-College</a>
                </a>

                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                </div>
            </div>
        </nav>
    </div>
    
    <div class="panel-transparent">
        <div class="row">
            <div class="panel-transparent col-sm-4">
                <iframe width="100%" height="80%" src="https://www.youtube-nocookie.com/embed/k7LSk9hnJk" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <p class="content-bold">Title of the video</p>
            </div>
            <div class="panel-transparent col-sm-4">
                <iframe width="100%" height="80%" src="https://www.youtube-nocookie.com/embed/k7LSk9hnJk" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <p class="content-bold">Title of the video</p>
            </div>
            <div class="panel-transparent col-sm-4">
                <iframe width="100%" height="80%" src="https://www.youtube-nocookie.com/embed/k7LSk9hnJk" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <p class="content-bold">Title of the video</p>
            </div>
        </div>
    </div>
</body>
</html>