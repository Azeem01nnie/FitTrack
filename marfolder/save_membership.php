<?php
session_start();

$conn = new mysqli("localhost", "root", "", "fittrack_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$plan = $_POST['plan'];
$user_id = $_SESSION['user_id']; 
$stmt = $conn->prepare("UPDATE users SET membership_plan = ? WHERE user_id = ?");
$stmt->bind_param("si", $plan, $user_id);

if ($stmt->execute()) {
    header("Location: conditions.html");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
