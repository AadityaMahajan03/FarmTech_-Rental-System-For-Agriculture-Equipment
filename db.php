<?php
$servername = "switchyard.proxy.rlwy.net";
$username = "root";
$password = "iiWvcKjlQKaDmOdkrHZnqLfDolwnHyQS";
$db = "railway";
$port = 12601;

// Create connection
$con = mysqli_connect($servername, $username, $password, $db, $port);

// Check connection
if (!$con) {
    die("❌ Connection failed: " . mysqli_connect_error());
} else {
    // echo "✅ Connected to MySQL successfully!";
}
?>
