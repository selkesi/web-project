<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "conferenceDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['session_id'])) {
    $id = $_POST['session_id'];
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $location = $_POST['location'];

    $sql = "UPDATE Session SET date='$date', start_time='$start_time', end_time='$end_time', location='$location' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Success";
    } else {
        echo "Error updating record: " . $conn->error;
    }
    exit();
}

$sql = "SELECT id, session_name, date, start_time, end_time, location FROM Session";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sessions</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Sessions</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Session Name</th>
            <th>Date</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Location</th>
            <th>Edit Session</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr id="row_<?php echo $row['id']; ?>">
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['session_name']; ?></td>
            <td><input type="date" class="date" data-id="<?php echo $row['id']; ?>" value="<?php echo $row['date']; ?>"></td>
            <td><input type="time" class="start_time" data-id="<?php echo $row['id']; ?>" value="<?php echo $row['start_time']; ?>"></td>
            <td><input type="time" class="end_time" data-id="<?php echo $row['id']; ?>" value="<?php echo $row['end_time']; ?>"></td>
            <td><input type="text" class="location" data-id="<?php echo $row['id']; ?>" value="<?php echo $row['location']; ?>"></td>
            <td>
                <button class="update-btn" data-id="<?php echo $row['id']; ?>">Update</button>
            </td>
        </tr>
        <?php } ?>
    </table>

    <script>
        $(document).ready(function() {
            $(".update-btn").click(function() {
                var id = $(this).data("id");
                var date = $(".date[data-id='" + id + "']").val();
                var start_time = $(".start_time[data-id='" + id + "']").val();
                var end_time = $(".end_time[data-id='" + id + "']").val();
                var location = $(".location[data-id='" + id + "']").val();

                $.ajax({
                    url: "",  // Since it's the same file, leave it empty
                    type: "POST",
                    data: {
                        session_id: id,
                        date: date,
                        start_time: start_time,
                        end_time: end_time,
                        location: location
                    },
                    success: function(response) {
                        if (response.trim() == "Success") {
                            alert("Session updated successfully!");
                        } else {
                            alert("Error: " + response);
                        }
                    },
                    error: function() {
                        alert("Error updating session.");
                    }
                });
            });
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>
