<?php
session_start();

// check whether user is student
if($_SESSION['end_user']!="student"){
    header("Location: /e-college/index");
    exit;
}

include '../partials/_nav.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Practical</title>

    <link rel="stylesheet" href="/e-college/partials/ide/ui/css/style.css" />
</head>
<body class="body-own">

    <div class="control-panel">
        <!-- <div class="language-container"> -->
            <label for="" class="form-label-own">Select Language:</label>
            &nbsp; &nbsp;
            <select id="languages" class="form-input languages" onchange="changeLanguage()">
                <option value="c"> C </option>
                <option value="cpp"> C++ </option>
                <option value="php"> PHP </option>
                <option value="python"> Python </option>
                <option value="node"> Node JS </option>
            </select>
        <!-- </div> -->
        <!-- <div class="button-container"> -->
            <button class="btn-normal" onclick="executeCode()"> Run </button>
        <!-- </div> -->
    </div>
    

    <div class="editor" id="editor"></div>

    <div class="output"></div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="/e-college/partials/ide/ui/js/lib/ace.js"></script>
    <script src="/e-college/partials/ide/ui/js/lib/theme-monokai.js"></script>
    <script src="/e-college/partials/ide/ui/js/ide.js"></script>

</body>
</html>