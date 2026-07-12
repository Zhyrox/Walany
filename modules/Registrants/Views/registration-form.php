<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../core/Database.php';
require_once __DIR__ . '/../Controllers/RegistrantController.php';
require_once __DIR__ . '/../Models/RegistrantModel.php'; // Load model for capacity gate check

$controller = new RegistrantController();
$database = new Database();
$dbConnection = $database->getConnection();
$registrantModel = new RegistrantModel($dbConnection);

$eventId = isset($_GET['event_id']) ? max(1, (int)$_GET['event_id']) : 1;
$context = $controller->loadEventContext($eventId);
$eventDetails = $context['eventData'] ?? ['name' => 'Event Registration', 'description' => ''];

// State 1: Check overall event capacity limit
$isSoldOut = !$registrantModel->isEventCapacityAvailable($eventId);

// State 2: Check concurrency limits (Set max users on form to 2 for easy testing)
$sessionId = session_id();
$trafficStatus = $registrantModel->manageTrafficQueue($eventId, $sessionId, 2);
$linePosition = $registrantModel->getTrafficLinePosition($eventId, $sessionId);

// Instantiate controller to clean up data initialization matching the model
$controller = new RegistrantController();
$eventId = isset($_GET['event_id']) ? max(1, (int)$_GET['event_id']) : 1;

// Fetch cleanly separated layout assets through the orchestration layer
$context = $controller->loadEventContext($eventId);
$eventDetails = $context['eventData'] ?? [
    'name' => 'Event Registration',
    'event_date' => 'Date to be announced',
    'description' => 'Complete the registration form to reserve your slot for this Walania event.'
];

if (!empty($eventDetails['event_date'])) {
    $eventDetails['event_date'] = date('F j, Y', strtotime($eventDetails['event_date']));
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
                            <img class="login-slide-image" src="<?= htmlspecialchars($context['registrationImage']) ?>" alt="<?= htmlspecialchars($context['eventName']) ?> registration showcase image">
                        </div>
                    </div>
                    <div class="slide-dots" aria-hidden="true">
                        <span class="is-active"></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>

                <div class="event-showcase-details">
                    <h1><?= htmlspecialchars($eventDetails['name']) ?></h1>
                    <p class="event-showcase-date"><?= htmlspecialchars($eventDetails['event_date']) ?></p>
                    <p><?= htmlspecialchars($eventDetails['description']) ?></p>
                </div>
            </section>

            <?php if ($isSoldOut): ?>
            <div class="registration-form event-registration-form" style="text-align: center; padding: 40px 20px; border-top: 4px solid #dc3545;">
                <div style="font-size: 48px; margin-bottom: 16px;">🛑</div>
                <h3>Event Fully Booked</h3>
                <p style="color: #6c757d; font-size: 14px; margin-bottom: 24px;">
                    Registration for <strong><?= htmlspecialchars($eventDetails['name']) ?></strong> is officially closed. All available ticket inventory slots have been claimed.
                </p>
                <a href="/PHP_Project/Walany/index.php?module=Home" class="primary-button" style="text-decoration: none; display: inline-block; width: 100%; box-sizing: border-box; background: #6c757d;">Return to Home</a>
            </div>
            <?php elseif ($trafficStatus === 'waiting'): ?>
                <div class="registration-form event-registration-form" style="text-align: center; padding: 45px 25px; border-top: 4px solid #0d6efd;">
                    <div class="spinner" style="width: 45px; height: 45px; border: 4px solid #f3f3f3; border-top: 4px solid #0d6efd; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 20px;"></div>
                    
                    <h3>High Traffic Volume</h3>
                    <p style="font-size: 14px; color: #6c757d; margin-bottom: 20px;">
                        Many users are attempting to register right now. You have been placed in our secure queue to protect system transactions.
                    </p>

                    <div style="background: #e2e3e5; color: #383d41; padding: 12px 20px; border-radius: 6px; font-size: 15px; display: inline-block; font-weight: bold; margin-bottom: 20px;">
                        Your Line Position: #<?= $linePosition ?>
                    </div>
                    
                    <p style="font-size: 12px; color: #adb5bd;">This page auto-refreshes. Do not close or reload the window.</p>
                    
                    <style>@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }</style>
                    <script>setTimeout(() => { window.location.reload(); }, 5000);</script>
                </div>
            <?php else: ?>
            <!-- Dispatches request parameters securely into module action handler -->
            <form class="registration-form event-registration-form" action="/PHP_Project/Walany/index.php?module=Registrants&action=submit_registration" method="POST">
                <h3>Registrant Details</h3>
                <p class="event-registration-note">Please fill out your details to reserve your slot and receive your reference ID token.</p>

                <input type="hidden" name="event_id" value="<?= htmlspecialchars((string)$eventId) ?>">

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
                </div>

                <button class="primary-button submit-button" type="submit">Complete Registration</button>
            </form>
            <?php endif; ?>
        </div>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 Walania. All rights reserved.</p>
    </footer>

    <script src="/PHP_Project/Walany/assets/script.js"></script>
</body>
</html>