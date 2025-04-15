<?php
session_start();
$ip_add = getenv("REMOTE_ADDR");
include "db.php";
if(isset($_POST["category"])){
	$category_query = "SELECT * FROM categories";
	$run_query = mysqli_query($con,$category_query) or die(mysqli_error($con));
	echo "
		<div class='nav nav-pills nav-stacked'>
			<li class='active'><a href='#'><h4>Categories</h4></a></li>
	";
	if(mysqli_num_rows($run_query) > 0){
		while($row = mysqli_fetch_array($run_query)){
			$cid = $row["cat_id"];
			$cat_name = $row["cat_title"];
			echo "
					<li><a href='#' class='category' cid='$cid'>$cat_name</a></li>
			";
		}
		echo "</div>";
	}
}
if(isset($_POST["brand"])){
	$brand_query = "SELECT * FROM brands";
	$run_query = mysqli_query($con,$brand_query);
	echo "
		<div class='nav nav-pills nav-stacked'>
			<li class='active'><a href='#'><h4>Brands</h4></a></li>
	";
	if(mysqli_num_rows($run_query) > 0){
		while($row = mysqli_fetch_array($run_query)){
			$bid = $row["brand_id"];
			$brand_name = $row["brand_title"];
			echo "
					<li><a href='#' class='selectBrand' bid='$bid'>$brand_name</a></li>
			";
		}
		echo "</div>";
	}
}
if(isset($_POST["page"])){
	$sql = "SELECT * FROM products";
	$run_query = mysqli_query($con,$sql);
	$count = mysqli_num_rows($run_query);
	$pageno = ceil($count/9);
	for($i=1;$i<=$pageno;$i++){
		echo "
			<li><a href='#' page='$i' id='page'>$i</a></li>
		";
	}
}

if (isset($_POST["getProduct"])) {
    $limit = 9;
    $start = 0;

    if (isset($_POST["setPage"]) && isset($_POST["pageNumber"])) {
        $pageno = intval($_POST["pageNumber"]);
        $start = ($pageno * $limit) - $limit;
    }

    include("db.php");
    $product_query = "SELECT * FROM products LIMIT $start, $limit";
    $run_query = mysqli_query($con, $product_query);
	$product_video = isset($_POST["product_video"]) ? mysqli_real_escape_string($con, $_POST["product_video"]) : "";


    while ($row = mysqli_fetch_array($run_query)) {
        $pro_id    = $row['product_id'];
        $pro_title = $row['product_title'];
        $video_url = $row['product_video'];
        $pro_qty   = $row['product_qty'];
        $pro_price = $row['product_price'];
        $pro_image = $row['product_image'];
        $pro_desc  = $row['product_desc'];

        $video_id = "";

        // Extract video ID if a valid YouTube URL is present
        if (!empty($video_url)) {
            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_url, $matches)) {
                $video_id = $matches[1];
            }
        }

        // Video embed code (only if available)
        $youtube_embed_code = "";
        if (!empty($video_id)) {
            $youtube_embed_code = "
            <div class='product-video' style='margin-top: 15px; text-align: center; display: none;'>
                <iframe width='100%' height='250' src='https://www.youtube.com/embed/$video_id' frameborder='0' allowfullscreen></iframe>
            </div>";
        }

        // Output the product card
        echo "
        <div class='col-md-4'>
            <div class='panel panel-info' style='padding: 15px; margin-bottom: 30px; border-radius: 10px;'>
                <div class='panel-heading' style='font-weight: bold; font-size: 16px; text-align: center;'>$pro_title</div>
                <div class='panel-body'>
                    <img src='product_images/$pro_image' class='img-responsive' style='width:100%; height:250px; border-radius: 8px;' />
                    <p style='margin-top: 10px; text-align: center; font-size: 14px;'>Total Quantity Available: <strong>$pro_qty</strong></p>
                </div>
                <div class='panel-footer' style='background-color: #f9f9f9; padding: 15px; text-align: center;'>
                    <h4>Rs. $pro_price.00</h4>
                    <button class='btn btn-info btn-xs showDescription' style='margin-top: 10px;' data-proid='$pro_id'>Description</button>
                    <div class='product-description' style='display: none; margin-top: 15px; padding: 10px; background-color: #fff; border: 1px solid #ddd; border-radius: 5px;'>
                        <p>$pro_desc</p>
                        $youtube_embed_code
                    </div>
                    <button pid='$pro_id' id='product' title='For Taking On Rent' class='btn btn-danger btn-xs' style='margin-top: 15px;'>Add To Cart</button>
                </div>
            </div>
        </div>";
    }
}


// Category, Brand, or Search Filtering
// Category, Brand, or Search Filtering
if (isset($_POST["get_seleted_Category"]) || isset($_POST["selectBrand"]) || isset($_POST["search"])) {
    include("db.php");
	$product_video = isset($_POST["product_video"]) ? mysqli_real_escape_string($con, $_POST["product_video"]) : "";

    if (isset($_POST["get_seleted_Category"]) && !empty($_POST["cat_id"])) {
        $id = mysqli_real_escape_string($con, $_POST["cat_id"]);
        $sql = "SELECT * FROM products WHERE product_cat = '$id'";
    } elseif (isset($_POST["selectBrand"]) && !empty($_POST["brand_id"])) {
        $id = mysqli_real_escape_string($con, $_POST["brand_id"]);
        $sql = "SELECT * FROM products WHERE product_brand = '$id'";
    } elseif (isset($_POST["search"]) && !empty($_POST["keyword"])) {
        $keyword = mysqli_real_escape_string($con, $_POST["keyword"]);
        $sql = "SELECT * FROM products WHERE product_keywords LIKE '%$keyword%'";
    } else {
        exit("Invalid input!");
    }

    $run_query = mysqli_query($con, $sql);

    while ($row = mysqli_fetch_array($run_query)) {
        $pro_id    = $row['product_id'];
        $pro_title = $row['product_title'];
        $video_url = $row['product_video'];
        $pro_qty   = $row['product_qty'];
        $pro_price = $row['product_price'];
        $pro_image = $row['product_image'];
        $pro_desc  = $row['product_desc'];

        $video_id = "";

        if (!empty($video_url)) {
            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_url, $matches)) {
                $video_id = $matches[1];
            }
        }

        $youtube_embed_code = "";
        if (!empty($video_id)) {
            $youtube_embed_code = "
            <div class='product-video' style='margin-top: 10px; text-align: center; display: none;'>
                <iframe width='100%' height='250' src='https://www.youtube.com/embed/$video_id' frameborder='0' allowfullscreen></iframe>
            </div>";
        }

        echo "
        <div class='col-md-4'>
            <div class='panel panel-info' style='padding: 15px; margin-bottom: 30px; border-radius: 10px;'>
                <div class='panel-heading' style='font-weight: bold; font-size: 16px; text-align: center;'>$pro_title</div>
                <div class='panel-body'>
                    <img src='product_images/$pro_image' class='img-responsive' style='width:100%; height:250px; border-radius: 8px;' />
                    <p style='margin-top: 10px; text-align: center; font-size: 14px;'>Total Quantity Available: <strong>$pro_qty</strong></p>
                </div>
                <div class='panel-footer' style='background-color: #f9f9f9; padding: 15px; text-align: center;'>
                    <h4>Rs. $pro_price.00</h4>
                    
                    <button class='btn btn-info btn-xs showDescription' style='margin-top: 10px;'>Description</button>
                    
                    $youtube_embed_code
                    
                    <div class='product-description' style='display: none; margin-top: 10px; padding: 10px; background-color: #fff; border: 1px solid #ddd; border-radius: 5px;'>
                        <p>$pro_desc</p>
                    </div>
                    
                    <button pid='$pro_id' id='product' title='For Taking On Rent' class='btn btn-danger btn-xs' style='margin-top: 15px;'>Add To Cart</button>
                </div>
            </div>
        </div>";
    
    }
}



	


	if(isset($_POST["addToCart"])){
        
        

		$p_id = $_POST["proId"];
		

		if(isset($_SESSION["uid"])){

		$user_id = $_SESSION["uid"];

		$sql = "SELECT * FROM cart WHERE p_id = '$p_id' AND user_id = '$user_id'";
		$run_query = mysqli_query($con,$sql);
		$count = mysqli_num_rows($run_query);
		if($count > 0){
			echo "
				<div class='alert alert-warning'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Product is already added into the cart Continue Shopping..!</b>
				</div>
			";//not in video
		} else {
			
			$sql = "INSERT INTO `cart`
			(`p_id`, `ip_add`, `user_id`, `qty`) 
			VALUES ('$p_id','$ip_add','$user_id','1')";
			if(mysqli_query($con,$sql)){
				echo "
					<div class='alert alert-success'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Product is Added..!</b>
					</div>
				";
			}
		}
		}else{
			$sql = "SELECT id FROM cart WHERE ip_add = '$ip_add' AND p_id = '$p_id' AND user_id = -1";
			$query = mysqli_query($con,$sql);
			if (mysqli_num_rows($query) > 0) {
				echo "
					<div class='alert alert-warning'>
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Product is already added into the cart Continue Shopping..!</b>
					</div>";
					exit();
			}
			$sql = "INSERT INTO `cart`
			(`p_id`, `ip_add`, `user_id`, `qty`) 
			VALUES ('$p_id','$ip_add','-1','1')";
			if (mysqli_query($con,$sql)) {
				echo "
					<div class='alert alert-success'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Your product is Added Successfully..!</b>
					</div>
				";
				exit();
			}
			
		}
		
		
		
		
	}

//Count User cart item
if (isset($_POST["count_item"])) {
	//When user is logged in then we will count number of item in cart by using user session id
	if (isset($_SESSION["uid"])) {
		$sql = "SELECT COUNT(*) AS count_item FROM cart WHERE user_id = $_SESSION[uid]";
	}else{
		//When user is not logged in then we will count number of item in cart by using users unique ip address
		$sql = "SELECT COUNT(*) AS count_item FROM cart WHERE ip_add = '$ip_add' AND user_id < 0";
	}
	
	$query = mysqli_query($con,$sql);
	$row = mysqli_fetch_array($query);
	echo $row["count_item"];
	exit();
}
//Count User cart item

//Get Cart Item From Database to Dropdown menu
if (isset($_POST["Common"])) {

	if (isset($_SESSION["uid"])) {
		//When user is logged in this query will execute
		$sql = "SELECT a.product_id,a.product_title,a.product_price,a.product_image,b.id,b.qty FROM products a,cart b WHERE a.product_id=b.p_id AND b.user_id='$_SESSION[uid]'";
	}else{
		//When user is not logged in this query will execute
		$sql = "SELECT a.product_id,a.product_title,a.product_price,a.product_image,b.id,b.qty FROM products a,cart b WHERE a.product_id=b.p_id AND b.ip_add='$ip_add' AND b.user_id < 0";
	}
	$query = mysqli_query($con,$sql);
	if (isset($_POST["getCartItem"])) {
		//display cart item in dropdown menu
		if (mysqli_num_rows($query) > 0) {
			$n=0;
			while ($row=mysqli_fetch_array($query)) {
				$n++;
				$product_id = $row["product_id"];
				$product_title = $row["product_title"];
				$product_price = $row["product_price"];
				$product_image = $row["product_image"];
				$cart_item_id = $row["id"];
				$qty = $row["qty"];
				echo '
					<div class="row">
						<div class="col-md-3">'.$n.'</div>
						<div class="col-md-3"><img class="img-responsive" src="product_images/'.$product_image.'" /></div>
						<div class="col-md-3">'.$product_title.'</div>
						<div class="col-md-3">Rs '.$product_price.'</div>
					</div>';
				
			}
			?>
				<a style="float:right;" href="cart.php" class="btn btn-warning">Go To Cart&nbsp;&nbsp;<span class="glyphicon glyphicon-edit"></span></a>
			<?php
			exit();
		}
	}
	
	if (isset($_POST["checkOutDetails"])) {
		if (mysqli_num_rows($query) > 0) {
			//display user cart item with "Ready to checkout" button if user is not login
			echo "<form method='post' action='login_form.php'>";
				$n=0;
				$final=0;
				$deposit_amount = 0; // Initialize before the loop

				while ($row=mysqli_fetch_array($query)) {
					$n++;
					$product_id = $row["product_id"];
					$product_title = $row["product_title"];
					$product_price = $row["product_price"];
					$product_image = $row["product_image"];
					$cart_item_id = $row["id"];
					$qty = $row["qty"];
					
					echo 
						'<div class="row">
								<div class="col-md-2">
									<div class="btn-group">
										<a href="#" remove_id="'.$product_id.'" class="btn btn-danger remove"><span class="glyphicon glyphicon-trash"></span></a>
										<a href="#" update_id="'.$product_id.'" class="btn btn-primary update"><span class="glyphicon glyphicon-ok-sign"></span></a>
									</div>
								</div>
								<input type="hidden" name="product_id[]" value="'.$product_id.'"/>
								<input type="hidden" name="" value="'.$cart_item_id.'"/>
								<div class="col-md-2"><img class="img-responsive" src="product_images/'.$product_image.'"></div>
								<div class="col-md-2">'.$product_title.'</div>
								<div class="col-md-2"><input type="text" name="quantity" class="form-control qty" value="'.$qty.'" ></div>
								<div class="col-md-2"><input type="text" class="form-control price" value="'.$product_price.'" readonly="readonly"></div>
								<div class="col-md-2"><input type="text" class="form-control total" value="'.$product_price.'" readonly="readonly"></div>
							</div>';
							
							$final += $product_price * $qty;
							$deposit_amount += $product_price * $qty;
							$finalcost = $final*2;
							$_SESSION['finalcost'] = $finalcost;	
				}
				echo '<div class="row">
							<div class="col-md-8"></div>
							<div class="col-md-4">
								<b class="" style="font-size:20px"  > </b>
					</div>';
			
					 if (!isset($_SESSION["uid"])) {
					 	// User is not logged in, show login button
					 	echo '<form method="post">
					 			<input type="submit" style="float:right;" name="login_user_with_product" class="btn btn-info btn-lg" title="Login First To Take On Rent" value="Login First">
					 		  </form>';
					 } else {
					 	// User is logged in, show checkout button
					 	echo '<div style="text-align: center; margin-top: 20px;">
       					 <a style="padding: 10px 20px; font-size: 18px; display: inline-block;" 
      				     name="submit_ch" class="btn btn-info btn-lg" title="Pay/Checkout" href="cart_process.php">
       					    Take On Rent
      					  </a>
    					</div>';

					}
			// Calculate deposit and final cost
			
			
						// Display amounts properly aligned

						echo '<div style="display: flex; justify-content: center; margin-top: 20px;">
        <div style="text-align: center; padding: 15px; border: 1px solid #ccc; border-radius: 8px; background-color: #f9f9f9; width: 50%; max-width: 400px;">
            <p class="net_total" style="margin-bottom: 10px;"><strong>Total Product Cost: Rs ' . $final . '</strong></p>
            <p class="deposit_amount" style="font-size: 16px; margin: 5px 0;">Deposit Amount: <strong>Rs ' . $deposit_amount . '</strong></p>
            <h4 class="total_cost" style="font-size: 16px; margin: 5px 0;"><strong>Total Cost (including deposit): Rs ' . $finalcost . '</strong></h4>
        </div>
      </div>';


					 		
					}
					
					
			}
	}
//Remove Item From cart
if (isset($_POST["removeItemFromCart"])) {
	$remove_id = $_POST["rid"];
	if (isset($_SESSION["uid"])) {
		$sql = "DELETE FROM cart WHERE p_id = '$remove_id' AND user_id = '$_SESSION[uid]'";
	}else{
		$sql = "DELETE FROM cart WHERE p_id = '$remove_id' AND ip_add = '$ip_add'";
	}
	if(mysqli_query($con,$sql)){
		echo "<div class='alert alert-danger'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Product is removed from cart</b>
				</div>";
		exit();
	}
}


//Update Item From cart
if (isset($_POST["updateCartItem"])) {
    session_start(); // Ensure session is started
    include("db.php"); // Include database connection if not already included

    $update_id = $_POST["update_id"];
    $qty = $_POST["qty"];
    $ip_add = $_SERVER['REMOTE_ADDR']; // Ensure IP is set if using guest cart

    if (isset($_SESSION["uid"])) {
        $sql = "UPDATE cart SET qty='$qty' WHERE p_id = '$update_id' AND user_id = '$_SESSION[uid]'";
    } else {
        $sql = "UPDATE cart SET qty='$qty' WHERE p_id = '$update_id' AND ip_add = '$ip_add'";
    }

    if (mysqli_query($con, $sql)) {
        // Corrected query to check both user_id and ip_add
        if (isset($_SESSION["uid"])) {
            $query = mysqli_query($con, "SELECT SUM(product_price * qty) AS total FROM cart WHERE user_id = '$_SESSION[uid]'");
        } else {
            $query = mysqli_query($con, "SELECT SUM(product_price * qty) AS total FROM cart WHERE ip_add = '$ip_add'");
        }

        $row = mysqli_fetch_assoc($query);
        $final = $row['total'] ?? 0; // Ensure a default value if no rows exist
        $deposit_amount = $final;
        $_SESSION['finalcost'] = $final; // Update session

        session_write_close(); // Save session update

        // Return the updated totals as JSON
        echo json_encode([
            "final" => $final,
            "deposit_amount" => $deposit_amount,
            "total_cost" => $_SESSION['finalcost']
        ]);
        exit();
    } else {
        echo json_encode(["error" => "Failed to update cart"]);
        exit();
    }
}



?>
