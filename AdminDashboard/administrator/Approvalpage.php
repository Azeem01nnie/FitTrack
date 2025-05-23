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
            <li><a href="Approvalpage.php" class="active">Approval log</a></li>
            <li><a href="Attendancepage.php">Attendance log</a></li>
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
    const sortSelect = document.getElementById("sort-options");
    const tableBody = document.querySelector("tbody");

    searchInput.addEventListener("input", function () {
        const filter = searchInput.value.toLowerCase();
        const rows = tableBody.querySelectorAll("tr");

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? "" : "none";
        });
    });

    sortSelect.addEventListener("change", function () {
        const rowsArray = Array.from(tableBody.querySelectorAll("tr"));
        const sortType = sortSelect.value;

        rowsArray.sort((a, b) => {
            const aText = a.children[2].textContent.toLowerCase(); // full name
            const bText = b.children[2].textContent.toLowerCase();
            const aDate = new Date(a.children[0].textContent);
            const bDate = new Date(b.children[0].textContent);

            switch (sortType) {
                case "a-z":
                    return aText.localeCompare(bText);
                case "z-a":
                    return bText.localeCompare(aText);
                case "recent":
                    return bDate - aDate;
                case "oldest":
                    return aDate - bDate;
                default:
                    return 0;
            }
        });

        rowsArray.forEach(row => tableBody.appendChild(row));
    });
});
</script>
<script>
  function refreshTable() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "fetch_pending_users.php", true);
    xhr.onload = function () {
      if (xhr.status === 200) {
        document.querySelector("tbody").innerHTML = xhr.responseText;
      }
    };
    xhr.send();
  }

  setInterval(refreshTable, 2000); // Refresh every 2 seconds
</script>

</body>
</html>

<?php
$conn->close();
?>
