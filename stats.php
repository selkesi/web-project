<?php
require 'config.php'; // Ensure database connection is available

$totalRegistrationQuery = $pdo->query("SELECT SUM(registration_fee) AS total_registration FROM Attendee WHERE type IN ('student', 'professional')");
$totalRegistration = $totalRegistrationQuery->fetch(PDO::FETCH_ASSOC)['total_registration'];

$totalSponsorshipQuery = $pdo->query("SELECT SUM(sponsorship_amount) AS total_sponsorship FROM Sponsor");
$totalSponsorship = $totalSponsorshipQuery->fetch(PDO::FETCH_ASSOC)['total_sponsorship'];

$totalIntake = $totalRegistration + $totalSponsorship;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intake Statistics</title>
</head>
<body>
    <h2>Intake Statistics</h2>
    
    <p><strong>Total Registration Intake (from students and professionals):</strong> $<?= number_format($totalRegistration, 2) ?></p>
    <p><strong>Total Sponsorship Intake (from companies):</strong> $<?= number_format($totalSponsorship, 2) ?></p>
    <p><strong>Total Intake:</strong> $<?= number_format($totalIntake, 2) ?></p>
</body>
</html>
