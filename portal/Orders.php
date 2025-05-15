<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)) {
  header("Location: ./index.php");
}
if (!isset($_SESSION['type'])) {
  header("Location: ../index.html");
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "icb_portal";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$query = "
  SELECT 
    o.order_id,
    o.quantity,
    o.Orderdate,
    c.name AS customer_name,
    c.email,
    c.whatsapp,
   p.name AS product_name
  FROM order_table o
  LEFT JOIN customer c ON o.customer_id = c.id
  LEFT JOIN product p ON o.product_id = p.id
";
$result = mysqli_query($conn, $query);
$totalOrders = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<head>
  <meta charset="UTF-8">
  <title>Order List</title>
  <style>
    <?php include "./css/header.css" ?>
    body {
      font-family: Arial, sans-serif;
      background: #f9f9f9;
      margin: 20px;
    }

    .company-header {
      display: flex;
      align-items: center;
      margin-bottom: 30px;
    }

    .company-header .info {
      font-size: 14px;
    }

    .company-header .info b {
      color: #003366;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
      background: #e9ecf3;
      border-radius: 8px;
      overflow: hidden;
    }

    thead {
      background: #d1d5db;
    }

    thead th {
      padding: 10px;
      text-align: left;
      font-weight: bold;
    }

    tbody tr:nth-child(even) {
      background: #f0f3f9;
    }

    tbody td {
      padding: 10px;
      vertical-align: middle;
    }

    .show-button {
      background: #007bff;
      border: none;
      color: white;
      padding: 6px 12px;
      border-radius: 20px;
      cursor: pointer;
      font-size: 14px;
    }

    .show-button:hover {
      background: #0056b3;
    }

    .summary {
      width: 100%;
      background: #d1d5db;
      border-radius: 8px;
      padding: 10px;
      font-weight: bold;
      display: flex;
      justify-content: flex-end;
    }

    #orderDetailsSlider {
      position: fixed;
      top: 0;
      right: 0;
      width: 350px;
      height: 100vh;
      background: #e3e3e3;
      border-left: 3px solid #007bff;
      padding: 20px;
      box-shadow: -2px 0 10px rgba(0,0,0,0.2);
      overflow-y: auto;
      z-index: 1000;
      display: none;
    }

    .slider-content h3 {
      margin-top: 0;
    }

    .details-scroll p {
      margin: 10px 0;
    }

    .close-btn {
      background: red;
      color: white;
      border: none;
      font-weight: bold;
      float: right;
      font-size: 16px;
      cursor: pointer;
    }

@media (max-width: 768px) {
  body {
    margin: 10px;
  }

  .company-header {
    flex-direction: column;
    align-items: flex-start;
  }

  table {
    font-size: 12px;
  }

  thead th, tbody td {
    padding: 8px;
  }

  .show-button {
    padding: 4px 8px;
    font-size: 12px;
  }

  .summary {
    flex-direction: column;
    align-items: flex-start;
    font-size: 14px;
  }

  #orderDetailsSlider {
   
    position: fixed;
    bottom: 0;
    box-sizing: border-box;
    border-left: none;
    border-top: 3px solid #007bff;
  }

  .slider-content {
    padding: 10px;
  }

  .close-btn {
    font-size: 14px;
  }

  .details-scroll p {
    font-size: 14px;
    margin: 5px 0;
  }

  .details-scroll p strong {
    color: #333;
  }

  .details-scroll span {
    color: #555;
  }

  .details-scroll p {
    font-size: 12px;
  }
}


  </style>
  <link rel="stylesheet" href="./css/bottomNav.css">
  <?php echo "<script>localStorage.setItem('id', ".$_SESSION['id'].");</script>"; ?>
  <script src="./js/sliderAccordian.js" defer></script>
  <script src="./js/sideBar.js" defer></script>
</head>
<body>
<?php include './header.php'; ?>

<div class="company-header">
  <div class="info">
    <b>Mandet India Pvt. Ltd.</b><br>
    Revolutionizing tech
  </div>
</div>

<table id="ordersTable">
  <thead>
    <tr>
      <th>Order ID</th>
      <th>Client Name</th>
      <th>Service</th>
      <th>Date of Purchase</th>
      <th>Phone</th>
      <th>Email</th>
      <th>Quantity</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody id="orderBody">
    <?php
    if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['order_id']}</td>";
        echo "<td>{$row['customer_name']}</td>";
        echo "<td>{$row['product_name']}</td>";
        echo "<td>{$row['Orderdate']}</td>";
        echo "<td>{$row['whatsapp']}</td>";
        echo "<td>{$row['email']}</td>";
        echo "<td>{$row['quantity']}</td>";
        echo "<td><button class='show-button' 
          data-id='{$row['order_id']}'
          data-client='{$row['customer_name']}'
          data-service='{$row['product_name']}'
          data-date='{$row['Orderdate']}'
          data-phone='{$row['whatsapp']}'
          data-email='{$row['email']}'
          data-quantity='{$row['quantity']}'
        
          onclick='showSlider(this)'>Show</button></td>";
        echo "</tr>";
      }
    }
    ?>
  </tbody>
</table>

<div class="summary">
  Total Orders: <span id="orderCount"><?= $totalOrders ?></span>
</div>

<!-- Order Details Slider -->
<div id="orderDetailsSlider">
  <div class="slider-content">
    <button class="close-btn" onclick="closeSlider()">X</button>
    <h3>Order Details</h3>
    <div class="details-scroll">
      <p><strong>Order ID:</strong> <span id="detail_order_id"></span></p>
      <p><strong>Client Name:</strong> <span id="detail_client"></span></p>
      <p><strong>Service:</strong> <span id="detail_service"></span></p>
      <p><strong>Date of Purchase:</strong> <span id="detail_date"></span></p>
      <p><strong>Phone:</strong> <span id="detail_phone"></span></p>
      <p><strong>Email:</strong> <span id="detail_email"></span></p>
      <p><strong>Quantity:</strong> <span id="detail_quantity"></span></p>
     
    </div>
  </div>
</div>

<?php include "./components/bottomNav.php"; ?>

<script>
  function showSlider(button) {
    document.getElementById("detail_order_id").innerText = button.dataset.id;
    document.getElementById("detail_client").innerText = button.dataset.client;
    document.getElementById("detail_service").innerText = button.dataset.service;
    document.getElementById("detail_date").innerText = button.dataset.date;
    document.getElementById("detail_phone").innerText = button.dataset.phone;
    document.getElementById("detail_email").innerText = button.dataset.email;
    document.getElementById("detail_quantity").innerText = button.dataset.quantity;
   

    document.getElementById("orderDetailsSlider").style.display = "block";
  }

  function closeSlider() {
    document.getElementById("orderDetailsSlider").style.display = "none";
  }
</script>

</body>
</html>
