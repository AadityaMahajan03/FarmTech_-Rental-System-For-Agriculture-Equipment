<?php 
session_start();

class Customers
{
    private $con;

    function __construct()
    {
        include_once("Database.php");
        $db = new Database();
        $this->con = $db->connect();
    }

    public function getCustomers() {
        $query = $this->con->query("SELECT `user_id`, `first_name`, `last_name`, `email`, `mobile`, `address1`, `address2` FROM `user_info`");
        $ar = [];

        if ($query && $query->num_rows > 0) {
            while ($row = $query->fetch_assoc()) {
                $ar[] = $row;
            }
            return ['status' => 202, 'message' => $ar];
        }
        return ['status' => 303, 'message' => 'no customer data'];
    }

    public function getCustomersOrder() {
        // Debug: Print the query to see what's being executed
        $query_string = "
            SELECT 
                o.order_id,  
                o.product_id,  
                o.qty, 
                o.trx_id, 
                o.p_status, 
                o.cust_name, 
                o.cust_num, 
                o.user_id,
                o.order_address, 
                p.product_title, 
                p.product_image
            FROM orders o 
            JOIN products p ON o.product_id = p.product_id
        ";
        
        // Log the query for debugging
        error_log("Executing query: " . $query_string);
        
        $query = $this->con->query($query_string);
        
        // Check for SQL errors
        if (!$query) {
            error_log("SQL Error: " . $this->con->error);
            return ['status' => 303, 'message' => 'Database error: ' . $this->con->error];
        }
        
        $orders = [];

        if ($query->num_rows > 0) {
            while ($row = $query->fetch_assoc()) {
                // Debug: Log each row
                error_log("Found order: " . json_encode($row));
                $orders[] = $row;
            }

            return ['status' => 202, 'message' => $orders];
        }
        
        error_log("No orders found in database");
        return ['status' => 303, 'message' => 'no orders yet'];
    }
}

// Handle AJAX requests
if (isset($_POST["GET_CUSTOMERS"])) {
    if (isset($_SESSION['admin_id'])) {
        $c = new Customers();
        echo json_encode($c->getCustomers());
        exit();
    }
}

if (isset($_POST["GET_CUSTOMER_ORDERS"])) {
    if (isset($_SESSION['admin_id'])) {
        $c = new Customers();
        echo json_encode($c->getCustomersOrder());
        exit();
    }
}
?>
