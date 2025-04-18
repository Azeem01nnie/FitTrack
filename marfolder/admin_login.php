<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "fittrack_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Only proceed if "Login as admin" is clicked
if (isset($_POST['admin_login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the admin table
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if admin exists
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        // If you used password_hash() during registration
        if ($password === $admin['password_hash']) {
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            header("Location: ../AdminDashboard/administrator/admin_dash.html"); // Change to your actual dashboard
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "Admin not found!";
    }

    $stmt->close();
}

$conn->close();
?>
