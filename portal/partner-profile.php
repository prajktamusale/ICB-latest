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

$productSalesQuery = "SELECT p.name, COUNT(o.order_id) as orders_count, SUM(o.total_price) as total_sales
                      FROM order_table o
                      JOIN product p ON o.product_id = p.id
                      WHERE MONTH(o.Orderdate) = '$currentMonth' 
                      AND YEAR(o.Orderdate) = '$currentYear'
                      GROUP BY p.id
                      ORDER BY total_sales DESC
                      LIMIT 5";
$productSalesResult = mysqli_query($conn, $productSalesQuery);

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

$sql = "SELECT * FROM company";
$result = $conn->query($sql);
if($result->num_rows > 0){
  $row = $result->fetch_assoc();
  $_SESSION['name'] = $row['name'];
  $_SESSION['Organization'] = $row['Organization'];
  $_SESSION['email'] = $row['email'];
  $_SESSION['phone'] = $row['phone'];
  $_SESSION['owner'] = $row['owner'];
  $_SESSION['Sector'] = $row['Sector'];
  $_SESSION['PAN'] = $row['PAN'];
  $_SESSION['profile_image'] = $row['profile_image'];

  $sql2 = "SELECT * FROM company_details";
$result2 = $conn->query($sql2);
if($result2->num_rows > 0)
{
    $row2 = $result2->fetch_assoc();
    $_SESSION['Customer_Care_phone_num_1'] = $row2['Customer_Care_phone_num_1'];
    $_SESSION['Customer_Care_phone_num_2'] = $row2['Customer_Care_phone_num_2'];
    $_SESSION['Customer_Care_Email'] = $row2['Customer_Care_Email'];
    
}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Partner-Profile</title>
  <style>
    <?php include "./css/header.css" ?>
  </style>
  <link rel="stylesheet" href="./css/bottomNav.css">
  <link rel="stylesheet" href="./css/partner-profile.css" />
  
    <?php 
      echo "<script>localStorage.setItem('id', ".$_SESSION['id'].");</script>";
    ?>
    <script src="./js/sliderAccordian.js" defer></script>
    <script src="./js/sideBar.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
</head>
<body>
<?php
      include './header.php';
    ?>
  <div class="container">
    <div class="header">
      <div class="logo"></div>
      <div>
        <div class="company-title">Mandet India Pvt. Ltd.</div>
        <div class="company-subtitle">Revolutionizing tech</div>
      </div>
    </div>
    
    <div class="main-content">
      <div class="section-title">Growth With Us</div>
      
      <div class="company-info">
        <div class="company-details">
          <div class="detail-row">
            <div class="detail-label"><strong>Name:</strong> <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ''; ?></div>
           
          </div>
          <div class="detail-row">
            <div class="detail-label"><strong>Organization:</strong> <?php echo isset($_SESSION['Organization']) ? $_SESSION['Organization'] : ''; ?></div>
            
          </div>
          <div class="detail-row">
            <div class="detail-label"><strong>Owner:</strong> <?php echo isset($_SESSION['owner']) ? $_SESSION['owner'] : ''; ?></div>
            
          </div>
          <div class="detail-row">
            <div class="detail-label"><strong>Sector:</strong> <?php echo isset($_SESSION['Sector']) ? $_SESSION['Sector'] : ''; ?></div>
            
          </div>
          <div class="detail-row">
            <div class="detail-label"><strong>PAN:</strong> <?php echo isset($_SESSION['PAN']) ? $_SESSION['PAN'] : ''; ?></div>
            
          </div>
        </div>
        
        <div class="growth-chart">
        <div class="chart-container">
            <canvas id="productSalesChart"></canvas>
        </div>
        </div>
        
        <div class="stats">
          <div class="stat-box">
            <div class="stat-number"><?php echo $formattedRevenue; ?></div>
            <div class="stat-label">Revenue</div>
          </div>
          <div class="stat-box">
            <div class="stat-number"><?php echo $associateCount; ?>+</div>
            <div class="stat-label">Associates</div>
          </div>
          <div class="stat-box">
            <div class="stat-number"><?php echo $customerCount; ?>+</div>
            <div class="stat-label">Clients</div>
          </div>
          <div class="stat-box">
            <div class="stat-number"><?php echo $serviceCount; ?>+</div>
            <div class="stat-label">Services</div>
          </div>
        </div>
      </div>
      
      <div class="contact-sections">
        <div class="contact-box">
          <div class="contact-title">Company Admin Contact</div>
          
          <div class="contact-item">
            <div class="contact-icon">@</div>
            <div><?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?></div>
          </div>
          
          <div class="contact-item">
            <div class="contact-icon">📞</div>
            <div><?php echo isset($_SESSION['phone']) ? $_SESSION['phone'] : ''; ?></div>
          </div>
          
          <div class="contact-item">
            <div class="contact-icon">W</div>
            <div>Mandet India Pvt Ltd. POC</div>
          </div>
        </div>
        
        <div class="contact-box" id="customer-contact-section">
          <div class="contact-title">Customer Contact</div>
          
          
          <div class="contact-item" id="phone-item">
            <div class="contact-icon">📞</div>
            <div id="phone-display"><?php echo isset($_SESSION['Customer_Care_phone_num_1']) ? $_SESSION['Customer_Care_phone_num_1'] : ''; ?>     /
            <?php echo isset($_SESSION['Customer_Care_phone_num_2']) ? $_SESSION['Customer_Care_phone_num_2'] : ''; ?></div>
          </div>
          
          <div class="contact-item" id="website-item">
            <div class="contact-icon">🌐</div>
            <div id="website-display">mandetindia.com</div>
          </div>
          
          <div class="contact-item" id="email-item">
            <div class="contact-icon">@</div>
            <div id="email-display"><?php echo isset($_SESSION['Customer_Care_Email']) ? $_SESSION['Customer_Care_Email'] : ''; ?></div>
          </div>
        </div>
        
        <div class="query-box">
          <div class="query-title">Got a Query</div>
          <div class="query-icon">?</div>
          <button class="query-btn">Get in touch ⟩</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    function toggleEdit() {
      const phoneDisplay = document.getElementById('phone-display');
      const websiteDisplay = document.getElementById('website-display');
      const emailDisplay = document.getElementById('email-display');
      const editBtn = document.querySelector('.edit-btn');
      
      if (phoneDisplay.contentEditable === 'true') {
        // Save mode
        phoneDisplay.contentEditable = 'false';
        websiteDisplay.contentEditable = 'false';
        emailDisplay.contentEditable = 'false';
        
        phoneDisplay.classList.remove('editable');
        websiteDisplay.classList.remove('editable');
        emailDisplay.classList.remove('editable');
        
        editBtn.textContent = 'Edit';
      } else {
        // Edit mode
        phoneDisplay.contentEditable = 'true';
        websiteDisplay.contentEditable = 'true';
        emailDisplay.contentEditable = 'true';
        
        phoneDisplay.classList.add('editable');
        websiteDisplay.classList.add('editable');
        emailDisplay.classList.add('editable');
        
        editBtn.textContent = 'Save';
        
        // Focus on the first field
        phoneDisplay.focus();
      }
    }
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
  <?php include "./components/bottomNav.php";?>
</body>
</html>