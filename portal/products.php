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

$searchOn = '';
$productList = [];

$conn = new mysqli("localhost", "root", "", "icb_portal");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM product";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $productList[] = $row;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Products Catalog: Care For Bharat</title>
  <link rel="stylesheet" href="./css/header.css" />
  <link rel="stylesheet" href="./css/product.css" />
  <link rel="stylesheet" href="./css/utils.css" />
  <link rel="stylesheet" href="./css/bottomNav.css" />
  <script src="./js/product_catalog.js" defer></script>
  <script src="./js/sideBar.js" defer></script>
  <script src="./js/searchBar.js" defer></script>
  <script src="./js/sliderAccordian.js" defer></script>

  <style>
  <?php include "./css/header.css"; ?>
  <?php include "./css/search.css"; ?>
  <?php include "./css/product.css"; ?>
  <?php include "./css/productcard.css"; ?>

 /* General Styles */
.search-container {
    padding: 1px;
    background-color: #f9f9f9;
}

.search-tools-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: inherit;
}

.ProductSearch__options {
    position: relative;
}

.ProductSearch__options__icon {
    background: #f2f2f2;
    padding: 8px;
    border-radius: 12px;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    width: 32px;
    height: 32px;
}

.ProductSearch__options__dropdown {
    display: none;
    position: absolute;
    top: 42px;
    right: 0;
    background-color: #fff;
    border-radius: 12px;
    min-width: 240px;
    max-width: 350px;
    z-index: 1000;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    padding: 10px 14px;
    overflow-y: auto;
    max-height: 360px;
}

.sort_options {
    font-size: 14px;
    color: #333;
    padding: 10px 12px;
    cursor: pointer;
    border-radius: 8px;
    transition: background-color 0.2s ease;
}

.sort_options:hover {
    background-color: #f2f2f2;
}

.sort_options.sort_options__active {
    font-weight: 600;
    background-color: #e6f3ff;
    color: #0077cc;
}

.filter-sidebar-container {
    display: flex;
    border: 1px solid #ccc;
    border-radius: 12px;
    overflow: hidden;
    max-width: 300px;
    width: 400px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.filter-tab-menu {
    width: 100px;
    border-right: 1px solid #eee;
    background-color: #fafafa;
    display: flex;
    flex-direction: column;
}

.filter-tab {
    padding: 10px;
    font-weight: 600;
    font-size: 14px;
    color: #333;
    cursor: pointer;
    border-left: 4px solid transparent;
    transition: background 0.3s;
    white-space: nowrap;
}

.filter-tab:hover,
.filter-tab.active {
    background-color: #f2f2f2;
    border-left: 4px solid #0077cc;
    color: #0077cc;
}

.filter-tab-content {
    flex: 1;
    padding: 12px 16px;
    overflow-y: auto;
    min-width: 180px;
}

.filter-tab-panel {
    display: none;
}

.filter-tab-panel.active {
    display: block;
}

.filter-tab-panel label {
    display: block;
    margin-bottom: 10px;
    font-size: 14px;
}

.filter-tab-panel input[type="range"] {
    width: 100%;
}

.selected-sort {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    display: inline-block;
    cursor: pointer;
    background: #eef6ff;
    font-weight: bold;
}

.ProductSearch__options__dropdown::-webkit-scrollbar {
    width: 6px;
}

.ProductSearch__options__dropdown::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.1);
    border-radius: 4px;
}

/* Mobile-Specific Styles */
@media screen and (max-width: 768px) {
    .ProductSearch__options__dropdown {
        min-width: 180px;
        max-height: 280px;
        top: 36px;
        left: auto;
        right: 0;
        font-size: 12px;
    }
    
    .ProductSearch__options__icon {
        width: 28px;
        height: 28px;
        padding: 6px;
    }

    .sort_options {
        padding: 6px 10px;
        font-size: 12px;
    }

    .filter-sidebar-container {
        width: 100%;
        flex-direction: column;
    }
    
    .filter-tab-menu {
        width: 100%;
        display: flex;
        flex-direction: row;
        overflow-x: auto;
        border-bottom: 1px solid #eee;
    }
    
    .filter-tab {
        flex: 1;
        text-align: center;
        border-left: none;
        border-bottom: 4px solid transparent;
    }
    
    .filter-tab:hover,
    .filter-tab.active {
        border-left: none;
        border-bottom: 4px solid #0077cc;
    }
}

  </style>
</head>
<body>
  <?php include './header.php'; ?>
  <div class="search-container">
    <div class="search-tools-wrapper">
      <?php include './components/searchBar.php'; ?>
      <div class="ProductSearch__options">
        <img src="./images/sort.png" alt="Sort" class="ProductSearch__options__icon" id="sortButton">
        <div class="ProductSearch__options__dropdown" id="sortOptions">
          <div class="sort_options" id="sort_price_asc">Sort By Price ↑</div>
          <div class="sort_options sort_options__active" id="sort_price_desc">Sort By Price ↓</div>
          <div class="sort_options" id="sort_popularity_asc">Sort By Popularity ↑</div>
          <div class="sort_options" id="sort_popularity_desc">Sort By Popularity ↓</div>
          <div class="sort_options" id="sort_arrival_asc">Sort By Arrival ↑</div>
          <div class="sort_options" id="sort_arrival_desc">Sort By Arrival ↓</div>
        </div>
      </div>
      <div class="ProductSearch__options">
        <img src="./images/filter.png" alt="Filter Button" class="ProductSearch__options__icon" id="filterButton">
        <div class="ProductSearch__options__dropdown" id="filterOptions">
          <div class="filter-sidebar-container">
            <div class="filter-tab-menu">
              <div class="filter-tab active" data-tab="brand">Brand</div>
              <div class="filter-tab" data-tab="price">Price</div>
              <div class="filter-tab" data-tab="type">Product Type</div>
            </div>
            <div class="filter-tab-content">
              <div class="filter-tab-panel active" id="tab-brand">
                <label><input type="checkbox" name="brand" value="Adidas"> Smart-Watch</label>
                <label><input type="checkbox" name="brand" value="Reebok"> Altra-Watch</label>
                <label><input type="checkbox" name="brand" value="Puma"> Fasttrack-Watch</label>
              </div>
              <div class="filter-tab-panel" id="tab-price">
                <input type="range" min="0" max="10000" step="100">
              </div>
              <div class="filter-tab-panel" id="tab-type">
                <label><input type="checkbox" name="type" value="Type 4"> Type 4</label>
                <label><input type="checkbox" name="type" value="Type 5"> Type 5</label>
                <label><input type="checkbox" name="type" value="Type 6"> Type 6</label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="main-catalog">
    <div class="product-catalog">
      <div class="scroll-container">
        <?php for ($i = 0; $i < 1; $i++) {
          include "./components/productcard_products.php";
        } ?>
      </div>
    </div>
  </div>
  <?php include "./components/bottomNav.php"; ?>
  <script>
 document.addEventListener("DOMContentLoaded", function () {
  const sortOptions = document.querySelectorAll(".sort_options");

  sortOptions.forEach(option => {
    option.addEventListener("click", function () {
      // Remove active class from all options
      sortOptions.forEach(opt => opt.classList.remove("sort_options__active"));

      // Add active class to the clicked option
      this.classList.add("sort_options__active");
    });
  });

  document.getElementById("sortButton").addEventListener("click", function (e) {
    e.stopPropagation();
    const dropdown = document.getElementById("sortOptions");
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    document.getElementById("filterOptions").style.display = "none";
  });

  document.getElementById("filterButton").addEventListener("click", function (e) {
    e.stopPropagation();
    const dropdown = document.getElementById("filterOptions");
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    document.getElementById("sortOptions").style.display = "none";
  });

  document.getElementById("filterOptions").addEventListener("click", function (e) {
    e.stopPropagation();
  });

  document.getElementById("sortOptions").addEventListener("click", function (e) {
    e.stopPropagation();
  });

  document.addEventListener("click", function () {
    document.getElementById("sortOptions").style.display = "none";
    document.getElementById("filterOptions").style.display = "none";
  });
});
const tabs = document.querySelectorAll(".filter-tab");
  const panels = document.querySelectorAll(".filter-tab-panel");

  tabs.forEach(tab => {
    tab.addEventListener("click", (e) => {
      e.stopPropagation(); // prevent outside click from triggering close
      tabs.forEach(t => t.classList.remove("active"));
      tab.classList.add("active");
      const selected = tab.getAttribute("data-tab");
      panels.forEach(panel => {
        panel.classList.remove("active");
        if (panel.id === "tab-" + selected) {
          panel.classList.add("active");
        }
      });
    });
  });



 

  
</script>

</body>
</html>