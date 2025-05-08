<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fittrack_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
$user_id = $_SESSION['user_id'];

// Load language
$preferredLanguage = isset($_COOKIE['preferredLanguage']) ? $_COOKIE['preferredLanguage'] : 'en';
$languageFile = "../../languages/lang_$preferredLanguage.php";
if (file_exists($languageFile)) {
    $lang = include $languageFile;
} else {
    $lang = include "../../languages/lang_en.php"; // fallback
}

$sql = "SELECT time_in, time_out, TIMEDIFF(time_out, time_in) AS duration, DATE(time_in) AS attendance_date 
        FROM attendance_logs 
        WHERE user_id = $user_id 
        ORDER BY time_in DESC";
$result = $conn->query($sql);

$attendance_records = [];
if ($result->num_rows > 0) {
    // Fetch all records
    while ($row = $result->fetch_assoc()) {
        $attendance_records[] = $row;
    }
} else {
    // No records found
    $attendance_records = [];
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Attendance</title>
  <link rel="stylesheet" href="attend.css" />
</head>
<body>
  <div class="header">
    <div style="display: flex; align-items: center;">
      <a href="../homepage/home_page.php" class="back-btn">
        <button class="back-button">←</button>
      </a>
    </div>
  </div>
  
  <div class="container">
    <div class="today-status-container">
      <!-- Dynamic recent check-in details -->
      <?php if (!empty($attendance_records)) { 
        $latest_record = $attendance_records[0]; // The most recent check-in
      ?>
        <h3><i class="fas fa-calendar-day"></i> <?php echo $lang['Recent_Time_in']; ?></h3>
        <p><i class="fas fa-check-circle"></i> You checked in at <?php echo date('g:i A', strtotime($latest_record['time_in'])); ?></p>
        <p><i class="fas fa-stopwatch"></i> <?php echo $lang['Time_in']; ?> duration: <?php echo $latest_record['duration']; ?></p>
      <?php } else { ?>
        <h3><i class="fas fa-calendar-day"></i> <?php echo $lang['Recent_Time_in']; ?></h3>
        <p><i class="fas fa-exclamation-circle"></i> <?php echo $lang['No_Attendance_Records']; ?></p>
      <?php } ?>
    </div>

    <div class="attendance-container">
      <h2><?php echo $lang['My_Attendance']; ?></h2>
      <div class="attendance-header">
        <i class="fas fa-calendar-alt"></i>
        <span><?php echo $lang['Attendance_History']; ?></span>
      </div>

      <table class="attendance-table">
        <thead>
          <tr>
            <th><?php echo $lang['Date']; ?></th>
            <th><?php echo $lang['Time_in']; ?></th>
            <th><?php echo $lang['Time_out']; ?></th>
            <th><?php echo $lang['Duration']; ?></th>
          </tr>
        </thead>
        <tbody>
          <!-- Loop through attendance records -->
          <?php if (!empty($attendance_records)) {
            foreach ($attendance_records as $record) {
          ?>
            <tr>
              <td><?php echo date('M d, Y', strtotime($record['attendance_date'])); ?></td>
              <td><?php echo date('g:i A', strtotime($record['time_in'])); ?></td>
              <td><?php echo date('g:i A', strtotime($record['time_out'])); ?></td>
              <td><?php echo $record['duration']; ?></td>
              <td><i class="fas fa-ellipsis-h"></i></td>
            </tr>
          <?php 
            }
          } else { ?>
            <tr>
              <td colspan="5"><?php echo $lang['No_Attendance_Records']; ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
</div>
<script>
  setInterval(function() {
    location.reload();
  }, 2000);
</script>
</body>
</html>
