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

// Get current month and year
$currentMonth = date('m');
$currentYear = date('Y');

// Fetch top 5 products
$query = "SELECT * FROM product LIMIT 5";
$result = mysqli_query($conn, $query);

// Count current month orders
$orderCountQuery = "SELECT COUNT(*) as orderCount FROM order_table 
                    WHERE MONTH(Orderdate) = '$currentMonth' 
                    AND YEAR(Orderdate) = '$currentYear'";
$orderCountResult = mysqli_query($conn, $orderCountQuery);
$orderCountRow = mysqli_fetch_assoc($orderCountResult);
$currentMonthOrders = $orderCountRow['orderCount'];

// Calculate current month revenue
$revenueQuery = "SELECT SUM(total_price) as monthlyRevenue FROM order_table 
                WHERE MONTH(Orderdate) = '$currentMonth' 
                AND YEAR(Orderdate) = '$currentYear'";
$revenueResult = mysqli_query($conn, $revenueQuery);
$revenueRow = mysqli_fetch_assoc($revenueResult);
$currentMonthRevenue = $revenueRow['monthlyRevenue'] ? $revenueRow['monthlyRevenue'] : 0;

// Format revenue display
$formattedRevenue = 'Rs ' . number_format($currentMonthRevenue);
if ($currentMonthRevenue >= 100000) {
    $formattedRevenue = (round($currentMonthRevenue / 100000, 1)) . 'L+';
}

// Fetch current month orders for display in table
$recentOrdersQuery = "SELECT o.order_id, o.product_id, o.quantity, o.total_price, o.Orderdate, 
                      c.name as customer_name, p.name as product_name
                      FROM order_table o
                      LEFT JOIN customer c ON o.customer_id = c.id
                      LEFT JOIN product p ON o.product_id = p.id
                      WHERE MONTH(o.Orderdate) = '$currentMonth' 
                      AND YEAR(o.Orderdate) = '$currentYear'
                      ORDER BY o.Orderdate DESC
                      LIMIT 6";
$recentOrdersResult = mysqli_query($conn, $recentOrdersQuery);

// Get count of unique customers
$customersQuery = "SELECT COUNT(DISTINCT customer_id) as customerCount FROM order_table";
$customersResult = mysqli_query($conn, $customersQuery);
$customersRow = mysqli_fetch_assoc($customersResult);
$customerCount = $customersRow['customerCount'] ? $customersRow['customerCount'] : 0;

// Get count of associates
$associatesQuery = "SELECT COUNT(*) as associateCount FROM associate";
$associatesResult = mysqli_query($conn, $associatesQuery);
$associatesRow = mysqli_fetch_assoc($associatesResult);
$associateCount = $associatesRow['associateCount'] ? $associatesRow['associateCount'] : 0;

// Get count of services/products
$servicesQuery = "SELECT COUNT(*) as serviceCount FROM product";
$servicesResult = mysqli_query($conn, $servicesQuery);
$servicesRow = mysqli_fetch_assoc($servicesResult);
$serviceCount = $servicesRow['serviceCount'] ? $servicesRow['serviceCount'] : 0;

// Get product sales data for chart
$productSalesQuery = "SELECT p.name, COUNT(o.order_id) as orders_count, SUM(o.total_price) as total_sales
                      FROM order_table o
                      JOIN product p ON o.product_id = p.id
                      WHERE MONTH(o.Orderdate) = '$currentMonth' 
                      AND YEAR(o.Orderdate) = '$currentYear'
                      GROUP BY p.id
                      ORDER BY total_sales DESC
                      LIMIT 5";
$productSalesResult = mysqli_query($conn, $productSalesQuery);

// Create arrays for chart data
$productLabels = [];
$productSalesValues = [];
$productOrderCounts = [];

while ($row = mysqli_fetch_assoc($productSalesResult)) {
    $productLabels[] = $row['name'];
    $productSalesValues[] = $row['total_sales'];
    $productOrderCounts[] = $row['orders_count'];
}

// Convert arrays to JSON for use in JavaScript
$productLabelsJSON = json_encode($productLabels);
$productSalesValuesJSON = json_encode($productSalesValues);
$productOrderCountsJSON = json_encode($productOrderCounts);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner Dashboard</title>
    <style>
       <?php include "./css/header.css" ?>
        <?php include "./css/search.css" ?>
        <?php include "./css/partnerDashboard.css" ?>
        <?php include "./css/sideBar.css" ?>
        <?php include "./css/sliderAccordian.css" ?>
    </style>
     <link rel="stylesheet" href="./css/bottomNav.css">
    <?php 
      echo "<script>localStorage.setItem('id', ".$_SESSION['id'].");</script>";
    ?>
    <script src="./js/sliderAccordian.js" defer></script>
    <script src="./js/sideBar.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
</head>
<body>
  <!-- Navigation Bar -->
  <?php
      include './header.php';
    ?>
    <div class="dashboard-container">
        <div class="header">
            <div class="company-logo">
                <div class="logo-circle"></div>
                <div class="company-info">
                    <h2>Mandet India Pvt. Ltd.</h2>
                    <p>Revolutionizing tech</p>
                </div>
            </div>
        </div>
        
        <div class="dashboard-content">
            <div class="charts-section">
                <div class="chart-panel">
                    <h3>Charts</h3>
                    <div class="stats-grid">
                        <div class="stat-box">
                            <div class="icon-box">💰</div>
                            <div class="stat-number"><?php echo $formattedRevenue; ?></div>
                            <div class="stat-label">Revenue</div>
                        </div>
                        <div class="stat-box">
                            <div class="icon-box">👥</div>
                            <div class="stat-number"><?php echo $associateCount; ?>+</div>
                            <div class="stat-label">Associates</div>
                        </div>
                        <div class="stat-box">
                            <div class="icon-box">👨‍💼</div>
                            <div class="stat-number"><?php echo $customerCount; ?>+</div>
                            <div class="stat-label">Clients</div>
                        </div>
                        <div class="stat-box">
                            <div class="icon-box">🔧</div>
                            <div class="stat-number"><?php echo $serviceCount; ?>+</div>
                            <div class="stat-label">Services</div>
                        </div>
                    </div>
                </div>
                
                <div class="chart-panel">
                    <h3>Product Sales Chart</h3>
                    <div class="chart-container">
                        <canvas id="productSalesChart"></canvas>
                    </div>
                </div>
                
                <div class="chart-panel">
                    <h3>Orders</h3>
                    <div class="orders-box">
                        <div class="icon-box" style="background-color: #0077cc; margin-top: 20px;">📦</div>
                        <div class="stat-number"><?php echo $currentMonthOrders; ?>+</div>
                        <div class="orders-label">Orders This Month</div>
                        <div class="view-button" onclick="window.location.href='Orders.php'">➤</div>
                    </div>
                </div>
            </div>
            
            <div class="two-col-section">
                <div class="orders-table">
                    <h3 style="margin-bottom: 15px;">Orders Received</h3>
                    <div class="table-header">
                        <div>Client Name</div>
                        <div>Service</div>
                        <div></div>
                    </div>
                    
                    <div class="order-slider">
                        <div class="slider-container">
                            <?php
                            // Count total orders for current month
                            $orderCount = mysqli_num_rows($recentOrdersResult);
                            
                            // If we have orders
                            if ($orderCount > 0) {
                                // Calculate how many slides we need (2 orders per slide)
                                $ordersPerSlide = 2;
                                $totalSlides = ceil($orderCount / $ordersPerSlide);
                                
                                // Reset pointer
                                mysqli_data_seek($recentOrdersResult, 0);
                                
                                // Create slides
                                for ($slideIndex = 0; $slideIndex < $totalSlides; $slideIndex++) {
                                    echo '<div class="slide">';
                                    
                                    // Add orders to this slide
                                    for ($i = 0; $i < $ordersPerSlide; $i++) {
                                        if ($row = mysqli_fetch_assoc($recentOrdersResult)) {
                            ?>
                                <div class="table-row">
                                    <div><?php echo htmlspecialchars($row['customer_name'] ?? 'Unknown'); ?></div>
                                    <div><?php echo htmlspecialchars($row['product_name'] ?? 'Unknown'); ?>:<?php echo $row['product_id']; ?></div>
                                    <div>
                                        <button class="details-button" onclick="viewOrderDetails(<?php echo $row['order_id']; ?>)">
                                            <span>Details</span> ➤
                                        </button>
                                    </div>
                                </div>
                            <?php
                                        }
                                    }
                                    echo '</div>';
                                }
                            } else {
                                // No orders for current month
                                echo '<div class="slide">';
                                echo '<div style="text-align: center; padding: 20px;">No orders found for current month</div>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                        <div class="slider-nav">
                            <?php
                            // Create slider dots based on number of slides
                            if ($orderCount > 0) {
                                for ($i = 0; $i < $totalSlides; $i++) {
                                    $activeClass = ($i == 0) ? 'active' : '';
                                    echo "<div class=\"slider-dot $activeClass\" onclick=\"goToSlide($i)\"></div>";
                                }
                            } else {
                                echo '<div class="slider-dot active"></div>';
                            }
                            ?>
                        </div>
                    </div>
                    
                    <div class="table-footer">
                        <div>Total</div>
                        <div><?php echo $currentMonthOrders; ?></div>
                    </div>
                </div>
                
                <div class="catalog-section">
                    <h3 style="margin-bottom: 15px;">My Product Catalog</h3>
                    <div class="product-grid">
                        <?php 
                        // Reset result pointer
                        mysqli_data_seek($result, 0);
                        while ($row = mysqli_fetch_assoc($result)) { 
                        ?>
                            <div class="product-card">
                                <div class="product-rating">4.0</div>
                                <div class="product-image">
                                    <img src="<?= $row['product_image_url'] ? $row['product_image_url'] : 'images/placeholder.png'; ?>" alt="<?= $row['name']; ?>" />
                                </div>
                                <div class="product-title"><?= $row['name']; ?></div>
                            </div>
                        <?php } ?>

                        <a href="Product-Catalogue.php" class="product-card view-more-card">
                            <div class="view-more-icon">➔</div>
                            <div class="view-more-text">View More</div>
                        </a>
                    </div>

                    <div class="query-section">
                        <div class="query-title">Got a Query</div>
                        <div class="query-icon">?</div>
                        <button class="contact-button">Get in touch ➤</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "./components/bottomNav.php";?>

    <script>
        // Order slider functionality
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.slider-dot');

        function goToSlide(slideIndex) {
            if (slides.length > 0) {
                const container = document.querySelector('.slider-container');
                currentSlide = slideIndex;
                container.style.transform = `translateX(-${currentSlide * 100}%)`;
                
                // Update active dot
                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentSlide);
                });
            }
        }

        // Function to view order details
        function viewOrderDetails(orderId) {
            window.location.href = `Orders.php?id=${orderId}`;
        }

        // Initialize product sales chart
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('productSalesChart').getContext('2d');
            
            // Get chart data from PHP
            const productLabels = <?php echo $productLabelsJSON ?: '[]'; ?>;
            const salesValues = <?php echo $productSalesValuesJSON ?: '[]'; ?>;
            const orderCounts = <?php echo $productOrderCountsJSON ?: '[]'; ?>;
            
            const productSalesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: productLabels,
                    datasets: [
                        {
                            label: 'Sales (₹)',
                            data: salesValues,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Orders',
                            data: orderCounts,
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                            // Use secondary y-axis
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Sales Amount (₹)'
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            },
                            title: {
                                display: true,
                                text: 'Order Count'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'Current Month Product Sales'
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>