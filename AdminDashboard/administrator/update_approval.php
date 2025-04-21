<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "fittrack_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user_id and action are set in the GET request
if (!isset($_GET['user_id']) || !isset($_GET['action'])) {
    die("Missing parameters.");
}

$user_id = intval($_GET['user_id']);  // Ensure it's an integer to prevent SQL injection
$action = $_GET['action'];            // Action is either 'approve' or 'reject'

// Set the approval status based on the action
if ($action === 'approve') {
    $sql = "UPDATE users SET approval_status = 'approved' WHERE user_id = ?";
} elseif ($action === 'reject') {
    $sql = "UPDATE users SET approval_status = 'rejected' WHERE user_id = ?";
} else {
    die("Invalid action.");
}

// Prepare and bind the SQL statement
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing query: " . $conn->error);
}

// Bind the user_id parameter
$stmt->bind_param("i", $user_id);

// Execute the query
if ($stmt->execute()) {
    // Redirect to the approval page after success
    header("Location: Approvalpage.php");
    exit();
} else {
    echo "Error: " . $stmt->error;  // Show error message if execution fails
}

$stmt->close();
$conn->close();
?>
