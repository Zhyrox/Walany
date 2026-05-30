<!-- page data -->

<?php
// Include dependencies
require_once __DIR__ . '../../models/Database.php';
require_once __DIR__ . '../../models/EventModel.php';
require_once __DIR__ . '../../models/RegistrantModel.php';
require_once __DIR__ . '../../controllers/PageData.php';

// Initialize your core database connection
$database = new Database();
$conn = $database->getConnection();

// Instantiate the controller, passing the connection into the constructor
$pageController = new PageDataController($conn);

// Request the structural array for the view
$data = $pageController->getPageData();

// Extract variables
$user = $data['user'];
$events = $data['events'];
$eventsMessage = $data['eventsMessage'];
$registrants = $data['registrants'];

?>


<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Walania | Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../images/Walania.svg">
    <link rel="stylesheet" href="../style.css">
</head>
<body class="dashboard-page">
    <header class="site-header">
        <!-- Header: brand, navigation, and theme toggle -->
        <a href="#dashboard-hero" class="logo-placeholder" aria-label="Walania home">
            <img src="images/Walania.svg" alt="Walania logo">
        </a>
        <p>Welcome, <?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?>!</p>
        <nav class="main-nav" aria-label="Main navigation">

        <?php if (!empty($user['role']) && $user['role'] !== 'user') : ?>
                <a href="event.php">Manage Events</a>
                <a href="registrant.php">Manage Registrants</a>
            <?php endif; ?>

            <a href="#events">Events</a>
            <a href="#registration">Register</a>
            <a href="#contacts">Contacts</a>
            <?php if ($user !== null) : ?>
                <a href="../controllers/logout.php">Logout</a>
            <?php else : ?>
                <a href="user_login.php">User Login</a>
                <a href="login.php">Admin</a>
            <?php endif; ?>
        </nav>
        <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle dark mode">
            <img class="theme-toggle-icon" data-theme-icon src="../images/LightModeIcon.svg" alt="" aria-hidden="true">
        </button>
    </header>

    <main>
        <!-- Hero block: primary welcome message and quick actions -->
        <section id="dashboard-hero" class="dashboard-hero" aria-labelledby="dashboard-hero-title">
            <div class="dashboard-hero-content">
                <h1 id="dashboard-hero-title">Welcome to Walania</h1>
                <p class="dashboard-hero-copy">
                    A calm place to explore upcoming events, manage registrations, and keep everything in one polished space.
                </p>

                <div class="dashboard-hero-actions">
                    <a class="primary-button" href="#events">View Events</a>
                    <a class="secondary-button" href="#registration">Register Now</a>
                </div>
            </div>
        </section>

        <!-- Events block: upcoming schedule table -->
        <section id="events" class="events-section">
            <div class="section-heading">
                <p class="eyebrow">Upcoming</p>
                <h2>Events</h2>
            </div>

            <?php if ($eventsMessage !== null) : ?>
                <div class="form-alert form-alert-info">
                    <p><?php echo htmlspecialchars($eventsMessage, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            <?php else : ?>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Event</th>
                                <th>Location</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($events as $event) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($event['date'] ?? $event['event_date'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($event['name'] ?? $event['event_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($event['location'] ?? $event['event_location'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= nl2br(htmlspecialchars($event['description'] ?? $event['event_description'] ?? '', ENT_QUOTES, 'UTF-8')); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

        <!-- Registration block: event signup form -->
        <section id="registration" class="registration-section">
            <div class="registration-layout">
                <div class="section-heading">
                    <p class="eyebrow">Register</p>
                    <h2>Event Registration</h2>
                    <div class="contact-copy">
                        <p><?php echo $user !== null ? 'You are logged in and ready to submit an event registration.' : 'Login or create a user account before submitting an event registration.'; ?></p>
                    </div>
                </div>

                <form class="registration-form" action="../controllers/registrantController.php" method="POST">
                    <!-- Form status and validation messaging -->
                    <?php if ($user === null) : ?>
                        <div class="form-alert form-alert-info">
                            <p>You need a user account to submit this form.</p>
                            <p><a href="user_login.php">Login</a> or <a href="user_register.php">Create an account</a>.</p>
                        </div>
                    <?php else : ?>
                        <div class="form-alert form-alert-success">Logged in as <?php echo htmlspecialchars($user['username'] ?? $user['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>.</div>
                    <?php endif; ?>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="fullname">Full Name</label>
                            <input type="text" id="fullname" name="fullname" required>
                        </div>
                        <div class="form-group">
                            <label for="age">Age</label>
                            <input type="number" id="age" name="age" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="contact_number">Contact Number</label>
                            <input type="text" id="contact_number" name="contact_number" required>
                        </div>
                        <div class="form-group form-group-wide">
                            <label for="preference_allergy">Preferences / Allergies</label>
                            <textarea class="preferencebox" id="preference_allergy" name="preference_allergy"></textarea>
                        </div>
                        <div class="form-group form-group-wide">
                            <label for="event_id">Select Event</label>
                            <select name="event_id" id="event_id" required>
                                <?php foreach ($events as $event): ?>
                                    <option value="<?= $event['id'] ?>"><?= htmlspecialchars($event['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <button class="primary-button submit-button" type="submit" name="add">Register</button>
                </form>
            </div>
        </section>

        <!-- Contact block: support details and office info -->
        <section id="contacts" class="contacts-section">
            <div class="registration-layout contact-layout">
                <div class="section-heading">
                    <p class="eyebrow">Contact</p>
                    <h2>Reach Out</h2>
                    <div class="contact-copy">
                        <p>Need help with events, registration, or admin access? Use the details below and I’ll keep this section easy to find at the bottom of the page.</p>
                    </div>
                </div>

                <div class="contact-card">
                    <div class="contact-item">
                        <span class="contact-label">Email</span>
                        <a href="mailto:walaniaevents@gmail.com">walaniaevents@gmail.com</a>
                    </div>

                    <div class="contact-item">
                        <span class="contact-label">Location</span>
                        <p>Cavite, Philippines</p>
                    </div>
                    <div class="contact-item">
                        <span class="contact-label">Office Hours</span>
                        <p>Monday to Friday, 9:00 AM - 5:00 PM</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="../script.js"></script>
</body>
</html>
