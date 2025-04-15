<?php
session_start();

// Set headers for JSON response
header('Content-Type: application/json');

// Check if user is logged in
if(!isset($_SESSION['uid']) || empty($_SESSION['uid'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'You must be logged in to save an address'
    ]);
    exit;
}

// Get user ID from session
$user_id = $_SESSION['uid'];

// Check if address and mobile were provided
if(!isset($_POST['address']) || empty($_POST['address']) || !isset($_POST['mobile']) || empty($_POST['mobile'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Address and mobile number are required'
    ]);
    exit;
}

// Database credentials
$servername = "switchyard.proxy.rlwy.net";
$username = "root";
$password = "iiWvcKjlQKaDmOdkrHZnqLfDolwnHyQS";
$db = "railway";
$port = 12601;

// Create connection
$con = mysqli_connect($servername, $username, $password, $db, $port);

// Check connection
if(mysqli_connect_errno()) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database connection failed: ' . mysqli_connect_error()
    ]);
    exit;
}

// Sanitize input data
$address = mysqli_real_escape_string($con, $_POST['address']);
$mobile = mysqli_real_escape_string($con, $_POST['mobile']);

// Validate mobile number (basic validation)
if(!preg_match('/^[0-9]{10}$/', $mobile)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Please enter a valid 10-digit mobile number'
    ]);
    exit;
}

// Check if user exists in user_info table
$check_query = "SELECT * FROM user_info WHERE user_id = '$user_id'";
$check_result = mysqli_query($con, $check_query);

if($check_result && mysqli_num_rows($check_result) > 0) {
    // Update existing user info
    $update_query = "UPDATE user_info SET address1 = '$address', mobile = '$mobile' WHERE user_id = '$user_id'";
    $result = mysqli_query($con, $update_query);
} else {
    // Insert new user info
    $insert_query = "INSERT INTO user_info (user_id, address1, mobile) VALUES ('$user_id', '$address', '$mobile')";
    $result = mysqli_query($con, $insert_query);
}

// Check if query was successful
if($result) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Address saved successfully',
        'address' => $address,
        'mobile' => $mobile
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error saving address: ' . mysqli_error($con)
    ]);
}

// Close database connection
mysqli_close($con);
?>