<?php
// Start session if not already started
if(!isset($_SESSION)) {
    session_start();
}

// Assuming user is logged in and we have user_id in session
// Get current user info from database
$user_id = isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;


// If user is logged in, fetch their current address
$current_address = "";
$current_mobile = "";
if($user_id > 0) {
    // Connect to database
    $servername = "switchyard.proxy.rlwy.net";
$username = "root";
$password = "iiWvcKjlQKaDmOdkrHZnqLfDolwnHyQS";
$db = "railway";
$port = 12601;

// Create connection
$con = mysqli_connect($servername, $username, $password, $db, $port);

    
    // Get user information
    $query = "SELECT address1, mobile FROM user_info WHERE user_id = '$user_id'";
    $result = mysqli_query($con, $query);
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $current_address = $row['address1'];
        $current_mobile = $row['mobile'];
    }
    mysqli_close($con);
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>FARMTECH</title>
		<link rel="stylesheet" href="css/bootstrap.min.css"/>
		<script src="js/jquery2.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="main.js"></script>
		<link rel="stylesheet" type="text/css" href="style.css"/>
	</head>
<body>
<div class="wait overlay">
	<div class="loader"></div>
</div>
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">	
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapse" aria-expanded="false">
					<span class="sr-only">navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a href="index.php" class="navbar-brand">FARMTECH</a>
			</div>
		<div class="collapse navbar-collapse" id="collapse">
			<ul class="nav navbar-nav">
				<li><a href="index.php"><span class="glyphicon glyphicon-home"></span>Home</a></li>
				<li><a href="productview.php"><span class="glyphicon glyphicon-modal-window"></span>Equipments</a></li>
			</ul>
		</div>
	</div>
	</div>
	<p><br/></p>
	<p><br/></p>
	<p><br/></p>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8" id="cart_msg">
				<!--Cart Message--> 
			</div>
			<div class="col-md-2"></div>
		</div>
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<div class="panel panel-primary">
					<div class="panel-heading">Cart Checkout</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-2 col-xs-2"><b>Action</b></div>
							<div class="col-md-2 col-xs-2"><b>Equipment Image</b></div>
							<div class="col-md-2 col-xs-2"><b>Equipment Name</b></div>
							<div class="col-md-2 col-xs-2"><b>Hiring Time(in Hours)</b></div>
							<div class="col-md-2 col-xs-2"><b>Equipment Cost in Rs(for 1 Hour)</b></div>
							<div class="col-md-2 col-xs-2"><b>Cost in Rs</b></div>
						</div>
						<div id="cart_checkout"></div>
						<!--<div class="row">
							<div class="col-md-8"></div>
							<div class="col-md-4">
								<b>Total $500000</b>
							</div> -->
						</div>
					</div>
					
					<!-- Adding Address Selection Section -->
					<div class="panel-footer">
						<div class="row">
							<div class="col-md-12">
								<h4>Delivery Address</h4>
								<hr>
								<form id="address_form">
									<div class="form-group">
										<div class="radio">
											<label>
												<input type="radio" name="address_option" id="current_address" value="current" checked>
												Use your current address
											</label>
										</div>
										<div class="well" style="margin-top: 10px; margin-bottom: 15px; padding: 10px;">
											<!-- Displaying current address from database -->
											<p><strong>Current Address:</strong> <span id="current_address_display"><?php echo $current_address; ?></span></p>
											<p><strong>Mobile:</strong> <span id="current_mobile_display"><?php echo $current_mobile; ?></span></p>
										</div>
									</div>
									
									<div class="form-group">
										<div class="radio">
											<label>
												<input type="radio" name="address_option" id="new_address" value="new">
												Use a different address
											</label>
										</div>
										<div id="new_address_form" style="display:none; border: 1px solid #ddd; padding: 15px; margin-top: 10px; border-radius: 4px;">
											<div class="form-group">
												<label for="address">Address:</label>
												<textarea class="form-control" id="address" name="address" rows="3"></textarea>
											</div>
											<div class="form-group">
												<label for="mobile">Mobile Number:</label>
												<input type="text" class="form-control" id="mobile" name="mobile">
											</div>
											<button type="button" id="save_address" class="btn btn-primary">Save Address</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-2"></div>
		</div>
	</div>

	<script>
		$(document).ready(function() {
    // Show/hide new address form based on radio selection
    $('input[name="address_option"]').change(function() {
        if ($(this).val() === 'new') {
            $('#new_address_form').show();
        } else {
            $('#new_address_form').hide();
        }
    });
    
    // Save address button functionality
    $('#save_address').click(function() {
        var address = $('#address').val();
        var mobile = $('#mobile').val();
        
        // Basic validation
        if (!address || !mobile) {
            $('#cart_msg').html("<div class='alert alert-danger'>Please fill all address fields</div>");
            return;
        }
        
        // Format address data
        var addressData = {
            address: address,
            mobile: mobile
        };
        
        // Send address data to server
        $.ajax({
            url: 'save_address.php',
            method: 'POST',
            data: addressData,
            dataType: 'json',  // This tells jQuery to expect and parse JSON
            success: function(data) {
                // No need to parse the response, jQuery does it automatically
                if(data.status === 'success') {
                    $('#cart_msg').html("<div class='alert alert-success'>" + data.message + "</div>");
                    
                    // Update the current address display
                    $('#current_address_display').text(data.address);
                    $('#current_mobile_display').text(data.mobile);
                    
                    // Switch to current address option
                    $('#current_address').prop('checked', true);
                    $('#new_address_form').hide();
                } else {
                    $('#cart_msg').html("<div class='alert alert-danger'>" + data.message + "</div>");
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                $('#cart_msg').html("<div class='alert alert-danger'>Error saving address. Please try again.</div>");
            }
        });
    });
});
	</script>
</body>
</html>