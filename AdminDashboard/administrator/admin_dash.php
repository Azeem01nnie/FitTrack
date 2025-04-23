<?php
session_start();
$conn = new mysqli("localhost", "root", "", "fittrack_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Redirect to login if admin is not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../logIn.php");
    exit();
}

$adminName = $_SESSION['admin_name'] ?? 'Admin';

// Dashboard counts
$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$activeUsers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE status = 'active'")->fetch_assoc()['total'];
$disabledUsers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE status = 'inactive'")->fetch_assoc()['total'];
$pendingUsers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE approval_status = 'pending'")->fetch_assoc()['total'];
$monthlyUsers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE plan = 'monthly'")->fetch_assoc()['total'];
$annualUsers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE plan = 'annually'")->fetch_assoc()['total'];

// Get the most recent attendance logs
$sqlRecentLogs = "
    SELECT u.full_name, al.time_in, al.time_out
    FROM attendance_logs al
    JOIN users u ON al.user_id = u.user_id
    ORDER BY al.time_in DESC
    LIMIT 5
";
$resultRecentLogs = $conn->query($sqlRecentLogs);

$recentLogs = [];
if ($resultRecentLogs && $resultRecentLogs->num_rows > 0) {
    while ($row = $resultRecentLogs->fetch_assoc()) {
        $recentLogs[] = [
            'full_name' => $row['full_name'],
            'time_in' => date("F j, Y - h:i A", strtotime($row['time_in'])),
            'time_out' => date("F j, Y - h:i A", strtotime($row['time_out'])),
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FitTrack</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="icon" type="image/png" href="/AdminDashboard/icons/FittrackFavIcon.png" />
  <script src="app.js" defer></script>
  <script src="modalss.js"></script>
</head>
<style>
.Box-divider {
  flex: 1;
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.recent-logs-box {
  background-color: #f0f4ff;
  padding: 15px 20px;
  margin: 15px 10px;
  border-left: 5px solid #4A90E2;
  border-radius: 8px;
  font-family: sans-serif;
}
.recent-logs-box h4 {
  margin: 0 0 5px 0;
  color: #4A90E2;
}
table {
  width: 100%;
  margin-top: 20px;
}
table, th, td {
  background-color: #f0f4ff;
  border: none; /* Removed table border */
  padding: 8px 12px;
  text-align: center;
}
th {
  font-weight: bold;
  padding-top: 10px;
  padding-bottom: 10px;
}
td {
  padding-top: 8px;
  padding-bottom: 8px;
}
</style>
<body>
  <nav>
    <div class="hamburger" onclick="toggleSidebar()">&#9776</div>
    <div class="topbar">
      <label for="search">FitTrack</label>
      <input type="text" id="search" placeholder="Search...">
    </div>
  </nav>
  <aside class="side-nav" id="sidebar">
    <ul>
      <li><a href="admin_dash.php" class="active">Home dashboard</a></li>
      <li><a href="Approvalpage.php">Approval log</a></li>
      <li><a href="Attendancepage.php">Attendance log</a></li>
      <li><a href="managepage.php">Manage account</a></li>
    </ul>
    <ul>
      <li><a class="btn-logout" onclick="openModal()">Logout</a></li>
    </ul>
  </aside>

  <main class="main-content">
    <h3>Home</h3>
    <?php if (!isset($_SESSION['welcome_shown']) || $_SESSION['welcome_shown'] === false): ?>
      <h4>Welcome back, <?= htmlspecialchars($adminName) ?>!</h4>
      <?php $_SESSION['welcome_shown'] = true; ?>
    <?php endif; ?>

    <!-- 🔹 Recent Attendance Logs in Table Format -->
    <div class="recent-logs-box">
      <h4>Recent Time In and Out:</h4>
      <?php if (empty($recentLogs)): ?>
        <p>No recent attendance logs.</p>
      <?php else: ?>
        <table>
          <thead>
            <tr>
              <th>Full Name</th>
              <th>Time In</th>
              <th>Time Out</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recentLogs as $log): ?>
              <tr>
                <td><?= htmlspecialchars($log['full_name']) ?></td>
                <td><?= $log['time_in'] ?></td>
                <td><?= $log['time_out'] ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>

    <div class="dashboardbox">
      <div class="Box-divider">
        <div class="box">
          <h1><?= $totalUsers ?></h1>
          <p>Total Accounts</p>
        </div>
        <div class="box">
          <h1><?= $disabledUsers ?></h1>
          <p>Disabled Accounts</p>
        </div>
      </div>
      <div class="Box-divider">
        <div class="box">
          <h1><?= $pendingUsers ?></h1>
          <p>Pending Accounts</p>
        </div>
        <div class="box">
          <h1><?= $activeUsers ?></h1>
          <p>Active Accounts</p>
        </div>
      </div>
      <div class="Box-divider">
        <div class="box">
          <h1><?= $monthlyUsers ?></h1>
          <p>Monthly Users</p>
        </div>
        <div class="box">
          <h1><?= $annualUsers ?></h1>
          <p>Annually Users</p>
        </div>
      </div>
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
</body>
</html>

<?php
$conn->close();
?>
