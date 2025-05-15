<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
if ($_SESSION['user_role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Database connection
  $conn = new mysqli('localhost', 'root', '', 'icb_portal');
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Get form values and sanitize
$product_name = $conn->real_escape_string($_POST['name']);
$About = $conn->real_escape_string($_POST['About']);
$Features = $conn->real_escape_string($_POST['Features']);
$keyword = $conn->real_escape_string($_POST['keyword']);
$product_image_url = $conn->real_escape_string($_POST['product_image_url']);
$Category = $conn->real_escape_string($_POST['Category']);
$faq = $conn->real_escape_string($_POST['faq']);
$Customer_details = $conn->real_escape_string($_POST['Customer_details']);
  $price = floatval($_POST['price']);



  // Validate form values
  if (empty($product_name) || empty($About) || empty($price)|| empty($Features) || empty($keyword) || empty($product_image_url) || empty($Category) || empty($faq) || empty($Customer_details)) {
    header("Location: ../add_product.php?error=Please+fill+in+all+fields");
    exit();
  }
  if (!is_numeric($price) || $price <= 0) {
    header("Location: ../add_product.php?error=Invalid+price");
    exit();
  }
  if (!filter_var($product_image_url, FILTER_VALIDATE_URL)) {
    header("Location: ../add_product.php?error=Invalid+image+URL");
    exit();
  }
  // Check if product already exists
  $sql = "SELECT * FROM product WHERE name='$product_name'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    header("Location: ../add_product.php?error=Product+already+exists");
    exit();
  }
  // Prepare and bind
  $stmt = $conn->prepare("INSERT INTO product (name, About, Features, keyword, product_image_url, Category, faq, Customer_details, price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssssssss", $product_name, $About, $Features, $keyword, $product_image_url, $Category, $faq, $Customer_details, $price);
  // Execute the statement
  if ($stmt->execute()) {
    header("msg=Product+added+successfully");
    exit();
  } else {
    echo "Error: " . $stmt->error;
  }
  // Close the statement and connection
  $stmt->close();
  $conn->close();
} else {
  // Redirect to the add product page if accessed directly
  header("Location: ../add_product.php");
  exit();
}

?>