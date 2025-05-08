<?php
session_start();
include '../../AdminDashboard/administrator/db_connection.php';

// Language file handling
$preferredLanguage = isset($_COOKIE['preferredLanguage']) ? $_COOKIE['preferredLanguage'] : 'en';
$languageFile = "../../languages/lang_$preferredLanguage.php";

if (file_exists($languageFile)) {
    $language = include $languageFile;
} else {
    $language = include "../../languages/lang_en.php";
}

if (!isset($_SESSION['user_id'])) {
    header('Location: /myprojects/FiTrackElective/FitTrack/marfolder/LoginUser.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$sql = "SELECT full_name, username, email, mobile, profile_picture, preferred_language FROM users WHERE user_id = ?";
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

$savedLanguage = isset($_COOKIE['preferredLanguage']) ? $_COOKIE['preferredLanguage'] : $user['preferred_language'];

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
    $mobile = $_POST['contact_no'];
    $preferredLanguage = $_POST['language'];

    $sqlUpdateDetails = "UPDATE users SET full_name = ?, username = ?, email = ?, mobile = ?, preferred_language = ? WHERE user_id = ?";
    $stmtUpdateDetails = $conn->prepare($sqlUpdateDetails);
    if ($stmtUpdateDetails) {
        $stmtUpdateDetails->bind_param('sssssi', $full_name, $username, $email, $mobile, $preferredLanguage, $user_id);
        $stmtUpdateDetails->execute();
    } else {
        die("User info update failed: " . $conn->error);
    }

    setcookie('preferredLanguage', $preferredLanguage, time() + 3600 * 24 * 30, '/');
    header("Location: setting.php");
    exit();

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= isset($language['account_settings']) ? $language['account_settings'] : 'Account Settings'; ?> - FitTrack</title>
  <link rel="stylesheet" href="setting.css">
</head>
<style>
  @media(max-width: 415px){
    .cancel-btn {
      margin-right: 100px;
    }
  }
</style>
<body>
  <div class="header">
    <div style="display: flex; align-items: center;">
      <a href="../homepage/home_page.php" class="back-btn">
        <button class="back-button">←</button>
      </a>
    </div>
  </div>

  <div class="settings-wrapper">
    <h3>👤 <?= $language['account_settings'] ?? 'Account Settings'; ?></h3>
    
    <form action="" method="POST" enctype="multipart/form-data">
      <div class="settings-item">
        <label for="language"><?= $language['language'] ?? 'Language'; ?></label>
        <select id="language" name="language">
          <option value="en" <?= $savedLanguage == 'en' ? 'selected' : ''; ?>><?= $language['language_english'] ?? 'English'; ?></option>
          <option value="fil" <?= $savedLanguage == 'fil' ? 'selected' : ''; ?>><?= $language['language_filipino'] ?? 'Filipino'; ?></option>
          <option value="ko" <?= $savedLanguage == 'ko' ? 'selected' : ''; ?>><?= $language['language_korean'] ?? 'Korean'; ?></option>
          <option value="ja" <?= $savedLanguage == 'ja' ? 'selected' : ''; ?>><?= $language['language_japanese'] ?? 'Japanese'; ?></option>
          <option value="km" <?= $savedLanguage == 'km' ? 'selected' : ''; ?>><?= $language['language_khmer'] ?? 'Khmer'; ?></option>
        </select>
      </div>

      <div class="settings-item">
        <h3><?= $language['edit_profile'] ?? 'Edit Profile'; ?></h3>

        <div class="form-group">
          <label for="full_name"><?= $language['full_name'] ?? 'Full Name'; ?></label>
          <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($user['full_name']); ?>" required>
        </div>

        <div class="form-group">
          <label for="username"><?= $language['username'] ?? 'Username'; ?></label>
          <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>
        </div>

        <div class="form-group">
          <label for="email"><?= $language['email'] ?? 'Email'; ?></label>
          <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="form-group">
          <label for="contact_no"><?= $language['contact_no'] ?? 'Contact Number'; ?></label>
          <input type="tel" id="contact_no" name="contact_no" value="<?= htmlspecialchars($user['mobile']); ?>" required>
        </div>

        <div class="btn-row">
          <button type="submit" class="save-btn"><?= $language['save_changes'] ?? 'Save Changes'; ?></button>
          <a href="../homepage/home_page.php" class="cancel-btn"><?= $language['cancel'] ?? 'Cancel'; ?></a>
        </div>
      </div>
    </form>
  </div>
</body>
</html>
