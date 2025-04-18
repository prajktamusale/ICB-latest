<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect if not logged in
if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)) {
    header("Location: ./index.php");
    exit;
}

if (!isset($_SESSION['type'])) {
    header("Location: ../index.html");
    exit;
}

$host = "localhost";
$user = "root";
$password = "";
$dbname = "icb_portal";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch logged-in user ID
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

// Calculate the maximum number of people the logged-in user can add
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

// Close database connections
$stmt_orders->close();
$stmt_current_people->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Downline</title>
    <link rel="stylesheet" href="manageDownline.css?v=123">
    <link rel="stylesheet" href="./css/donate.css" />
    <link rel="stylesheet" href="./css/header.css" />
    <link rel="stylesheet" href="./css/utils.css">
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        background: #f7f7f7;
        white-space: nowrap;
        overflow-x: hidden;
        position:relative;
    }

    .header-card {
        background: linear-gradient(to right, #4da9ff, #8c6eff);
        color: white;
        padding: 20px;
        border-bottom-left-radius: 40px;
        position: relative;
    }

    .header-card .user-info {
        display: flex;
        align-items: center;
    }

    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #333;
        margin-right: 15px;
    }

    .header-card .income-box {
        position: absolute;
        right: 20px;
        top: 20px;
        background: white;
        color: #4a4a4a;
        padding: 10px 15px;
        border-radius: 15px;
        font-weight: bold;
    }

    .section-title {
        text-align: center;
        font-size: 24px;
        margin: 30px 0 10px;
    }

    .table-wrapper {
        width: 85%;
        margin: 0 auto;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        overflow-y: scroll;
        max-height: 400px;
    }

    .table-style {
        width: 100%;
        border-collapse: collapse;
        margin: 0 auto;
    }

    .table-style th,
    .table-style td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
        text-align: left;
    }

    .table-style th {
        background-color: #f0f0f0;
        font-weight: bold;
    }

    .footer-nav {
        display: flex;
        justify-content: center;
        padding: 15px;
        background: #eaeaea;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
    }

    .footer-nav span {
        font-size: 24px;
    }

    .mobile-downline-view {
        display: none;
    }

    @media screen and (max-width: 768px) {
        .desktop-view,
        .table-wrapper {
            display: none !important;
        }

        .mobile-downline-view {
            display: block !important;
            padding: 10px;
            overflow: hidden;
        }

        .modern-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        padding: 16px;
        margin: 12px 0;
        display: flex;
        flex-direction: column;
    }

        .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

        .avatar-container {
        position: relative;
        width: 60px;
        height: 60px;
    }

        .avatar-circle {
            width: 100%;
            height: 100%;
            background: #ccc;
            border-radius: 50%;
            border: 3px solid #2196F3;
        }

        .check2 {
            position: absolute;
            bottom: 10%;
            right: -10%;
            width: 20px;
            height: 20px;
            background-color: #4CAF50;
            border-radius: 50%;
            border: 2px solid white;
        }
        .card-info {
            flex: 1;
                }

        .uid {
            font-size: 12px;
            color: #777;
            margin: 0;
        }

        .name {
            font-size: 16px;
            font-weight: bold;
            margin: 2px 0 6px;
        }

        .sub-row {
            font-size: 14px;
            margin: 2px 0;
        }

        .sub-row .label {
            color: #555;
            margin-right: 6px;
        }

        .sub-row .value {
            font-weight: 500;
            color: #000;
        }

        .share {
            text-align: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .share-label {
            font-size: 12px;
            color: #777;
        }

        .share-amount {
            font-size: 16px;
            font-weight: bold;
            color: #000;
        }

        .icon img {
            width: 20px;
            height: 20px;
            margin-top: 6px;
        }
    }
</style>


    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/search.css">
    <link rel="stylesheet" href="./css/manageDownline.css">
    <link rel="stylesheet" href="./css/bottomNav.css">
    <link rel="stylesheet" href="./css/dpuserProfile.css">


    <script src="./js/sliderAccordian.js" defer></script>
    <script src="./js/sideBar.js" defer></script>
    <script src="./js/manageDownline.js" defer></script>
</head>

<body>

    <?php include './header.php'; ?>

    <div class="downline-top">
        <div id="downline_details">
            <div id="downline_details__username"><?php echo htmlspecialchars($_SESSION['full_name']); ?></div>
            <div id="downline_details__id">UID: <?php echo htmlspecialchars($_SESSION['id']); ?></div>
        </div>
        <div id="downline_income">
    <div id="downline_income__details">
        <div id="downline_income__message">Total&nbsp;Income</div>
        <div id="downline_income__total">&nbsp;₹<?php echo number_format($total_income); ?>/-</div>
        <div id="downline_income__sales">Monthly&nbsp;Sales</div>
        <div id="downline_income__sales_amount">&nbsp;₹<?php echo number_format($monthly_sales); ?>/-</div>
    </div>
</div>
    </div>
    
<?php if (isset($error_message)): ?>
    <div style="color: red; font-weight: bold; text-align: center; margin-top: 10px;">
        <?php echo $error_message; ?>
    </div>
<?php endif; ?>

    <h2 class="section-title">My Downline</h2>
    <!-- Desktop Table -->
<!-- Desktop Table -->
<div class="table-wrapper desktop-view">
    <table class="table-style">
        <thead>
            <tr>
                <th>UID</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Income</th>
                <th>My % Share</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($rows) > 0): ?>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['mobile']); ?></td>
                        <td>₹ <?php echo number_format($row['income']); ?></td>
                        <td>₹ <?php echo number_format($row['share_amount']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align:center;">No downline records found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Mobile Card View -->
<div class="mobile-downline-view">
    <?php foreach ($rows as $row): ?>
        <div class="modern-card">
            <div class="card-header">
            <div class="avatar-container">
                    <div class="avatar-circle"></div>
                    <img class="check2" src="./images/undefined.png" alt="Check Icon">
                </div>
                <div class="card-info">
                    <p class="uid"><?php echo htmlspecialchars($row['id']); ?></p>
                    <p class="name"><?php echo htmlspecialchars($row['username']); ?></p>
                    <div class="sub-row">
                        <span class="label">Total Income:</span>
                        <span class="value">₹ <?php echo number_format($row['income']); ?></span>
                    </div>
                    <div class="sub-row">
                        <span class="label">Phone:</span>
                        <span class="value"><?php echo htmlspecialchars($row['mobile']); ?></span>
                    </div>
                   
                </div>
                <div class="share">
                    <div class="share-label">My % Share:</div>
                    <div class="share-amount">₹ <?php echo number_format($row['share_amount']); ?></div>
                    <div class="icon">
                        <img src="./images/call.png" alt="share" />
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>


    <?php include "./components/bottomNav.php"; ?>

</body>

</html>