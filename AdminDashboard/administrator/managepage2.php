<?php
include 'db_connection.php';

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    // Fetch user information from the database
    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the updated membership plan
    $plan = $_POST['membership'];

    // Update the membership plan in the database
    $update_sql = "UPDATE users SET plan = ? WHERE user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('si', $plan, $user_id);
    $update_stmt->execute();

    // Redirect to the same page to reflect changes
    header("Location: managepage.php?user_id=" . $user_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitTrack</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="icon" type="image/png" href="../FitTrack/AdminDashboard/icons/FittrackFavIcon.png">
    <script type="text/javascript" src="app.js" defer></script>
    <script src="modalss.js"></script>
</head>
<style>
   .details-form {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-left: 30px;
    margin-top: 20px;
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
            <li><a href="Hdashboard.php">Home dashboard</a></li>
            <li><a href="Approvalpage.php">Approval log</a></li>
            <li><a href="Attendancepage.php">Attendance log</a></li>
            <li><a href="managepage.php" class="active">Manage account</a></li>
        </ul>
        <ul>
            <li><a class="btn-logout" onclick="openModal()">Logout</a></li>
        </ul>
    </aside>
    <main class="manage-container">
        <div class="header">
            <h3>Account Details</h3>
        </div>

        <form class="details-form" method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <?= htmlspecialchars($user['full_name']) ?>
            </div>

            <div class="form-group">
                <label>Username</label>
                <?= htmlspecialchars($user['username']) ?>
            </div>

            <div class="form-group">
                <label for="membership">Membership Type</label>
                <select id="membership" name="membership" required>
                    <option value="dailypay" <?= $user['plan'] == 'dailypay' ? 'selected' : '' ?>>Pay as you go</option>
                    <option value="monthly" <?= $user['plan'] == 'monthly' ? 'selected' : '' ?>>Monthly</option>
                    <option value="annually" <?= $user['plan'] == 'annually' ? 'selected' : '' ?>>Annually</option>
                </select>
            </div>

            <div class="form-group">
                <label>Email</label>
                <?= htmlspecialchars($user['email']) ?>
            </div>

            <div class="form-group">
                <label>Address</label>
                <?= htmlspecialchars($user['address']) ?>
            </div>

            <div class="form-group">
                <label>Contact No.</label>
                <?= htmlspecialchars($user['mobile']) ?>
            </div>

            <div class="form-group">
                <label>Weight (kg) / Height (cm)</label>
                <?= htmlspecialchars($user['weight_kg']) ?>kg / <?= htmlspecialchars($user['height_cm']) ?>cm
            </div>

            <div class="form-group">
                <label>BMI</label>
                <?= $user['bmi'] ? htmlspecialchars($user['bmi']) : 'N/A' ?>
            </div>

            <div class="form-group">
                <label>Emergency Contact</label>
                <div>Name: <?= htmlspecialchars($user['emergency_firstname']) ?> <?= htmlspecialchars($user['emergency_lastname']) ?></div>
                <div>Relationship: <?= htmlspecialchars($user['relationship']) ?></div>
                <div>Contact No: <?= htmlspecialchars($user['emergency_mobile']) ?></div>
            </div>

            <div class="form-group">
                <label>Gender</label>
                <?= ucfirst($user['gender']) ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn save">Save Changes</button>
                <button type="button" class="btn cancel" onclick="window.location.href = 'managepage.php'">Cancel</button>
                <button type="button" class="btn delete" onclick="if(confirm('Are you sure you want to delete this account?')) { window.location.href = 'managepage.php?user_id=<?= $user_id ?>&delete=true'; }">Delete Account</button>
            </div>
        </form>
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