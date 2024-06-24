<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = ''; // Leave empty if no password set for MySQL
$db_name = 'school_database'; // Replace with your database name

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

