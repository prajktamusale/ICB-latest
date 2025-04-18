<?php
  if (session_status() == PHP_SESSION_NONE) session_start();
  if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)) {
    header("Location: ./index.php");
    exit;
  }
  if (!isset($_SESSION['type'])) {
    header("Location: ../index.html");
    exit;
  }

  $productList = [];
  $conn = new mysqli("localhost", "root", "", "icb_portal");
  if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

  $sql = "SELECT * FROM product";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $productList[$row['id']] = $row['name'];
    }
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
  <link rel="stylesheet" href="./css/utils.css" />
  <link rel="stylesheet" href="./css/salesCRM.css" />
  <link rel="stylesheet" href="./css/search.css" />
  <link rel="stylesheet" href="./css/notification.css" />
  <link rel="stylesheet" href="./css/bottomNav.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <script>localStorage.setItem('id', <?php echo $_SESSION['id']; ?>);</script>
  <script src="./js/sliderAccordian.js" defer></script>
  <script src="./js/sideBar.js" defer></script>
  <script src="./js/salesCRM.js" defer></script>
  <script src="./js/searchBar.js" defer></script>
  <script src="./js/notification.js" defer></script>
  <style>
    .top-bar {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      padding: 10px 20px;
      background: transparent;
    }
    .explore-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background-color: #3498db;
      color: white;
      text-decoration: none;
      padding: 10px 15px;
      border-radius: 8px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
      transition: background-color 0.3s ease;
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
</head>
<body>

  <div class="top-bar">
    <a href="products.php" class="explore-btn">
      <img src="./images/Explore.png" alt="Explore Icon" class="explore-icon">Explore
    </a>
  </div>

  <?php include './header.php'; ?>
  <?php include './components/searchBar.php'; ?>

  <!-- Forms & Tables Section -->
  <?php include './components/formsSection.php'; ?>
  <?php include './components/salesTables.php'; ?>

  <!-- Total Amount Section -->
  <div class="total-amount" id="total_amount">
    <div>Grand Total:</div>
    <div id="payment-amount">
      ₹<?php echo "20370/-"; ?>
    </div>
    <form method="post" action="#"><input type="submit" class="form_btn" value="Pay" name="total_pay"></form>
  </div>

  <!-- Notifications and Bottom Nav -->
  <?php include "./components/notification.php"; ?>
  <?php include "./components/bottomNav.php"; ?>

  <!-- Dynamic Product Load Script -->
  <script defer>
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        let ids = "<option value=''>Select Product ID</option>";
        let names = "<option value=''>Select Product Name</option>";
        let products = JSON.parse(this.responseText);
        for (const key in products) {
          ids += `<option value='${key}'>${key}</option>`;
          names += `<option value='${products[key]}'>${products[key]}</option>`;
        }
        document.getElementById("product_id").innerHTML = ids;
        document.getElementById("product_name").innerHTML = names;
      }
    };
    xmlhttp.open("GET", `database/getProductList.php`, true);
    xmlhttp.send();
  </script>

</body>
</html>
