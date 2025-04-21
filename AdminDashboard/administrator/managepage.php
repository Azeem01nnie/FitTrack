<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "fittrack_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch only approved users
$sql = "SELECT * FROM users WHERE approval_status = 'approved'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitTrack - Manage Account</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="icon" type="image/png" href="/AdminDashboard/icons/FittrackFavIcon.png">
    <script src="app.js"></script>
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
            <li><a href="Approvalpage.php">Approval log</a></li>
            <li><a href="Attendancepage.html">Attendance log</a></li>
            <li><a href="managepage.php" class="active">Manage account</a></li>
        </ul>
        <ul>
            <li><a class="btn-logout" onclick="openModal()">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <h3>Manage Account</h3>
        <div class="sort-section">
            <label>Sort by:</label>
            <select id="sort-options" class="sort-btn">
                <option value="a-z">A - Z</option>
                <option value="z-a">Z - A</option>
                <option value="recent">Most Recent</option>
                <option value="oldest">Oldest</option>
            </select>
        </div>
        <div class="approval-container">
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Membership Type</th>
                        <th>Membership Deadline</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php
                            $registration = new DateTime($row['registration_date']);
                            switch ($row['plan']) {
                                case 'dailypay':
                                    $registration->modify('+1 day');
                                    break;
                                case 'monthly':
                                    $registration->modify('+1 month');
                                    break;
                                case 'annually':
                                    $registration->modify('+1 year');
                                    break;
                            }
                            $deadline = $registration->format('Y-m-d');
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['full_name']) ?></td>
                            <td><?= ucfirst($row['plan']) ?></td>
                            <td><?= $deadline ?></td>
                            <td>
                                <a href="managepage2.php?user_id=<?= $row['user_id'] ?>">
                                    <button class="btn"><i class="fa-solid fa-user-gear" style="color: green;"></i></button>
                                </a>
                                <a href="delete_user.php?user_id=<?= $row['user_id'] ?>" onclick="return confirm('Delete <?= $row['full_name'] ?>?')">
                                    <button class="btn"><i class="fa-solid fa-trash" style="color: red;"></i></button>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer>FitTrack Version 1.1</footer>

    <!-- Logout Modal -->
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
