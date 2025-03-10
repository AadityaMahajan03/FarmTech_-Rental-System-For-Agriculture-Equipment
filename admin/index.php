<?php 
session_start();  

if (!isset($_SESSION['admin_id'])) {
    header("location: login.php");
    exit();
}

include "./templates/top.php"; 
include "./templates/navbar.php"; 
include "./templates/sidebar.php"; 
?>
<!-- Main content of index.php continues here -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmTech Rental System</title>
</head>
<body>

<?php include "./templates/navbar.php"; ?>

<div class="container-fluid">
  <div class="row">
    
    <?php include "./templates/sidebar.php"; ?>

    <h2>Other Admins</h2>
    <div class="table-responsive">
      <table class="table table-striped table-sm">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="admin_list">
          <tr>
            <td>1,001</td>
            <td>Lorem</td>
            <td>ipsum</td>
            <td>dolor</td>
            <td>sit</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include "./templates/footer.php"; ?>

<script type="text/javascript" src="./js/admin.js"></script>

</body>
</html>
