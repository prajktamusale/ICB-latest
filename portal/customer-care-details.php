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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Care Details</title>
    <style>
        <?php include "./css/header.css" ?>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        body {
            background-color: #f5f5f5;
            min-height: 100vh;
            position: relative;
            padding-bottom: 60px; /* Space for bottom nav */
        }

        .page-content {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 120px); /* Adjust based on your header height */
            padding: 20px;
        }

        .container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 550px;
            padding: 24px;
            position: relative;
            margin: 20px 0;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .header-container h2 {
            font-weight: 600;
            color: #262626;
            font-size: 18px;
        }

        .frame-label {
            font-size: 13px;
            color: #888;
            margin-bottom: 20px;
            margin-top: -8px;
        }

        .edit-icon {
            color: #333;
            cursor: pointer;
            width: 20px;
            height: 20px;
        }

        .form-group {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .form-group label {
            width: 150px;
            font-weight: 500;
            color: #333;
            font-size: 14px;
        }

        .form-group input {
            flex: 1;
            padding: 10px 14px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .form-group input:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
        }

        .button-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 24px;
        }

        .save-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 12px 28px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .save-button:hover {
            background-color: #3d9140;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
            transform: translateY(-1px);
        }

        .save-button:active {
            transform: translateY(1px);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Mobile responsiveness */
        @media (max-width: 600px) {
            .container {
                padding: 20px;
                border-radius: 8px;
            }

            .form-group {
                flex-direction: column;
                align-items: flex-start;
            }

            .form-group label {
                width: 100%;
                margin-bottom: 8px;
            }

            .form-group input {
                width: 100%;
            }

            .button-container {
                justify-content: center;
            }

            .save-button {
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
    
    <div class="page-content">
        <div class="container">
            <div class="header-container">
                <h2>Customer Care Details</h2>
                <svg class="edit-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
            </div>
            
            <form id="customer-care-form">
                <div class="form-group">
                    <label for="poc-name">Customer Care Name</label>
                    <input type="text" id="poc-name" placeholder="Name" value="">
                </div>
                
                <div class="form-group">
                    <label for="phone-number-1">Phone Number 1</label>
                    <input type="tel" id="phone-number-1" placeholder="XXX XXXX XXX" value="">
                </div>
                
                <div class="form-group">
                    <label for="phone-number-2">Phone Number 2</label>
                    <input type="tel" id="phone-number-2" placeholder="XXX XXXX XXX" value="">
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" placeholder="abc@gmail.com" value="">
                </div>
                
                <div class="button-container">
                    <button type="submit" class="save-button">Save</button>
                </div>
            </form>
        </div>
    </div>
    
    <?php include "./components/bottomNav.php"; ?>
    
    <script>
        // Load saved data when page loads
        window.addEventListener('DOMContentLoaded', function() {
            // Retrieve saved data (can be replaced with API calls for real implementation)
            const savedName = localStorage.getItem('pocName') || '';
            const savedPhone1 = localStorage.getItem('pocPhone1') || '';
            const savedPhone2 = localStorage.getItem('pocPhone2') || '';
            const savedEmail = localStorage.getItem('pocEmail') || '';
            
            // Populate form fields
            document.getElementById('poc-name').value = savedName;
            document.getElementById('phone-number-1').value = savedPhone1;
            document.getElementById('phone-number-2').value = savedPhone2;
            document.getElementById('email').value = savedEmail;
        });

        // Save data when form is submitted
        document.getElementById('customer-care-form').addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Get form values
            const name = document.getElementById('poc-name').value;
            const phone1 = document.getElementById('phone-number-1').value;
            const phone2 = document.getElementById('phone-number-2').value;
            const email = document.getElementById('email').value;
            
            // Save data (using localStorage as an example)
            localStorage.setItem('pocName', name);
            localStorage.setItem('pocPhone1', phone1);
            localStorage.setItem('pocPhone2', phone2);
            localStorage.setItem('pocEmail', email);
            
            // Visual feedback
            const saveBtn = document.querySelector('.save-button');
            const originalText = saveBtn.textContent;
            
            saveBtn.textContent = 'Saved!';
            saveBtn.style.backgroundColor = '#2E7D32';
            
            // Reset button after delay
            setTimeout(function() {
                saveBtn.textContent = originalText;
                saveBtn.style.backgroundColor = '#4CAF50';
            }, 2000);
        });
    </script>
</body>
</html>