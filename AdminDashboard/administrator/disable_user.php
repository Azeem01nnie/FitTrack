<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "fittrack_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Disable User (set status to 'inactive')
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Update query to disable the user
    $sql = "UPDATE users SET status = 'inactive' WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "User has been disabled successfully.";
    } else {
        echo "Error disabling user.";
    }

    // Redirect back to the manage page
    header("Location: managepage.php");
    exit();
}

$stmt->close();
$conn->close();
?>
