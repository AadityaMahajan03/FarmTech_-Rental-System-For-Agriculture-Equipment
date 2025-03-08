<?php

$servername = getenv("hopper.proxy.rlwy.net");  // Railway MySQL host from environment variable
$username = getenv("root");  // Railway MySQL username
$password = getenv("vaGeGgncyJbKewohxvNkQFgNbBIZLmDB");  // Railway MySQL password
$db = getenv("railway");  // Railway database name
$port = getenv("54108");  // Railway MySQL port

// Create connection
$con = mysqli_connect($servername, $username, $password, $db, $port);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Connected successfully!";
}

?>
