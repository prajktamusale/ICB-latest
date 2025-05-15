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
$sql2 = "SELECT * FROM company_details";
$result2 = $conn->query($sql2);
if($result2->num_rows > 0)
{
    $row2 = $result2->fetch_assoc();
    $_SESSION['vision'] = $row2['vision'];
    $_SESSION['mission'] = $row2['mission'];
    $_SESSION['objective'] = $row2['objective'];
    $_SESSION['tagline'] = $row2['tagline'];
    $_SESSION['State'] = $row2['State'];
    $_SESSION['city'] = $row2['city'];
    $_SESSION['pin'] = $row2['pin'];
    $_SESSION['Add_line_1'] = $row2['Add_line_1'];
    $_SESSION['Add_line_2'] = $row2['Add_line_2'];
    $_SESSION['Customer_Care_Name'] = $row2['Customer_Care_Name'];
    $_SESSION['Customer_Care_phone_num_1'] = $row2['Customer_Care_phone_num_1'];
    $_SESSION['Customer_Care_phone_num_2'] = $row2['Customer_Care_phone_num_2'];
    $_SESSION['Customer_Care_Email'] = $row2['Customer_Care_Email'];
    $_SESSION['POC_Name'] = $row2['POC_Name'];
    $_SESSION['Poc_Email'] = $row2['Poc_Email'];
    $_SESSION['Poc_Phone'] = $row2['Poc_Phone'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About Company</title>
    <style>
       <?php include "./css/header.css" ?>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            color: #333;
            min-height: 100vh;
            position: relative;
            padding-bottom: 60px; /* Space for bottom nav */
        }
        
        .page-content {
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 20px;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }
        
        .section-header h1 {
            font-size: 20px;
            font-weight: 500;
            margin: 0;
        }
        
        .edit-icon {
            color: #777;
            cursor: pointer;
            font-size: 18px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: #444;
        }
        
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
            color: #555;
        }
        
        .form-control::placeholder {
            color: #999;
        }
        
        .button-container {
            text-align: center;
            margin-top: 30px;
        }
        
        .save-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .save-button:hover {
            background-color: #45a049;
        }
        
        .section-divider {
            height: 10px;
            border-bottom: 1px solid #eee;
            margin: 30px 0;
        }
        
        .section {
            margin-bottom: 20px;
        }
        
        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .column {
            flex: 1;
            margin-bottom: 0;
        }
        
        /* Mobile responsiveness */
        @media (max-width: 600px) {
            .form-row {
                flex-direction: column;
                gap: 10px;
            }
            
            .container {
                margin: 10px;
                padding: 15px;
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
    
    <div class="page-content">
        <div class="container">
            <div class="section-header">
                <h1>About Company</h1>
                <div class="edit-icon">✏️</div>
            </div>
            
            <form id="about-company-form">
                <div class="form-group">
                    <label for="vision">Vision</label>
                    <input type="text" id="vision" class="form-control" placeholder="Our Vision is ..." value="<?php echo isset($_SESSION['vision']) ? $_SESSION['vision'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="mission">Mission</label>
                    <input type="text" id="mission" class="form-control" placeholder="Our Mission is ..." value="<?php echo isset($_SESSION['mission']) ? $_SESSION['mission'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="objective">Objective</label>
                    <input type="text" id="objective" class="form-control" placeholder="Our Objective is ..." value="<?php echo isset($_SESSION['objective']) ? $_SESSION['objective'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="tagline">Tagline</label>
                    <input type="text" id="tagline" class="form-control" placeholder="Company Tag Line..." value="<?php echo isset($_SESSION['tagline']) ? $_SESSION['tagline'] : ''; ?>">
                </div>
                
                <div class="section-divider"></div>
                
                <div class="section">
                    <div class="section-header">
                        <h1>Location</h1>
                        <div class="edit-icon">✏️</div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group column">
                            <label for="State">State</label>
                            <input type="text" id="State" class="form-control" placeholder="State" value="<?php echo isset($_SESSION['State']) ? $_SESSION['State'] : ''; ?>">
                        </div>
                        <div class="form-group column">
                            <label for="city">City</label>
                            <input type="text" id="city" class="form-control" placeholder="City" value="<?php echo isset($_SESSION['city']) ? $_SESSION['city'] : ''; ?>">
                        </div>
                        <div class="form-group column">
                            <label for="pin">PIN</label>
                            <input type="text" id="pin" class="form-control" placeholder="Pin" value="<?php echo isset($_SESSION['pin']) ? $_SESSION['pin'] : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="Add_line_1">Address Line 1</label>
                        <input type="text" id="Add_line_1" class="form-control" placeholder="Address Line 1" value="<?php echo isset($_SESSION['Add_line_1']) ? $_SESSION['Add_line_1'] : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="Add_line_2">Address Line 2</label>
                        <input type="text" id="Add_line_2" class="form-control" placeholder="Address Line 2" value="<?php echo isset($_SESSION['Add_line_2']) ? $_SESSION['Add_line_2'] : ''; ?>">
                    </div>
                </div>
                
                <div class="section-divider"></div>
                
                <div class="section">
                    <div class="section-header">
                        <h1>Customer Care</h1>
                        <div class="edit-icon">✏️</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="Customer_Care_Name">Customer Care Name</label>
                        <input type="text" id="Customer_Care_Name" class="form-control" placeholder="Customer Care Name" value="<?php echo isset($_SESSION['Customer_Care_Name']) ? $_SESSION['Customer_Care_Name'] : ''; ?>">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group column">
                            <label for="Customer_Care_phone_num-1">Phone Number 1</label>
                            <input type="text" id="Customer_Care_phone_num_1" class="form-control" placeholder="Phone Number 1" value="<?php echo isset($_SESSION['Customer_Care_phone_num_1']) ? $_SESSION['Customer_Care_phone_num_1'] : ''; ?>">
                        </div>
                        <div class="form-group column">
                            <label for="Customer_Care_phone_num-2">Phone Number 2</label>
                            <input type="text" id="Customer_Care_phone_num_2" class="form-control" placeholder="Phone Number 2" value="<?php echo isset($_SESSION['Customer_Care_phone_num_2']) ? $_SESSION['Customer_Care_phone_num_2'] : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="Customer_Care_Email">Customer Care Email</label>
                        <input type="email" id="Customer_Care_Email" class="form-control" placeholder="customer.care@example.com" value="<?php echo isset($_SESSION['Customer_Care_Email']) ? $_SESSION['Customer_Care_Email'] : ''; ?>">
                    </div>
                </div>
                
                <div class="section-divider"></div>
                
                <div class="section">
                    <div class="section-header">
                        <h1>Point of Contact</h1>
                        <div class="edit-icon">✏️</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="POC_Name">POC Name</label>
                        <input type="text" id="POC_Name" class="form-control" placeholder="Point of Contact Name" value="<?php echo isset($_SESSION['POC_Name']) ? $_SESSION['POC_Name'] : ''; ?>">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group column">
                            <label for="Poc_Email">POC Email</label>
                            <input type="email" id="Poc_Email" class="form-control" placeholder="poc@example.com" value="<?php echo isset($_SESSION['Poc_Email']) ? $_SESSION['Poc_Email'] : ''; ?>">
                        </div>
                        <div class="form-group column">
                            <label for="Poc_Phone">POC Phone</label>
                            <input type="text" id="Poc_Phone" class="form-control" placeholder="Phone Number" value="<?php echo isset($_SESSION['Poc_Phone']) ? $_SESSION['Poc_Phone'] : ''; ?>">
                        </div>
                    </div>
                </div>
                
                <div class="button-container">
                    <button type="submit" class="save-button">Save</button>
                </div>
            </form>
        </div>
    </div>
    
    <?php include "./components/bottomNav.php"; ?>
    <script>
   document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('about-company-form');
    
    if (!form) {
        console.error('Form with ID "about-company-form" not found');
        return;
    }
    
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form submission
        
        const saveBtn = document.querySelector('.save-button');
        let originalText = '';
        let originalBgColor = '';
        
        if (saveBtn) {
            originalText = saveBtn.textContent;
            originalBgColor = saveBtn.style.backgroundColor;
            saveBtn.textContent = 'Saving...';
            saveBtn.disabled = true;
        }
        
        // Get the form data properly with actual input field values
        const data = {
            // Important: We'll let the server handle the ID value
            // The server will use ID 101 instead of session ID
            vision: document.getElementById('vision').value,
            mission: document.getElementById('mission').value,
            objective: document.getElementById('objective').value,
            tagline: document.getElementById('tagline').value,
            State: document.getElementById('State').value,
            city: document.getElementById('city').value,
            pin: document.getElementById('pin').value,
            Add_line_1: document.getElementById('Add_line_1').value,
            Add_line_2: document.getElementById('Add_line_2').value,
            Customer_Care_Name: document.getElementById('Customer_Care_Name').value,
            Customer_Care_phone_num_1: document.getElementById('Customer_Care_phone_num_1').value,
            Customer_Care_phone_num_2: document.getElementById('Customer_Care_phone_num_2').value,
            Customer_Care_Email: document.getElementById('Customer_Care_Email').value,
            POC_Name: document.getElementById('POC_Name').value,
            Poc_Email: document.getElementById('Poc_Email').value,
            Poc_Phone: document.getElementById('Poc_Phone').value
        };
        
        console.log('Sending data:', data); // Debug log
        
        fetch('database/update_company_details.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(responseData => {
            console.log('Response:', responseData); // Debug log
            
            if (responseData.success) {
                alert('Company details updated successfully!');
                
                if (saveBtn) {
                    saveBtn.textContent = 'Saved!';
                    saveBtn.style.backgroundColor = '#2E7D32';
                    
                    setTimeout(function() {
                        saveBtn.textContent = originalText;
                        saveBtn.style.backgroundColor = originalBgColor || '#4CAF50';
                        saveBtn.disabled = false;
                    }, 2000);
                }
            } else {
                alert('Error: ' + (responseData.message || 'Unknown error occurred'));
                if (saveBtn) {
                    saveBtn.textContent = originalText;
                    saveBtn.style.backgroundColor = originalBgColor || '#4CAF50';
                    saveBtn.disabled = false;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving changes: ' + error.message);
            
            if (saveBtn) {
                saveBtn.textContent = originalText;
                saveBtn.style.backgroundColor = originalBgColor || '#4CAF50';
                saveBtn.disabled = false;
            }
        });
    });
});
    </script>
</body>
</html>