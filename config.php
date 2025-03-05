<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "tata_excel";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
