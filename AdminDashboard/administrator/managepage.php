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
    <script src="modalss.js"></script>
</head>
<style>
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
        <li><a href="Attendancepage.php">Attendance log</a></li>
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
                <option value="activity">Annually-Monthly-Dailypay</option>
                <option value="acitve">Active - Inactive</option>
            </select>
        </div>
    <div class="approval-container">
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Membership Type</th>
                    <th>Membership Expires</th>
                    <th>Status</th>
                    <th>Update</th>
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

                        $today = new DateTime();
                        $interval = $today->diff($registration);
                        $daysRemaining = (int)$interval->format('%r%a');
                        $deadlineNote = "";

                        if ($daysRemaining === 1) {
                            $deadlineNote = " - 1 day remaining";
                        } elseif ($daysRemaining < 0) {
                            $deadlineNote = " - past due";
                        }
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                        <td><?= ucfirst($row['plan']) ?></td>
                        <td><?= $deadline . $deadlineNote ?></td>
                        <td><?= ucfirst($row['status']) ?></td>
                        <td>
                            <a href="managepage2.php?user_id=<?= $row['user_id'] ?>">
                                <button class="btn" style="background-color: #2ecc71; color: white; width: 50px;">
                                    <i class="fa-solid fa-user-gear" style="color: white;"></i>
                                </button>
                            </a>
                        </td>
                        <td>
                            <?php if ($row['status'] == 'active'): ?>
                                <a href="disable_user.php?user_id=<?= $row['user_id'] ?>" onclick="return confirm('Disable <?= $row['full_name'] ?>?')">
                                    <button class="btn" style="background-color: #e74c3c; color: white; width: 50px">
                                        <i class="fa-solid fa-ban"></i> 
                                    </button>
                                </a>
                            <?php else: ?>
                                <a href="re_enable_user.php?user_id=<?= $row['user_id'] ?>" onclick="return confirm('Re-enable <?= $row['full_name'] ?>?')">
                                    <button class="btn" style="background-color: #2ecc71; color: white; width: 50px;">
                                        <i class="fa-solid fa-check-circle"></i>
                                    </button>
                                </a>
                            <?php endif; ?>
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

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("search");
    const tableBody = document.querySelector("tbody");

    searchInput.addEventListener("input", function () {
        const filter = searchInput.value.toLowerCase();
        const rows = tableBody.querySelectorAll("tr");

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? "" : "none";
        });
    });
});
</script>
<script>
document.getElementById("sort-options").addEventListener("change", function () {
    const option = this.value;
    const table = document.querySelector("table tbody");
    const rows = Array.from(table.querySelectorAll("tr"));

    rows.sort((a, b) => {
        const nameA = a.children[1].textContent.trim().toLowerCase(); // Full Name
        const nameB = b.children[1].textContent.trim().toLowerCase();
        const planA = a.children[2].textContent.trim().toLowerCase();
        const planB = b.children[2].textContent.trim().toLowerCase();
        const statusA = a.children[4].textContent.trim().toLowerCase();
        const statusB = b.children[4].textContent.trim().toLowerCase();

        if (option === "a-z") {
            return nameA.localeCompare(nameB);
        } else if (option === "z-a") {
            return nameB.localeCompare(nameA);
        } else if (option === "activity") {
            const order = { annually: 1, monthly: 2, dailypay: 3 };
            return (order[planA] || 4) - (order[planB] || 4);
        } else if (option === "acitve") {
            const order = { active: 1, inactive: 2 };
            return (order[statusA] || 3) - (order[statusB] || 3);
        }

        return 0;
    });

    // Append sorted rows back to the table
    rows.forEach(row => table.appendChild(row));
});
</script>

</body>
</html>

<?php
$conn->close();
?>
