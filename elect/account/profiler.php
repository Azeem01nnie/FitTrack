<?php
session_start();
include '../../AdminDashboard/administrator/db_connection.php'; // Corrected path

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the connection is successful
if (!$conn) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from users table
$sql = "SELECT full_name, address, email, mobile FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('Prepare failed: ' . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $full_name = $user['full_name'];
    $address = $user['address'];
    $email = $user['email'];
    $phone = $user['mobile'];
} else {
    // Default values if user not found
    $full_name = $address = $email = $phone = "N/A";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Profile Account - FitTrack</title>
  <style>
    /* (Unchanged existing styles) */
    * { box-sizing: border-box; }
    html, body {
      height: 100%; margin: 0; padding: 0;
      font-family: sans-serif; color: white;
    }
    body {
      background-image: linear-gradient(rgb(252, 254, 255), rgba(173, 216, 230, 0.5));
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      overflow-x: hidden;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .overlay {
      position: absolute; top: 0; left: 0;
      width: 100%; height: 100%;
      background-color: rgba(0, 0, 0, 0.4);
      z-index: 1;
    }
    .header {
      display: flex; justify-content: space-between; align-items: center;
      background-color: #4A90E2; padding: 15px 20px;
      color: white; width: 100%;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
      z-index: 1001;
    }
    .logo { font-size: 24px; font-weight: bold; }
    .header-buttons button {
      margin-left: 10px; padding: 8px 12px;
      border: none; border-radius: 4px;
      cursor: pointer; color: white;
    }
    .container {
      width: 80%;
      font-size: 10px;
      position: relative;
      top: 65px;
    }
    .profilePic {
      margin: 10px auto;
      display: block;
      width: 120px; height: 120px;
      border-radius: 50%;
      border: 4px solid #3db8f5;
      object-fit: cover;
      margin-bottom: 30px;
    }
    .profilePic-container {
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      margin: 20px 0;
    }
    .profilePic-container label {
      cursor: pointer;
      color: white;
      font-size: 14px;
      text-decoration: underline;
    }
    .profilePic-container input { display: none; }

    .form-row {
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      align-items: center;
      width: 90%;
      max-width: 500px;
      margin: 10px auto;
      gap: 10px;
    }

    .header-label {
      font-weight: bold;
      color: black;
      flex: 1;
      text-align: left;
    }

    .info-group {
      flex: 2;
      padding: 5px 10px;
      background-color: white;
      color: rgb(68, 42, 42);
      border-radius: 5px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .delete-btn {
      display: block;
      width: 100%;
      max-width: 250px;
      margin: 10px auto 20px auto;
      padding: 10px 18px;
      background-color: red;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      box-sizing: border-box;
      transition: background-color 0.3s ease;
      text-align: center;
    }

    .delete-btn:hover {
      background-color: darkred;
    }

    .back-btn {
      text-decoration: none;
    }

    .back-button {
      font-size: 20px;
      background-color: transparent;
      border: none;
      color: white;
      cursor: pointer;
      padding: 8px;
      font-weight: bold;
    }

    .submit-btn {
      display: block;
      width: 100%;
      max-width: 250px;
      padding: 12px 20px;
      background-color: #3db8f5;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      box-sizing: border-box;
      transition: background-color 0.3s ease;
      text-align: center;
      margin-top: 20px;
    }

    .submit-btn:hover {
      background-color: #2895c4;
    }
  </style>
</head>
<body>
  <div class="header">
    <a href="../homepage/home_page.php" class="back-btn">
      <button class="back-button">←</button>
    </a>
  </div>

  <div class="container" id="mainContent">

    <div class="form-row">
      <div class="header-label"><label>FULL NAME</label></div>
      <div class="info-group"><p><?php echo $full_name; ?></p></div>
    </div>

    <div class="form-row">
      <div class="header-label"><label>ADDRESS</label></div>
      <div class="info-group"><p><?php echo $address; ?></p></div>
    </div>

    <div class="form-row">
      <div class="header-label"><label>EMAIL</label></div>
      <div class="info-group"><p><?php echo $email; ?></p></div>
    </div>

    <div class="form-row">
      <div class="header-label"><label>PHONE NUMBER</label></div>
      <div class="info-group"><p><?php echo $phone; ?></p></div>
    </div>
  </div>
</body>
</html>
