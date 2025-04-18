<?php session_start(); ?>
<?php include_once("./templates/top.php"); ?>
<?php include_once("./templates/navbar.php"); ?>
<div class="container-fluid">
  <div class="row">
    <?php include "./templates/sidebar.php"; ?>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <div class="row">
        <div class="col-10">
          <h2>Orders</h2>
        </div>
      </div>
      
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>#</th>
              <th>Order #</th>
              <th>Equipment Id</th>
              <th>Equipment Name</th>
              <th>Hiring Time(in Hrs)</th>
              <th>Trx Id</th>
              <th>Payment Status</th>
              <th>Customer Name</th>
              <th>Customer Contact</th>
              <th>Customer Address</th>

            </tr>
          </thead>
          <tbody id="customer_order_list">
            <!-- Orders will be loaded here dynamically -->
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="add_product_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Equipment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-product-form" enctype="multipart/form-data">
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label>Equipment Name</label>
                <input type="text" name="product_name" class="form-control" placeholder="Enter Product Name">
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>Brand Name</label>
                <select class="form-control brand_list" name="brand_id">
                  <option value="">Select Brand</option>
                </select>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>Category Name</label>
                <select class="form-control category_list" name="category_id">
                  <option value="">Select Category</option>
                </select>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>Equipment Description</label>
                <textarea class="form-control" name="product_desc" placeholder="Enter product desc"></textarea>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>Equipment Cost in Rs(for 1 Hour)</label>
                <input type="number" name="product_price" class="form-control" placeholder="Enter Product Price">
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>Equipment Keywords <small>(eg: tractor, farming, heavy, dig)</small></label>
                <input type="text" name="product_keywords" class="form-control" placeholder="Enter Product Keywords">
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>Equipment Image <small>(format: jpg, jpeg, png)</small></label>
                <input type="file" name="product_image" class="form-control">
              </div>
            </div>
            <input type="hidden" name="add_product" value="1">
            <div class="col-12">
              <button type="button" class="btn btn-primary add-product">Add Equipment</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->

<?php include_once("./templates/footer.php"); ?>

<script type="text/javascript" src="./js/customers.js"></script>