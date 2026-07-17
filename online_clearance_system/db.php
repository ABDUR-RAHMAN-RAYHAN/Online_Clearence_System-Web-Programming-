<?php
$server = "localhost";
$user = "root";
$password = "";
$dbname = "online_clearance_system";

$conn = new mysqli($server, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>