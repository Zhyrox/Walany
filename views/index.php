<?php
require_once __DIR__ . '../../models/db.php';
require_once __DIR__ . '../../controllers/auth.php';

$registrationStatus = null;
$registrationErrors = [];
$events = [];
$eventsMessage = null;
$user = current_user();

try {
    $eventResult = walania_db()->query(
        'SELECT event_date_label, event_name, event_description
         FROM events
         ORDER BY event_id ASC'
    );

    $events = $eventResult->fetch_all(MYSQLI_ASSOC);
} catch (Throwable $error) {
    $eventsMessage = 'Events are not available yet. Please import events.sql and start MySQL.';
}

$allowedEvents = array_column($events, 'event_name');

function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registration_form'])) {
    $fullName = trim($_POST['full_name'] ?? '');
    $age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT);
    $email = trim($_POST['email'] ?? '');
    $contactNumber = trim($_POST['contact_number'] ?? '');
    $eventName = trim($_POST['event_name'] ?? '');
    $preferenceAllergy = trim($_POST['preference_allergy'] ?? '');

    if (!user_is_logged_in()) {
        $registrationErrors[] = 'Please login or create a user account before submitting an event registration.';
    }

    if ($fullName === '') {
        $registrationErrors[] = 'Full name is required.';
    }

    if ($age === false || $age < 1 || $age > 120) {
        $registrationErrors[] = 'Please enter a valid age.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $registrationErrors[] = 'Please enter a valid email address.';
    }

    if ($contactNumber === '') {
        $registrationErrors[] = 'Contact number is required.';
    }

    if (!in_array($eventName, $allowedEvents, true)) {
        $registrationErrors[] = 'Please select a valid event.';
    }

    if ($registrationErrors === []) {
        try {
            $statement = walania_db()->prepare(
                'INSERT INTO event_registrations (full_name, age, email, contact_number, event_name, preference_allergy)
                 VALUES (?, ?, ?, ?, ?, ?)'
            );

            $statement->bind_param(
                'sissss',
                $fullName,
                $age,
                $email,
                $contactNumber,
                $eventName,
                $preferenceAllergy
            );

            $statement->execute();
            $registrationStatus = 'success';
        } catch (Throwable $error) {
            $registrationStatus = 'error';
            $registrationErrors[] = 'Registration could not be saved. Please make sure the database is imported and MySQL is running.';
        }
    } else {
        $registrationStatus = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Walania | Event Registration</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
<link rel="icon" type="image/svg+xml" href="images\Walania.svg">
</head>
<body>
    <header class="site-header">
        <a href="index.php#home" class="logo-placeholder" aria-label="Walania home">
            <img src="images/Walania.svg" alt="Walania logo">
        </a>

        <nav class="main-nav" aria-label="Main navigation">
            <a href="#events">Events</a>
            <a href="#registration">Register</a>
            <a href="#contacts">Contacts</a>
            <?php if ($user !== null) : ?>
                <a href="..\controllers\logout.php">Logout</a>
            <?php else : ?>
                <a href="user_login.php">User Login</a>
                <a href="login.php">Admin</a>
            <?php endif; ?>
        </nav>

        <button class="theme-toggle" id="themeToggle" type="button" aria-label="Switch to dark mode">
            <span class="theme-icon" aria-hidden="true">D</span>
        </button>
    </header>

    <main>
        <section class="hero-section" id="home">
            <div class="hero-orb hero-orb-left"></div>
            <div class="hero-orb hero-orb-right"></div>

            <div class="hero-content">
                <h1>Walania</h1>
                <p>Click. Register. Experience.</p>
                <button class="primary-button" id="registerNowBtn" type="button">Register Now!</button>
            </div>
        </section>

        <section class="events-section" id="events">
            <div class="section-inner">
                <div class="section-heading">
                    <p class="eyebrow">Browse upcoming activities</p>
                    <h2>Events Section</h2>
                    <p>Scroll inside the frame to view more events.</p>
                </div>

                <div class="event-tools">
                    <label class="event-search-label" for="eventSearch">Search events</label>
                    <div class="event-search-row">
                        <input id="eventSearch" type="search" name="event_search" placeholder="Search by event name">
                        <button class="secondary-button" id="eventSearchBtn" type="button">Search</button>
                    </div>
                </div>

                <div class="events-frame" tabindex="0" aria-label="Scrollable list of events">
                    <?php if ($eventsMessage !== null) : ?>
                        <p class="no-events-message is-visible"><?php echo h($eventsMessage); ?></p>
                    <?php elseif ($events === []) : ?>
                        <p class="no-events-message is-visible">No events available yet.</p>
                    <?php else : ?>
                        <?php foreach ($events as $event) : ?>
                            <article class="event-card">
                                <span class="event-date"><?php echo h($event['event_date_label']); ?></span>
                                <div>
                                    <h3><?php echo h($event['event_name']); ?></h3>
                                    <p><?php echo h($event['event_description']); ?></p>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <p class="no-events-message" id="noEventsMessage">No events found. Try another search term.</p>
                </div>
            </div>
        </section>

        <section class="registration-section" id="registration">
            <div class="section-inner registration-layout">
                <div class="section-heading">
                    <p class="eyebrow">Event Registration System</p>
                    <h2>Event Registration</h2>
                    <p><?php echo $user !== null ? 'You are logged in and ready to submit an event registration.' : 'Login or create a user account before submitting an event registration.'; ?></p>
                </div>

                <form class="registration-form" action="index.php#registration" method="POST">
                    <input type="hidden" name="registration_form" value="1">
                    <h3>Add Participant</h3>

                    <?php if ($user === null) : ?>
                        <div class="form-alert form-alert-info">
                            <p>You need a user account to submit this form.</p>
                            <p><a href="user_login.php">Login</a> or <a href="user_register.php">create an account</a> first.</p>
                        </div>
                    <?php else : ?>
                        <p class="form-alert form-alert-success">Logged in as <?php echo h($user['full_name']); ?>.</p>
                    <?php endif; ?>

                    <?php if ($registrationStatus === 'success') : ?>
                        <p class="form-alert form-alert-success">Registration saved successfully.</p>
                    <?php elseif ($registrationStatus === 'error') : ?>
                        <div class="form-alert form-alert-error">
                            <?php foreach ($registrationErrors as $registrationError) : ?>
                                <p><?php echo htmlspecialchars($registrationError, ENT_QUOTES, 'UTF-8'); ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="fullName">Full Name</label>
                            <input id="fullName" name="full_name" type="text" value="<?php echo h($user['full_name'] ?? ''); ?>" autocomplete="name" <?php echo $user === null ? 'disabled' : ''; ?> required>
                        </div>

                        <div class="form-group">
                            <label for="age">Age</label>
                            <input id="age" name="age" type="number" min="1" max="120" <?php echo $user === null ? 'disabled' : ''; ?> required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" value="<?php echo h($user['email'] ?? ''); ?>" autocomplete="email" <?php echo $user === null ? 'disabled' : ''; ?> required>
                        </div>

                        <div class="form-group">
                            <label for="contactNumber">Contact Number</label>
                            <input id="contactNumber" name="contact_number" type="tel" autocomplete="tel" <?php echo $user === null ? 'disabled' : ''; ?> required>
                        </div>

                        <div class="form-group form-group-wide">
                            <label for="eventName">Event Name</label>
                            <select class="eventName" id="eventName" name="event_name" <?php echo $user === null ? 'disabled' : ''; ?> required>
                                <option value="">-- Select Event --</option>
                                <?php foreach ($events as $event) : ?>
                                    <option value="<?php echo h($event['event_name']); ?>"><?php echo h($event['event_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group form-group-wide">
                            <label for="preferenceAllergy">Preference/Allergy</label>
                            <input class="preferenceAllergy" id="preferenceAllergy" name="preference_allergy" type="text" placeholder="Food preference, accessibility needs, or allergies" <?php echo $user === null ? 'disabled' : ''; ?>>
                        </div>
                    </div>

                    <button class="primary-button submit-button" type="submit" <?php echo $user === null ? 'disabled' : ''; ?>>Submit</button>
                </form>
            </div>
        </section>

        <section class="contacts-section" id="contacts">
            <div class="section-inner contact-layout">
                <div class="contact-image-placeholder">
                    <span>Image Placeholder</span>
                </div>

                <div class="contact-copy">
                    <p class="eyebrow">Need help?</p>
                    <h2>Contacts</h2>
                    <p>Email: support@walania.test</p>
                    <p>Phone: +63 900 000 0000</p>
                    <p>Office: Student Activities Center, Main Campus</p>
                </div>
            </div>
        </section>
    </main>

    <button class="back-to-top" id="backToTop" type="button" aria-label="Scroll back to top">Top</button>

    <script src="script.js"></script>
</body>
</html>
