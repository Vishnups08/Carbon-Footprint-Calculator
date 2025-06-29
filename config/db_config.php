<?php
// Database configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'carbon_calculator');

// Attempt to connect to MySQL database
$conn = mysqli_connect('localhost','root','','carbon_calculator');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>