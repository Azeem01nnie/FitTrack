<?php

date_default_timezone_set('Asia/Manila'); // Ensure Philippine time zone

session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect to login if not authenticated
}

$conn = new mysqli("localhost", "root", "", "fittrack_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';
$search_user = $_GET['search_user'] ?? '';

$whereClauses = [];
$params = [];
$query = "SELECT u.username, u.full_name, u.plan, a.time_in, a.time_out
          FROM attendance_logs a
          INNER JOIN users u ON a.user_id = u.user_id";  // Join users with attendance_logs based on user_id

if (!empty($from_date) && !empty($to_date)) {
    $whereClauses[] = "DATE(a.time_in) BETWEEN ? AND ?";
    $params[] = $from_date;
    $params[] = $to_date;
}

if (!empty($search_user)) {
    $whereClauses[] = "(u.username LIKE ? OR u.full_name LIKE ?)";
    $params[] = "%" . $search_user . "%";
    $params[] = "%" . $search_user . "%";
}

if (!empty($whereClauses)) {
    $query .= " WHERE " . implode(' AND ', $whereClauses);
}

$query .= " ORDER BY a.time_in DESC";

$stmt = $conn->prepare($query);

// Check if prepare failed
if ($stmt === false) {
    die("Error preparing query: " . $conn->error);
}

// Bind parameters dynamically
if (!empty($params)) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params); // 's' for string, adjust if using other data types
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FitTrack - Attendance Log</title>
  <link rel="stylesheet" href="style.css">
  <script src="modalss.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <link rel="icon" type="image/png" href="../FitTrack/AdminDashboard/icons/FittrackFavIcon.png">
</head>
<style>
  .topbtn {
    padding: 10px;
    border: none;
    background-color: #ddd;
    font-size: 12px;
    margin-left: 10px;
    margin-right: 10px;
    border-radius: 7px;
  }
  .topbtn:hover {
    background-color: #ddd;
    border: #4A90E2 2px solid;
  }

  @media(max-width: 412px){
    body{
        grid-template-columns: 1fr;
    }
    aside{
        margin-top: 45.5px;
        position: fixed;
        width: 200px;
        display: none;
        background-color: white;
    }
    .show{
        display: block;
    }
    .topbar{
        flex: 1;
        padding: 15px;
        font-size: 16px;
        margin-left: 20px;
        justify-content: space-between;
        align-items: center;
        background: white;
        border-radius: 10px;
        color: black;
    }

    .side-nav{
      position: fixed;
      top: 55px;
      left: 0;
    }
    .topbar input {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 150px;
        margin-left: 10px;
    }
    .topbar label{
        font-family: sans-serif;
        display: none;
    }
    .hamburger{
        font-size: 24px;
        cursor: pointer;
        display: inline-block;
    }
  }
</style>
<body>

<nav>
  <div class="hamburger" onclick="toggleSidebar()">&#9776;</div>
  <div class="topbar">
    <label for="search">FitTrack</label>
    <input type="text" id="search" placeholder="Search...">
  </div>
</nav>

<aside class="side-nav" id="sidebar">
  <ul>
    <li><a href="admin_dash.php">Home dashboard</a></li>
    <li><a href="Approvalpage.php">Approval log</a></li>
    <li><a href="Attendancepage.php" class="active">Attendance Log</a></li>
    <li><a href="managepage.php">Manage account</a></li>
  </ul>
  <ul>
    <li><a class="btn-logout" onclick="openModal()">Logout</a></li>
  </ul>
</aside>

<main class="main-content">
  <h3>Attendance Log</h3>

  <form method="GET" style="margin-bottom: 20px;">
    <label>From: <input type="date" name="from_date" value="<?= htmlspecialchars($from_date) ?>"></label>
    <label>To: <input type="date" name="to_date" value="<?= htmlspecialchars($to_date) ?>"></label>
    <label>Username: <input type="text" name="search_user" value="<?= htmlspecialchars($search_user) ?>" placeholder="Search username or name"></label>
    <button type="submit" class="topbtn">Filter</button>
    <button type="button" onclick="exportToExcel('attendanceTable')" class="topbtn">Export to Excel</button>
    <button type="button" onclick="window.print()" class="topbtn">Print</button>
  </form>

  <div class="approval-container">
    <table id="attendanceTable">
      <thead>
        <tr>
          <th>Username</th>
          <th>Name</th>
          <th>Plan</th>
          <th>Time In</th>
          <th>Time Out</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <?php 
            // Format time_in and time_out to Philippine time
            $timeIn = date('Y-m-d H:i:s', strtotime($row['time_in']));
            $timeOut = date('Y-m-d H:i:s', strtotime($row['time_out']));
            ?>
            <tr>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td><?= htmlspecialchars($row['full_name']) ?></td>
              <td><?= htmlspecialchars($row['plan']) ?></td>
              <td><?= htmlspecialchars($timeIn) ?></td>
              <td><?= htmlspecialchars($timeOut) ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="6">No attendance records found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>

<footer>FitTrack Version 1.1</footer>

<div id="logoutModal" class="modal">
  <div class="modal-content">
    <h2>Confirm Logout</h2>
    <p>Are you sure you want to log out?</p>
    <div class="modal-buttons">
      <button class="confirm" onclick="logout()">Yes, Logout</button>
      <button class="cancel" onclick="closeModal()">Cancel</button>
    </div>
  </div>
</div>

<!-- JS for search and Excel export -->
<script>
  document.getElementById('search').addEventListener('keyup', function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#attendanceTable tbody tr');

    rows.forEach(row => {
      let username = row.cells[0].textContent.toLowerCase();
      let name = row.cells[1].textContent.toLowerCase();
      row.style.display = (username.includes(filter) || name.includes(filter)) ? '' : 'none';
    });
  });

  function exportToExcel(tableID) {
    let table = document.getElementById(tableID);
    if (table.rows.length === 1) {
        alert("No data to export.");
        return;
    }
    let html = table.outerHTML.replace(/ /g, '%20');
    let filename = 'attendance_export.xls';
    let downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);
    downloadLink.href = 'data:application/vnd.ms-excel,' + html;
    downloadLink.download = filename;
    downloadLink.click();
    document.body.removeChild(downloadLink);
  }
</script>
<script>
  function fetchAttendanceTable() {
    fetch('fetch_attendance.php')
      .then(response => response.text())
      .then(data => {
        document.querySelector('#attendanceTable tbody').innerHTML = data;
      })
      .catch(error => console.error('Error fetching attendance:', error));
  }

  setInterval(fetchAttendanceTable, 2000); // Fetch every 2 seconds
</script>


</body>
</html>
