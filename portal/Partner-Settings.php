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
if ($conn->connect_error){
  die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM company";
$result = $conn->query($sql);
if($result->num_rows > 0){
  $row = $result->fetch_assoc();
  $_SESSION['name'] = $row['name'];
  $_SESSION['Organization'] = $row['Organization'];
  $_SESSION['email'] = $row['email'];
  $_SESSION['phone'] = $row['phone'];
  $_SESSION['Sector'] = $row['Sector'];
  $_SESSION['PAN'] = $row['PAN'];
  $_SESSION['profile_image'] = $row['profile_image'];


}
$sql2 = "SELECT * FROM company_details";
$result2 = $conn->query($sql2);
if($result2->num_rows > 0)
{
    $row2 = $result2->fetch_assoc();
    $_SESSION['vision'] = $row2['vision'];
    $_SESSION['Add_line_1'] = $row2['Add_line_1'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Account Details</title>
    <style>
        <?php include "./css/header.css" ?>
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-container {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        .sidebar {
            width: 180px;
            background-color: #f0f0f0;
            border-right: 1px solid #ddd;
            padding: 20px 0;
            overflow-y: auto;
        }

        .sidebar h3 {
            padding: 0 15px;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            padding: 10px 15px;
            cursor: pointer;
        }

        .sidebar ul li:hover {
            background-color: #e0e0e0;
        }

        .sidebar ul li.active {
            background-color: #e0e0e0;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .section {
            margin-bottom: 30px;
        }

        .section h2 {
            font-size: 18px;
            margin-bottom: 15px;
        }

        .form-row {
            display: flex;
            margin-bottom: 15px;
            align-items: center;
        }

        .form-row label {
            width: 120px;
            font-weight: bold;
        }

        .form-row input {
            padding: 8px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .save-btn {
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 4px;
            cursor: pointer;
            float: right;
        }

        .save-btn:hover {
            background-color: #4cae4c;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .section-header h2 {
            margin: 0;
        }

        .address-label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .vision-textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            height: 80px;
            resize: none;
        }

        @media (max-width: 768px) {
            .sidebar {
            width: 100%;
            border-right: none;
            border-bottom: 1px solid #ddd;
            }

            .sidebar ul li {
            text-align: center;
            }

            .main-container {
            flex-direction: column;
            }

            .main-content {
            padding: 10px;
            }

            .form-row {
            flex-direction: column;
            align-items: flex-start;
            }

            .form-row label {
            width: 100%;
            margin-bottom: 5px;
            }

            .form-row input {
            width: 100%;
            }

            .save-btn {
            width: 100%;
            text-align: center;
            }

            .vision-textarea {
            width: 100%;
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
    <?php include './header.php'; ?>
    
    <div class="main-container">
        <div class="sidebar">
            <h3>Account Details</h3>
            <ul>
                <li class="active" onclick="navigateTo('company_details.php')">Company Details</li>
                <li onclick="navigateTo('company-brief.php')">Company Brief</li>
                <li onclick="navigateTo('company-brief.php')">POC Details</li>
                <li onclick="navigateTo('company-brief.php')">Customer Care Details</li>
            </ul>

            <h3>Contact Team</h3>
            <ul>
                <li onclick="navigateTo('coreTeam.php')">Contact Team</li>
            </ul>

            <h3>Privacy and Policy</h3>
            <ul>
                <li onclick="navigateTo('privacy_policy.php')">Privacy and Policy</li>
            </ul>
        </div>

        <div class="main-content">
            <div class="section">
                <div class="section-header">
                    <h2>Company Details</h2>
                  
                </div>
                
                <div class="form-row">
                    <label>Company Name:</label>
                    <input type="text" value="<?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ''; ?>" readonly>
                </div>
                
                <div class="form-row">
                    <label>Email:</label>
                    <input type="email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" readonly>
                </div>
                
                <div class="form-row">
                    <label>Phone:</label>
                    <input type="text" value="<?php echo isset($_SESSION['phone']) ? $_SESSION['phone'] : ''; ?>" readonly>
                </div>
                
                <div class="form-row">
                    <label>PAN:</label>
                    <input type="text" value="<?php echo isset($_SESSION['PAN']) ? $_SESSION['PAN'] : ''; ?>" readonly>
                </div>
                
                <div class="form-row">
                    <label>Sector:</label>
                   <input type="text" value="<?php echo isset($_SESSION['Sector']) ? $_SESSION['Sector'] : ''; ?>" readonly>
                </div>
                
                <div class="form-row">
                    <label>Organization:</label>
                    <input type="text" value="<?php echo isset($_SESSION['Organization']) ? $_SESSION['Organization'] : ''; ?>" readonly>
                </div>
                
              
                
                <div class="form-row">
                    <label>Address</label>
                    <input type = "text" value="<?php echo isset($_SESSION['Add_line_1']) ? $_SESSION['Add_line_1'] : ''; ?>" readonly>
                </div>
            </div>

            <div class="section">
                <div class="section-header">
                    <h2>Company Brief</h2>
                </div>
                
                <div>
                    <label class="address-label">Vision</label>
                    <textarea class="vision-textarea"><?php echo isset($_SESSION['vision']) ? $_SESSION['vision'] : ''; ?> </textarea>
                </div>
            </div>
        </div>
    </div>
    
    <?php include "./components/bottomNav.php"; ?>
    
    <script>
        function navigateTo(page) {
            // In a real application, this would navigate to other pages
            console.log("Navigating to: " + page);
            window.location.href = page;
        }
    </script>
</body>
</html>