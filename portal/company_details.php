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

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Account Details</title>
  <link rel="stylesheet" href="./css/header.css">
  <link rel="stylesheet" href="./css/bottomNav.css">
  <style>
    * {
      box-sizing: border-box;
    }
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: #f2f2f2;
    }
    .main-wrapper {
      display: flex;
      min-height: calc(100vh - 60px); /* assuming header is 60px */
    }
    .sidebar {
      width: 220px;
      background-color: #fff;
      padding: 20px;
      border-right: 1px solid #ddd;
    }
    .sidebar img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      margin-bottom: 10px;
      background: #ccc;
    }
    .sidebar div {
      margin: 10px 0;
    }
    .content {
      flex: 1;
      padding: 30px;
    }
    .card {
      background: white;
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 20px;
      max-width: 600px;
    }
    .row {
      margin-bottom: 12px;
    }
    .row label {
      font-weight: bold;
      width: 150px;
      display: inline-block;
    }
    input[readonly] {
      border: none;
      background: transparent;
      color: #333;
    }
    input {
      font-size: 14px;
    }
    .actions {
      text-align: right;
      margin-top: 15px;
    }
    .save-btn {
      padding: 6px 14px;
      background-color: green;
      color: white;
      border: none;
      border-radius: 4px;
    }
    .edit-icon {
      float: right;
      cursor: pointer;
      font-size: 18px;
    }
    .profile-img {
      float: inline-end;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #ccc;
    margin-top: -34%;
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

        .image-carousel {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }


  </style>
  <?php 
    echo "<script>localStorage.setItem('id', ".$_SESSION['id'].");</script>";
  ?>
  <script src="./js/sliderAccordian.js" defer></script>
  <script src="./js/sideBar.js" defer></script>
</head>
<body>

<?php include "./header.php"; ?>

<div class="container ">
  <!-- Sidebar -->
  

  <!-- Main Content -->
  <div class="content">
    <h3>Account</h3>
    <div class="card">
      <div>
        <strong>Company Details</strong>
        <span class="edit-icon" onclick="enableEdit()">✏️</span>
      </div>

      <div class="row"><label>Company Name:</label> <input type="text" value="<?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ''; ?>" readonly></div>

      <div class="row"><label>Organization Name:</label> <input type="text" value="<?php echo isset($_SESSION['Organization']) ? $_SESSION['Organization'] : ''; ?>" readonly></div>
      <div class="row"><label>Email:</label> <input type="email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" readonly></div>
      <div class="row"><label>Phone:</label> <input type="text" value="<?php echo isset($_SESSION['phone']) ? $_SESSION['phone'] : ''; ?>" readonly></div>
      <div class="row"><label>Sector:</label> <input type="text" value="<?php echo isset($_SESSION['Sector']) ? $_SESSION['Sector'] : ''; ?>" readonly></div>
      <div class="row"><label>PAN:</label> <input type="text" value="<?php echo isset($_SESSION['PAN']) ? $_SESSION['PAN'] : ''; ?>" readonly></div>

<div class="image-upload" id="imageDropArea">
                        <input type="file" id="productImage" accept="image/*" class="hidden">
                        <div class="plus">+</div>
                        <div class="upload-text">ADD IMAGE</div>
                        <div class="refresh-icon">↻</div>
                    </div>
      <div class="actions">
        <button class="save-btn" onclick="saveChanges()">Save</button>
      </div>
    </div>
  </div>
</div>

<?php include "./components/bottomNav.php"; ?>

<script>
  
  function enableEdit() {
    document.querySelectorAll("input").forEach(input => input.removeAttribute("readonly"));
  }

  function saveChanges() {
    const data = {};
    document.querySelectorAll(".row").forEach(row => {
      const label = row.querySelector("label").innerText.replace(":", "").trim().toLowerCase().replace(" ", "_");
      const input = row.querySelector("input");
      const value = input ? input.value : "";
      data[label] = value;
    });

    // Debug: Check what you're sending
    console.log("Sending data:", data);

fetch('database/update_company.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(data)
})
    .then(response => response.json())
    .then(result => {
      console.log('Success:', result);
      if (result.success) {
        alert("Changes saved successfully!");
      } else {
        alert("Failed to save changes. Please try again.");
      }
    })
    .catch(error => {
      console.error('Error', error);
      alert("An error occurred while saving changes.");
    });
  }
</script>

</body>
</html>
