<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <link rel="icon" type="image/png" href="/AdminDashboard/icons/FittrackFavIcon.png">
</head>
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
    <li><a href="Hdashboard.html">Home Dashboard</a></li>
    <li><a href="Approvalpage.php">Approval Log</a></li>
    <li><a href="Attendancepage.php" class="active">Attendance Log</a></li>
    <li><a href="managepage.php">Manage Account</a></li>
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
    <button type="submit">Filter</button>
    <button type="button" onclick="exportToExcel('attendanceTable')">Export to Excel</button>
    <button type="button" onclick="window.print()">Print</button>
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
            <tr>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td><?= htmlspecialchars($row['full_name']) ?></td>
              <td><?= htmlspecialchars($row['plan']) ?></td>
              <td><?= htmlspecialchars($row['time_in']) ?></td>
              <td><?= htmlspecialchars($row['time_out']) ?></td>
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

</body>
</html>
