<?php
include 'db_connection.php';

if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
    $sql = "DELETE FROM users WHERE user_id = $user_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: managepage.php?success=deleted");
        exit();
    } else {
        echo "Error deleting user: " . $conn->error;
    }
}
?>
