<?php
$conn = new mysqli("localhost", "root", "", "fittrack_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['user_id']) || !isset($_GET['action'])) {
    die("Missing parameters.");
}

$user_id = intval($_GET['user_id']);  // Make sure it's an integer
$action = $_GET['action'];            // 'approve' or 'reject'

// Set the approval status based on the action
if ($action === 'approve') {
    $sql = "UPDATE users SET approval_status = 'approved' WHERE user_id = ?";
} elseif ($action === 'reject') {
    $sql = "UPDATE users SET approval_status = 'rejected' WHERE user_id = ?";
} else {
    die("Invalid action.");
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    header("Location: Approvalpage.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
