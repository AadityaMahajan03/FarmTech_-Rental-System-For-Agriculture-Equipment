$(document).ready(() => {
	getCustomers()
	getCustomerOrders()
  
	function getCustomers() {
	  $.ajax({
		url: "../admin/classes/Customers.php",
		method: "POST",
		data: { GET_CUSTOMERS: 1 },
		success: (response) => {
		  console.log(response)
		  var resp = $.parseJSON(response)
		  if (resp.status == 202) {
			var customersHTML = ""
			$.each(resp.message, (index, value) => {
			  customersHTML +=
				"<tr>" +
				"<td>" +
				(index + 1) +
				"</td>" +
				"<td>" +
				value.first_name +
				" " +
				value.last_name +
				"</td>" +
				"<td>" +
				value.email +
				"</td>" +
				"<td>" +
				value.mobile +
				"</td>" +
				"<td>" +
				value.address1 +
				"<br>" +
				value.address2 +
				"</td>" +
				"</tr>"
			})
			$("#customer_list").html(customersHTML)
		  } else if (resp.status == 303) {
		  }
		},
	  })
	}
  
	function getCustomerOrders() {
	  $.ajax({
		url: "../admin/classes/Customers.php",
		method: "POST",
		data: { GET_CUSTOMER_ORDERS: 1 },
		success: (response) => {
		  console.log("Raw response:", response)
  
		  try {
			var resp = $.parseJSON(response)
			console.log("Parsed response:", resp)
  
			if (resp.status == 202) {
			  var customerOrderHTML = ""
			  if (resp.message.length === 0) {
				console.log("Response status is 202 but message array is empty")
				$("#customer_order_list").html("<tr><td colspan='10'>No orders found</td></tr>")
				return
			  }
  
			  $.each(resp.message, (index, value) => {
				console.log("Processing order:", value)
  
				customerOrderHTML +=
				  "<tr>" +
				  "<td>" +
				  (index + 1) +
				  "</td>" +
				  "<td>" +
				  value.order_id +
				  "</td>" +
				  "<td>" +
				  value.product_id +
				  "</td>" +
				  "<td>" +
				  value.product_title +
				  "</td>" +
				  "<td>" +
				  value.qty +
				  "</td>" +
				  "<td>" +
				  value.trx_id +
				  "</td>" +
				  "<td>" +
				  value.p_status +
				  "</td>" +
				  "<td>" +
				  value.cust_name +
				  "</td>" +
				  "<td>" +
				  value.cust_num +
				  "</td>" +
				  "<td>" +
				  (value.order_address || "Address not found") +
				  "</td>" +
				  "</tr>"
			  })
  
			  $("#customer_order_list").html(customerOrderHTML)
			} else if (resp.status == 303) {
			  console.log("Status 303 received:", resp.message)
			  $("#customer_order_list").html("<tr><td colspan='10'>" + resp.message + "</td></tr>")
			}
		  } catch (e) {
			console.error("Error parsing response:", e)
			console.log("Raw response that caused error:", response)
			$("#customer_order_list").html("<tr><td colspan='10'>Error processing data</td></tr>")
		  }
		},
		error: (xhr, status, error) => {
		  console.error("AJAX Error:", status, error)
		  $("#customer_order_list").html("<tr><td colspan='10'>Error fetching data</td></tr>")
		},
	  })
	}
  })
  