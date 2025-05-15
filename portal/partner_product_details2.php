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
if (empty($product['icb_price']) && !empty($product['price'])) {
    $calculated_icb = round($product['price'] * 0.10, 2);

    $update_sql = "UPDATE product SET icb_price = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("di", $calculated_icb, $productId);
    $update_stmt->execute();

    $product['icb_price'] = $calculated_icb; // Update local copy
}
// Fetch sales data for the product
function getSalesData($conn, $productId) {
    $salesData = [];

    $currentYear = date('Y');
    $currentMonth = date('n'); // Numeric month without leading zeros

    // Get all sales for the current month, grouped by date
    $sql = "SELECT 
                DAY(Orderdate) as order_day,
                SUM(quantity) as total_quantity
            FROM 
                order_table
            WHERE 
                product_id = $productId
                AND YEAR(Orderdate) = $currentYear
                AND MONTH(Orderdate) = $currentMonth
                AND Orderdate IS NOT NULL
            GROUP BY 
                DAY(Orderdate)
            ORDER BY 
                order_day ASC";

    $result = $conn->query($sql);

    // Fill all days of the current month with 0 by default
    $daysInMonth = date('t');
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $salesData[(string)$day] = 0;
    }

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $orderDay = (string)intval($row['order_day']);
            $salesData[$orderDay] = max(0, intval($row['total_quantity'])); // Ensure no negative values
        }
    }

    return $salesData;
}

// Get sales data for the product
$salesData = getSalesData($conn, $productId);

$sql2=" SELECT   Sector,name  FROM Company ";
$result2 = $conn->query($sql2);
if ($result2->num_rows > 0){
    while ($row = $result2->fetch_assoc()){
        $companyName = $row['name'];
        $service = $row['Sector'];
    }
}else {
       echo "Company not found.";
       exit;
    }
    $input = json_decode(file_get_contents('php://input'), true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner_Product_details</title>
    <link rel="stylesheet" href="./css/header.css" />
    <link rel="stylesheet" href="./css/product.css" />
    <link rel="stylesheet" href="./css/utils.css" />
    <link rel="stylesheet" href="./css/bottomNav.css" />
    <style>
       .sales-chart-container {
            width: 100%;
            height: 300px;
            margin-top: 20px;
       }
    </style>
   <link rel="stylesheet" href="./css/bottomNav.css">
  
    <link rel="stylesheet" href="./css/Partner_Product_details.css">
    
    <?php 
      echo "<script>localStorage.setItem('id', ".$_SESSION['id'].");</script>";
    ?>
    <script src="./js/sliderAccordian.js" defer></script>
    <script src="./js/sideBar.js" defer></script>
    <!-- Add Chart.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
</head>
<body>
    <?php include "./header.php"; ?>
    
    <!-- Success notification -->
    
    <div class="container">
        <header>
            <div class="company-logo">M</div>
            <div class="company-info">
                <h2>Maxidel India Pvt. Ltd.</h2>
                <p>Revolutionizing tech</p>
            </div>
        </header>
        
        <div class="main-content">
            <div class="image-section">
                <div class="main-image">
                <img src="<?php echo htmlspecialchars($product['product_image_url']); ?>" alt="Product Image">
                </div>
                <div class="nav-dots">
                    <div class="dot active"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>
                
            </div>
            
            <div class="info-section">
                <h1 class="service-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <div class="rating">
                    <span><?php echo htmlspecialchars($product['Rating']); ?>/5</span>
                </div>
                
                <div class="section price-section">
    <h3 class="section-title">Price</h3>
    <button class="edit-btn" onclick="editMRP(<?php echo $productId; ?>)">Edit</button>
    <div class="section-content" id="price-content">
        <table class="price-table">
            <tr>
                <th>MRP</th>
                <td>₹<span id="mrp"><?php echo htmlspecialchars($product['price']); ?></span>/-</td>
            </tr>
            <tr>
                <th>ICB Price</th>
                <td>₹<span id="icb-price"><?php echo htmlspecialchars($product['icb_price']); ?></span>/-</td>
            </tr>
        </table>
    </div>
</div>


                
                <div class="section about-section">
                    <h3 class="section-title">About Product</h3>
                    <button class="edit-btn" data-target="about-content" data-field="About" data-product-id="<?php echo $productId; ?>">Edit</button>
                    <div class="section-content" id="about-content" data-original-value="<?php echo htmlspecialchars($product['About']); ?>">
                        <p><?php echo nl2br(htmlspecialchars($product['About'])); ?></p>
                    </div>
                </div>
                
                <div class="section features-section">
                    <h3 class="section-title">Features</h3>
                    <button class="edit-btn" data-target="features-content" data-field="Features" data-product-id="<?php echo $productId; ?>">Edit</button>
                    <div class="section-content" id="features-content" data-original-value="<?php echo htmlspecialchars($product['Features']); ?>">
                        <ul class="features-list">
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
                
                <div class="section keywords-section">
                    <h3 class="section-title">Keywords</h3>
                    <button class="edit-btn" data-target="keywords-content" data-field="keyword" data-product-id="<?php echo $productId; ?>">Edit</button>
                    <div class="section-content" id="keywords-content" data-original-value="<?php echo htmlspecialchars($product['keyword']); ?>">
                        <p><?php echo htmlspecialchars($product['keyword']); ?></p>
                    </div>
                </div>
                
                <div class="section additional-info-section">
                    <h3 class="section-title">Additional Information</h3>
                    
                    <div class="section-content" id="additional-info-content">
                        <table class="price-table">
                            <tr>
                                <th>Company Name</th>
                                <td>Mandet</td>
                            </tr>
                            <tr>
                                <th>Service</th>
                                <td>Web-development</td>
                            </tr>
                            <tr>
                                <th>Product Name</th>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                            </tr>
                            <tr>
                                <th>Product Code</th>
                                <td><?php echo htmlspecialchars($product['id']); ?></td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td><?php echo htmlspecialchars($product['Category']); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="section sales-report-section">
                    <h3 class="section-title">Sales Report</h3>
                   
                    <div class="section-content" id="sales-report-content">
                        <div class="sales-chart-container">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="section faq-section">
    <h3 class="section-title">FAQs</h3>
    <button class="edit-btn" data-target="faq-content" data-field="faq" data-product-id="<?php echo $productId; ?>">Edit</button>
    <div class="section-content" id="faq-content">
        <?php 
        // Function to parse FAQ content from the database format to an array
        function parseFaqContent($faqContent) {
            $faqs = [];
            if (!empty($faqContent)) {
                $faqPairs = explode(';', $faqContent);
                foreach ($faqPairs as $faqPair) {
                    if (empty(trim($faqPair))) continue; // Skip empty pairs
                    
                    $parts = explode('?', $faqPair, 2);
                    if (count($parts) === 2) {
                        $faqs[] = [
                            'question' => trim($parts[0]),
                            'answer' => trim($parts[1])
                        ];
                    
                    }
                }
            }
            return $faqs;
        }

        // Single, consolidated POST handler for FAQ updates
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['faq_data'])) {
            $formattedFaq = '';
            $productId = intval($_POST['product_id']); // Ensure product ID is retrieved from POST data
            
            // Determine if the data is JSON or already formatted
            $firstChar = substr(trim($_POST['faq_data']), 0, 1);
            
            if ($firstChar === '[' || $firstChar === '{') {
                // Process JSON data
                $faqData = json_decode($_POST['faq_data'], true);
                
                if (is_array($faqData)) {
                    // Format the FAQ data as "Question?Answer;" pairs
                    foreach ($faqData as $faq) {
                        if (isset($faq['question']) && isset($faq['answer'])) {
                            $question = $conn->real_escape_string(trim($faq['question']));
                            $answer = $conn->real_escape_string(trim($faq['answer']));
                            
                            if (!empty($question) && !empty($answer)) {
                                $formattedFaq .= "$question?$answer;";
                            }
                        }
                    }
                }
            } else {
                // Data is already in the needed format or needs escaping
                $formattedFaq = $conn->real_escape_string(trim($_POST['faq_data']));
            }
            
            // Update the database
            $updateFaqSql = "UPDATE product SET faq = '$formattedFaq' WHERE id = $productId";
            
            
            exit;
        }

        // Parse the FAQ content from the database
        $product['faq'] = isset($product['faq']) ? $product['faq'] : ''; // Ensure 'faq' is not null
        $faqs = parseFaqContent($product['faq']);
        ?>

        <?php if (empty($faqs)): ?>
            <p>No FAQs available. Add some FAQs to display here.</p>
        <?php else: ?>
            <?php foreach ($faqs as $index => $faq): ?>
                <div class="faq-item">
                    <div class="faq-question">
                        <div>Q: <?php echo htmlspecialchars($faq['question']); ?></div>
                        <div class="faq-toggle">+</div>
                    </div>
                    <div class="faq-answer" style="display: none;">
                        <p><?php echo nl2br(htmlspecialchars($faq['answer'])); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

        </div>
    </div>
    <?php include "./components/bottomNav.php"; ?>
    
    <script>
// Handle FAQ toggle
document.addEventListener('DOMContentLoaded', function() {
    // Set up FAQ toggles
    document.querySelectorAll('.faq-question').forEach(question => {
        question.addEventListener('click', () => {
            const answer = question.nextElementSibling;
            const toggle = question.querySelector('.faq-toggle');
            
            if (answer.style.display === 'block') {
                answer.style.display = 'none';
                toggle.textContent = '+';
            } else {
                answer.style.display = 'block';
                toggle.textContent = '-';
            }
        });
    });

    // Set up edit buttons
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-target');
            const fieldName = btn.getAttribute('data-field');
            const contentElement = document.getElementById(targetId);
            
            // Get product ID from a data attribute
            const productId = btn.getAttribute('data-product-id');
            
            console.log('Edit button clicked:', { targetId, fieldName, productId });
            
            // Check if content element exists
            if (!contentElement) {
                console.error(`Element with ID ${targetId} not found`);
                return;
            }
            
            // Toggle editable state
            if (contentElement.classList.contains('editable')) {
                // Save changes
                contentElement.classList.remove('editable');
                contentElement.contentEditable = 'false';
                btn.textContent = 'Edit';
                
                // Hide save button if exists
                const saveBtn = btn.nextElementSibling;
                if (saveBtn && saveBtn.classList.contains('save-btn')) {
                    saveBtn.style.display = 'none';
                }

                // Only save to database if there's a field name and productId
                if (fieldName && productId) {
                    let newValue;
                    
                    try {
                        // Get the value based on the field type
                        if (fieldName === 'Features') {
                            newValue = extractFeatures(contentElement);
                        } else if (fieldName === 'price') {
                            // For price, get the value from the editable span
                            const priceField = contentElement.querySelector('.editable-field');
                            newValue = priceField ? priceField.textContent.trim() : contentElement.textContent.trim();
                        } else if (fieldName === 'faq') {
                            newValue = extractFAQData(contentElement);
                        } else {
                            // For other content fields, get innerHTML or textContent based on content type
                            newValue = contentElement.querySelector('p') ? 
                                      contentElement.querySelector('p').textContent.trim() : 
                                      contentElement.textContent.trim();
                        }
                        
                        console.log('Saving to database:', { fieldName, newValue, productId });
                        
                        // Check if we have all required values
                        if (!fieldName || !productId) {
                            console.error('Missing required parameters:', { fieldName, productId });
                            showNotification("Error: Missing field name or product ID");
                            return;
                        }
                        
                        // Save the changes to the database
                        saveDataToServer(fieldName, newValue, productId);
                    } catch (error) {
                        console.error('Error while processing edit:', error);
                        showNotification("Error processing your changes: " + error.message);
                    }
                } else {
                    console.error('Missing required parameters:', { fieldName, productId });
                    showNotification("Error: Missing field name or product ID");
                }
            } else {
                // Store original content for cancel operation
                let originalValue;
                
                if (fieldName === 'price') {
                    const priceField = contentElement.querySelector('.editable-field');
                    originalValue = priceField ? priceField.textContent : contentElement.textContent;
                } else if (fieldName === 'Features') {
                    originalValue = extractFeatures(contentElement);
                } else if (fieldName === 'faq') {
                    originalValue = extractFAQData(contentElement);
                } else {
                    originalValue = contentElement.querySelector('p') ? 
                                  contentElement.querySelector('p').textContent : 
                                  contentElement.textContent;
                }
                
                // Store original value as data attribute
                contentElement.setAttribute('data-original-value', originalValue);
                
                // Make editable
                contentElement.classList.add('editable');
                contentElement.classList.add('editing');
                
                // Handle special case for price field
                if (fieldName === 'price') {
                    const priceField = contentElement.querySelector('.editable-field');
                    if (priceField) {
                        priceField.contentEditable = 'true';
                        priceField.focus();
                    } else {
                        contentElement.contentEditable = 'true';
                        contentElement.focus();
                    }
                } else {
                    contentElement.contentEditable = 'true';
                    contentElement.focus();
                }
                
                btn.textContent = 'Save';
                
                // Create save button if it doesn't exist
                let saveBtn = btn.nextElementSibling;
                if (!saveBtn || !saveBtn.classList.contains('save-btn')) {
                    saveBtn = document.createElement('button');
                    saveBtn.classList.add('save-btn');
                    saveBtn.textContent = 'Cancel';
                    btn.parentNode.insertBefore(saveBtn, btn.nextSibling);
                    
                    saveBtn.addEventListener('click', () => {
                        // Revert changes
                        contentElement.classList.remove('editable', 'editing');
                        contentElement.contentEditable = 'false';
                        
                        // Get original value
                        const originalValue = contentElement.getAttribute('data-original-value');
                        
                        // Handle special case for price field
                        if (fieldName === 'price') {
                            const priceField = contentElement.querySelector('.editable-field');
                            if (priceField) {
                                priceField.contentEditable = 'false';
                                priceField.textContent = originalValue;
                            }
                        } else if (fieldName === 'Features') {
                            // Restore original features
                            let featuresHtml = '';
                            if (originalValue) {
                                const features = originalValue.split(',');
                                features.forEach(feature => {
                                    featuresHtml += '<li>' + feature.trim() + '</li>';
                                });
                            } else {
                                featuresHtml = '<li>No features listed.</li>';
                            }
                            const featuresList = contentElement.querySelector('.features-list');
                            if (featuresList) {
                                featuresList.innerHTML = featuresHtml;
                            }
                        } else if (fieldName === 'faq') {
                            // Restore original FAQ items
                            contentElement.innerHTML = '';
                            const faqItems = parseFAQString(originalValue);
                            
                            faqItems.forEach(faq => {
                                const faqItem = document.createElement('div');
                                faqItem.classList.add('faq-item');
                                
                                const questionDiv = document.createElement('div');
                                questionDiv.classList.add('faq-question');
                                questionDiv.textContent = 'Q: ' + faq.question;
                                
                                const toggleSpan = document.createElement('span');
                                toggleSpan.classList.add('faq-toggle');
                                toggleSpan.textContent = '+';
                                questionDiv.appendChild(toggleSpan);
                                
                                const answerDiv = document.createElement('div');
                                answerDiv.classList.add('faq-answer');
                                answerDiv.style.display = 'none';
                                answerDiv.textContent = 'A: ' + faq.answer;
                                
                                faqItem.appendChild(questionDiv);
                                faqItem.appendChild(answerDiv);
                                contentElement.appendChild(faqItem);
                                
                            });
                        } else if (fieldName === 'About') {
                            contentElement.innerHTML = '<p>' + originalValue.replace(/\n/g, '<br>') + '</p>';
                        } else {
                            contentElement.innerHTML = '<p>' + originalValue + '</p>';
                        }
                        
                        btn.textContent = 'Edit';
                        saveBtn.style.display = 'none';
                    });
                } else {
                    saveBtn.style.display = 'inline-block';
                }
            }
        });
    });

    // Initialize sales chart using Chart.js
    const salesChartCtx = document.getElementById('salesChart').getContext('2d');
    
    // Get sales data from PHP
    const salesData = <?php echo json_encode($salesData); ?>;
    
    // Create datasets for the chart 
    // For demonstration, we'll create dataset1 from actual sales data 
    // In a real application, you might want to modify this to show actual vs target or current year vs previous year
    
    const labels = Object.keys(salesData);
    const dataset1Values = Object.values(salesData);
    
    
    // This is just an example - you could replace this with actual comparison data
    
    const salesChart = new Chart(salesChartCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Dataset 1',
                    data: dataset1Values,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgb(255, 99, 132)',
                    borderWidth: 2,
                    tension: 0.1
                },
                
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: false,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Sales Report',
                    font: {
                        size: 16
                    }
                },
                legend: {
                    position: 'top'
                }
            }
        }
    });
});

// Show notification function
function showNotification(message) {
    const notification = document.getElementById('notification');
    if (notification) {
        notification.textContent = message;
        notification.style.display = 'block';
        
        // Hide after 3 seconds
        setTimeout(() => {
            notification.style.display = 'none';
        }, 3000);
    } else {
        // Create notification element if it doesn't exist
        const newNotification = document.createElement('div');
        newNotification.id = 'notification';
        newNotification.className = 'notification';
        newNotification.textContent = message;
        newNotification.style.position = 'fixed';
        newNotification.style.top = '20px';
        newNotification.style.right = '20px';
        newNotification.style.padding = '10px 20px';
        newNotification.style.backgroundColor = '#4CAF50';
        newNotification.style.color = 'white';
        newNotification.style.borderRadius = '5px';
        newNotification.style.zIndex = '1000';
        
        document.body.appendChild(newNotification);
        
        // Hide after 3 seconds
        setTimeout(() => {
            newNotification.remove();
        }, 3000);
    }
    
    console.log('Notification:', message);
}

// Function to handle saving data to database
function saveDataToServer(field, value, productId) {
    console.log('saveDataToServer called with:', { field, value, productId });
    
    // Validate parameters
    if (!field || typeof productId === 'undefined') {
        showNotification("Error: Missing required parameters");
        console.error('Missing parameters:', { field, value, productId });
        return;
    }
    
    // Method 1: Using FormData
    const formData = new FormData();
    formData.append('field', field);
    formData.append('value', value !== null && value !== undefined ? value : '');
    formData.append('product_id', productId);
    
    console.log('FormData created:', {
        field: formData.get('field'),
        value: formData.get('value'),
        product_id: formData.get('product_id')
    });
    
    // Send AJAX request using FormData
    fetch('database/update_product.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Server response:', data);
        if (data.success) {   
            showNotification("Changes saved successfully!");
        } else {
            showNotification("Error saving changes: " + (data.message || "Unknown error"));
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showNotification("Error saving changes: " + error.message);
    });
}

// Function to extract features from the list
function extractFeatures(featuresElement) {
    const listItems = featuresElement.querySelectorAll('li');
    const featuresArray = [];
    
    listItems.forEach(item => {
        const featureText = item.textContent.trim();
        if (featureText !== 'No features listed.') {
            featuresArray.push(featureText);
        }
    });
    
    return featuresArray.join(',');
}

// Function to extract FAQ data
function extractFAQData(faqElement) {
    const faqItems = faqElement.querySelectorAll('.faq-item');
    const faqArray = [];
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question').textContent.trim();
        const answer = item.querySelector('.faq-answer').textContent.trim();
        // Remove "Q: " and "A: " prefixes if they exist
        const cleanQuestion = question.startsWith('Q: ') ? question.substring(3) : question;
        const cleanAnswer = answer.startsWith('A: ') ? answer.substring(3) : answer;
        faqArray.push(cleanQuestion + '?' + cleanAnswer);
    });
    
    return faqArray.join(';');
}

// Function to parse FAQ string back to array of objects
function parseFAQString(faqString) {
    if (!faqString) return [];
    
    const faqArray = [];
    const faqItems = faqString.split(';');
    
    faqItems.forEach(item => {
        if (item.trim()) {
            const parts = item.split('?');
            if (parts.length >= 2) {
                faqArray.push({
                    question: parts[0].trim(),
                    answer: parts.slice(1).join('?').trim()
                });
            }
        }
    });
    
    return faqArray;
}
    </script>

<script>
function editMRP(productId) {
    const mrpSpan = document.getElementById('mrp');
    const currentMRP = mrpSpan.textContent.trim();
    const input = document.createElement('input');
    input.type = 'number';
    input.value = currentMRP;
    input.onblur = function () {
        const newMRP = parseFloat(input.value).toFixed(2);
        const newICB = (newMRP * 0.10).toFixed(2);

        // Update in UI
        mrpSpan.textContent = newMRP;
        document.getElementById('icb-price').textContent = newICB;

        // Send update to backend
        fetch('database/update_price.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `productId=${productId}&price=${newMRP}&icb_price=${newICB}`
})
.then(response => response.text())
.then(data => console.log('Update response:', data))
.catch(error => console.error('Error updating price:', error));


        mrpSpan.style.display = '';
        input.remove();
    };

    mrpSpan.style.display = 'none';
    mrpSpan.parentNode.insertBefore(input, mrpSpan);
    input.focus();
}

</script>
</body>
</html>