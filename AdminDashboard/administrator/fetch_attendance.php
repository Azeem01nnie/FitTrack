<?php
$conn = new mysqli("localhost", "root", "", "fittrack_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

date_default_timezone_set('Asia/Manila');

$query = "SELECT u.username, u.full_name, u.plan, a.time_in, a.time_out
          FROM attendance_logs a
          INNER JOIN users u ON a.user_id = u.user_id
          ORDER BY a.time_in DESC";

$result = $conn->query($query);

if ($result && $result->num_rows > 0):
    while ($row = $result->fetch_assoc()):
        $timeIn = date('Y-m-d H:i:s', strtotime($row['time_in']));
        $timeOut = date('Y-m-d H:i:s', strtotime($row['time_out']));
?>
<tr>
  <td><?= htmlspecialchars($row['username']) ?></td>
  <td><?= htmlspecialchars($row['full_name']) ?></td>
  <td><?= htmlspecialchars($row['plan']) ?></td>
  <td><?= htmlspecialchars($timeIn) ?></td>
  <td><?= htmlspecialchars($timeOut) ?></td>
</tr>
<?php
    endwhile;
else:
?>
<tr><td colspan="5">No attendance records found.</td></tr>
<?php
endif;

$conn->close();
?>
