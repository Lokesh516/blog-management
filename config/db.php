<?php
$host = 'your_host'; 
$username = 'your_username'; 
$password = 'your_password'; 
$dbname = 'your_db';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
