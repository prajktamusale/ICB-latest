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

$product = null;

if (isset($_GET['id'])) {
    $productId = intval($_GET['id']);
    $sql = "SELECT * FROM product WHERE id = $productId";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found.";
        exit;
    }
} else {
    echo "Product ID missing in URL.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Details Page</title>
  <link rel="stylesheet" href="./css/header.css" />
  <link rel="stylesheet" href="./css/utils.css" />
  <link rel="stylesheet" href="./css/bottomNav.css" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <script src="./js/sideBar.js" defer></script>
  <script src="./js/searchBar.js" defer></script>
  <script src="./js/sliderAccordian.js" defer></script>
  <style>
    <?php include "./css/header.css"; ?>
  <?php include "./css/search.css"; ?>
    /* Existing styles for desktop view remain unchanged */
body {
  font-family: 'Roboto', sans-serif;
  margin: 0;
  padding: 0;
  background-color: #f6f6f6;
}

.container {
  display: flex;
  flex-direction: column;
  max-width: 1000px;
  margin: 20px auto;
  background-color: #fff;
  padding: 20px 30px;
  border-radius: 12px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
}

.title-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
}

.title-row h2 {
  font-size: 24px;
  font-weight: 700;
  margin: 0;
}

.tags {
  display: flex;
  gap: 10px;
  margin: 10px 0;
  flex-wrap: wrap;
}

.tag {
  padding: 5px 10px;
  border-radius: 20px;
  font-size: 14px;
  font-weight: bold;
  color: white;
}

.icb-choice { background-color: #2d9cdb; }
.customer-choice { background-color: #eb5757; }

.content-row {
  display: flex;
  flex-direction: row;
  gap: 30px;
  margin-top: 20px;
  flex-wrap: wrap;
}

.image-section {
  flex: 0 0 250px;
}

.image-section img {
  width: 100%;
  max-width: 250px;
  height: auto;
  border-radius: 10px;
}

.rating {
  font-size: 16px;
  font-weight: 700;
  margin-top: 10px;
}

.description-section {
  flex: 1;
  min-width: 250px;
}

.description-section h3 {
  margin-top: 0;
  margin-bottom: 8px;
}

.description-section p {
  font-size: 14px;
  color: #333;
}

.features {
  margin-top: auto;
}

.features ul {
  padding-left: 20px;
}

.features li {
  font-size: 14px;
  margin-bottom: 6px;
}

.action-buttons {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-top: 30px;
  flex-wrap: wrap;
}

.action-buttons a,
.action-buttons button {
  padding: 10px 20px;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  text-decoration: none;
  text-align: center;
}

.refer-btn { background-color: #3498db; color: white; }
.pitch-btn { background-color: #e74c3c; color: white; }

/* Mobile Responsive Styles */
@media screen and (max-width: 700px) {
  .container {
    padding: 16px;
    margin: 10px;
  }

  .title-row {
    flex-direction: column;
    align-items: flex-start;
  }

  .title-row h2 {
    font-size: 20px;
    margin-bottom: 10px;
  }

  .tags {
    display: flex;
    flex-direction: row; /* Keep tags in a row on mobile */
    justify-content: flex-start;
  }

  .content-row {
    flex-direction: column;
    align-items: center;
  }

  .image-section {
    flex: 1;
    max-width: 100%;
    text-align: center;
  }

  .image-section img {
    max-width: 200px; /* Slightly smaller image on mobile */
  }

  .rating {
    text-align: center;
  }

  .description-section {
    width: 100%;
    margin-top: 20px;
  }

  .action-buttons {
    flex-direction: column;
    align-items: center;
  }

  .action-buttons a,
  .action-buttons button {
    width: 100%;
    max-width: 300px; /* Limit button width on mobile */
    margin-bottom: 10px;
  }

  .features ul {
    padding-left: 16px;
  }

  /* Ensure bottom navigation is fixed on mobile */
  .bottom-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background-color: #fff;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: space-around;
    padding: 10px 0;
  }

  /* Add padding to body to prevent content from being hidden under bottom nav */
  body {
    padding-bottom: 80px; /* Adjust based on bottom nav height */
  }
}

/* Desktop-specific styles to ensure they remain unaffected */
@media screen and (min-width: 701px) {
  .bottom-nav {
    display: none; /* Hide bottom nav on desktop if not needed */
  }

  body {
    padding-bottom: 0; /* No extra padding on desktop */
  }
}
  </style>
</head>
<body>
<?php include './header.php'; ?>
<br>
<?php include './components/searchBar.php'; ?>

<div class="container">
  <div class="title-row">
    <h2><?php echo htmlspecialchars($product['name']); ?></h2>
    <div class="tags">
      <div class="tag icb-choice">ICB Choice</div>
      <div class="tag customer-choice">Customers Choice</div>
    </div>
  </div>

  <div class="content-row">
    <div class="image-section">
      <img src="<?php echo htmlspecialchars($product['product_image_url']); ?>" alt="Product Image">
      <div class="rating">Rating: <?php echo htmlspecialchars($product['Rating']); ?>/5</div>
    </div>

    <div class="description-section">
      <h3>About Product</h3>
      <p><?php echo nl2br(htmlspecialchars($product['About'])); ?></p>

      <div class="features">
        <h3>Features</h3>
        <ul>
          <?php
            if (!empty($product['Features'])) {
              $features = explode(',', $product['Features']);
              foreach ($features as $feature) {
                echo '<li>' . htmlspecialchars(trim($feature)) . '</li>';
              }
            } else {
              echo '<li>No features listed.</li>';
            }
          ?>
        </ul>
      </div>
    </div>
  </div>

  <div class="action-buttons">
    <a href="refer_product.php?id=<?php echo $product['id']; ?>" class="refer-btn">Refer Product</a>
    <a href="salesCRM.php?&status=Pitching" class="pitch-btn">Pitch Product</a>
    </div>
</div>
<?php include "./components/bottomNav.php"; ?>

</body>
</html>
