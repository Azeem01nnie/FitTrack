<?php
session_start();
$conn = new mysqli("localhost", "root", "", "fittrack_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['user_login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (isset($user['status']) && $user['status'] === 'inactive') {
            header("Location: LoginUser.php?error=Your+account+has+been+deactivated+by+admin");
            exit();
        }

        if ($user['approval_status'] !== 'approved') {
            header("Location: LoginUser.php?error=Your+account+is+not+yet+approved");
            exit();
        }
        if ($password === $user['password_hash']) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['full_name']; // full name
            $_SESSION['username'] = $user['username'];   // if you need actual username
            header("Location: ../elect/homepage/home_page.php");
            exit();
        } else {
            header("Location: logIn.php?error=Incorrect+password");
            exit();
        }
    } else {
        header("Location: logIn.php?error=User+not+found");
        exit();
    }
}
?>
