<?php
session_start();
date_default_timezone_set('Asia/Manila');
$conn = new mysqli("localhost", "root", "", "fittrack_db");

// Dashboard counts
$data = [
    'totalUsers' => $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'],
    'activeUsers' => $conn->query("SELECT COUNT(*) AS total FROM users WHERE status = 'active'")->fetch_assoc()['total'],
    'disabledUsers' => $conn->query("SELECT COUNT(*) AS total FROM users WHERE status = 'inactive'")->fetch_assoc()['total'],
    'pendingUsers' => $conn->query("SELECT COUNT(*) AS total FROM users WHERE approval_status = 'pending'")->fetch_assoc()['total'],
    'monthlyUsers' => $conn->query("SELECT COUNT(*) AS total FROM users WHERE plan = 'monthly'")->fetch_assoc()['total'],
    'annualUsers' => $conn->query("SELECT COUNT(*) AS total FROM users WHERE plan = 'annually'")->fetch_assoc()['total']
];

echo json_encode($data);
$conn->close();
