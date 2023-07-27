<?php
require_once 'config/config.php';

   // Establish the database connection
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check the connection
if ($mysqli->connect_errno) {
    die("Database connection failed: " . $mysqli->connect_error);
}


?>
