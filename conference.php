<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conference Database</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    <style>
        h1 {
            font-family: 'Dancing Script', cursive;
            text-align: center;
            color: black;
            font-size: 48px; 
        }
    </style>
</head>
<body>
    <header>
        <h1>Conference Database</h1>
    </header>
    <nav>
        <ul style="font-size: 18px;">    
            <li><a href="subcommittees.php">Subcommittees</a></li>
            <li><a href="sponsors.php">Sponsors</a></li>
            <li><a href="sessions.php">Sessions</a></li>
            <li><a href="attendees.php">Attendees</a></li>
            <li><a href="rooms.php">Hotel Rooms</a></li>
            <li><a href="jobs.php">Job Ads</a></li>
            <li><a href="schedule.php">Schedule</a></li>
            <li><a href="stats.php">Intake Statistics</a></li>
        </ul>
    </nav>

    <img src="flowers.jpg" alt="Pretty tulips" width="100%" height="auto">
</body>
</html>
