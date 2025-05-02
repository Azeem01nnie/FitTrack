<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fittrack_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

// Load language
$preferredLanguage = isset($_COOKIE['preferredLanguage']) ? $_COOKIE['preferredLanguage'] : 'en';
$languageFile = "../../languages/lang_$preferredLanguage.php";
if (file_exists($languageFile)) {
    $lang = include $languageFile;
} else {
    $lang = include "../../languages/lang_en.php"; // fallback
}

// Get user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT full_name, plan, registration_date FROM users WHERE user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $full_name = $row['full_name'];
    $plan_type = $row['plan'];
    $registration_date = $row['registration_date'];

    $amount_paid = 0;
    if ($plan_type == "annually") {
        $amount_paid = 1500;
    } elseif ($plan_type == "monthly") {
        $amount_paid = 700;
    } elseif ($plan_type == "dailypay") {
        $amount_paid = 60;
    }

    $expiry_date = "";
    if ($plan_type == "annually") {
        $expiry_date = date('F d, Y', strtotime("+1 year", strtotime($registration_date)));
    } elseif ($plan_type == "monthly") {
        $expiry_date = date('F d, Y', strtotime("+1 month", strtotime($registration_date)));
    } elseif ($plan_type == "dailypay") {
        $expiry_date = date('F d, Y', strtotime("+1 day", strtotime($registration_date)));
    }
} else {
    $full_name = "Unknown User";
    $plan_type = "N/A";
    $registration_date = "N/A";
    $expiry_date = "N/A";
    $amount_paid = 0;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $lang['Payment_Status']; ?></title>
    <link rel="stylesheet" href="status.css">
</head>
<body>
    <div class="header">
        <a href="../homepage/home_page.php" class="back-btn">
            <button class="back-button">←</button>
        </a>
    </div>

    <div class="main-container">
        <div class="label"><?php echo $lang['Payment_Status']; ?></div>
        <div class="status-container">
            <div class="card">
                <p><strong><?php echo $lang['Membership_Plan']; ?>:</strong> <?php echo ucfirst($plan_type); ?></p>
                <p><strong><?php echo $lang['Amount_Paid']; ?>:</strong> ₱<?php echo number_format($amount_paid, 2); ?></p>
                <p><strong><?php echo $lang['Payment_Date']; ?>:</strong> <?php echo date('F d, Y', strtotime($registration_date)); ?></p>
                <p><strong><?php echo $lang['Membership_Expires']; ?>:</strong> <?php echo $expiry_date; ?></p>
            </div>
        </div>

        <div class="reminder-container">
            <p>🔔 <strong><?php echo $lang['Reminder']; ?>:</strong><br>
            <?php echo sprintf($lang['Reminder_Text'], $expiry_date); ?></p>
        </div>

        <div id="successMessage" class="success-message">
            <?php echo $lang['Renew_Message']; ?>
        </div>
    </div>

    <script>
        function showRenewMessage() {
            document.getElementById('successMessage').style.display = 'block';
            setTimeout(() => {
                document.getElementById('successMessage').scrollIntoView({ behavior: 'smooth' });
            }, 100);
        }
    </script>
</body>
</html>
