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

$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : (isset($_GET['id']) ? intval($_GET['id']) : 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerKeys = [];
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'dataKey') === 0 && !empty($value)) {
            $customerKeys[] = $value;
        }
    }
    $customerDetailsStr = implode(';', $customerKeys) . ';';

    // Then update the database with $customerDetailsStr instead of $customerDetailsJson
    $stmt = $conn->prepare("UPDATE product SET Customer_details = ? WHERE id = ?");
    $stmt->bind_param("si", $customerDetailsStr, $product_id);

    if ($stmt->execute()) {
        // Successfully updated
    } else {
        echo "Error updating record: " . $conn->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Details - Mandeet India Pvt. Ltd.</title>
    <style>
      <?php include "./css/header.css"; ?>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f0f2f5;
            color: #333;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .logo {
            width: 40px;
            height: 40px;
            background-color: #1a4179;
            border-radius: 4px;
            margin-right: 10px;
        }
        
        .company-info h1 {
            font-size: 14px;
            font-weight: 600;
            margin: 0;
            color: #333;
        }
        
        .company-info p {
            font-size: 10px;
            color: #666;
            margin: 0;
        }
        
        .progress-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
        }
        
        .progress-item {
            display: flex;
            align-items: center;
        }
        
        .progress-circle {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            z-index: 2;
        }
        
        .progress-circle.active {
            background-color: #1DC9F2;
        }
        
        .progress-line {
            height: 2px;
            width: 50px;
            background-color: #ccc;
            margin: 0 5px;
        }
        
        .main-content {
            background-color: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .instruction-text {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 15px;
            color: #333;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .image-upload-section {
            display: flex;
            margin-bottom: 20px;
        }
        
        .image-upload {
            width: 150px;
            height: 150px;
            border: 1px dashed #ccc;
            border-radius: 4px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-right: 30px;
            position: relative;
        }
        
        .image-upload .plus {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            font-size: 20px;
            color: #999;
        }
        
        .image-upload .upload-text {
            font-size: 12px;
            color: #999;
        }
        
        .refresh-icon {
            position: absolute;
            bottom: 5px;
            left: 5px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #fff;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1DC9F2;
            font-size: 12px;
        }
        
        .circle-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: #fff;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1DC9F2;
            font-size: 14px;
            cursor: pointer;
        }
        
        .circle-arrow.left {
            left: -12px;
        }
        
        .circle-arrow.right {
            right: -12px;
        }
        
       
        
        .carousel-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #ccc;
            margin: 0 4px;
            cursor: pointer;
        }
        
        .carousel-dot.active {
            background-color: #666;
        }
        
        .form-row {
            display: flex;
            margin-bottom: 15px;
            gap: 15px;
        }
        
        .form-group {
            flex: 1;
            position: relative;
            margin-bottom: 15px;
        }
        
        .form-control {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .form-group .icon {
            position: absolute;
            left: 10px;
            top: 9px;
            color: #777;
            font-size: 14px;
        }
        
        .form-control.with-icon {
            padding-left: 30px;
        }
        
        .additional-section {
            margin-top: 25px;
        }
        
        .add-more {
            display: flex;
            justify-content: center;
            margin-top: 15px;
        }
        
        .add-more-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: white;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            color: black;
            cursor: pointer;
        }
        
        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            gap: 10px;
        }
        
        .btn-back {
            padding: 8px 20px;
            background-color: #1DC9F2;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }
        
        .btn-next {
            padding: 8px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }
        
        .btn-back i, .btn-next i {
            margin: 0 5px;
        }
        
        .hidden {
            display: none;
        }
        
    
      
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 10px;
            }
            
            .image-upload {
                width: 100%;
                margin-right: 0;
                margin-bottom: 15px;
            }
            
            .image-upload-section {
                flex-direction: column;
            }
        }
    </style>
    <link rel="stylesheet" href="./css/bottomNav.css">
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
                <div class="progress-circle active">2</div>
            </div>
            <div class="progress-line"></div>
            <div class="progress-item">
                <div class="progress-circle">3</div>
            </div>
        </div>
        
        <div class="main-content">
            <p class="instruction-text">Enter the details you require from the customer</p>
            
            <form id="customerForm" method="post">
                <div class="form-section">
                    <h3 class="section-title">Customer Details</h3>
                    
                   
                </div>
                
                <div class="additional-section">
                    <h3 class="section-title">Additional Data required</h3>
                    
                    <div id="additionalFields">
                        <?php
                        // Render existing additional data fields
                        $additionalDataCount = 0;
                        if (!empty($customerDetails)) {
                            foreach ($customerDetails as $key => $value) {
                                // Skip main customer fields
                                if (!in_array($key, ['customerName', 'phone', 'email'])) {
                                    $additionalDataCount++;
                                    echo '
                                    <div class="form-row">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="dataKey'.$additionalDataCount.'" 
                                                placeholder="Key" value="'.htmlspecialchars($key).'">
                                        </div>
                                    </div>';
                                }
                            }
                        }
                        
                        // Add default empty fields if no additional data exists
                        if ($additionalDataCount === 0) {
                            echo '
                            <div class="form-row">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="dataKey1" placeholder="Key">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="dataKey2" placeholder="Key">
                                </div>
                            </div>';
                            $additionalDataCount = 2;
                        }
                        ?>
                    </div>
                    
                    <div class="add-more">
                        <div class="add-more-btn" id="addDataBtn">+</div>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <button type="button" class="btn-back" id="backButton">
                        <i>←</i> Back
                    </button>
                    <button type="submit" class="btn-next" id="nextButton">
                        Next <i>→</i>
                    </button>
                </div>
                
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            </form>
        </div>
    </div>
    <?php include "./components/bottomNav.php"; ?>
    
    <script>
        // Add more data functionality
        let dataCount = <?php echo $additionalDataCount; ?>;
        document.getElementById('addDataBtn').addEventListener('click', () => {
            dataCount++;
            const dataSection = document.getElementById('additionalFields');
            
            const newDataRow = document.createElement('div');
            newDataRow.className = 'form-row';
            newDataRow.innerHTML = `
                <div class="form-group">
                    <input type="text" class="form-control" name="dataKey${dataCount}" placeholder="Key">
                </div>
            `;
            
            dataSection.appendChild(newDataRow);
        });
        
       
            
         
            // AJAX submission
            fetch('database/add_product.php', {
                method: 'POST',
                body: new FormData(document.getElementById('customerForm')),
                

                
})
         
            .then(result => {
                console.log('Success:', result);
                // Redirect to the next step
                window.location.href = 'Final_Add_product.php?id=<?php echo $product_id; ?>';
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('An error occurred while saving the customer details. Please try again.');
            });
    
        
        // Back button functionality
        document.getElementById('backButton').addEventListener('click', () => {
            window.location.href = 'add-product.php?id=<?php echo $product_id; ?>';
        });
    </script>
</body>

</html>