<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registrar Panel - Walania Events</title>
    <!-- Add your global stylesheet linking here later -->
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 30px; }
        .dashboard-container { max-width: 900px; margin: 0 auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eaeaea; padding-bottom: 15px; margin-bottom: 20px; }
        .logout-btn { background: #dc3545; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; font-size: 14px; }
        .event-card { border: 1px solid #ddd; background: #fafafa; padding: 15px; margin-bottom: 15px; border-radius: 6px; display: flex; justify-content: space-between; align-items: center; }
        .btn-action { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold; }
        .btn-action:hover { background: #0056b3; }
    </style>
</head>
<body>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$managerName = $_SESSION['manager_name'] ?? 'Guest';
?>

<div class="dashboard-container">
    <div class="header">
        <div>
            <h2>Registrar Operation Center</h2>
            <p>Logged in as: <strong><?php echo htmlspecialchars($managerName); ?></strong></p>
        </div>
        <a href="?module=Auth&action=logout" class="logout-btn">Log Out</a>
    </div>

    <h3>Select Active Event for QR Scanning</h3>
    <p>Choose an ongoing event to initialize the attendance tracking scan monitor terminal:</p>

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