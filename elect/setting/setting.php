<?php
session_start();
include '../../AdminDashboard/administrator/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /myprojects/FiTrackElective/FitTrack/marfolder/LoginUser.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$sql = "SELECT full_name, username, email, mobile, profile_picture FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header('Location: /myprojects/FiTrackElective/FitTrack/marfolder/LoginUser.php');
    exit();
}

$savedLanguage = isset($_COOKIE['preferredLanguage']) ? $_COOKIE['preferredLanguage'] : 'en';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $profilePic = $_FILES['profile_picture'];
        $uploadDir = 'uploads/profile_pics/';
        $uploadFile = $uploadDir . basename($profilePic['name']);

        if (move_uploaded_file($profilePic['tmp_name'], $uploadFile)) {
            $sqlUpdate = "UPDATE users SET profile_picture = ? WHERE user_id = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            if ($stmtUpdate) {
                $stmtUpdate->bind_param('si', $uploadFile, $user_id);
                $stmtUpdate->execute();
            } else {
                die("Profile picture update failed: " . $conn->error);
            }
        }
    }

    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $mobile = $_POST['contact_no']; // Adjusted for DB column

    $sqlUpdateDetails = "UPDATE users SET full_name = ?, username = ?, email = ?, mobile = ? WHERE user_id = ?";
    $stmtUpdateDetails = $conn->prepare($sqlUpdateDetails);
    if ($stmtUpdateDetails) {
        $stmtUpdateDetails->bind_param('ssssi', $full_name, $username, $email, $mobile, $user_id);
        $stmtUpdateDetails->execute();
    } else {
        die("User info update failed: " . $conn->error);
    }

    header("Location: ../homepage/home_page.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Account Settings - FitTrack</title>
  <link rel="stylesheet" href="setting.css">
</head>
<body>
  <div class="header">
    <div style="display: flex; align-items: center;">
    <a href="../homepage/home_page.php" class="back-btn">
        <button class="back-button">←</button>
      </a>
    </div>
  </div>

  <div class="settings-wrapper">
    <h3>👤 Account Settings</h3>
    
    <!-- Language Selection -->
    <div class="settings-item">
      <label for="language">Language</label>
      <select id="language" onchange="setLanguagePreference()">
        <option value="en" <?php echo $savedLanguage == 'en' ? 'selected' : ''; ?>>English</option>
        <option value="fil" <?php echo $savedLanguage == 'fil' ? 'selected' : ''; ?>>Filipino</option>
        <option value="ko" <?php echo $savedLanguage == 'ko' ? 'selected' : ''; ?>>Korean</option>
        <option value="ja" <?php echo $savedLanguage == 'ja' ? 'selected' : ''; ?>>Japanese</option>
        <option value="km" <?php echo $savedLanguage == 'km' ? 'selected' : ''; ?>>Khmer</option>
      </select>
    </div>

    <!-- Edit Profile Section -->
    <div class="settings-item">
      <h3>Edit Profile</h3>
      <form action="" method="POST" enctype="multipart/form-data">

        <div class="form-group">
          <label for="full_name">Full Name</label>
          <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
        </div>

        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>

        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="form-group">
          <label for="contact_no">Contact Number</label>
          <input type="tel" id="contact_no" name="contact_no" value="<?php echo htmlspecialchars($user['mobile']); ?>" required>
        </div>
        <div class="btn-row">
          <button type="submit" class="save-btn">Save Changes</button>
          <a href="../homepage/home_page.php" class="cancel-btn">Cancel</a>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Save user language preference to localStorage
    function setLanguagePreference() {
      const languageSelect = document.getElementById('language');
      localStorage.setItem('preferredLanguage', languageSelect.value);
      alert('Language preference updated! Refresh the page to apply.');
    }

    // JavaScript for Image Preview
    document.getElementById('profilePicUpload').addEventListener('change', function(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('profilePreview').src = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    });
  </script>
  <style>
    /* General Body Styles */
html, body {
  height: 100%;
  margin: 0;
  padding: 0;
  font-family: Arial, sans-serif;
  background-image: linear-gradient(rgb(252, 254, 255), rgba(173, 216, 230, 0.5));
  background-size: cover;
  background-position: center;
  overflow-x: hidden;
}

/* Header Styles */
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #4A90E2;
  padding: 10px 20px;
  color: white;
  width: 100%;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

/* Back Button Styling */
.back-btn {
  text-decoration: none;
}

.back-button {
  font-size: 24px;
  background-color: transparent;
  border: none;
  color: white;
  cursor: pointer;
  padding: 10px;
  font-weight: bold;
}

/* Settings Page Wrapper */
.settings-wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: 50px;
}

.settings-wrapper h3 {
  font-size: 24px;
  margin-bottom: 30px;
  color: #333;
}

/* Settings Page Items */
.settings-item {
  margin-bottom: 20px;
  width: 500px;
  text-align: left;
}

.settings-item label {
  display: block;
  margin-bottom: 8px;
  font-weight: bold;
  font-size: 15px;
  color: #555;
}

.settings-item select,
.settings-item button {
  width: 100%;
  padding: 10px;
  font-size: 15px;
  border-radius: 6px;
  border: 1px solid #ccc;
  background: #fff;
}

.settings-item button {
  background-color: #007bff;
  color: white;
  cursor: pointer;
  transition: background 0.3s ease;
  border: none;
}

.settings-item button:hover {
  background-color: #0056b3;
}

/* Profile Picture Section */
.profile-pic-section {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 20px;
}

.profile-pic {
  width: 140px;
  height: 140px;
  object-fit: cover;
  border-radius: 50%;
  border: 3px solid #007bff;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
  margin-bottom: 10px;
}

.upload-btn {
  display: inline-block;
  padding: 6px 12px;
  background-color: #007bff;
  color: white;
  border-radius: 6px;
  cursor: pointer;
}

/* Form Group Styling */
.form-group {
  margin-bottom: 15px;
}

.form-group label {
  font-weight: bold;
  display: block;
  margin-bottom: 6px;
}

.form-group input {
  width: 100%;
  padding: 10px;
  border-radius: 6px;
  border: 1px solid #ccc;
}

/* Buttons Row */
.btn-row {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
}

.save-btn {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 6px;
  font-weight: bold;
  cursor: pointer;
}

.cancel-btn {
  background-color: #e0e0e0;
  padding: 10px 20px;
  border-radius: 6px;
  text-decoration: none;
  color: #333;
  font-weight: bold;
  display: inline-block;
}

/* Responsive Design */
@media (max-width: 768px) {
  .settings-item,
  .form-group {
    width: 90%;
  }

  .btn-row {
    flex-direction: column;
  }

  .save-btn,
  .cancel-btn {
    width: 100%;
    margin-top: 10px;
    text-align: center;
  }

  .settings-wrapper h3 {
    font-size: 20px;
  }

  .profile-pic {
    width: 120px;
    height: 120px;
  }
}

  </style>
</body>
</html>
