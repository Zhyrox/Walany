<?php
// 1. Check if core Database wrapper is required, if not already included
if (!class_exists('Database')) {
    require_once __DIR__ . '/../../../core/Database.php';
}

// 2. Fetch the specific event ID from the URL parameter
$eventId = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;
$eventName = 'Campus Event'; // Safe default string
$registrationImage = '/Walany/assets/images/Event_Image%20(1).jpg'; // Safe default path

if ($eventId > 0) {
    try {
        $dbInstance = new Database();
        $db = $dbInstance->getConnection();
        
        $stmt = $db->prepare("SELECT * FROM `walania_event` WHERE `id` = :id LIMIT 1");
        $stmt->execute([':id' => $eventId]);
        $eventData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($eventData) {
            $eventName = $eventData['name'] ?? 'Campus Event';
            $thumbnailValue = isset($eventData['thumbnail']) ? trim($eventData['thumbnail']) : '';
            
            if (!empty($thumbnailValue) && $thumbnailValue !== 'uploads/events/default-banner.png') {
                $registrationImage = $thumbnailValue;
            }
        }
    } catch (PDOException $e) {
        // Fallback defaults remain if database query encounters an issue
    }
}
?>

<?php
$eventId = isset($_GET['event_id']) ? max(1, (int) $_GET['event_id']) : 1;
$eventDetails = [
    'name' => 'Event Registration',
    'event_date' => 'Date to be announced',
    'description' => 'Complete the registration form to reserve your slot for this Walania event.'
];

try {
    require_once __DIR__ . '/../../../core/Database.php';

    $database = new Database();
    $db = $database->getConnection();
    $stmt = $db->prepare('SELECT name, event_date, description FROM walania_event WHERE id = ? LIMIT 1');
    $stmt->execute([$eventId]);
    $eventRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($eventRow) {
        $eventDetails = array_merge($eventDetails, $eventRow);
        if (!empty($eventRow['event_date'])) {
            $eventDetails['event_date'] = date('F j, Y', strtotime($eventRow['event_date']));
        }
    }
} catch (Throwable $e) {
    // Keep the registration page usable even if event details are unavailable.
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration - Walania</title>
    <link rel="icon" type="image/x-icon" href="/PHP_Project/Walany/assets/images/Walania.svg">
    <link rel="stylesheet" href="/PHP_Project/Walany/assets/style.css">
</head>
<body class="registration-page event-registration-page">
    <header class="site-header login-header headbar">
        <a href="/PHP_Project/Walany/index.php?module=Home" class="logo-placeholder" aria-label="Walania home">
            <img src="/PHP_Project/Walany/assets/images/Walania.svg" alt="Walania logo">
        </a>
        <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle dark mode">
            <img class="theme-toggle-icon" data-theme-icon src="/PHP_Project/Walany/assets/images/LightModeIcon.svg" alt="" aria-hidden="true">
        </button>
    </header>

    <main class="registration-section">
        <div class="registration-layout">
            <section class="event-registration-showcase" aria-label="Walania event highlights">
                <div class="event-slideshow-card">
                    <div class="login-slideshow">
<div class="login-slide is-active">
    <!-- Pulls the dynamic thumbnail string directly from the database query mapping above -->
    <img class="login-slide-image" src="<?= htmlspecialchars($registrationImage) ?>" alt="<?= htmlspecialchars($eventName) ?> registration showcase image">
</div>
                    </div>
                    <div class="slide-dots" aria-hidden="true">
                        <span class="is-active"></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>

                <div class="event-showcase-details">
                    <h1><?php echo htmlspecialchars($eventDetails['name']); ?></h1>
                    <p class="event-showcase-date"><?php echo htmlspecialchars($eventDetails['event_date']); ?></p>
                    <p><?php echo htmlspecialchars($eventDetails['description']); ?></p>
                </div>
            </section>

            <!-- The form submits to the Registrants module action processor -->
            <form class="registration-form event-registration-form" action="/PHP_Project/Walany/index.php?module=Registrants&action=submit_registration" method="POST">
                <h3>Registrant Details</h3>
                <p class="event-registration-note">Please fill out your details to reserve your slot and receive your reference ID token.</p>

                <!-- Crucial: Capture the incoming event_id from the landing page click -->
                <input type="hidden" name="event_id" value="<?php echo htmlspecialchars((string) $eventId); ?>">

                <div class="form-grid">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" required>
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" required>
                    </div>
                </div>

                <div class="form-group form-group-wide">
                    <label for="middle_name">Middle Name <span class="note">(Optional)</span></label>
                    <input type="text" id="middle_name" name="middle_name">
                </div>

                <div class="form-group form-group-wide">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="contact_number">Contact Number</label>
                        <input type="text" id="contact_number" name="contact_number" required placeholder="e.g., 09123456789">
                    </div>

                    <div class="form-group">
                        <label for="birthdate">Birthdate</label>
                        <input type="date" id="birthdate" name="birthdate" required>
                    </div>
                </div>

                <button class="primary-button submit-button" type="submit">Complete Registration</button>
            </form>
        </div>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 Walania. All rights reserved.</p>
    </footer>

    <script src="/PHP_Project/Walany/assets/script.js"></script>
</body>
</html>
