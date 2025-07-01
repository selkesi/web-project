<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "conferenceDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$room_id = isset($_POST['room']) ? $_POST['room'] : '';

$rooms = $conn->query("SELECT id, room_number FROM HotelRoom");
$students = [];
if ($room_id) {
    $stmt = $conn->prepare("SELECT Attendee.name FROM Attendee 
                            JOIN RoomAssignment ON Attendee.id = RoomAssignment.student_id 
                            WHERE RoomAssignment.room_id = ?");
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $students[] = $row['name'];
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hotel Rooms</title>
</head>
<body>
    <h2>Hotel Rooms</h2>
    <form method="POST">
        <label for="room">Choose a Room:</label>
        <select name="room" id="room" onchange="this.form.submit()">
            <option value="">Select...</option>
            <?php while ($row = $rooms->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>" <?php if ($room_id == $row['id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($row['room_number']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>
    
    <?php if ($room_id && count($students) > 0): ?>
        <h2>Students in this room:</h2>
        <ul>
            <?php foreach ($students as $student): ?>
                <li><?php echo htmlspecialchars($student); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php elseif ($room_id): ?>
        <p>No students assigned to this room.</p>
    <?php endif; ?>
</body>
</html>
