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
$sql2 = "SELECT o.order_id, o.product_id, o.customer_id, o.quantity,
         c.name AS customer_name, c.whatsapp, c.email, c.state, c.city, c.address, c.pin,
         p.name AS product_name, p.product_image_url AS product_image,
         pr.status AS prospectus_status, pr.order_id AS prospectus_order_id
         FROM order_table o
         JOIN customer c ON o.customer_id = c.id
         JOIN product p ON o.product_id = p.id
         LEFT JOIN prospectus pr ON o.order_id = pr.order_id
         WHERE pr.status IN (0,1)";
         
$result2 = $conn->query($sql2);
if ($result2->num_rows > 0) {
    while ($row = $result2->fetch_assoc()) {
        $prospectusList[$row['order_id']] = [
            'product_id' => $row['product_id'],
            'product_name' => $row['product_name'],
            'product_image' => $row['product_image'],
            'customer_id' => $row['customer_id'],
            'customer_name' => $row['customer_name'],
            'whatsapp' => $row['whatsapp'],
            'email' => $row['email'],
            'state' => $row['state'],
            'city' => $row['city'],
            'address' => $row['address'],
            'pin' => $row['pin'],
            'quantity' => $row['quantity'],
            'status' => $row['prospectus_status']
        ];
    }
}

// Sold list
$sql3 = "SELECT s.order_id, s.status, o.total_price, o.quantity, p.name AS product_name, 
                c.name AS customer_name, c.email, c.whatsapp
         FROM sold s
         JOIN order_table o ON s.order_id = o.order_id
         JOIN product p ON o.product_id = p.id
         JOIN customer c ON o.customer_id = c.id
         WHERE s.status IN (2, 3)"; // Include both Closed (2) and Paid (3) statuses
$result3 = $conn->query($sql3);

$soldList = [];
if ($result3->num_rows > 0) {
    while ($row = $result3->fetch_assoc()) {
        $soldList[$row['order_id']] = [
            'order_id' => $row['order_id'],
            'status' => $row['status'],
            'total_price' => $row['total_price'],
            'quantity' => $row['quantity'],
            'product_name' => $row['product_name'],
            'customer_name' => $row['customer_name'],
            'email' => $row['email'],
            'whatsapp' => $row['whatsapp']
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
//prospectus list
$sql5 = "SELECT * FROM associate WHERE id = " . $_SESSION['id'];
$result5 = $conn->query($sql5);
if ($result5->num_rows > 0){
  while($row = $result5->fetch_assoc()){
    }
}
  


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

    ob_start(); // Start output buffering
    echo json_encode($response);
    $output = ob_get_clean(); // Get the output
    error_log("Output: $output"); // Log the output
    echo $output;
    exit();
}
?>
 
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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

        .prospectus_product_name {
            font-weight: normal; /* Ensure the font weight is normal */
            font-size: 16px; /* Adjust the font size if needed */
            color: #333; /* Optional: Set a color for the text */
        }
    </style>
    <link rel="stylesheet" href="./css/bottomNav.css">
    <?php 
      echo "<script>localStorage.setItem('id', ".$_SESSION['id'].");</script>";
    ?>
    <script src="./js/sliderAccordian.js" defer></script>
    <script src="./js/sideBar.js" defer></script>
    <script src="./js/salesCRM.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script>
        const prospectusList = <?php echo json_encode($prospectusList, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;
    </script>
    <script>
        const products = <?php echo json_encode($productList, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;
    </script>
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
/* Style the dropdown */
.status-select {
    width: 120px;
    padding: 5px 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
    appearance: none; /* Remove default dropdown arrow */
    cursor: pointer;
    transition: all 0.3s ease;
}

/* Add hover effect */
.status-select:hover {
    border-color: #888;
    background-color: #f1f1f1;
}

/* Specific styles for each status */
.status-select option.status-cancelled {
    background-color: #ffcccc;
    color: #dc3545;
}

.status-select option.status-pitching {
    background-color: #fffbe6;
    color: #ffc107;
}

.status-select option.status-closed {
    background-color: #e6ffe6;
    color: #28a745;
}
/* Change the dropdown's background color dynamically */
.status-select.cancelled {
    background-color: #ffcccc;
    color: #dc3545;
}

.status-select.pitching {
    background-color: #fffbe6;
    color: #ffc107;
}

.status-select.closed {
    background-color: #e6ffe6;
    color: #28a745;
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
<form id="edit-form-details" style="max-width: 600px; margin: auto; background: #f9f9f9; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
    <button type="button" id="edit_form_close" style="float: right; background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
    <h3 style="text-align: center; margin-bottom: 20px;">Edit Order Details</h3>

    <div style="margin-bottom: 15px;">
        <label for="ordId" style="display: block; font-weight: bold;">Product Id:</label>
        <input type="text" name="ordId" id="ordId" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" readonly />
    </div>

    <div style="margin-bottom: 15px;">
        <label for="quantity" style="display: block; font-weight: bold;">Quantity:</label>
        <input type="number" name="quantity" id="edit_order_area__details__product_qty__product_qty" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required />
    </div>

    <h4 style="margin-top: 20px; margin-bottom: 10px;">Edit Customer Details</h4>

    <div style="margin-bottom: 15px;">
        <label for="customer_name" style="display: block; font-weight: bold;">Customer Name:</label>
        <input type="text" name="customer_name" id="edit_customer_details__customer_name" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required />
    </div>

    <div style="margin-bottom: 15px;">
        <label for="whatsapp" style="display: block; font-weight: bold;">Phone:</label>
        <input type="text" name="whatsapp" id="edit_customer_details__whatsapp" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required />
    </div>

    <div style="margin-bottom: 15px;">
        <label for="email" style="display: block; font-weight: bold;">Email:</label>
        <input type="email" name="email" id="edit_customer_details__email" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required />
    </div>

    <div style="margin-bottom: 15px;">
        <label for="state" style="display: block; font-weight: bold;">State:</label>
        <input type="text" name="state" id="edit_customer_details__state" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required />
    </div>

    <div style="margin-bottom: 15px;">
        <label for="city" style="display: block; font-weight: bold;">City:</label>
        <input type="text" name="city" id="edit_customer_details__city" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required />
    </div>

    <div style="margin-bottom: 15px;">
        <label for="address" style="display: block; font-weight: bold;">Address:</label>
        <textarea name="address" id="edit_customer_details__address" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required></textarea>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="pin" style="display: block; font-weight: bold;">Pin Code:</label>
        <input type="text" name="pin" id="edit_customer_details__pin" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required />
    </div>

    <button type="submit" style="width: 100%; padding: 10px; background: #28a745; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">Update</button>
</form>

        <!-- Show details -->

       

        <!-- Add new details -->
<div class="add-details">
  <h2 class="add-details__title">New Entry</h2>
  <img src="https://cdn-icons-png.flaticon.com/128/463/463612.png" alt="close" id="close_add-details" class="close_add-details"/>
  <div id="content"></div>
  <form action="database/addCustomer2.php" method="post">
    <label for="product_id" class="form_label">
        <div>Product ID:</div>
        <select name="id" id="product_id" class="form-input" required>
            <option value="">Select Product ID</option>
            <?php foreach ($productList as $id => $name): ?>
                <option value="<?php echo $id; ?>"><?php echo $id; ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label for="product_name" class="form_label">
        <div>Product Name:</div>
        <select name="name" id="product_name" class="form-input" required>
            <option value="">Select Product Name</option>
            <?php foreach ($productList as $id => $name): ?>
                <option value="<?php echo $name; ?>"><?php echo $name; ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label class="form_label" for="product_qty">
        <div>Quantity:</div>
        <input type="number" id="product_qty" class="form-input" name="qty" placeholder="1,2,..." required />
    </label>
    <label class="form_label" for="product_cust_name">
        <div>Customer Name:</div>
        <input type="text" id="product_cust_name" class="form-input" name="product_cust_name" required />
    </label>
    <label class="form_label" for="cust_email">
        <div>Email:</div>
        <input type="email" id="cust_email" class="form-input" name="cust_email" required />
    </label>
    <label class="form_label" for="cust_whatasapp">
        <div>WhatsApp Number:</div>
        <input type="text" id="cust_whatasapp" class="form-input" name="cust_whatasapp" required />
    </label>
    <label class="form_label" for="cust_address">
        <div>Address:</div>
        <input type="text" id="cust_address" class="form-input" name="cust_address" required />
    </label>
    <label class="form_label" for="cust_city">
        <div>City:</div>
        <input type="text" id="cust_city" class="form-input" name="cust_city" required />
    </label>
    <label class="form_label" for="cust_state">
        <div>State:</div>
        <input type="text" id="cust_state" class="form-input" name="cust_state" required />
    </label>
    <label class="form_label" for="cust_pin">
        <div>Pin:</div>
        <input type="number" id="cust_pin" class="form-input" name="cust_pin" required />
    </label>
    <label for="status" class="form_label">
        <div>Status:</div>
        <select name="status" id="status" class="status-select form-input" required>
            <option value="1" class="status-pitching">Pitching</option>
            <option value="2" class="status-closed">Closed</option>
            <option value="0" class="status-cancelled">Cancelled</option>
        </select>
    </label>
    <input type="submit" value="Submit">
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
        <div class="td prospectus_product_details">
            <p class="prospectus_product_qty">
                <span><strong>Quantity:</strong> <?php echo htmlspecialchars($details['quantity']); ?></span>
            </p>
            <p class="prospectus_product_name">
                <span><strong>Product:</strong> <?php echo htmlspecialchars($details['product_name']); ?></span>
            </p>
        </div>
        <div class="td prospectus_customer_details">
            <p class="prospectus_customer_name"><strong>Name:</strong> <?php echo htmlspecialchars($details['customer_name']); ?></p>
            <p class="prospectus_customer_phone"><strong>Phone:</strong> <?php echo htmlspecialchars($details['whatsapp']); ?></p>
            <p class="prospectus_customer_email"><strong>Email:</strong> <?php echo htmlspecialchars($details['email']); ?></p>
        </div>
        <div class="td prospectus_status">
            <select class="status-select" data-order-id="<?php echo $orderId; ?>">
                <option value="0" class="status-cancelled" <?php echo $details['status'] == 0 ? 'selected' : ''; ?>>Cancelled</option>
                <option value="1" class="status-pitching" <?php echo $details['status'] == 1 ? 'selected' : ''; ?>>Pitching</option>
                <option value="2" class="status-closed" <?php echo $details['status'] == 2 ? 'selected' : ''; ?>>Closed</option>
            </select>
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
    <?php foreach ($soldList as $sold): ?>
        <div class="tr sold_table_row" id="sold-card-<?php echo $sold['order_id']; ?>">
            <div class="td sold_product_name"><?php echo htmlspecialchars($sold['product_name']); ?></div>
            <div class="td sold_quantity"><?php echo htmlspecialchars($sold['quantity']); ?></div>
            <div class="td sold_customer_id"><?php echo htmlspecialchars($sold['order_id']); ?></div>
            <div class="td sold_customer_details">
                <?php echo htmlspecialchars($sold['customer_name']); ?><br>
                <?php echo htmlspecialchars($sold['email']); ?><br>
                <?php echo htmlspecialchars($sold['whatsapp']); ?>
            </div>
            <div class="td sold_total_price">₹<?php echo htmlspecialchars($sold['total_price']); ?></div>
            <div class="td sold_pay">
                <?php if ($sold['status'] == 2): // If status is Closed ?>
                    <button class="pay-button" onclick="markAsPaid(<?php echo $sold['order_id']; ?>, document.getElementById('sold-card-<?php echo $sold['order_id']; ?>'))">Pay</button>
                <?php else: // If status is Paid ?>
                    Paid
                <?php endif; ?>
            </div>
            <div class="td sold_status"><?php echo $sold['status'] == 2 ? 'Closed' : 'Paid'; ?></div>
        </div>
    <?php endforeach; ?>
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
</div>
 
    <h1 id="response"></h1>

    <?php include "./components/bottomNav.php";?>

  <script defer>
    document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.getElementById('edit-form-details');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const orderId = formData.get('ordId');
            
            // Log the data being sent for debugging
            console.log("Sending form data:", Object.fromEntries(formData));
            
            // Send data to backend using fetch API
            fetch('./database/updateDetailsProspectusOrder.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log("Response status:", response.status);
                return response.json();
            })
            .then(data => {
                console.log("Response data:", data);
                
                if (data.success) {
                    alert(data.message);
                    
                    // Update the frontend dynamically
                    const row = document.getElementById(`card-${orderId}`);
                    if (row) {
                        // Update the row with new values
                        row.querySelector('.prospectus_product_qty span').innerText = formData.get('quantity');
                        row.querySelector('.prospectus_customer_name').innerText = 'Name: ' + formData.get('customer_name');
                        row.querySelector('.prospectus_customer_phone').innerText = 'whatsapp: ' + formData.get('whatsapp');
                        row.querySelector('.prospectus_customer_email').innerText = 'Email: ' + formData.get('email');
                    }
                    
                    // Close the edit form
                    document.getElementById('edit-form-details').style.display = 'none';
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the record.');
            });
        });
    }
});


    document.getElementById('product_name').addEventListener('change', function () {
        const productName = this.value;
        const productIdSelect = document.getElementById('product_id');

        // Find the Product ID corresponding to the selected Product Name
        const productId = Object.keys(products).find(id => products[id] === productName);

        if (productId) {
            productIdSelect.value = productId;
        } else {
            productIdSelect.value = ''; // Reset if no match is found
        }
    });

    const productIdSelect = document.getElementById('product_id');
    const productNameSelect = document.getElementById('product_name');

    // Populate the Product Name dropdown when Product ID is selected
    productIdSelect.addEventListener('change', function () {
        const selectedId = this.value;
        productNameSelect.value = products[selectedId] || '';
    });

    // Populate the Product ID dropdown when Product Name is selected
    productNameSelect.addEventListener('change', function () {
        const productName = this.value;
        const productId = Object.keys(products).find(id => products[id] === productName);

        if (productId) {
            productIdSelect.value = productId;
        } else {
            productIdSelect.value = ''; // Reset if no match is found
        }
    });
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
    
    
    
    // Close modal functionality
    document.getElementById('edit_form_close').addEventListener('click', function () {
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

    const productIdSelect = document.getElementById('product_id');
    const productNameSelect = document.getElementById('product_name');

    // Populate the Product Name dropdown when Product ID is selected
    productIdSelect.addEventListener('change', function () {
        const selectedId = this.value;
        productNameSelect.value = products[selectedId] || '';
    });

    // Populate the Product ID dropdown when Product Name is selected
    productNameSelect.addEventListener('change', function () {
        const productName = this.value;
        const productId = Object.keys(products).find(id => products[id] === productName);
        productIdSelect.value = productId || '';
    });
  });
  
  function openEditModal(orderId) {
    console.log("Opening edit modal for order ID:", orderId);

    // Get the row data using the orderId
    const row = document.getElementById(`card-${orderId}`);
    if (!row) {
        console.error("Row not found for order ID:", orderId);
        return;
    }

    // Extract data from the row
    const productName = row.querySelector('.prospectus_product_name').innerText.replace('Product: ', '');
    const quantity = row.querySelector('.prospectus_product_qty').innerText.replace('Quantity: ', '');
    const customerName = row.querySelector('.prospectus_customer_name').innerText.replace('Name: ', '');
    const phone = row.querySelector('.prospectus_customer_phone').innerText.replace('Phone: ', '');
    const email = row.querySelector('.prospectus_customer_email').innerText.replace('Email: ', '');

    // Fetch the address from the backend data (prospectusList)
    const address = <?php echo json_encode($prospectusList); ?>[orderId].address;
    const state = <?php echo json_encode( $prospectusList); ?>[orderId].state;
    const city = <?php echo json_encode( $prospectusList); ?>[orderId].city;
    const pin = <?php echo json_encode( $prospectusList); ?>[orderId].pin;



    console.log("Populating edit form with data:", {
        productName, quantity, customerName, phone, email, address,state,city,pin
    });

    // Populate the edit form fields
    document.getElementById('ordId').value = orderId;
    document.getElementById('edit_order_area__details__product_qty__product_qty').value = quantity;
    document.getElementById('edit_customer_details__customer_name').value = customerName;
    document.getElementById('edit_customer_details__whatsapp').value = phone;
    document.getElementById('edit_customer_details__email').value = email;
    document.getElementById('edit_customer_details__address').value = address;
    document.getElementById('edit_customer_details__state').value = state;
    document.getElementById('edit_customer_details__city').value = city;
    document.getElementById('edit_customer_details__pin').value = pin;



    // Show the edit form
    document.getElementById('edit-form-details').style.display = 'block';
}
  
  function openDetailsModal(orderId) {
    const row = document.getElementById(`card-${orderId}`);
    if (!row) {
        console.error("Row not found for order ID:", orderId);
        return;
    }

    // Extract data from the row
    const productName = row.querySelector('.prospectus_product_name').innerText.replace('Product: ', '');
    const quantity = row.querySelector('.prospectus_product_qty').innerText.replace('Quantity: ', '');
    const customerName = row.querySelector('.prospectus_customer_name').innerText.replace('Name: ', '');
    const phone = row.querySelector('.prospectus_customer_phone').innerText.replace('Phone: ', '');
    const email = row.querySelector('.prospectus_customer_email').innerText.replace('Email: ', '');

    // Populate the details modal
    document.getElementById('show_order_area__details__product_name').innerText = productName;
    document.getElementById('show_order_area__details__product_qty__product_qty').innerText = quantity;
    document.getElementById('customer_name_value').innerText = customerName;
    document.getElementById('customer_phone_value').innerText = phone;
    document.getElementById('customer_email_value').innerText = email;

    // Show the details modal
    document.getElementById('show-form-details').style.display = 'block';
  }
   

// Function to update status on the server
function updateStatusOnServer(orderId, status, selectElement) {
    console.log(`Updating status for Order ID: ${orderId} to ${status}`);
    
    const originalValue = selectElement.getAttribute('data-previous-value');
    updateStatusClass(selectElement);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'database/updateProspectusStatus.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onload = function() {
        console.log("Response received from server:", this.responseText);
        
        if (this.status === 200) {
            try {
                const response = JSON.parse(this.responseText);
                if (response.success) {
                    console.log('Status updated successfully');
                    selectElement.setAttribute('data-previous-value', status);

                    // If status is "Closed" (2), move the record to Sold
                    if (status == 2) {
                        const card = document.getElementById(`card-${orderId}`);
                        if (card) {
                            // Clone the card and move it to the Sold section
                            const soldCard = card.cloneNode(true);
                            soldCard.id = `sold-card-${orderId}`;

                            // Update the Pay button and status
                            const payButton = document.createElement('button');
                            payButton.innerText = 'Pay';
                            payButton.classList.add('pay-button');
                            payButton.addEventListener('click', function() {
                                markAsPaid(orderId, soldCard);
                            });

                            soldCard.querySelector('.prospectus_status').innerHTML = '';
                            soldCard.querySelector('.prospectus_status').appendChild(payButton);
                            soldCard.querySelector('.prospectus_options').innerHTML = '<span>Pending</span>';

                            // Append to the Sold section
                            document.getElementById('sold').appendChild(soldCard);

                            // Remove the card from the Prospectus section
                            card.remove();
                        }
                    }
                } else {
                    console.error('Failed to update status:', response.message);
                    alert('Failed to update status: ' + response.message);
                    selectElement.value = originalValue;
                    updateStatusClass(selectElement);
                }
            } catch (e) {
                console.error('Error parsing response:', e);
                alert('Error processing server response');
                selectElement.value = originalValue;
                updateStatusClass(selectElement);
            }
        } else {
            console.error('Server error:', this.status);
            alert('Server error: ' + this.status);
            selectElement.value = originalValue;
            updateStatusClass(selectElement);
        }
    };
    
    xhr.onerror = function() {
        console.error('Network error');
        alert('Network error occurred');
        selectElement.value = originalValue;
        updateStatusClass(selectElement);
    };
    
    xhr.send(`order_id=${orderId}&status=${status}`);
}

// Function to mark an item as Paid
function markAsPaid(orderId, soldCard) {
    console.log(`Marking Order ID: ${orderId} as Paid`);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'database/markAsPaid.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (this.status === 200) {
            try {
                const response = JSON.parse(this.responseText);
                if (response.success) {
                    alert('Payment successful!');
                    soldCard.querySelector('.sold_pay').innerHTML = 'Paid';
                    soldCard.querySelector('.sold_status').innerHTML = 'Paid';
                } else {
                    alert('Failed to mark as Paid: ' + response.message);
                }
            } catch (e) {
                console.error('Error parsing response:', e);
                alert('Error processing server response');
            }
        } else {
            console.error('Server error:', this.status);
            alert('Server error: ' + this.status);
        }
    };

    xhr.onerror = function() {
        console.error('Network error');
        alert('Network error occurred');
    };

    xhr.send(`order_id=${orderId}`);
}
// Add this function to your JavaScript code
function updateStatusClass(selectElement) {
    // Get the currently selected value
    const value = selectElement.value;
    
    // Apply direct styling based on value
    if (value == '0') {
        selectElement.style.backgroundColor = '#ffcccc';
        selectElement.style.color = '#dc3545';
    } else if (value == '1') {
        selectElement.style.backgroundColor = '#fffbe6';
        selectElement.style.color = '#ffc107';
    } else if (value == '2') {
        selectElement.style.backgroundColor = '#e6ffe6';
        selectElement.style.color = '#28a745';
    }
    
    console.log("Applied styles directly to element");
}
// Also, initialize the status classes when the page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log("Setting up status selects");
    const statusSelects = document.querySelectorAll('.status-select');
    console.log("Found", statusSelects.length, "status selects");
    
    statusSelects.forEach(select => {
        // Save the initial value
        select.setAttribute('data-previous-value', select.value);
        
        // Apply the initial class based on the selected option
        updateStatusClassOnLoad(select);
        
        // Add change event listener
        select.addEventListener('change', function() {
            console.log("Status changed:", this.value);
            const orderId = this.getAttribute('data-order-id');
            const newStatus = this.value;
            const oldStatus = this.getAttribute('data-previous-value');
            
            // Apply new class immediately for visual feedback
            updateStatusClass(this);
            
            if (newStatus !== oldStatus) {
                // Update status on server
                updateStatusOnServer(orderId, newStatus, this);
            }
        });
    });
});

// Function to apply initial status classes on page load
function updateStatusClassOnLoad(selectElement) {
    // Remove all status classes first
    selectElement.classList.remove('cancelled', 'pitching', 'closed');
    
    // Get the currently selected value
    const value = selectElement.value;
    
    // Add appropriate class
    if (value == '0') {
        selectElement.classList.add('cancelled');
    } else if (value == '1') {
        selectElement.classList.add('pitching');
    } else if (value == '2') {
        selectElement.classList.add('closed');
    }
    
    console.log("Initialized select with value", value, "and classes", selectElement.classList);
}
  
  // Product ID and Product Name sync
  document.getElementById('product_id').addEventListener('change', function() {
    const productId = this.value;
    const productSelect = document.getElementById('product_name');
    
    for (let i = 0; i < productSelect.options.length; i++) {
      if (productSelect.options[i].value === productSelect.options[productId].value) {
        productSelect.selectedIndex = i;
        break;
      }
    }
  });
  
  document.getElementById('product_name').addEventListener('change', function() {
    const productName = this.value;
    const productIdSelect = document.getElementById('product_id');
    
    for (let i = 0; i < productIdSelect.options.length; i++) {
      if (productIdSelect.options[i].value && document.getElementById('product_name').options[i].value === productName) {
        productIdSelect.selectedIndex = i;
        break;
      }
    }
  });
  
  // Search functionality
  document.querySelector('.search-input').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#prospectus .tr');
    
    rows.forEach(row => {
      const productName = row.querySelector('.prospectus_product_details p:first-child').innerText.toLowerCase();
      const customerName = row.querySelector('.prospectus_customer_details p:first-child').innerText.toLowerCase();
      const phone = row.querySelector('.prospectus_customer_details p:nth-child(2)').innerText.toLowerCase();
      const email = row.querySelector('.prospectus_customer_details p:nth-child(3)').innerText.toLowerCase();
      
      if (productName.includes(searchTerm) || 
          customerName.includes(searchTerm) || 
          phone.includes(searchTerm) || 
          email.includes(searchTerm)) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  });
  
  // Form submission via AJAX
  document.getElementById('edit-form-details').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    // Send data to the backend
    fetch('./database/updateDetailsProspectusOrder.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);

                // Update the frontend dynamically
                const orderId = formData.get('ordId');
                const row = document.getElementById(`card-${orderId}`);
                if (row) {
                    row.querySelector('.prospectus_product_qty').innerText = `Quantity: ${formData.get('quantity')}`;
                    row.querySelector('.prospectus_customer_name').innerText = `Name: ${formData.get('customer_name')}`;
                    row.querySelector('.prospectus_customer_phone').innerText = `Phone: ${formData.get('whatsapp')}`;
                    row.querySelector('.prospectus_customer_email').innerText = `Email: ${formData.get('email')}`;
                }

                // Close the edit form
                document.getElementById('edit-form-details').style.display = 'none';
            } else {
                alert(`Error: ${data.message}`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the record.');
        });
});

function updateGrandTotal() {
    const checkboxes = document.querySelectorAll('.sold_item_checkbox:checked');
    let total = 0;

    checkboxes.forEach(checkbox => {
        total += parseFloat(checkbox.getAttribute('data-price'));
    });

    document.getElementById('payment-amount').innerText = `₹${total.toFixed(2)}/-`;
}

function paySelectedItems() {
    const checkboxes = document.querySelectorAll('.sold_item_checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Please select at least one item to pay.');
        return;
    }

    const selectedOrderIds = [];
    checkboxes.forEach(checkbox => {
        const row = checkbox.closest('.sold_table_row');
        const orderId = row.id.replace('sold-card-', '');
        selectedOrderIds.push(orderId);
    });

    alert(`Payment initiated for Order IDs: ${selectedOrderIds.join(', ')}`);
    // Add payment logic here
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const productIdSelect = document.getElementById('product_id');
    const productNameSelect = document.getElementById('product_name');

    if (productIdSelect && productNameSelect) {
        // Populate the Product Name dropdown when Product ID is selected
        productIdSelect.addEventListener('change', function () {
            const selectedId = this.value;
            productNameSelect.value = products[selectedId] || '';
        });

        // Populate the Product ID dropdown when Product Name is selected
        productNameSelect.addEventListener('change', function () {
            const productName = this.value;
            const productId = Object.keys(products).find(id => products[id] === productName);
            productIdSelect.value = productId || '';
        });
    } else {
        console.error('Product ID or Product Name dropdown not found in the DOM.');
    }

    const addNewButton = document.querySelector('.add_new');
    if (addNewButton) {
        addNewButton.addEventListener('click', function () {
            document.querySelector('.add-details').style.display = 'block';
        });
    } else {
        console.error('Add New button not found in the DOM.');
    }
});
</script>
</body>
</html>