<?php
// Database configuration
$host = "localhost"; // Host name 
$username = "appeecoc_op01"; // Mysql username 
$password = "qiMcUSjY%S9630IiE3eKR0p$%!58Z9pyjDu@7uBD"; // Mysql password 
$db_name = "appeecoc_K01"; // Database name 

// Create a new database connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// echo "Connected successfully";
?>
