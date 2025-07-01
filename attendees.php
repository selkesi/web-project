<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "conferenceDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add attendee
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_attendee'])) {
    $name = $_POST['name'];
    $type = $_POST['type'];

    $stmt = $conn->prepare("INSERT INTO Attendee (name, type) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $type);

    if ($stmt->execute()) {
        $attendee_id = $stmt->insert_id;
        $stmt->close();

        if ($type == 'student') {
            $room_query = $conn->query("
                SELECT id 
                FROM HotelRoom 
                WHERE id NOT IN (SELECT room_id FROM RoomAssignment GROUP BY room_id HAVING COUNT(*) >= 2) 
                LIMIT 1
            ");

            if ($room = $room_query->fetch_assoc()) {
                $room_id = $room['id'];
                $stmt = $conn->prepare("INSERT INTO RoomAssignment (student_id, room_id) VALUES (?, ?)");
                $stmt->bind_param("ii", $attendee_id, $room_id);
                $stmt->execute();
                $stmt->close();
            }
        }
        header("Location: attendees.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

$students = $conn->query("SELECT name FROM Attendee WHERE type='student'");
$professionals = $conn->query("SELECT name FROM Attendee WHERE type='professional'");
$sponsors = $conn->query("SELECT name FROM Attendee WHERE type='sponsor'");

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Conference Attendees</title>
</head>
<body>
    <h2>Students</h2>
    <ul>
        <?php while ($row = $students->fetch_assoc()): ?>
            <li><?php echo htmlspecialchars($row['name']); ?></li>
        <?php endwhile; ?>
    </ul>
    
    <h2>Professionals</h2>
    <ul>
        <?php while ($row = $professionals->fetch_assoc()): ?>
            <li><?php echo htmlspecialchars($row['name']); ?></li>
        <?php endwhile; ?>
    </ul>
    
    <h2>Sponsors</h2>
    <ul>
        <?php while ($row = $sponsors->fetch_assoc()): ?>
            <li><?php echo htmlspecialchars($row['name']); ?></li>
        <?php endwhile; ?>
    </ul>
    
    <h2>Add a New Attendee</h2>
    <form method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" required>
        <label for="type">Type:</label>
        <select name="type">
            <option value="student">Student</option>
            <option value="professional">Professional</option>
        </select>
        <button type="submit" name="add_attendee">Add Attendee</button>
    </form>
</body>
</html>
