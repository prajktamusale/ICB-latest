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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productName = $_POST['name'];
    $keywords = $_POST['keyword'];
    $Category = $_POST['Category'];
    $price = $_POST['price'];
    $About = $_POST['About'];
    $Features = $_POST['Features'];
    $faq = $_POST['faq'];
   

    // Handle image upload
    if (isset($_FILES['product_image_url']) && $_FILES['product_image_url']['error'] == 0) {
        $imageName = $_FILES['product_image_url']['name'];
        $imageTmpName = $_FILES['product_image_url']['tmp_name'];
        move_uploaded_file($imageTmpName, "uploads/" . $imageName);
    } else {
        $imageName = null; // Set a default value or handle the error
    }
        
    

    // Insert product details into the database
    $sql = "INSERT INTO product (name, keyword, Category, price, About, Features, faq, product_image_url) VALUES ('$productName', '$keywords', '$Category', '$price', '$About', '$Features', '$faq', '$imageName')";
    if ($conn->query($sql) === TRUE) {
        // Get the ID of the newly inserted product
        $product_id = $conn->insert_id;
        
        // Close the database connection
        $conn->close();
        
        // Redirect to the next page with the product ID
        header("Location: Customer_details.php?product_id=" . $product_id);
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        // Close the database connection
        $conn->close();
    }
} 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ... (existing POST handling code above)

    // Collect all FAQ questions and answers
    $faqList = [];
    foreach ($_POST as $key => $value) {
        if (preg_match('/^faqQuestion(\d+)$/', $key, $matches)) {
            $index = $matches[1];
            $question = trim($value);
            $answerKey = "faqAnswer$index";
            $answer = isset($_POST[$answerKey]) ? trim($_POST[$answerKey]) : '';
            if ($question !== '' || $answer !== '') {
                $faqList[] = [
                    'question' => $question,
                    'answer' => $answer
                ];
            }
        }
    }

    // If only the first FAQ is present (from the first row)
    if (empty($faqList) && !empty($_POST['faq'])) {
        $faqList[] = [
            'question' => trim($_POST['faq']),
            'answer' => isset($_POST['faqAnswer1']) ? trim($_POST['faqAnswer1']) : ''
        ];
    }

    // Encode FAQ as JSON for storage
    $faqJson = json_encode($faqList, JSON_UNESCAPED_UNICODE);

    // Update the $faq variable to store JSON
    $faq = $faqJson;

    // ... (rest of your code continues)
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product - Mandeet India Pvt. Ltd.</title>
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

        .form-title {
            font-size: 16px;
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

        .form-section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .form-row {
            display: flex;
            margin-bottom: 15px;
            gap: 15px;
        }

        .form-group {
            flex: 1;
            position: relative;
            margin-bottom: 10px;
        }

        .form-control {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .circle-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 5px;
            background-color: #1DC9F2;
            vertical-align: middle;
        }

        textarea.form-control {
            min-height: 80px;
            width: 100%;
            resize: vertical;
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
            justify-content: flex-end;
            margin-top: 20px;
            gap: 10px;
        }

        .btn-cancel {
            padding: 8px 15px;
            border: 1px solid #ddd;
            background-color: white;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            color: #666;
        }

        .btn-next {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
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
    echo "<script>localStorage.setItem('id', " . $_SESSION['id'] . ");</script>";
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
                <div class="progress-circle active">1</div>
            </div>
            <div class="progress-line"></div>
            <div class="progress-item">
                <div class="progress-circle">2</div>
            </div>
            <div class="progress-line"></div>
            <div class="progress-item">
                <div class="progress-circle">3</div>
            </div>
        </div>

        <div class="main-content">
            <h2 class="form-title">New Product</h2>

            <form id="productForm" action="add-product.php" method="POST" enctype="multipart/form-data">
                <div class="image-upload-section">
                    <div class="image-upload" id="imageDropArea">
                        <input type="file" id="productImage" name="product_image_url" accept="image/*" style="opacity:0;position:absolute;left:0;top:0;width:100%;height:100%;cursor:pointer;">
                        <div class="plus">+</div>
                        <div class="upload-text">ADD IMAGE</div>
                        <div class="refresh-icon">↻</div>
                    </div>

                    <div class="form-fields">
                        <div class="form-group">
                            <span class="circle-indicator"></span>
                            <input type="text" id="productName" name="name" class="form-control"
                                placeholder="Product Name" required>
                        </div>

                        <div class="form-group">
                            <span class="circle-indicator"></span>
                            <input type="text" id="keywords" name="keyword" class="form-control" placeholder="Keywords"
                                required>
                        </div>

                        <div class="form-group">
                            <span class="circle-indicator"></span>
                            <select id="category" name="Category" class="form-control" required>
                                <option value="" disabled selected>Select Category</option>
                                <option value="IT">IT</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Tax">Tax-Consulting</option>
                            </select>
                        </div>
                    </div>
                </div>

              

                <div class="form-section">
                    <h3 class="section-title">Price</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <span class="circle-indicator"></span>
                            <input type="text" id="mrp" name="price" class="form-control" placeholder="MRP" required>
                        </div>
                        
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">Details</h3>
                    <div class="form-group">
                        <textarea id="description" name="About" class="form-control"
                            placeholder="Describe about your product..."></textarea>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">Features</h3>
                    <div class="form-group">
                        <span class="circle-indicator"></span>
                        <input type="text" class="form-control" name="Features" placeholder="Feature 1...">
                    </div>
                    <div class="form-group">
                        <span class="circle-indicator"></span>
                        <input type="text" class="form-control" name="feature2" placeholder="Feature 2...">
                    </div>

                    <div class="add-more">
                        <div class="add-more-btn" id="addFeatureBtn">+</div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">Additional Specification</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" class="form-control" name="specKey1" placeholder="Key">
                        </div>
                    
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" class="form-control" name="specKey2" placeholder="Key">
                        </div>
                        
                    </div>

                    <div class="add-more">
                        <div class="add-more-btn" id="addSpecBtn">+</div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">FAQ</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" class="form-control" name="faq" placeholder="Question">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="faqAnswer1" placeholder="Answer">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" class="form-control" name="faqQuestion2" placeholder="Question">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="faqAnswer2" placeholder="Answer">
                        </div>
                    </div>

                    <div class="add-more">
                        <div class="add-more-btn" id="addFaqBtn">+</div>
                    </div>
                </div>

                <div class="action-buttons">
                    <button type="button" class="btn-cancel">Cancel</button>
                    <button type="submit" class="btn-next">Next →</button>

                </div>
            </form>
        </div>
    </div>
    <?php include "./components/bottomNav.php"; ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image upload functionality
    const imageDropArea = document.getElementById('imageDropArea');
    const productImage = document.getElementById('productImage');

    if (imageDropArea && productImage) {
        // Clicking the area triggers file input
        imageDropArea.addEventListener('click', () => {
            productImage.click();
        });

        // Show image preview on file select
        productImage.addEventListener('change', (e) => {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();

                reader.onload = (event) => {
                    imageDropArea.innerHTML = '';
                    const img = document.createElement('img');
                    img.src = event.target.result;
                    img.style.maxWidth = '100%';
                    img.style.maxHeight = '100%';
                    img.style.objectFit = 'contain';

                    // Add the refresh icon back
                    const refreshIcon = document.createElement('div');
                    refreshIcon.className = 'refresh-icon';
                    refreshIcon.innerHTML = '↻';
                    refreshIcon.addEventListener('click', function(ev) {
                        ev.stopPropagation();
                        productImage.value = '';
                        imageDropArea.innerHTML = `
                            <input type="file" id="productImage" name="product_image_url" accept="image/*" style="opacity:0;position:absolute;left:0;top:0;width:100%;height:100%;cursor:pointer;">
                            <div class="plus">+</div>
                            <div class="upload-text">ADD IMAGE</div>
                            <div class="refresh-icon">↻</div>
                        `;
                    });

                    imageDropArea.appendChild(img);
                    imageDropArea.appendChild(refreshIcon);
                };

                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // Drag and drop functionality
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            imageDropArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
            }, false);
        });

        imageDropArea.addEventListener('dragenter', () => {
            imageDropArea.style.borderColor = '#1DC9F2';
        }, false);

        imageDropArea.addEventListener('dragleave', () => {
            imageDropArea.style.borderColor = '#ccc';
        }, false);

        imageDropArea.addEventListener('drop', (e) => {
            imageDropArea.style.borderColor = '#ccc';
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files && files[0]) {
                productImage.files = files;

                const reader = new FileReader();
                reader.onload = (event) => {
                    imageDropArea.innerHTML = '';
                    const img = document.createElement('img');
                    img.src = event.target.result;
                    img.style.maxWidth = '100%';
                    img.style.maxHeight = '100%';
                    img.style.objectFit = 'contain';

                    // Add the refresh icon back
                    const refreshIcon = document.createElement('div');
                    refreshIcon.className = 'refresh-icon';
                    refreshIcon.innerHTML = '↻';
                    refreshIcon.addEventListener('click', function(ev) {
                        ev.stopPropagation();
                        productImage.value = '';
                        imageDropArea.innerHTML = `
                            <input type="file" id="productImage" name="product_image_url" accept="image/*" style="opacity:0;position:absolute;left:0;top:0;width:100%;height:100%;cursor:pointer;">
                            <div class="plus">+</div>
                            <div class="upload-text">ADD IMAGE</div>
                            <div class="refresh-icon">↻</div>
                        `;
                    });

                    imageDropArea.appendChild(img);
                    imageDropArea.appendChild(refreshIcon);
                };

                reader.readAsDataURL(files[0]);
            }
        }, false);
    }

// Image carousel dots functionality
const carouselDots = document.querySelectorAll('.carousel-dot');
carouselDots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
        carouselDots.forEach(d => d.classList.remove('active'));
        dot.classList.add('active');
    });
});
function addFeatureField() {
    const featureSection = document.querySelectorAll('.form-section')[2];
    const addMoreBtn = featureSection.querySelector('#addFeatureBtn');
    const newFeatureIndex = featureSection.querySelectorAll('input[name^="feature"]').length + 1;

    const formGroup = document.createElement('div');
    formGroup.className = 'form-group';

    const indicator = document.createElement('span');
    indicator.className = 'circle-indicator';

    const input = document.createElement('input');
    input.type = 'text';
    input.className = 'form-control';
    input.name = `feature${newFeatureIndex}`;
    input.placeholder = `Feature ${newFeatureIndex}...`;

    formGroup.appendChild(indicator);
    formGroup.appendChild(input);

    addMoreBtn.parentNode.parentNode.insertBefore(formGroup, addMoreBtn.parentNode);
}

document.getElementById('addFeatureBtn').addEventListener('click', addFeatureField);

// Add more specification key fields
function addSpecField() {
    const specSection = document.querySelectorAll('.form-section')[3];
    const addMoreBtn = specSection.querySelector('#addSpecBtn');
    const currentRows = specSection.querySelectorAll('.form-row').length;
    const newSpecIndex = currentRows + 1;

    const formRow = document.createElement('div');
    formRow.className = 'form-row';

    const formGroup = document.createElement('div');
    formGroup.className = 'form-group';

    const input = document.createElement('input');
    input.type = 'text';
    input.className = 'form-control';
    input.name = `specKey${newSpecIndex}`;
    input.placeholder = 'Key';

    formGroup.appendChild(input);
    formRow.appendChild(formGroup);

    addMoreBtn.parentNode.parentNode.insertBefore(formRow, addMoreBtn.parentNode);
}

document.getElementById('addSpecBtn').addEventListener('click', addSpecField);
function addFaqField() {
    const faqSection = document.querySelectorAll('.form-section')[4];
    const addMoreBtn = faqSection.querySelector('#addFaqBtn');
    const currentRows = faqSection.querySelectorAll('.form-row').length;
    const newFaqIndex = currentRows + 1;

    const formRow = document.createElement('div');
    formRow.className = 'form-row';

    const formGroupQ = document.createElement('div');
    formGroupQ.className = 'form-group';
    const inputQ = document.createElement('input');
    inputQ.type = 'text';
    inputQ.className = 'form-control';
    inputQ.name = `faqQuestion${newFaqIndex}`;
    inputQ.placeholder = 'Question';
    formGroupQ.appendChild(inputQ);

    const formGroupA = document.createElement('div');
    formGroupA.className = 'form-group';
    const inputA = document.createElement('input');
    inputA.type = 'text';
    inputA.className = 'form-control';
    inputA.name = `faqAnswer${newFaqIndex}`;
    inputA.placeholder = 'Answer';
    formGroupA.appendChild(inputA);

    formRow.appendChild(formGroupQ);
    formRow.appendChild(formGroupA);

    addMoreBtn.parentNode.parentNode.insertBefore(formRow, addMoreBtn.parentNode);
}

document.getElementById('addFaqBtn').addEventListener('click', addFaqField);

// Form submission
document.querySelector('.btn-cancel').addEventListener('click', function () {
    if (confirm('Are you sure you want to cancel?')) {
        window.location.href = 'Product-Catalogue.php';
    }
});

// Remove the fetch API override since we're using the standard form submission
</script>
</body>
</html>