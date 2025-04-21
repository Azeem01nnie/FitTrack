<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "fittrack_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch pending users
$sql = "SELECT * FROM users WHERE approval_status = 'pending'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitTrack - Approval Page</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="icon" type="image/png" href="/AdminDashboard/icons/FittrackFavIcon.png">
    <script type="text/javascript" src="app.js" defer></script>
    <script src="modalss.js"></script>
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
            <li><a href="Hdashboard.html">Home dashboard</a></li>
            <li><a href="Approvalpage.php" class="active">Approval log</a></li>
            <li><a href="Attendancepage.html">Attendance log</a></li>
            <li><a href="managepage.php">Manage account</a></li>
        </ul>
        <ul>
            <li><a class="btn-logout" onclick="openModal()">Logout</a></li>
        </ul>
    </aside>
    <main class="main-content">
        <h3>Account Approval Log</h3>
        <div class="approval-container">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Action</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['registration_date']) ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['full_name']) ?></td>
                            <td>
                                <!-- Approve Button -->
                                <button onclick="window.location.href='update_approval.php?user_id=<?= $row['user_id'] ?>&action=approve'" class="approve">Approve</button>
                                <!-- Reject Button -->
                                <button onclick="window.location.href='update_approval.php?user_id=<?= $row['user_id'] ?>&action=reject'" class="reject">Reject</button>
                            </td>
                            <td><?= htmlspecialchars($row['approval_status']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
    <footer>FitTrack Version 1.1</footer>
</body>
</html>

<?php
$conn->close();
?>
