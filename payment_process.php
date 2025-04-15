<?php 
session_start(); 
include_once("db.php");

// Check if the user is logged in 
if (!isset($_SESSION["uid"])) {
    header("Location: index.php");
    exit(); 
}

$user_id = $_SESSION["uid"]; 
$trx_id = uniqid("TRX_"); 
$p_status = "completed";
$order_date = date("Y-m-d H:i:s"); // Add current date and time

// Fetch user's full name, mobile number, and address from the database
$user_query = "SELECT first_name, last_name, mobile, address1 FROM user_info WHERE user_id = ?";
$stmt = mysqli_prepare($con, $user_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$user_result = mysqli_stmt_get_result($stmt);

if ($user_row = mysqli_fetch_assoc($user_result)) {
    $cust_name = $user_row['first_name'] . " " . $user_row['last_name'];
    $cust_num = $user_row['mobile'];
    $cust_address = $user_row['address1']; // Get address from database
} else {
    // Use session name as fallback if database query fails
    $cust_name = $_SESSION["name"];
    $cust_num = ""; // Default empty if not available
    $cust_address = ""; // Default empty address
}

// Fetch items from the cart
$cart_query = "SELECT p_id, qty FROM cart WHERE user_id = ?";
$stmt = mysqli_prepare($con, $cart_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$cart_result = mysqli_stmt_get_result($stmt);

// Check if the cart is empty
if (mysqli_num_rows($cart_result) == 0) {
    echo "Cart is empty. Please add items before checkout.";
    echo "<br><a href='index.php'>Return to Home</a>";
    exit();
}

// Store cart items in an array before processing
$cart_items = array();
while ($cart_row = mysqli_fetch_assoc($cart_result)) {
    $cart_items[] = $cart_row;
}

// Begin transaction
mysqli_begin_transaction($con);

try {
    // Modify the SQL query to include address and order_date
    $order_query = "INSERT INTO orders (user_id, product_id, qty, trx_id, p_status, cust_name, cust_num, order_address, order_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $order_query);
    
    // Loop through stored cart items
    foreach ($cart_items as $cart_item) {
        $product_id = $cart_item["p_id"];
        $qty = $cart_item["qty"];
        
        // Corrected bind_param with 9 parameters
        mysqli_stmt_bind_param($stmt, "iiissssss", $user_id, $product_id, $qty, $trx_id, $p_status, $cust_name, $cust_num, $cust_address, $order_date);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error inserting order: " . mysqli_stmt_error($stmt));
        }
    }
    
    // Clear the cart
    $clear_cart_query = "DELETE FROM cart WHERE user_id = ?";
    $stmt = mysqli_prepare($con, $clear_cart_query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Error clearing cart: " . mysqli_stmt_error($stmt));
    }
    
    // Commit transaction
    mysqli_commit($con);
    
    // Redirect to payment success page with transaction ID
    header("Location: payment_success.php?trx_id=$trx_id");
    exit();
    
} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($con);
    echo "Transaction failed: " . $e->getMessage();
    echo "<br><a href='cart.php'>Return to Cart</a>";
    exit();
}
?>