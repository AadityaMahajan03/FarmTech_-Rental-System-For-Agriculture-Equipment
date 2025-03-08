
<?php

$servername = "hopper.proxy.rlwy.net";  // Railway MySQL host
$username = "root";  // Railway MySQL username
$password = "vaGeGgncyJbKewohxvNkQFgNbBIZLmDB";  // Railway MySQL password
$db = "railway";  // Railway database name
$port = 54108;  // Railway MySQL port

// Create connection
$con = mysqli_connect($servername, $username, $password, $db, $port);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Connected successfully!";
}

?>
