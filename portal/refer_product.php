<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)) {
    header("Location: ./index.php");
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

if (!isset($_GET['id'])) {
    echo "Product ID not provided.";
    exit;
}

$productId = intval($_GET['id']);
$sql = "SELECT * FROM product WHERE id = $productId";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Product not found.";
    exit;
}
$product = $result->fetch_assoc();

$referralLink = "https://careforbharat.org/pro?pid=" . $product['id'];
$qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($referralLink) . "&size=150x150";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Refer Product</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
            margin: 0;
        }
        .container {
            display: flex;
            gap: 8px;
            max-width: 900px;
            flex-wrap: nowrap;
            justify-content: space-between;
        }
        .card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 20px;
            width: 45%;
            text-align: center;
        }
        .card img {
            width: 80%;
            border-radius: 10px;
        }
        .tags {
            margin-top: 8px;
        }
        .tag {
            display: inline-block;
            padding: 5px 12px;
            font-size: 13px;
            font-weight: bold;
            border-radius: 20px;
            color: #fff;
            margin: 5px;
        }
        .icb { background-color: #2d9cdb; }
        .customer { background-color: #eb5757; }
        .qr img {
            margin-top: 8px;
            width: 170px;
            height: 180px;
        }
        .share-link {
            position: relative; /* Make the container relative for absolute positioning of the button */
            margin-top: 15px;
            width: 100%;
            max-width: 280px;
            margin-left: auto;
            margin-right: auto;
        }
        .share-link input {
            width: 100%;
            padding: 8px 80px 8px 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            text-align: left; 
            font-size: 0.9rem;
            box-sizing: border-box;
        }
        .share-link button {
            position: absolute;
            right: 5px; /* Position the button inside the input */
            top: 50%;
            transform: translateY(-50%); /* Center vertically */
            padding: 5px 10px;
            border: none;
            background-color: #3498db;
            color: #fff;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s;
            font-size: 0.8rem;
        }
        .share-link button:hover {
            background-color: #2980b9;
            transform: translateY(-50%) scale(1.05); /* Adjust transform for hover */
        }
        .share-link button:active {
            background-color: #2471a3;
            transform: translateY(-50%) scale(0.95);
        }

        /* Mobile Responsiveness */
        @media (max-width: 700px) {
            body {
                padding: 10px;
                height: auto;
                align-items: flex-start;
            }
            .container {
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }
            .card {
                width: 90%;
                padding: 15px;
            }
            .card h3 {
                font-size: 1.2rem;
            }
            .card img {
                width: 70%;
            }
            .tag {
                font-size: 11px;
                padding: 4px 10px;
            }
            .qr img {
                width: 140px;
                height: 140px;
            }
            .qr p {
                font-size: 0.9rem;
            }
            .share-link {
                max-width: 100%; /* Full width for mobile */
            }
            .share-link input {
                font-size: 0.9rem;
                padding-right: 80px; /* Ensure space for button */
            }
            .share-link button {
                font-size: 0.8rem;
                padding: 5px 10px;
            }
        }

        /* Extra small screens */
        @media (max-width: 400px) {
            .card {
                width: 95%;
                padding: 10px;
            }
            .card h3 {
                font-size: 1rem;
            }
            .card img {
                width: 65%;
            }
            .qr img {
                width: 120px;
                height: 120px;
            }
            .share-link input {
                font-size: 0.8rem;
                padding-right: 70px; /* Slightly less padding for smaller screens */
            }
            .share-link button {
                font-size: 0.7rem;
                padding: 4px 8px;
            }
            body {
    padding-bottom: 110px; /* Adjust based on bottom nav height */
  }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
        <img src="<?php echo htmlspecialchars($product['product_image_url']); ?>" alt="Product Image">
        <div class="tags">
            <span class="tag icb">ICB Choice</span>
            <span class="tag customer">Customers Choice</span>
        </div>
    </div>
    <div class="card">
        <div class="username"><b>Username</b></div>
        <div class="qr">
            <p>Scan QR or Share the Referral Link</p>
            <img src="<?php echo $qrCodeUrl; ?>" alt="QR Code">
        </div>
        <div class="share-link">
            <input type="text" value="<?php echo $referralLink; ?>" id="referralInput" readonly>
            <button onclick="copyLink()" id="copyButton">Copy</button>
        </div>
    </div>
</div>

<script>
    function copyLink() {
        var copyText = document.getElementById("referralInput");
        var copyButton = document.getElementById("copyButton");
        
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        
        navigator.clipboard.writeText(copyText.value).then(() => {
            copyButton.innerText = "Copied!";
            copyButton.style.backgroundColor = "#27ae60";
            
            setTimeout(() => {
                copyButton.innerText = "Copy";
                copyButton.style.backgroundColor = "#3498db";
            }, 2000);
        }).catch(err => {
            alert("Failed to copy. Try again!");
            console.error("Copy failed:", err);
        });
    }
</script>

</body>
</html>