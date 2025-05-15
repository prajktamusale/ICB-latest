<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)) {
  header("Location: ./index.php");
  exit;
}
if (!isset($_SESSION['type'])) {
  header("Location: ../index.html");
  exit;
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "icb_portal";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get product id from GET or POST
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : (isset($_POST['product_id']) ? intval($_POST['product_id']) : 0);

if ($product_id > 0) {
  // Fetch product details from database
  $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $product = $stmt->get_result()->fetch_assoc();
  $stmt->close();
} else {
  $product = null;
}

// If form is submitted, get previous input values

$product_name = isset($product['name']) ? htmlspecialchars($product['name']) : '';
$category = isset($product['category']) ? htmlspecialchars($product['category']) : '';
$price = isset($product['price']) ? htmlspecialchars($product['price']) : '';
$icb_price = isset($product['icb_price']) ? htmlspecialchars($product['icb_price']) : '';
$About = isset($product['About']) ? htmlspecialchars($product['About']) : '';
$Features = isset($product['Features']) ? htmlspecialchars($product['Features']) : '';
$keyword = isset($product['keyword']) ? htmlspecialchars($product['keyword']) : '';
$faq = isset($product['faq']) ? htmlspecialchars($product['faq']) : '';
$Customer_details = isset($product['Customer_details']) ? htmlspecialchars($product['Customer_details']) : '';
$product_image_url = isset($product['product_image_url']) ? htmlspecialchars($product['product_image_url']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Final-Stage-Add-Product</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet"/>
    <style>
      <?php include "./css/header.css"; ?>
       
        </style>
    <link rel="stylesheet" href="./css/bottomNav.css">
    <link rel="stylesheet" href="./css/final_add_product.css">
    <?php 
      echo "<script>localStorage.setItem('id', ".$_SESSION['id'].");</script>";
    ?>
    <script src="./js/sliderAccordian.js" defer></script>
    <script src="./js/sideBar.js" defer></script>
</head>
<body>
<?php include "./header.php"; ?>
    <div class="container">
        <header>
            <div class="logo"></div>
            <div class="company-info">
                <h1>Mandeet India Pvt. Ltd.</h1>
                <p>Manufacturing Unit</p>
            </div>
        </header>
        
        <div class="progress-bar">
            <div class="progress-item">
                <div class="progress-circle">1</div>
            </div>
            <div class="progress-line"></div>
            <div class="progress-item">
                <div class="progress-circle ">2</div>
            </div>
            <div class="progress-line"></div>
            <div class="progress-item">
                <div class="progress-circle active">3</div>
            </div>
        </div>
        <div class="container">

<div class="product-section">
  <div class="carousel">
    <!-- Placeholder for image carousel -->
    <?php if (!empty($product_image_url)): ?>
      <img src="<?php echo htmlspecialchars($product_image_url); ?>" alt="Product Image" style="max-width:100%;max-height:300px;object-fit:contain;">
    <?php else: ?>
      <span>No image available</span>
    <?php endif; ?>
  </div>

  <div class="details">
    <h2>Name of the product</h2>
    <p><strong>Keywords:</strong> List of keywords</p>
    <p><strong>Category:</strong> Category Entered</p>
    <p><strong>MRP:</strong> Rs.</p>
    <p><strong>ICB Price:</strong> Rs.</p>

    <div class="section">
      <p><strong>Description:</strong> Lorem Ip Sum</p>
    </div>

    <div class="section">
      <p class="section-title">Features</p>
      <ul>
        <li>Lorem Ip Sum</li>
        <li>Lorem Ip Sum</li>
        <li>Lorem Ip Sum</li>
      </ul>
    </div>

    <div class="section">
      <p class="section-title">Additional Specification</p>
      <p><strong>Spec 1:</strong> Spec Description</p>
      <p><strong>Spec 2:</strong> Spec Description</p>
      <p><strong>Spec 3:</strong> Spec Description</p>
    </div>

    <div class="section">
      <p class="section-title">FAQ</p>
      <p><strong>Question 1</strong></p>
      <p>Answer 1</p>
      <p><strong>Question 2</strong></p>
      <p>Answer 2</p>
    </div>
  </div>
</div>

<div class="form-section">
  <h3>Data Required</h3>
  <p><strong>Customer Details</strong></p>
  <div class="form-group">
    <i>👤</i><input type="text" placeholder="Customer Name" />
  </div>
  <div class="form-group">
    <i>📞</i><input type="text" placeholder="Phone" />
  </div>
  <div class="form-group">
    <i>📧</i><input type="email" placeholder="Email" />
  </div>
  <div class="form-group">
    <i>🆔</i><input type="text" placeholder="Aadhar" />
  </div>
  <div class="form-group">
    <i>🆔</i><input type="text" placeholder="PAN" />
  </div>
  <div class="form-group">
    <i>🔢</i><input type="number" placeholder="Quantity" />
  </div>
</div>

</div>


<div class="additional-section">
        <h4>Additional Data Required</h4>
        <div id="keyValueContainer">
          <div class="key-value-row">
            <input type="text" placeholder="Key" />

          </div>
        </div>
        <button class="add-button" onclick="addKeyValueRow()">➕</button>

        <div class="button-row">
          <button class="btn btn-back" onclick="window.location.href='Customer_details.php'">⬅️ Back</button>
          <button class="btn btn-save">Save ✅</button>
        </div>
      </div>
    </div>
    <?php include "./components/bottomNav.php"; ?>
    
    <script>
    function addKeyValueRow() {
      const container = document.getElementById('keyValueContainer');
      const row = document.createElement('div');
      row.className = 'key-value-row';
      row.innerHTML = `
        <input type="text" placeholder="Key" />
       
      `;
      container.appendChild(row);
    }
    function handleSubmit(event) {
      event.preventDefault(); // Prevent real submission for now
      const form = document.getElementById('additionalForm');
      const formData = new FormData(form);

      const keys = formData.getAll('keys[]');
      const values = formData.getAll('values[]');

      console.log("Submitted Data:");
      keys.forEach((key, i) => {
        console.log(`- ${key} : ${values[i]}`);
      });

      alert("Form submitted successfully! (Check console)");
      // Optional: Send to backend using fetch/ajax here
    }
  </script>

</body>
</html>