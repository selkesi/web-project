<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "conferenceDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$subcommittee_id = isset($_POST['subcommittee']) ? $_POST['subcommittee'] : '';

$subcommittees = $conn->query("SELECT id, name FROM Subcommittee");
$members = [];
if ($subcommittee_id) {
    $stmt = $conn->prepare("SELECT CommitteeMember.name FROM CommitteeMember 
                            JOIN CommitteeMembership ON CommitteeMember.id = CommitteeMembership.member_id 
                            WHERE CommitteeMembership.subcommittee_id = ?");
    $stmt->bind_param("i", $subcommittee_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $members[] = $row['name'];
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Subcommittees</title>
</head>
<body>
    <h2>Subcommittees</h2>
    <form method="POST">
        <label for="subcommittee">Choose a Subcommittee:</label>
        <select name="subcommittee" id="subcommittee" onchange="this.form.submit()">
            <option value="">Select...</option>
            <?php while ($row = $subcommittees->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>" <?php if ($subcommittee_id == $row['id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($row['name']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>
    
    <?php if ($subcommittee_id && count($members) > 0): ?>
        <h2>Members:</h2>
        <ul>
            <?php foreach ($members as $member): ?>
                <li><?php echo htmlspecialchars($member); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php elseif ($subcommittee_id): ?>
        <p>No members found for this subcommittee.</p>
    <?php endif; ?>
</body>
</html>
