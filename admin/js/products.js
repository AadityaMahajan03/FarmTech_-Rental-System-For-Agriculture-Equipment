$(document).ready(function() {
    var productList;

    // Improved error handling function
    function handleError(error, context) {
        console.error(`Error in ${context}:`, error);
        let errorMsg = "An error occurred. Please try again.";
        
        if (typeof error === 'string') {
            errorMsg = error;
        } else if (error.responseText) {
            try {
                const resp = JSON.parse(error.responseText);
                errorMsg = resp.message || errorMsg;
            } catch (e) {
                errorMsg = error.responseText;
            }
        }
        
        // Use a proper notification system in production instead of alert
        alert(errorMsg);
        return false;
    }

    // Improved product fetching with cache busting
    function getProducts() {
        $.ajax({
            url: '../admin/classes/Products.php',
            method: 'POST',
            data: { 
                GET_PRODUCT: 1,
                _: new Date().getTime() // Cache buster
            },
            dataType: 'json', // Explicitly expect JSON
            success: function(resp) {
                if (resp.status == 202) {
                    renderProducts(resp.message);
                } else {
                    handleError(resp.message || "Failed to load products", "getProducts");
                }
            },
            error: function(xhr, status, error) {
                handleError(xhr, "getProducts AJAX");
            }
        });
    }

    // Separate rendering logic for better maintainability
    function renderProducts(data) {
        try {
            productList = data.products;
            let productHTML = '';

            if (productList && productList.length) {
                productList.forEach(value => {
                    productHTML += `<tr>
                        <td>${value.product_id || ''}</td>
                        <td>${value.product_title || ''}</td>
                        <td><img width="60" height="60" src="../product_images/${value.product_image || 'default.jpg'}" alt="${value.product_title}"></td>
                        <td>${value.product_desc || ''}</td>
                        <td>${value.product_price || '0.00'}</td>
                        <td>${value.product_qty || '0'}</td>
                        <td>${value.cat_title || ''}</td>
                        <td>${value.brand_title || ''}</td>
                        <td>
                            <a class="btn btn-sm btn-info edit-product" style="color:#fff;">
                                <span style="display:none;">${JSON.stringify(value)}</span>
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a pid="${value.product_id}" class="btn btn-sm btn-danger delete-product" style="color:#fff;">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>`;
                });

                $("#product_list").html(productHTML);
            } else {
                $("#product_list").html('<tr><td colspan="9" class="text-center">No products found</td></tr>');
            }

            renderDropdowns(data.categories, data.brands);
        } catch (e) {
            handleError(e, "renderProducts");
        }
    }

    // Render category and brand dropdowns
    function renderDropdowns(categories, brands) {
        try {
            let catSelectHTML = '<option value="">Select Category</option>';
            categories.forEach(value => {
                catSelectHTML += `<option value="${value.cat_id}">${value.cat_title}</option>`;
            });
            $(".category_list").html(catSelectHTML);

            let brandSelectHTML = '<option value="">Select Brand</option>';
            brands.forEach(value => {
                brandSelectHTML += `<option value="${value.brand_id}">${value.brand_title}</option>`;
            });
            $(".brand_list").html(brandSelectHTML);
        } catch (e) {
            handleError(e, "renderDropdowns");
        }
    }

    // Form validation helper
    function validateForm(formId) {
        const form = document.getElementById(formId);
        if (!form.checkValidity()) {
            form.reportValidity();
            return false;
        }
        return true;
    }

    // Initialize
    getProducts();

    // Add product handler
    $(".add-product").on("click", function(e) {
        e.preventDefault();
        
        if (!validateForm("add-product-form")) return;

        const formData = new FormData($("#add-product-form")[0]);

        $.ajax({
            url: "../admin/classes/Products.php",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(resp) {
                if (resp.status == 202) {
                    $("#add-product-form").trigger("reset");
                    $("#add_product_modal").modal("hide");
                    getProducts();
                    alert("Product added successfully!");
                } else {
                    handleError(resp.message, "add-product");
                }
            },
            error: function(xhr) {
                handleError(xhr, "add-product AJAX");
            }
        });
    });

    // Edit product handler
    $(document.body).on("click", ".edit-product", function() {
        try {
            const product = JSON.parse($.trim($(this).find("span").text()));
            const form = $("#edit-product-form")[0];
            
            form.e_product_name.value = product.product_title || '';
            form.e_brand_id.value = product.brand_id || '';
            form.e_category_id.value = product.cat_id || '';
            form.e_product_desc.value = product.product_desc || '';
            form.e_product_qty.value = product.product_qty || '';
            form.e_product_price.value = product.product_price || '';
            form.e_product_keywords.value = product.product_keywords || '';
            form.e_product_video.value = product.product_video || '';
            form.pid.value = product.product_id || '';
            
            // Update image preview
            $("input[name='e_product_image']")
                .siblings("img")
                .attr("src", `../product_images/${product.product_image || 'default.jpg'}`);
            
            $("#edit_product_modal").modal("show");
        } catch (e) {
            handleError(e, "edit-product click");
        }
    });

    // Submit edit handler
    $(".submit-edit-product").on('click', function() {
        if (!validateForm("edit-product-form")) return;

        $.ajax({
            url: '../admin/classes/Products.php',
            method: 'POST',
            data: new FormData($("#edit-product-form")[0]),
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(resp) {
                if (resp.status == 202) {
                    $("#edit-product-form").trigger("reset");
                    $("#edit_product_modal").modal('hide');
                    getProducts();
                    alert("Product updated successfully!");
                } else {
                    handleError(resp.message, "edit-product submit");
                }
            },
            error: function(xhr) {
                handleError(xhr, "edit-product AJAX");
            }
        });
    });

    // Delete product handler
    $(document.body).on('click', '.delete-product', function() {
        const pid = $(this).attr('pid');
        if (!confirm("Are you sure you want to delete this product?")) return;

        $.ajax({
            url: '../admin/classes/Products.php',
            method: 'POST',
            data: { 
                DELETE_PRODUCT: 1, 
                pid: pid 
            },
            dataType: 'json',
            success: function(resp) {
                if (resp.status == 202) {
                    getProducts();
                    alert("Product deleted successfully!");
                } else {
                    handleError(resp.message, "delete-product");
                }
            },
            error: function(xhr) {
                handleError(xhr, "delete-product AJAX");
            }
        });
    });
});
