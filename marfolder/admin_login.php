<?php
session_start();
$conn = new mysqli("localhost", "root", "", "fittrack_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['admin_login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        // Use password_verify if using hashed passwords
        if ($password === $admin['password_hash']) {
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            header("Location: ../AdminDashboard/administrator/admin_dash.html");
            exit();
        } else {
            header("Location: logIn.php?error=Incorrect+password");
            exit();
        }
    } else {
        header("Location: logIn.php?error=Admin+not+found");
        exit();
    }
}
?>
