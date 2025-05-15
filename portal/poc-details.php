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
    <title>POC Details Form</title>
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
            position: relative;
            min-height: 100vh;
        }

        .main-content {
            padding: 20px;
            padding-top: 80px; /* Space for header */
            padding-bottom: 80px; /* Space for bottom nav */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 160px); /* Adjust for header and bottom nav */
        }

        .container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            padding: 24px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #eee;
        }

        .header h2 {
            font-weight: 600;
            color: #333;
            font-size: 20px;
        }

        .edit-icon {
            color: #666;
            cursor: pointer;
            width: 20px;
            height: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.2s;
        }

        .form-group input:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
        }

        .save-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.2s;
        }

        .save-button:hover {
            background-color: #45a049;
        }

        /* Mobile responsiveness */
        @media (max-width: 480px) {
            .container {
                padding: 16px;
                margin: 0 10px;
            }

            .header h2 {
                font-size: 18px;
            }

            .form-group label {
                font-size: 13px;
            }

            .form-group input {
                padding: 8px 10px;
                font-size: 14px;
            }

            .save-button {
                padding: 10px 20px;
                font-size: 14px;
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
    
    <div class="main-content">
        <div class="container">
            <div class="header">
                <h2>POC Details</h2>
                <svg class="edit-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
            </div>
            
            <form id="poc-form">
                <div class="form-group">
                    <label for="poc-name">POC Name</label>
                    <input type="text" id="poc-name" placeholder="Name" value="">
                </div>
                
                <div class="form-group">
                    <label for="phone-number">Phone Number</label>
                    <input type="tel" id="phone-number" placeholder="XXX XXXX XXX" value="">
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" placeholder="abc@gmail.com" value="">
                </div>
                
                <button type="submit" class="save-button">Save</button>
            </form>
        </div>
    </div>

    <?php include "./components/bottomNav.php"; ?>

    <script>
        // Load saved data when page loads
        window.addEventListener('DOMContentLoaded', function() {
            // Fetch data from server/database
            // For now, using localStorage as an example
            const savedName = localStorage.getItem('pocName') || '';
            const savedPhone = localStorage.getItem('pocPhone') || '';
            const savedEmail = localStorage.getItem('pocEmail') || '';
            
            // Populate form fields with saved data
            document.getElementById('poc-name').value = savedName;
            document.getElementById('phone-number').value = savedPhone;
            document.getElementById('email').value = savedEmail;
        });

        // Save data when form is submitted
        document.getElementById('poc-form').addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Get current form values
            const name = document.getElementById('poc-name').value;
            const phone = document.getElementById('phone-number').value;
            const email = document.getElementById('email').value;
            
            // Save to localStorage (temporary solution)
            localStorage.setItem('pocName', name);
            localStorage.setItem('pocPhone', phone);
            localStorage.setItem('pocEmail', email);
            
            // In production, you would send this data to the server
            // Example AJAX call:
            /*
            fetch('save_poc_details.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: name,
                    phone: phone,
                    email: email,
                    userId: localStorage.getItem('id')
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
            */
            
            // Provide feedback to user
            const saveBtn = document.querySelector('.save-button');
            const originalText = saveBtn.textContent;
            
            saveBtn.textContent = 'Saved!';
            saveBtn.style.backgroundColor = '#2E7D32';
            
            // Reset button text after a short delay
            setTimeout(function() {
                saveBtn.textContent = originalText;
                saveBtn.style.backgroundColor = '#4CAF50';
            }, 2000);
        });
    </script>
</body>
</html>