<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "fittrack_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Re-enable User (set status back to 'active')
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Update query to re-enable the user
    $sql = "UPDATE users SET status = 'active' WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "User has been re-enabled successfully.";
    } else {
        echo "Error re-enabling user.";
    }

    // Redirect back to the manage page
    header("Location: managepage.php");
    exit();
}

$stmt->close();
$conn->close();
?>
