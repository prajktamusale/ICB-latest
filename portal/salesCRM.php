<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)) {
    header("Location: ./index.php");
    exit();
}
if (!isset($_SESSION['type'])) {
    header("Location: ../index.html");
    exit();
}

$searchOn = '';
$productList = array();
$prospectusList = array();
$soldList = array();
$customerList = array();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "icb_portal";

// Handle optional GET parameters safely
$productName   = $_GET['product_name'] ?? '';
$productQty    = $_GET['product_qty'] ?? '';
$customerId    = $_GET['customer_id'] ?? '';
$customerName  = $_GET['customer_name'] ?? '';
$email         = $_GET['email'] ?? '';
$phone         = $_GET['phone'] ?? '';
$status        = $_GET['status'] ?? '';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Product list
$sql = "SELECT * FROM product";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productList[$row['id']] = $row['name'];
    }
}

// Prospectus list
// Prospectus list
$sql2 = "SELECT o.order_id, o.product_id, o.customer_id, o.quantity,
         c.name AS customer_name, c.whatsapp, c.email, c.state, c.city, c.address, c.pin,
         p.name AS product_name, p.product_image_url AS product_image,
         pr.status AS prospectus_status, pr.order_id AS prospectus_order_id
         FROM order_table o
         JOIN customer c ON o.customer_id = c.id
         JOIN product p ON o.product_id = p.id
         LEFT JOIN prospectus pr ON o.order_id = pr.order_id
         WHERE pr.status = 1 OR pr.status = 0";
$result2 = $conn->query($sql2);
if ($result2->num_rows > 0) {
    while ($row = $result2->fetch_assoc()) {
        $prospectusList[$row['order_id']] = [
            'product_id' => $row['product_id'],
            'product_name' => $row['product_name'],
            'product_image' => $row['product_image'],
            'customer_id' => $row['customer_id'],
            'customer_name' => $row['customer_name'],
            'phone' => $row['whatsapp'],
            'email' => $row['email'],
            'state' => $row['state'],
            'city' => $row['city'],
            'address' => $row['address'],
            'pin' => $row['pin'],
            'quantity' => $row['quantity'],
            'status' => $row['prospectus_status']        ];
    }
}

// Sold list
$sql3 = "SELECT * FROM sold";
$result3 = $conn->query($sql3);
if ($result3->num_rows > 0) {
    while ($row = $result3->fetch_assoc()) {
        $soldList[$row['order_id']] = [
            'status' => $row['status']
        ];
    }
}

// Customer list
$sql4 = "SELECT * FROM customer";
$result4 = $conn->query($sql4);
if ($result4->num_rows > 0) {
    while ($row = $result4->fetch_assoc()) {
        $customerList[$row['id']] = $row['name'];
    }
}
$sql5 = "SELECT * FROM associate WHERE id = " . $_SESSION['id'];
$result5 = $conn->query($sql5);
if ($result5->num_rows > 0){
  while($row = $result5->fetch_assoc()){
    }
}
  
$conn->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $orderId = $data['order_id'];
    $customerName = $data['customer_name'];
    $phone = $data['phone'];
    $email = $data['email'];
    $quantity = $data['quantity'];
    $status = $data['status'];

    $conn = new mysqli('localhost', 'root', '', 'icb_portal');

    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed']);
        exit();
    }

    $query = "UPDATE order_table o
              JOIN customer c ON o.customer_id = c.id
              SET c.name = ?, c.phone = ?, c.email = ?, o.quantity = ?, o.status = ?
              WHERE o.order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssiii', $customerName, $phone, $email, $quantity, $status, $orderId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update details']);
    }

    $stmt->close();
    $conn->close();
}
?>
 
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="widdiv=device-widdiv, initial-scale=1.0" />
    <title>Sales CRM: Care For Bharat</title>
    <link rel="stylesheet" href="./css/donate.css" />
    <link rel="stylesheet" href="./css/header.css" />
    <link rel="stylesheet" href="./css/utils.css">
    <link rel="stylesheet" href="./css/salesCRM2.css">
    <style>
        <?php include "./css/header.css" ?>
        <?php include "./css/search.css" ?>
        <?php include "./css/salesCRM.css" ?>  
        <?php include "./css/notification.css" ?>
        
        /* Additional CSS for customer details */
        .data_details, .data_details_50 {
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #dee2e6;
            font-size: 14px;
        }
        
        .data_details {
            width: 100%;
        }
        
        .data_details_50 {
            width: 48%;
            display: inline-block;
            margin-right: 2%;
        }
        
        #show-form-details {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 90%;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 999;
            display: none;
        }
        
        .close_add-details {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            width: 24px;
            height: 24px;
        }
        
        .edit_order_area {
            display: flex;
            margin-bottom: 20px;
        }
        
        .edit_order_area__image {
            flex: 0 0 100px;
            margin-right: 15px;
        }
        
        .edit_order_area__image img {
            max-width: 100%;
            border-radius: 5px;
        }
        
        .edit_order_area__details {
            flex: 1;
        }
        
        .make-sticky {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
            display: none;
            justify-content: center;
            align-items: center;
        }
    </style>
    <link rel="stylesheet" href="./css/bottomNav.css">
    <?php 
      echo "<script>localStorage.setItem('id', ".$_SESSION['id'].");</script>";
    ?>
    <script src="./js/sliderAccordian.js" defer></script>
    <script src="./js/sideBar.js" defer></script>
    <script src="./js/salesCRM.js" defer></script>
    <script src="./js/searchBar.js" defer></script>
    <script src="./js/notification.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    
</head>
  <body>
<style>
  /* Container for Search Bar and Explore Button */
/* Search Bar Container */
/* Container for Search Bar and Explore Button */
.search-explore-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 20px auto;
    max-width: 800px;
    gap: 20px; /* Add spacing between the search bar and button */
}

/* Search Bar Container */
.search-container {
    display: flex;
    align-items: center;
    flex: 1; /* Allow the search bar to take up available space */
    background-color: #f7f7f7;
    border: 1px solid #ddd;
    border-radius: 25px;
    padding: 10px 15px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Search Input */
.search-input {
    flex: 1;
    border: none;
    outline: none;
    font-size: 16px;
    background: transparent;
    padding: 10px;
    color: #555;
}

/* Search Button */
.search-button {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 18px;
    color: #888;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #e0e0e0;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.search-button:hover {
    background-color: #d4d4d4;
    color: #555;
}

/* Explore Button */
.explore-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: #3498db;
    color: white;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease;
    white-space: nowrap; /* Prevent text wrapping */
}

.explore-btn:hover {
    background-color: #2980b9;
}

.explore-icon {
    width: 20px;
    height: 20px;
    margin-right: 8px;
}

</style>


    <!-- Navigation Bar -->
    <?php
      include './header.php';
    ?>
    <br>

    <div class="search-explore-container">
    <div class="search-container">
        <input type="text" placeholder="Search Product" class="search-input">
        <button class="search-button">
            <i class="fa fa-search"></i>
        </button>
    </div>
    <a href="products.php" class="explore-btn">
        <img src="./images/Explore.png" alt="Explore Icon" class="explore-icon">
        Explore
    </a>
</div>


<!-- Edit Details Form -->
<div id="edit-form-details" class="make-sticky">
  <img src="https://cdn-icons-png.flaticon.com/128/463/463612.png" alt="close" id="edit_form_close" class="close_add-details"/>

          <form method="post" action="database/updateDetailsProspectusOrder.php" id="order_edit_form" class="order_edit_form">
            <div class="edit_order_details">
              <h2>Edit Order Details</h2>
              <div class="edit_order_area">
                <div class="edit_order_area__image">
                  <img src="" alt="Product Image" id="edit_image"/>
                </div>
                <div class="edit_order_area__details">
                <div id="edit_order_area__details__product_id"><span>Product Id:</span><div id="edit_order_area__details__product_id__details"></div></div>
                <div id="edit_order_area__details__product_name">Temp ProductNmae</div>
                <div id="edit_order_area__details__product_qty"><input type="number" id="edit_order_area__details__product_qty__product_qty" for="quantity" name="quantity" max="10" min="1" value="1" placeholder="Quantity" class="input_details"></div>
           </div>
              </div>
            </div>
            <div class="edit_customer_details">
            <h4>Edit Customer Details</h4>
            <div class="edit_customer_details">
              <input name="customer_name" for="customer_name" id="edit_customer_details__customer_name" class="input_details_50" value="Temp" placeholder="Customer Name" minlength="2" required /> 
              <input name="whatsapp" for="whatsapp" id="edit_customer_details__whatsapp" class="input_details_50" value="9876543212" placeholder="Phone Number" min="1000000000" max="9999999999" required /> 
              <input name="email" for="email" id="edit_customer_details__email" class="input_details" value="abc@gmail.com" placeholder="Email" required /> 
              <input name="state" for="state" id="edit_customer_details__state" class="input_details_50" value="" placeholder="State" required /> 
              <input name="city" for="city" id="edit_customer_details__city" class="input_details_50" value="" placeholder="City" required /> 
              <input name="address" for="address" id="edit_customer_details__address" type="textarea" class="input_details" value="" placeholder="Address" required /> 
              <input name="pin" for="pin" id="edit_customer_details__pin" class="input_details_50" value="" placeholder="Pin Code" min="100000" max="999999" required /> 
            </div>
            </div>
            <input type="hidden" name="ordId" id="ordId" value="" required/>
            <input type="hidden" name="customer_id" id="customer_id" value="" required/>
            <input type="submit" class="form_btn" value="Update" id="edit_update_btn"/>
          </form>
        </div>

        <!-- Show details -->

        <div id="show-form-details" class="make-sticky">
          <img src="https://cdn-icons-png.flaticon.com/128/463/463612.png" alt="close" id="show_form_close" class="close_add-details"/>

            <div class="edit_order_details">
              <h4>Order Details</h4>
              <div class="edit_order_area">
                <div class="edit_order_area__image">
                  <img src="" alt="Product Image" id="details_image"/>
                </div>
                <div class="edit_order_area__details">
                  <div id="show_order_area__details__product_id"><strong>Product Id:</strong> <span id="show_order_area__details__product_id__details"></span></div>
                  <div id="show_order_area__details__product_name"></div>
                  <div id="show_order_area__details__product_qty"><strong>Quantity:</strong> <span id="show_order_area__details__product_qty__product_qty"></span></div>
                </div>
              </div>
            </div>
            <div class="edit_customer_details">
              <h4>Customer Details</h4>
              <div class="edit_customer_details">
                <div id="show_customer_details__customer_name" class="data_details_50"><strong>Name:</strong> <span id="customer_name_value"></span></div>
                <div id="show_customer_details__whatsapp" class="data_details_50"><strong>Phone:</strong> <span id="customer_phone_value"></span></div> 
                <div id="show_customer_details__email" class="data_details"><strong>Email:</strong> <span id="customer_email_value"></span></div>
                <div id="show_customer_details__state" class="data_details_50"><strong>State:</strong> <span id="customer_state_value"></span></div>
                <div id="show_customer_details__city" class="data_details_50"><strong>City:</strong> <span id="customer_city_value"></span></div>
                <div id="show_customer_details__address" class="data_details"><strong>Address:</strong> <span id="customer_address_value"></span></div>
                <div id="show_customer_details__pin" class="data_details_50"><strong>Pin:</strong> <span id="customer_pin_value"></span></div>
              </div>
            </div>
        </div>

        <!-- Add new details -->
<div class="add-details">
  <h2 class="add-details__title">New Entry</h2>
  <img src="https://cdn-icons-png.flaticon.com/128/463/463612.png" alt="close" id="close_add-details" class="close_add-details"/>
  <div id="content"></div>
          <form action="database/addCustomer2.php" method="post">
          <label for="product_id" class="form_label"><div>Product ID:</div> 
              <select name="id" id="product_id" class="form-input" required>
              <option value="">Select Product ID</option>

              </select>
            </label>
            <label for="product_name" class="form_label"><div>Product Name: </div>
              <select name="name" id="product_name" class="form-input" required>
              <option value="">Select Product Name</option>
              </select></label>
            <label class="form_label" for="product_qty"><div>Quantity: </div><input for="product_qty"  class="form-input" name="qty" placeholder="1,2,..." required/></label>
            <!-- <label for="product_name">Customer ID: <input for="customer_id" name="customer_id"/></label> -->
            <label class="form_label" for="product_cust_name"><div>Customer Name: </div><input class="form-input" for="customer_name" name="product_cust_name" required/></label>
            <label class="form_label" for="cust_email"><div>Email: </div><input class="form-input" for="product_name" name="cust_email" required/></label>
            <label class="form_label" for="cust_whatasapp"><div>Whatsapp Number: </div><input class="form-input" for="cust_whatasapp" name="cust_whatasapp" required /></label>
            <label class="form_label" for="cust_address"><div>Address: </div><input class="form-input" for="cust_address" name="cust_address" required/></label>
            <label class="form_label" for="cust_city"><div>City: </div><input class="form-input" for="cust_city" name="cust_city" required/></label>
            <label class="form_label" for="cust_state"><div>State: </div><input class="form-input" for="cust_state" name="cust_state" required/></label>
            <label class="form_label" for="cust_pin"><div>Pin: </div><input class="form-input" for="cust_pin" name="cust_pin" required/></label>
            <label for="status" class="form_label"><div>Status:</div> 
              <select name="status" id="status" class="form-input" required>
                <option value="1">Pitching</option> <!-- Pitching: 1 -->
                <option value="2">Closed</option> <!-- Closed: 2 -->
                <option value="0">Cancelled</option> <!-- Canclled: 0 -->
              </select>
            </label>
            <input type="submit">
          </form>
        </div>

    <div class="sales">
      <div class="sales-tab">
        <div class="sale-tab active-option" id="sale-tab-1">Prospectus</div>
        <div class="sale-tab" id="sale-tab-2">Sold</div>
      </div>
      <div class="sales-table" id="table-1">
      <div class="table" id="prospectus">
    <?php foreach ($prospectusList as $orderId => $details): ?>
        <div class="tr prospectus_table_row" id="card-<?php echo $orderId; ?>">
            <div class="td prospectus_customer_details">
                <p class="prospectus_customer_name"><strong>Name:</strong> <?php echo htmlspecialchars($details['customer_name']); ?></p>
                <p class="prospectus_customer_phone"><strong>Phone:</strong> <?php echo htmlspecialchars($details['phone']); ?></p>
                <p class="prospectus_customer_email"><strong>Email:</strong> <?php echo htmlspecialchars($details['email']); ?></p>
            </div>
            <div class="td prospectus_status">
                <span class="status <?php echo strtolower($details['status'] == 1 ? 'pitching' : ($details['status'] == 2 ? 'closed' : 'cancelled')); ?>">
                    <?php echo $details['status'] == 1 ? 'Pitching' : ($details['status'] == 2 ? 'Closed' : 'Cancelled'); ?>
                </span>
            </div>
            <div class="td prospectus_options">
                <button class="prospectus_table_button" onclick="openEditModal('<?php echo $orderId; ?>')">Edit</button>
            </div>
        </div>
    <?php endforeach; ?>
</div>
        
      <button class="add_new" title="Add new entry"><img src="./images/add.png" class="add" alt="Add new details"/></button>

      </div>
      <div class="sales-table" id="table-2">
    <div class="tr" id="sold_table">
        <div class="th sold_product_name">Product Name</div>
        <div class="th sold_quantity">Quantity</div>
        <div class="th sold_customer_id">Customer ID</div>
        <div class="th sold_customer_details">Customer Details</div>
        <div class="th sold_total_price">Total Price</div>
        <div class="th sold_pay">Pay</div>
        <div class="th sold_status">Status</div>
    </div>
    <div class="table" id="sold">
      
      <form method="post" action="#">
        
        <div id="sold_table_data"></div>
    </div>
</div>

</div>
  <div class="total-amount" id="total_amount">
            <div>Grand Total:</div>
            <div id="payment-amount">
              <?php
                $totalPay = "20370";
                echo "₹".$totalPay."/-";
                ?>
            </div>
            <form action="post" action="#"><input type="submit" class="form_btn" value="Pay" name="total_pay"></form>
      </div>
    <h1 id="response"></h1>

    <?php include "./components/notification.php";?>
    <?php include "./components/bottomNav.php";?>
  
  <script defer>
  var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        let ids = "";
        let names = "";
        let products = JSON.parse(this.responseText);
        for(const key in products){
          ids+=`<option value='${key}'>${key}</option>`;
          names+=`<option value='${products[key]}'>${products[key]}</option>`;
        }
        document.getElementById("product_id").innerHTML = ids;
        document.getElementById("product_name").innerHTML = names;
      }
    };
    xmlhttp.open("GET",`database/getProductList.php`,true);
    xmlhttp.send();
  </script>

  <script defer>
  document.addEventListener('DOMContentLoaded', function() {
    // Edit button functionality
    const editButtons = document.querySelectorAll('.edit_btn');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            openEditModal(orderId);
        });
    });
    
    // Details button functionality
    const detailsButtons = document.querySelectorAll('.details_btn');
    detailsButtons.forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            openDetailsModal(orderId);
        });
    });
    
    // Close modal functionality
    document.getElementById('edit_form_close').addEventListener('click', function() {
        document.getElementById('edit-form-details').style.display = 'none';
    });
    
    document.getElementById('show_form_close').addEventListener('click', function() {
        document.getElementById('show-form-details').style.display = 'none';
    });
    
    document.getElementById('close_add-details').addEventListener('click', function() {
        document.querySelector('.add-details').style.display = 'none';
    });
    
    // Add new entry functionality
    document.querySelector('.add_new').addEventListener('click', function() {
        document.querySelector('.add-details').style.display = 'block';
    });
  });
  
  function openEditModal(orderId) {
    // Get data from hidden spans
    const customerId = document.getElementById(`customer-id-${orderId}`).innerText;
    const customerName = document.getElementById(`card-${orderId}`).querySelector('.prospectus_customer_details p:first-child').innerText;
    const phone = document.getElementById(`card-${orderId}`).querySelector('.prospectus_customer_details p:nth-child(2)').innerText;
    const email = document.getElementById(`card-${orderId}`).querySelector('.prospectus_customer_details p:nth-child(3)').innerText;
    const quantity = document.getElementById(`quantity-${orderId}`).innerText;
    const productId = document.getElementById(`product-id-${orderId}`).innerText;
    const productName = document.getElementById(`card-${orderId}`).querySelector('.prospectus_product_details p:first-child').innerText;
    const state = document.getElementById(`customer-state-${orderId}`).innerText;
    const city = document.getElementById(`customer-city-${orderId}`).innerText;
    const address = document.getElementById(`customer-address-${orderId}`).innerText;
    const pin = document.getElementById(`customer-pin-${orderId}`).innerText;
    const productImage = document.getElementById(`card-${orderId}`).querySelector('.product-img').src;
    
    // Populate the edit form
    document.getElementById('edit_image').src = productImage;
    document.getElementById('edit_order_area__details__product_id__details').innerText = productId;
    document.getElementById('edit_order_area__details__product_name').innerText = productName;
    document.getElementById('edit_order_area__details__product_qty__product_qty').value = quantity;
    document.getElementById('edit_customer_details__customer_name').value = customerName;
    document.getElementById('edit_customer_details__whatsapp').value = phone;
    document.getElementById('edit_customer_details__email').value = email;
    document.getElementById('edit_customer_details__state').value = state;
    document.getElementById('edit_customer_details__city').value = city;
    document.getElementById('edit_customer_details__address').value = address;