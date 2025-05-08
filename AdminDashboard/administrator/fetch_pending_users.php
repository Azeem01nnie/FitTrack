<?php
$conn = new mysqli("localhost", "root", "", "fittrack_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM users WHERE approval_status = 'pending'";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['registration_date']) ?></td>
        <td><?= htmlspecialchars($row['username']) ?></td>
        <td><?= htmlspecialchars($row['full_name']) ?></td>
        <td>
            <button onclick="window.location.href='update_approval.php?user_id=<?= $row['user_id'] ?>&action=approve'" class="approve">Approve</button>
            <button onclick="window.location.href='update_approval.php?user_id=<?= $row['user_id'] ?>&action=reject'" class="reject">Reject</button>
        </td>
        <td><?= htmlspecialchars($row['approval_status']) ?></td>
    </tr>
<?php endwhile;
$conn->close();
?>
