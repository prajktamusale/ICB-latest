<?php
   if(!isset($counsumerBought)){
     $counsumerBought = "200+";
   }
   if(!isset($productName)){
     $productName = "Product Name";
   }
   if(!isset($productLink)){
     $productLink = "NA";
   }
   if(!isset($_SESSION["id"]) || $_SESSION['type']=="voluneteer"){
     $action_button_message = "Purchase";
   }else if($_SESSION['type']=="associate"){
     $action_button_message = "Pitch Product";
   }else{
     $action_button_message = "Pitch Product";
   }

   // Add button class logic
   $buttonClass = $productLink == "NA" ? "inactive" : "";
   if ($action_button_message === "Pitch Product") {
     $buttonClass .= " blue-button"; // Add your custom blue style
   }
?>

<style>
  /* Desktop product grid */
  .desktop-grid-wrapper {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 50px;
    padding: 20px;
  }
  
  .product-component {
    background: #D9D9D9;
    padding: 16px;
    border-radius: 16px;
    text-align: center;
    transition: transform 0.3s ease;
    max-width: 220px;
    margin: 0 auto;
  }
  
  .product-component:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  }
  
  .card-img {
    width: 100%;
    height: auto;
    border-radius: 12px;
    object-fit: cover;
  }
  
  .name {
    font-size: 18px;
    font-weight: 700;
    margin: 8px 0;
    color: #333;
  }
  
  .product_details {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 12px;
  }
  
  .product_details__bought {
    font-weight: 600;
    font-size: 16px;
    color: #333;
  }
  
  .product_details__button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  
  .product_details__button:hover {
    background-color: #0056b3;
  }
  
  .product_details__watchlater {
    margin-left: 6px;
    cursor: pointer;
  }
  
  /* Mobile-specific styles */
  @media only screen and (max-width: 767px) {
    .desktop-grid-wrapper {
      display: none; /* Hide desktop view on mobile */
    }
    
    .mobile-grid-wrapper {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px; /* Increased gap between grid items */
      padding: 20px; /* Increased padding around the grid */
      margin-bottom: 20px; /* Add bottom margin */
    }
    
    .mobile-grid-wrapper .product-component {
      width: 100%;
      margin: -15px;
      margin-bottom: 30px; /* Additional spacing between rows */
    }
    
    /* Adding spacing between products in the same row */
    .mobile-grid-wrapper .product-component:nth-child(odd) {
      margin-right: 5px;
    }
    
    .mobile-grid-wrapper .product-component:nth-child(even) {
      margin-left: 5px;
    }
    
    /* Adding vertical spacing between products */
    .mobile-product-row {
      margin-bottom: 20px;
    }
  }
  
  /* Desktop-specific styles */
  @media only screen and (min-width: 768px) {
    .mobile-grid-wrapper {
      display: none; /* Hide mobile view on desktop */
    }
  }
</style>

<!-- Mobile View with improved spacing -->
<div class="mobile-grid-wrapper">
  <?php foreach($productList as $product): ?>
    <div class="product-component">
      <div class="card-image">
        <img src="<?php echo $product['product_image_url']; ?>" alt="Product Image" class="card-img">
      </div>
      
      <div class="card-content">
        <h2 class="name"><?php echo $product['name']; ?></h2>
        <div class="product_details">
          <div class="product_details__bought">200+</div>
          
          <a href="Partner_Product_details.php?id=<?php echo $product['id']; ?>" style="text-decoration: none;">
            <button class="product_details__button">
              View Product
            </button>
          </a>
          
          <div class="product_details__watchlater">
            <img src="./images/watch_later.png" alt="watch_later" style="width: 20px; height: 20px;">
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<!-- Desktop View -->
<div class="desktop-grid-wrapper">
  <?php foreach($productList as $product): ?>
    <div class="product-component">
      <div class="card-image">
        <img src="<?php echo $product['product_image_url']; ?>" alt="Product Image" class="card-img">
      </div>
      
      <div class="card-content">
        <h2 class="name"><?php echo $product['name']; ?></h2>
        <div class="product_details">
          <div class="product_details__bought">200+</div>
          
          <a href="Partner_Product_details.php?id=<?php echo $product['id']; ?>" style="text-decoration: none;">
            <button class="product_details__button">
              View Product
            </button>
          </a>
          
          <div class="product_details__watchlater">
            <img src="./images/watch_later.png" alt="watch_later" style="width: 20px; height: 20px;">
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>