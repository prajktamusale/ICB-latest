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

$host = "localhost";
$user = "root";
$password = "";
$dbname = "icb_portal";

$conn = new mysqli($host, $user, $password, $dbname);
include './config.php';
$sql = 'SELECT * FROM success_stories';

$result = mysqli_query($mysqli, $sql) or die('Data fetching issues');
$successStories = [];
if (mysqli_num_rows(($result))>0)
{
  while($row = mysqli_fetch_assoc($result)){
    $successStories[] = $row;
}
}
$sql2 = 'SELECT * FROM product';
$result2 = mysqli_query($mysqli, $sql2) or die('Error fetching products');
$products = [];
if (mysqli_num_rows($result2) >0)
{
  while($row = mysqli_fetch_assoc($result2)){
    $products[] = $row;
}
}
mysqli_close($mysqli);
$borderColor= '#4B0082';
$_SESSION["profile_border"] = '#4B0082';
$checkSrc= './images/verify-asscociate.png'; 

$user_id = $_SESSION['id'];

// Initialize total income and monthly sales
$total_income = 0;
$monthly_sales = 0;

// Calculate monthly sales
$current_month = date('m'); // Current month
$current_year = date('Y'); // Current year

$query_orders = "SELECT product_id FROM order_table WHERE MONTH(Orderdate) = ? AND YEAR(Orderdate) = ?";
$stmt_orders = $conn->prepare($query_orders);
$stmt_orders->bind_param("ii", $current_month, $current_year);
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result();

if ($result_orders->num_rows > 0) {
    while ($order = $result_orders->fetch_assoc()) {
        $product_id = $order['product_id'];

        // Fetch the associate share for the product from the product table
        $query_product = "SELECT Associate_Share FROM product WHERE id = ?";
        $stmt_product = $conn->prepare($query_product);
        $stmt_product->bind_param("i", $product_id);
        $stmt_product->execute();
        $result_product = $stmt_product->get_result();

        if ($result_product->num_rows > 0) {
            $product = $result_product->fetch_assoc();
            $monthly_sales += $product['Associate_Share']; // Add the associate share to the monthly sales
        }
    }
}
$max_people_to_add = 3; // Initially, the user can add 3 people
if ($monthly_sales >= 50000) {
    $max_people_to_add += floor($monthly_sales / 50000) * 3; // Add 3 people for every ₹50,000 in sales
}
// Fetch the current number of people added by the logged-in user
$query_current_people = "SELECT id, username, mobile, joining_fee FROM users WHERE referred_by = ?";
$stmt_current_people = $conn->prepare($query_current_people);
$stmt_current_people->bind_param("i", $user_id);
$stmt_current_people->execute();
$result_current_people = $stmt_current_people->get_result();

$rows = [];
if ($result_current_people->num_rows > 0) {
    while ($row = $result_current_people->fetch_assoc()) {
        $row['income'] = 5000; // Default income is ₹5,000
        $row['share_amount'] = $row['income'] * 0.05; // Calculate 5% share
        $row['joining_fee_share'] = $row['joining_fee'] * 0.10; // Calculate 10% of joining fee
        $rows[] = $row; // Store the downline member's data
    }
}

// Limit the rows to the maximum number of people allowed
$rows = array_slice($rows, 0, $max_people_to_add);

// Calculate total income for the logged-in user based on the displayed rows
foreach ($rows as $row) {
    $total_income += $row['share_amount']; // Add 5% share from downline users
    $total_income += $row['joining_fee_share']; // Add 10% of joining fee
}

$total_earning=0;
$total_earning= $monthly_sales + $total_income;

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Associate Dashboard-Care for Bharath</title>
  <link rel="stylesheet" href="./css/donate.css" />
  <link rel="stylesheet" href="./css/header2.css" />
  <link rel="stylesheet" href="./css/utils.css" />
  <link rel="stylesheet" href="./css/dashboard-2.css" />
  <link rel="stylesheet" href="./css/search.css">
  <!-- <link rel="stylesheet" href="./css/index.css" /> -->
  <!-- <link rel="stylesheet" href="./css/global.css" /> -->
  <!-- Swiper CSS -->
  <link rel="stylesheet" href="./css/swiper-bundle.min.css" />
  <!-- <link rel="stylesheet" href="./css/servicecard.css" /> -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@3.1.9/dist/css/splide.min.css">
  <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@3.1.9/dist/js/splide.min.js"></script>


  <style>
    <?php include "./css/header2.css" ?><?php include "./css/search.css" ?><?php include "./css/servicecard.css" ?><?php include "./css/dashboard-2.css" ?>
   

  </style>
  <link rel="stylesheet" href="./css/bottomNav.css">
<script src="./js/sideBar.js" defer></script>
  <script src="./js/sliderAccordian.js" defer></script>
  <script src="./js/sideBar.js" defer></script>
  <script src="./js/servicecard1.js" defer></script>
  <script src="./js/swiper-bundle.min.js" defer></script>

</head>

<body>
  <!-- Navigation Bar -->
  <?php
  include './header.php';
  ?><br>
  <div class="welcome">
    <div class="welcome-background"></div>
    <!-- <div class="welcome-strip"></div> -->
    <div class="white-strip">
      <div class="important-message">
        <span class="message-text"><span>Important message goes here.....Important message goes here.....Important
            message goes here.....Important message goes here.....Important message goes here.....Important message
            goes here.....Important message goes here.....Important message goes here.....Important message goes
            here.....
      </div>
    </div>
    <!-- <div class="profile-icon">
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
          
        </svg>
      </div> -->

    <div class="dashboard-text">Dashboard</div> 
    <div class="profile-container">
      <div class="profile-img">
        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="81" viewBox="0 0 80 81" fill="none">
          <path
            d="M78.8892 40.5C78.8892 62.6009 61.3318 80.5 39.6946 80.5C18.0574 80.5 0.5 62.6009 0.5 40.5C0.5 18.3991 18.0574 0.5 39.6946 0.5C61.3318 0.5 78.8892 18.3991 78.8892 40.5Z"
            fill="#464646" stroke="#2196F3" />
        </svg>
        <?php echo "<img class='check " . $display . "' src='" . $checkSrc . "' alt='Verified'>"; ?>
        </div>
    </div>
    <div class="welcome-text">Welcome to ICB</div><br><span class="uid-text">UID: 16426790</span>
    <hr class="stright-line">



    <div class="welcome-container">
      <a href="#"></a>
      <a href="#"></a>
      <a href="#"></a>
      <a href="#"></a>
    </div>
    <div class="cards-container">
      <div class="card card-left">
        <div class="card-content">
          <div><b class="card-title">Total Sells<img class="welcome-icon" src="images/undefined2.png" alt="Icon"
                width="30" height="30"></b></div>
          <div class="card-icon">

            <b class="card-text">&nbsp;₹<?php echo number_format($monthly_sales); ?></b>
          </div>

          <a href="#" class="card-link" target="_blank"></a>
        </div>
      </div>
      <div class="card card-center">
        <div class="card-content">
          <div><b class="card-title">Total Earning<img class="welcome-icon" src="images/undefined2.png" alt="Icon"
                width="30" height="30"></b></div>
          <div class="card-icon">
            <b class="card-text">&nbsp;₹<?php echo number_format($total_earning);?></b>
          </div>

          <a href="#" class="card-link" target="_blank"></a>
        </div>
      </div>
      <div class="card card-right">
        <div class="card-content">
          <div class="card-content-container">
            <div class="card-title">My Network<img class="welcome-icon" src="images/undefined3.png" alt="Icon"
                width="30" height="30"></b>
                <a href="manageDownline.php" style="text-decoration: none;">
    <div class="card-icon"><b class="card-text" >40</b></div> </div>
</a></div>
          


            <!-- <div class="card-icon"> -->
          </div>
          <a href="#" class="card-link" target="_blank"></a>
        </div>
      </div>
    </div>
  </div>
<br>
  <?php {
    include "./components/searchBar.php";
  }
  ?>


  <!--required breaks dont remove -->
  <!-- strt -->
  <!-- Refer A FREIEND BAR -->
  <div class="cta-container">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="button-container">
            <div class="custom-button green-button">
              <a href="products.php">Refer a Friend</a>
            </div>
            <div class="custom-button blue-button">
              <a href="enquire-now.php">Add to my Network</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="main-container1-a">My Products</div>
  <div class="main-container1 slide-container">
    <div class="swipper-button-group">

      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </div>
    <div class="slide-content">
      <div class="swiper-wrapper">
      <?php foreach ($products as $product): ?>
  <div class="product-outer swiper-slide">
    <div class="product-component swiper-slide">
      <div class="card-image">
        <img src="<?php echo htmlspecialchars($product['product_image_url']); ?>" alt="Product Image" class="card-img" />
      </div>
      <div class="card-content">
      <h2 class="name" style="font-size: 18px; font-weight: 700; margin: 8px 0;"><?php echo $product['name']; ?></h2>
      <div class="product_details" style="display: flex; align-items: center; justify-content: space-between; margin-top: 12px;">
        <div class="product_details__bought" style="font-weight: 600; font-size: 16px;">200+</div>

        <a href="salesCRM.php?id=<?php echo $product['id']; ?>" style="text-decoration: none;">
          <button class="product_details__button" style="background-color: #007bff; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-weight: 600; cursor: pointer;">
            Pitch Product
          </button>
        </a>

        <div class="product_details__watchlater" style="margin-left: 6px;">
          <img src="./images/watch_later.png" alt="watch_later" style="width: 20px; height: 20px;">
        </div>
      </div>
    </div>
    </div>
  </div>
<?php endforeach; ?>

        
        
 </div>
      </div>

      <!-- <div class="swiper-pagination"></div> -->
    </div>
  <br>
  <!-- SECOND SLIDE OF  PRODUCTS CARDS -->
  <!--div class="main-container1 slide-container">
    <div class=" swiper mySwiper2">
      <div class="swiper-wrapper">
        <div class="product-component swiper-slide">
          <div class="card-image">
            <img src="images/undefined6.png" alt="" class="card-img" />
          </div>

          <div class="card-content">
            <h2 class="name">SMART WATCH</h2>
            <button class="button" id="button2"><b>PITCH PRODUCT</b></button>
          </div>
        </div>
        <div class="product-component swiper-slide">
          <div class="card-image">
            <img src="images/undefined6.png" alt="" class="card-img" />
          </div>

          <div class="card-content">
            <h2 class="name">SMART WATCH</h2>
            <button class="button" id="button2"><b>PITCH PRODUCT</b></button>
          </div>
        </div>
        <div class="product-component swiper-slide">
          <div class="card-image">
            <img src="images/undefined6.png" alt="" class="card-img" />
          </div>

          <div class="card-content">
            <h2 class="name">SMART WATCH</h2>
            <button class="button" id="button2"><b>PITCH PRODUCT</b></button>
          </div>
        </div>
        <div class="product-component swiper-slide">
          <div class="card-image">
            <img src="images/undefined6.png" alt="" class="card-img" />
          </div>

          <div class="card-content">
            <h2 class="name">SMART WATCH</h2>
            <button class="button" id="button2"><b>PITCH PRODUCT</b></button>
          </div>
        </div>
        <div class="product-component swiper-slide">
          <div class="card-image">
            <img src="images/undefined6.png" alt="" class="card-img" />
          </div>
          <div class="card-content">
            <h2 class="name">SMART WATCH</h2>
            <button class="button" id="button2"><b>PITCH PRODUCT</b></button>
          </div>
        </div>
        <div class="product-component swiper-slide">
          <div class="card-image">
            <img src="images/undefined6.png" alt="" class="card-img" />
          </div>

          <div class="card-content">
            <h2 class="name">SMART WATCH</h2>
            <button class="button" id="button2"><b>PITCH PRODUCT</b></button>
          </div>
        </div>
        <div class="product-component swiper-slide">
          <div class="card-image">
            <img src="images/undefined6.png" alt="" class="card-img" />
          </div>

          <div class="card-content">
            <h2 class="name">SMART WATCH</h2>
            <button class="button" id="button2"><b>PITCH PRODUCT</b></button>
          </div>
        </div>
      </div>
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
      <!-- <div class="swiper-pagination"></div> -->







  <br>
  <!-- IMAGE SLIDER DONT MOVE THE DIVS -->
  <b class="main-container-4a">Recommended</b>
<br>
<div class="main-container1 slide-container">
    <div class="swipper-button-group">

      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </div>
    <div class="slide-content">
      <div class="swiper-wrapper">
      <?php foreach ($products as $product): ?>
  <div class="product-outer swiper-slide">
    <div class="product-component swiper-slide">
      <div class="card-image">
        <img src="<?php echo htmlspecialchars($product['product_image_url']); ?>" alt="Product Image" class="card-img" />
      </div>
       <div class="card-content">
      <h2 class="name" style="font-size: 18px; font-weight: 700; margin: 8px 0;"><?php echo $product['name']; ?></h2>
      <div class="product_details" style="display: flex; align-items: center; justify-content: space-between; margin-top: 12px;">
        <div class="product_details__bought" style="font-weight: 600; font-size: 16px;">200+</div>

        <a href="product_detail_page.php?id=<?php echo $product['id']; ?>" style="text-decoration: none;">
          <button class="product_details__button" style="background-color: #007bff; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-weight: 600; cursor: pointer;">
            Pitch Product
          </button>
        </a>

        <div class="product_details__watchlater" style="margin-left: 6px;">
          <img src="./images/watch_later.png" alt="watch_later" style="width: 20px; height: 20px;">
        </div>
      </div>
    </div>
    </div>
  </div>
<?php endforeach; ?>

    </div>   
        </div>
 </div>
      </div>


  <br>
  <!--DONT REMOVE THIS BELOW DIV -->
  <div class="my-slider-progress">
    <div class="my-slider-progress-bar"></div>
  </div>
<!-- testimonial start HTML Structure -->
  <div class="main-container3">
  <b class="main-container3-a">Success Story</b>

  <div class="Testimonial">
    <div class="splide testimonial-splide-instance">
      <div class=" splide__track">
      <ul class=" splide__list">
  <?php
  $chunks = array_chunk($successStories, 1); // Group by 2
  foreach ($chunks as $group):
  ?>
    <!-- <li class="splide__slide"> -->
    <!-- <div class="card-slide-wrapper" style="display: flex; justify-content: center; gap: 200px;"> -->
        <!-- <?php foreach ($group as $story): ?> -->
          <!-- <div class="testimonial-card" style="flex: 0 0 0 50%; background: #fff; border-radius: 16px; padding: 10px; margin-left: 30px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            <div class="testimony">
              <img src="<?php echo $story['image_url']; ?>" alt="Image" class="testimonial-image"  />
              <div class="testimonial-content">
                <h3 class = "testimonial-name"><?php echo htmlspecialchars($story['Name']); ?></h3>
                <p class="testimonial-description"><?php echo htmlspecialchars($story['Description']); ?></p>
                <div class="testimonial-card-footer">
  <b class="Profit-text2">Profit: <?php echo htmlspecialchars($story['Profit_Earn']); ?> Lakh</b>
</div>

              </div>
            </div>
          </div>
          </div> -->
        <!-- <?php endforeach; ?> -->
      
    <!-- </li> -->

    <li class="splide__slide">
    
        <?php foreach ($group as $story): ?>
            <div class="testimonial-card" style="flex: 0 0 0 50%; background: #fff;  padding: 10px;  box-shadow: 0 4px 10px rgba(0,0,0,0.1); margin-right: 30px; border-radius: 0%;">
                <div class="testimony">
                    <img src="<?php echo htmlspecialchars($story['image_url']); ?>" alt="Testimonial Image" class="testimonial-image" />
                    <div class="testimonial-content">
                        <h3 class="testimonial-name"><?php echo htmlspecialchars($story['Name']); ?></h3>
                        <p class="testimonial-description"><?php echo htmlspecialchars($story['Description']); ?></p>
                        <div class="testimonial-profit">
        <b>Profit: <?php echo htmlspecialchars($story['Profit_Earn']); ?> Lakh</b>
    </div>
                    </div>
                </div>
            
        <?php endforeach; ?>
    </div>
</li>

        
  <?php endforeach; ?>
</ul>

      </div>
    </div>
  </div>
</div>


  <br><br>


  <?php include "./components/bottomNav.php"; ?>  <!-- ALL THE SCRIPTS START -->
  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
  <script src="./js/servicecard1.js"></script>
  <script src="./js/Testimonial.js"></script>
  <script src="./js/swiper-bundle.min.js"></script>

  <!-- testimonial script -->
  <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@3.1.9/dist/js/splide.min.js"></script>
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    new Splide('.testimonial-splide-instance', {
      type: 'loop',
      perPage: 2,
      gap: '1rem',
      pagination: true,
      arrows: true,
      breakpoints: {
        768: { perPage: 1 }
      }
    }).mount();
  });
</script>


  <!-- image slider -->
  <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@3.1.9/dist/js/splide.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var splide = new Splide('.splide', {
        type: 'loop', // Enable loop mode
        perPage: 2, // Display one slide at a time
        autoplay: true; // Enable automatic sliding
        interval: 3000, // Set the interval to 5 seconds
        speed: 2000, // Adjust the speed for smoother transitions (milliseconds)
        arrows: true,
      breakpoints: {
        768: { perPage: 1 }
      }
      });

      var bar = document.querySelector('.my-slider-progress-bar');

      splide.on('mounted move', function () {
        var end = splide.Components.Controller.getEnd() + 1;
        var rate = Math.min((splide.index + 1) / end, 1);
        bar.style.width = String(100 * rate) + '%';
      });

      splide.mount();
    });
  </script>
  
</body>

</html>
