<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "e_college";

$conn = mysqli_connect($servername, $username, $password, $database);

if(!$conn){
    die("Sorry connection failed!");
}

?>