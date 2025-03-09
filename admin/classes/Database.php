<?php
// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Database credentials
$servername = "hopper.proxy.rlwy.net";  // Railway MySQL host
$username = "root";  // Railway MySQL username
$password = "vaGeGgncyJbKewohxvNkQFgNbBIZLmDB";  // Railway MySQL password
$db = "railway";  // Railway database name
$port = 54108;  // Railway MySQL port

// Create a connection
$con = new mysqli($servername, $username, $password, $db, $port);

// Check connection
if ($con->connect_error) {
    exit("Connection failed: " . $con->connect_error);
}

// Database Class
class Database {
    private $con;

    public function __construct() {
        global $servername, $username, $password, $db, $port;
        $this->con = new mysqli($servername, $username, $password, $db, $port);

        if ($this->con->connect_error) {
            exit("Connection failed: " . $this->con->connect_error);
        }
    }

    public function connect() {
        return $this->con;
    }
}
?>
