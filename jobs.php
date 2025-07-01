<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "conferenceDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$jobs = $conn->query("SELECT Sponsor.company_name, Job.job_title, Job.location_city, Job.location_province, Job.salary FROM Job INNER JOIN Sponsor ON Job.company_id = Sponsor.id ORDER BY Sponsor.company_name ASC");

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Jobs</title>
</head>
<body>
    <h2>Jobs</h2>
    <table border="1">
        <tr>
            <th>Company Name</th>
            <th>Job Title</th>
            <th>Location</th>
            <th>Salary</th>
        </tr>
        <?php while ($row = $jobs->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                <td><?php echo htmlspecialchars($row['job_title']); ?></td>
                <td><?php echo htmlspecialchars($row['location_city'] . ', ' . $row['location_province']); ?></td>
                <td><?php echo htmlspecialchars($row['salary']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
