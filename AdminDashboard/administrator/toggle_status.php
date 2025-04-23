<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "fittrack_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user ID from the URL
$user_id = $_GET['user_id'];

// Get the current account status
$sql = "SELECT account_status FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    // Toggle the account status
    $new_status = ($row['account_status'] == 'active') ? 'disabled' : 'active';

    // Update the account status
    $update_sql = "UPDATE users SET account_status = ? WHERE user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $new_status, $user_id);
    $update_stmt->execute();

    // Redirect back to the manage accounts page
    header("Location: managepage.php");
    exit();
} else {
    echo "User not found.";
}

$conn->close();
?>
