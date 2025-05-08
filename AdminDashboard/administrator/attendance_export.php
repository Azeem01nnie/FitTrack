<?php
// Set timezone to Philippines
date_default_timezone_set('Asia/Manila');

// Connect to the database
$conn = new mysqli("localhost", "root", "", "fittrack_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$where = "";

if (!empty($from) && !empty($to)) {
    $where = "WHERE al.time_in BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
}

// Prepare Excel headers
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=attendance_logs.xls");
echo "Username\tFull Name\tTime In\tTime Out\n";

// Query attendance data
$sql = "
    SELECT u.username, u.full_name, al.time_in, al.time_out 
    FROM attendance_logs al
    JOIN users u ON u.user_id = al.user_id
    $where
    ORDER BY al.time_in DESC
";

$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Format time_in and time_out to Philippine time
        $timeIn = date('Y-m-d H:i:s', strtotime($row['time_in']));
        $timeOut = date('Y-m-d H:i:s', strtotime($row['time_out']));

        echo "{$row['username']}\t{$row['full_name']}\t$timeIn\t$timeOut\n";
    }
}
?>
