<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");  // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['user_name'];

// Connect to the database to fetch user details
$conn = new mysqli("localhost", "root", "", "fittrack_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT full_name FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($full_name);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Home page</title>
  <link rel="stylesheet" href="HM.css" />
  <link rel="stylesheet" href="/FitTrack/AdminDashboard/administrator/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

  <nav>
    <div class="hamburger" onclick="toggleSidebar()">&#9776;</div>
    <div class="topbar">
        <label for="search">FitTrack</label>
    </div>
  </nav>

  <aside class="side-nav" id="sidebar">
    <div class="profilePic-container">
      <img src="gym1.jpg" alt="Profile Picture" id="profilePic" class="profilePic" />
    </div>
    <ul>
      <li><a href="../account/profiler.php">Profile Account </a></li>
      <li><a href="../myAttendance/attendance.html">My Attendance</a></li>
      <li><a href="../membershipStatus/membershipStatus.html">Membership Status</a></li>
      <li><a href="../setting/setting.html">Setting</a></li>
    </ul>
    <ul>
      <li><a class="btn-logout" onclick="openModal()">Logout</a></li>
    </ul>
  </aside>
  
  <div id="mainContent">
    <div id="liveClockContainer" class="live-clock-container">
      <div id="liveClock" class="live-clock">Loading current time...</div>
      <span id="refreshIcon" class="refresh-icon" title="Refresh session" style="scale: 2;">
        🔄
      </span>
    </div>    
    <div id="circle" class="circle-box">
      <i class="fa-solid fa-power-off fa-beat" style="color: #ffffff; scale: 1.4;"></i>
    </div>
    <div class="time-labels">
      <div id="timeInLabel" class="label">Time In: --</div>
    </div>
    <div class="time-labels">
      <div id="timeOutLabel" class="label">Time Out: --</div>
    </div>
  </div>

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

  <script src="attendance.js"></script>


  <!-- Pass user_id to JavaScript -->
  <script>
    const loggedInUserId = <?php echo json_encode($_SESSION['user_id']); ?>;
    const fullName = <?php echo json_encode($full_name); ?>;
    document.getElementById('liveClock').textContent = 'Hello, ' + fullName + ' | ' + document.getElementById('liveClock').textContent;
  </script>
</body>
</html>
