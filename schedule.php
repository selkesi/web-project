<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "conferenceDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$selected_date = isset($_POST['date']) ? $_POST['date'] : '';

$dates = $conn->query("SELECT DISTINCT date FROM Session ORDER BY date ASC");
$sessions = [];
if ($selected_date) {
    $stmt = $conn->prepare("SELECT session_name, start_time, end_time, location FROM Session WHERE date = ? ORDER BY start_time");
    $stmt->bind_param("s", $selected_date);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $sessions[] = $row;
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Schedule</title>
</head>
<body>
    <h2>Schedule</h2>
    <form method="POST">
        <label for="date">Choose a Date:</label>
        <select name="date" id="date" onchange="this.form.submit()">
            <option value="">Select...</option>
            <?php while ($row = $dates->fetch_assoc()): ?>
                <option value="<?php echo $row['date']; ?>" <?php if ($selected_date == $row['date']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($row['date']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>
    
    <?php if ($selected_date && count($sessions) > 0): ?>
        <h2>Schedule for <?php echo htmlspecialchars($selected_date); ?>:</h2>
        <table border="1">
            <tr>
                <th>Session Name</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Location</th>
            </tr>
            <?php foreach ($sessions as $session): ?>
                <tr>
                    <td><?php echo htmlspecialchars($session['session_name']); ?></td>
                    <td><?php echo htmlspecialchars($session['start_time']); ?></td>
                    <td><?php echo htmlspecialchars($session['end_time']); ?></td>
                    <td><?php echo htmlspecialchars($session['location']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif ($selected_date): ?>
        <p>No sessions scheduled for this day.</p>
    <?php endif; ?>
</body>
</html>
