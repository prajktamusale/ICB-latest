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
$searchOn = ''
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Products Catalog: Care For Bharat</title>
  <!-- Splidejs -->
  <link rel="stylesheet" href="url-to-cdn/splide.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="./css/donate.css" />
  <link rel="stylesheet" href="./css/header.css" />
  <link rel="stylesheet" href="./css/product.css" />
  <link rel="stylesheet" href="./css/utils.css">
  <style>
    <?php include "./css/header.css" ?>
    <?php include "./css/search.css" ?>
    /* <?php include "./css/products.css" ?> */
    <?php include "./css/productcard.css" ?>
    <?php include "./css/product-details.css" ?>
    <?php include "./css/common-properties.css" ?>
  </style>
  <link rel="stylesheet" href="./css/bottomNav.css">


  <?php
  if(!isset($_GET['p'])){
    $_SESSION["message"] = "404 Not Found!";
    header("Location: error.php");
  }else{
    $product_id = $_GET['p'];
    echo "<script>localStorage.setItem('prod_id', ".$product_id.");</script>";
  }
  ?>
  <script src="./js/getProductdetails__data.js" defer></script>
  <script src="./js/sideBar.js" defer></script>
  <script src="./js/searchBar.js" defer></script>  
  <script src="./js/sliderAccordian.js" defer></script>
  <script src="./js/sideBar.js" defer></script>
  <script src="./js/product_details__popup.js" defer></script>
  <!-- <script src="./js/getProductdetails__data.js" defer></script> -->
  <!-- splidejs cdn -->
  <!-- <script type="module">
    import splidejssplide from 'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/+esm'
  </script> -->
 
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@3.1.9/dist/css/splide.min.css">
  <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@3.1.9/dist/js/splide.min.js"></script>
  <script src="./js/product_details_product_slider.js" defer></script>
  <!-- <script src="./js/product_catalog.js" defer></script> -->
</head>

<body>
  <!-- Navigation Bar -->
  <?php
  include './header.php';
  ?>

<!-- Product Search Bar -->
  <div class="ProductSearch">
    <div class="ProductSearch__search_bar">
      <?php {
        include "./components/searchBar.php";
      }
      ?>
    </div>
    <!-- Relevance button -->
    <div class="ProductSearch__options"><img src="./images/sort.png" alt="Relevance Button" class="ProductSearch__options__icon"></div>
    <!-- Filter button -->
    <div class="ProductSearch__options"><img src="./images/filter.png" alt="Filter Button" class="ProductSearch__options__icon"></div>
  </div>


  <div class="section-popup" id="section-popup">
    <div class="section-popup__layer">
      <span class="section-popup__close" id="close_popup">X</span>

      <div class="section-popup__layer_sections">
      <div class="product_details__title__product_name" id="product_title_popup">
        Smart Watch: IPX 6 Waterproof
</div>
      <div class="product_details__title__tags popup-tags">
        <div class="product_details__title__tags__btn bg-blue">ICB Choice</div>
        <div class="product_details__title__tags__btn bg-red">Customers Choice</div>
      </div>

      <div class="section-popup__layer_sections__image">
        <img src="./images/slider-img.jpg" alt="product_image_1" class="product_details__images__product_slider__img inside_popup_slide">
      </div>
    </div>

    <div class="section-popup__layer__section">
      <div class="section-popup__layer__section__username">
        <?php echo $_SESSION['username']; ?>
      </div>

      <div class="section-popup__layer__section__qr_code">
        <img src="./images/QR CODE.png" alt="Refereal Code" class="qr_product_refer">
      </div>

      <div class="section-popup__layer__section__message">Scan QR or Share the Referral Link</div>
      
      <div class="section-popup__layer__section__share">
        <div class="section-popup__layer__section__share__copy">
          <img src="./images/copy.png" alt="copy button" class="section-popup__layer__section__share__copy_img">
        </div>
        <div class="section-popup__layer__section__share__link">careforbharat.org/pro?pid...</div>
        <button class="section-popup__layer__section__share_btn">Share</button>
      </div>
    </div>


    </div>
  </div>

      <div class="product_details">
        <!-- Image slider -->
        <div class="product_details__images">
            <section id="product_images_carousel" class="splide" aria-label="Display Proudct Images">
              <div class="splide__track">
                <ul class="splide__list" id="product_list">
                  <li class="splide__slide">
                  <img src="./images/slider-img.jpg" alt="product_image_1" class="product_details__images__product_slider__img">
                  </li>
                  <li class="splide__slide">
                  <img src="./images/slider-img.jpg" alt="product_image_1" class="product_details__images__product_slider__img">
                  </li>
                  <li class="splide__slide">
                  <img src="./images/slider-img.jpg" alt="product_image_1" class="product_details__images__product_slider__img">
                  </li>
                  
                </ul>
              </div>
            </section>
        </div>

        <!-- Title of page -->
        <div class="product_details__title">
          <div class="product_details__title__product_name" id="product_name">Smart Watch: IPX 6 Waterproof</div>
          <div class="product_details__title__company"><a href="" id="company_name"></a></div>
          <div class="product_details__title__tag_rating">
            <div class="product_details__title__tags">
            <div class="product_details__title__tags__btn bg-blue">ICB Choice</div>
            <div class="product_details__title__tags__btn bg-red">Customers Choice</div>
            </div>
            <div class="product_details__title__rating" id="product_rating">4.0</div>
          </div>

        </div>
        <div class="product_details__description">
          <!-- About Product -->
          <div class="product_details__description__title">
          About Product
          </div>
          <div class="product_details__description__details" id="product_description">
          "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
          </div>

          <!-- Description -->
          <div class="product_details__description__title">Features</div>
          <div class="product_details__description__details" id="product_features">
            <ul>
              <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
              <li>Etiam laoreet urna hendrerit finibus pellentesque.</li>
              <li>Aliquam faucibus est at urna fermentum varius.</li>
              <li>Pellentesque vel nisl sit amet eros vulputate tempor id quis libero.</li>
              <li>Phasellus sed turpis id nulla ultrices faucibus.</li>
              <li>Nulla eu nunc ut lorem iaculis tincidunt.</li>
              <li>Suspendisse at massa at justo convallis auctor non sit amet augue.</li>
              <li>Curabitur bibendum eros sit amet dui maximus ullamcorper.</li>
            </ul>
          </div>

          <div class="product_details__description__title">
          Additional Information
          </div>
          <div class="product_details__description__details" id="additional_info">
          "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
          </div>

          <!-- FAQ -->
          <div class="product_details__description__title">FAQ</div>
          <div class="product_details__description__details" id="faq">
            <ul>
              <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
              <li>Etiam laoreet urna hendrerit finibus pellentesque.</li>
              <li>Aliquam faucibus est at urna fermentum varius.</li>
              <li>Pellentesque vel nisl sit amet eros vulputate tempor id quis libero.</li>
              <li>Phasellus sed turpis id nulla ultrices faucibus.</li>
              <li>Nulla eu nunc ut lorem iaculis tincidunt.</li>
              <li>Suspendisse at massa at justo convallis auctor non sit amet augue.</li>
              <li>Curabitur bibendum eros sit amet dui maximus ullamcorper.</li>
            </ul>
          </div>
        </div>
        <div class="product_details__button_action">
            <button class="product_details__button_action__options" id="product_details__button_action__options__refer">Refer Product</button>
            <button class="product_details__button_action__options" id="product_details__button_action__options__pitch">Pitch Product</button>
        </div>
      </div>


      
  
    <?php include "./components/bottomNav.php";?>
</body>

</html>