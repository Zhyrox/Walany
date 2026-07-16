<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registrar Panel - Walania Events</title>
    <link rel="stylesheet" href="/Walany/assets/style.css">
</head>
<body class="registrar-events-page">

    <!-- Fix the address of the style.css -->
    <div class="connection-warning" style="background-color: #ffcccc; color: #cc0000; border: 2px solid #cc0000; padding: 20px; font-size: 24px; font-family: sans-serif; font-weight: bold; text-align: center; position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 100; width: 90%; max-width: 600px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    If you are seeing this, paki ayos nung href nung scripts, assets, and stylesheet files. For easy fix move your project folder inside the htdocs
    </div>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$managerName = $_SESSION['manager_name'] ?? 'Guest';
?>

<div class="dashboard-container">
    <div class="content-shell">
        <div class="header">
            <div class="header-copy">
                <h2>Registrar Operation Center</h2>
                <p>Logged in as: <strong><?php echo htmlspecialchars($managerName); ?></strong></p>
            </div>
            <a href="?module=Auth&action=logout" class="logout-btn">Log Out</a>
        </div>

        <div class="intro-panel">
            <h3>Select Active Event for QR Scanning</h3>
            <p>Choose an ongoing event to initialize the attendance tracking scan monitor terminal:</p>
        </div>

        <div class="events-roster">
        <!-- Mock testing element: if your walania_events database table is empty for now -->
        <?php if (empty($events)): ?>
            <div class="event-card">
                <div>
                    <h4>Development Test Event (Mock)</h4>
                    <p>Date: July 2026 | Location: Campus Main Auditorium</p>
                </div>
                <a href="?module=Attendance&action=scanner&event_id=1" class="btn-action">Launch Attendance Scanner</a>
            </div>
        <?php else: ?>
            <!-- Dynamic Database Loop -->
            <?php foreach ($events as $event): ?>
                <div class="event-card">
                    <div>
                        <h4><?php echo htmlspecialchars($event['name']); ?></h4>
                        <p>Date: <?php echo htmlspecialchars($event['event_date']); ?> | Location: <?php echo htmlspecialchars($event['location']); ?></p>
                    </div>
                    <a href="?module=Attendance&action=scanner&event_id=<?php echo $event['id']; ?>" class="btn-action">Launch Attendance Scanner</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>