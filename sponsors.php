<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "conferenceDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add sponsor
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_sponsor'])) {
    $company_name = $_POST['company_name'];
    $sponsorship_level = $_POST['sponsorship_level'];
    
    $stmt = $conn->prepare("INSERT INTO Sponsor (company_name, sponsorship_level) VALUES (?, ?)");
    $stmt->bind_param("ss", $company_name, $sponsorship_level);
    $stmt->execute();
    $stmt->close();
    header("Location: sponsors.php");
    exit();
}

// Delete sponsor
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_sponsor'])) {
    $sponsor_id = $_POST['sponsor_id'];
    
    $stmt = $conn->prepare("DELETE FROM Sponsor WHERE id = ?");
    $stmt->bind_param("i", $sponsor_id);
    $stmt->execute();
    $stmt->close();
    header("Location: sponsors.php");
    exit();
}

$sponsors = $conn->query("SELECT id, company_name, sponsorship_level FROM Sponsor ORDER BY sponsorship_level DESC");
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sponsors</title>
</head>
<body>
    <h2>Sponsors</h2>
    <table border="1">
        <tr>
            <th>Company Name</th>
            <th>Sponsorship Level</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $sponsors->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                <td><?php echo htmlspecialchars($row['sponsorship_level']); ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="sponsor_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete_sponsor">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    
    <h2>Add a New Sponsor</h2>
    <form method="POST">
        <label for="company_name">Company Name:</label>
        <input type="text" name="company_name" required>
        <label for="sponsorship_level">Sponsorship Level:</label>
        <select name="sponsorship_level">
            <option value="platinum">Platinum</option>
            <option value="gold">Gold</option>
            <option value="silver">Silver</option>
            <option value="bronze">Bronze</option>
        </select>
        <button type="submit" name="add_sponsor">Add Sponsor</button>
    </form>
</body>
</html>
