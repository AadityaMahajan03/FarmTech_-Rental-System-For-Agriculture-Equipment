<?php
session_start();
if (!isset($_SESSION["uid"])) {
    header("location:index.php");
    exit();
}

include_once("db.php");

// Ensure database connection is established
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION["uid"];

// Fetch user's orders
$orders_list = "SELECT o.order_id, o.user_id, o.product_id, o.qty, o.trx_id, o.p_status, 
                        p.product_title, p.product_price, p.product_image 
                FROM orders o 
                INNER JOIN products p ON o.product_id = p.product_id 
                WHERE o.user_id='$user_id'";

$query = mysqli_query($con, $orders_list) or die("Error: " . mysqli_error($con));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FARMTECH - Orders</title>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <script src="js/jquery2.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="main.js"></script>
    <style>
        table tr td { padding: 10px; }
    </style>
</head>
<body>
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">    
            <div class="navbar-header">
                <a href="#" class="navbar-brand">FARMTECH</a>
            </div>
            <ul class="nav navbar-nav">
                <li><a href="index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                <li><a href="productview.php"><span class="glyphicon glyphicon-modal-window"></span> Equipments</a></li>
            </ul>
        </div>
    </div>

    <p><br/></p><p><br/></p><p><br/></p>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2>Customer Order Details</h2>
                    </div>
                    <div class="panel-body">
                        <hr/>
                        <?php
                        if (mysqli_num_rows($query) > 0) {
                            while ($row = mysqli_fetch_array($query)) {
                        ?>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <img style="float:right;" src="product_images/<?php echo $row['product_image']; ?>" 
                                             class="img-responsive img-thumbnail" width="200px"/>
                                    </div>
                                    <div class="col-md-6">
                                        <table>
                                            <tr><td><strong>Equipment Name:</strong></td><td><?php echo $row["product_title"]; ?></td></tr>
                                            <tr><td><strong>Equipment Cost:</strong></td><td><?php echo "Rs " . $row["product_price"]; ?></td></tr>
                                            <tr><td><strong>Hiring Time (in Hrs):</strong></td><td><?php echo $row["qty"]; ?></td></tr>
                                            <tr><td><strong>Transaction ID:</strong></td><td><?php echo $row["trx_id"]; ?></td></tr>
                                            <tr><td><strong>Payment Status:</strong></td><td><?php echo ucfirst($row["p_status"]); ?></td></tr>
                                        </table>
                                    </div>
                                </div>
                                <hr/>
                        <?php
                            }
                        } else {
                            echo "<h3 class='text-center text-danger'>No orders found!</h3>";
                        }
                        ?>
                    </div>
                    <div class="panel-footer text-center">
                        <a href="productview.php" class="btn btn-primary">Shop More</a>
                    </div>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
</body>
</html>
