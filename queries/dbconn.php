<?php
$servername = "localhost";
$username = "aytgrcom_counterstrike";
$password = "counterstrike1$";
$dbname = "aytgrcom_Counterstrike";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// echo "Connected successfully";
?>