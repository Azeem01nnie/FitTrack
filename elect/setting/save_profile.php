<?php
session_start();
$conn = new mysqli("localhost", "root", "", "fittrack_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to edit your profile.");
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT profile_picture FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$current_profile_pic = $user['profile_picture'];

$profile_pic = $current_profile_pic; // Default to the current picture
if ($_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/';
    $upload_file = $upload_dir . basename($_FILES['profile_picture']['name']);
    
    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_file)) {
        $profile_pic = $upload_file; // Update with the new file path
    }
}

$full_name = $_POST['full_name'];
$username = $_POST['username'];
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$email = $_POST['email'];
$contact_no = $_POST['contact_no'];

$sql = "SELECT password_hash FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stored_password = $user['password_hash'];

if (password_verify($current_password, $stored_password)) {
    if (!empty($new_password)) {
        $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);
        $update_password_sql = "UPDATE users SET password_hash = ? WHERE user_id = ?";
        $stmt = $conn->prepare($update_password_sql);
        $stmt->bind_param('si', $new_password_hash, $user_id);
        $stmt->execute();
    }

    $update_sql = "UPDATE users SET full_name = ?, username = ?, email = ?, contact_no = ?, profile_picture = ? WHERE user_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param('sssssi', $full_name, $username, $email, $contact_no, $profile_pic, $user_id);
    $stmt->execute();

    echo "Profile updated successfully!";
    header('Location: ../homepage/home_page.php');
} else {
    echo "Current password is incorrect.";
}
?>
