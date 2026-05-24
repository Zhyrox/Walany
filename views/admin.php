<?php
require_once __DIR__ . '../../models/db.php';

$registrations = [];
$events = [];
$databaseMessage = null;
$eventStatus = null;
$eventErrors = [];

// Add or Delete Events (admin side)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_action'])) {
    $eventAction = $_POST['event_action'];

    try {
        if ($eventAction === 'add') {
            // Sanitize inputs before validation.
            $eventDateLabel = trim($_POST['event_date_label'] ?? '');
            $eventName = trim($_POST['event_name'] ?? '');
            $eventDescription = trim($_POST['event_description'] ?? '');

            if ($eventDateLabel === '') {
                $eventErrors[] = 'Event date label is required.';
            }

            if ($eventName === '') {
                $eventErrors[] = 'Event name is required.';
            }

            if ($eventDescription === '') {
                $eventErrors[] = 'Event description is required.';
            }

            if ($eventErrors === []) {
                // Add the event to database when no errors occur.
                $statement = walania_db()->prepare(
                    'INSERT INTO events (event_date_label, event_name, event_description)
                     VALUES (?, ?, ?)'
                );
                $statement->bind_param('sss', $eventDateLabel, $eventName, $eventDescription);
                $statement->execute();
                $eventStatus = 'Event added successfully.';
            }
        }

        if ($eventAction === 'delete') {
            // Delete event from the database.
            $eventId = filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);

            if ($eventId === false || $eventId === null) {
                $eventErrors[] = 'Please choose a valid event to remove.';
            }

            if ($eventErrors === []) {
                $statement = walania_db()->prepare('DELETE FROM events WHERE event_id = ?');
                $statement->bind_param('i', $eventId);
                $statement->execute();
                $eventStatus = 'Event removed successfully.';
            }
        }
    } catch (mysqli_sql_exception $error) {
        // Detect duplicate event names
        $eventErrors[] = $error->getCode() === 1062
            ? 'An event with that name already exists.'
            : 'Event changes could not be saved. Please make sure events.sql is imported.';
    } catch (Throwable $error) {
        // Generic error handling for database failures
        $eventErrors[] = 'Event changes could not be saved. Please make sure events.sql is imported.';
    }
}

// Determine whether to display a success message or validation errors.
$eventFormStatus = $eventErrors === [] ? $eventStatus : null;

// Load recent participant registrations for the admin database table.
try {
    $result = walania_db()->query(
        'SELECT registration_id, full_name, age, email, contact_number, event_name, preference_allergy, registered_at
         FROM event_registrations
         ORDER BY registered_at DESC
         LIMIT 25'
    );

    $registrations = $result->fetch_all(MYSQLI_ASSOC);
} catch (Throwable $error) {
    $databaseMessage = 'Import event_registrations.sql and start MySQL to view saved registrations.';
}

// Display existing events from db
try {
    $eventResult = walania_db()->query(
        'SELECT event_id, event_date_label, event_name, event_description
         FROM events
         ORDER BY event_id ASC'
    );

    $events = $eventResult->fetch_all(MYSQLI_ASSOC);
} catch (Throwable $error) {
    $eventErrors[] = 'Import events.sql and start MySQL to manage events.';
}

// Default display when no participants are selected (no input)
$selectedRegistration = $registrations[0] ?? [
    'full_name' => '',
    'age' => '',
    'email' => '',
    'contact_number' => '',
    'event_name' => '',
    'preference_allergy' => '',
];

function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Walania | Admin</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="images/Walania.svg">
</head>
<body class="admin-page">
    <!-- Admin Page -->
    <header class="site-header admin-header">
        <a href="index.php#home" class="logo-placeholder" aria-label="Walania home">
            <img src="images/Walania.svg" alt="Walania logo">
        </a>

        <nav class="main-nav" aria-label="Admin navigation">
            <a href="#eventDatabase" aria-current="page">Database</a>
            <a href="#eventManager">Events</a>
            <a href="#editParticipant">Edit</a>
            <a href="index.php#registration">Register</a>
            <a href="login.php">Logout</a>
        </nav>

        <button class="theme-toggle" id="themeToggle" type="button" aria-label="Switch to dark mode">
            <span class="theme-icon" aria-hidden="true">D</span>
        </button>
    </header>

    <main>
        <!-- Admin Dashboard -->
        <section class="admin-section" id="eventDatabase">
            <div class="hero-orb hero-orb-left"></div>
            <div class="hero-orb hero-orb-right"></div>

            <div class="section-inner admin-workspace">
                <div class="admin-panel">
                    <div class="admin-panel-heading">
                        <p class="eyebrow">Admin Side</p>
                        <h1>Event Database</h1>
                    </div>

                    <div class="admin-database">
                        <div class="admin-table-wrap" aria-label="Participant database preview">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Age</th>
                                        <th>Email</th>
                                        <th>Contact Number</th>
                                        <th>Event Name</th>
                                        <th>Preference/Allergy</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($databaseMessage !== null) : ?>
                                        <tr>
                                            <td colspan="7"><?php echo h($databaseMessage); ?></td>
                                        </tr>
                                    <?php elseif ($registrations === []) : ?>
                                        <tr>
                                            <td colspan="7">No registrations saved yet.</td>
                                        </tr>
                                    <?php else : ?>
                                        <?php foreach ($registrations as $registration) : ?>
                                            <tr>
                                                <td><?php echo h($registration['full_name']); ?></td>
                                                <td><?php echo h($registration['age']); ?></td>
                                                <td><?php echo h($registration['email']); ?></td>
                                                <td><?php echo h($registration['contact_number']); ?></td>
                                                <td><?php echo h($registration['event_name']); ?></td>
                                                <td><?php echo h($registration['preference_allergy'] ?: 'None'); ?></td>
                                                <td><button class="text-button" type="button">Edit</button></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Event Management -->
                <div class="admin-event-manager" id="eventManager">
                    <div class="admin-form-heading">
                        <p class="eyebrow">Event Controls</p>
                        <h2>Manage Events</h2>
                    </div>

                    <?php if ($eventFormStatus !== null) : ?>
                        <p class="form-alert form-alert-success"><?php echo h($eventFormStatus); ?></p>
                    <?php endif; ?>

                    <?php if ($eventErrors !== []) : ?>
                        <div class="form-alert form-alert-error">
                            <?php foreach ($eventErrors as $eventError) : ?>
                                <p><?php echo h($eventError); ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form class="admin-event-form" action="admin.php#eventManager" method="POST">
                        <input type="hidden" name="event_action" value="add">

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="eventDateLabel">Date Label</label>
                                <input id="eventDateLabel" name="event_date_label" type="text" placeholder="Jun 08" maxlength="20" required>
                            </div>

                            <div class="form-group">
                                <label for="adminEventTitle">Event Name</label>
                                <input id="adminEventTitle" name="event_name" type="text" maxlength="120" required>
                            </div>

                            <div class="form-group form-group-wide">
                                <label for="adminEventDescription">Description</label>
                                <input id="adminEventDescription" name="event_description" type="text" maxlength="255" required>
                            </div>
                        </div>

                        <button class="primary-button submit-button" type="submit">Add Event</button>
                    </form>

                    <div class="admin-event-list" aria-label="Current events">
                        <?php if ($events === []) : ?>
                            <p class="admin-empty-state">No events available yet.</p>
                        <?php else : ?>
                            <?php foreach ($events as $event) : ?>
                                <article class="admin-event-item">
                                    <span class="event-date"><?php echo h($event['event_date_label']); ?></span>
                                    <div>
                                        <h3><?php echo h($event['event_name']); ?></h3>
                                        <p><?php echo h($event['event_description']); ?></p>
                                    </div>
                                    <form action="admin.php#eventManager" method="POST">
                                        <input type="hidden" name="event_action" value="delete">
                                        <input type="hidden" name="event_id" value="<?php echo h($event['event_id']); ?>">
                                        <button class="secondary-button" type="submit">Remove</button>
                                    </form>
                                </article>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Editable Participant Form -->
                <form class="admin-edit-form" id="editParticipant" action="" method="POST">
                    <div class="admin-form-heading">
                        <p class="eyebrow">Editable Fields</p>
                        <h2>Edit Participant</h2>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="adminFullName">Full Name</label>
                            <input id="adminFullName" name="full_name" type="text" value="<?php echo h($selectedRegistration['full_name']); ?>" autocomplete="name" required>
                        </div>

                        <div class="form-group">
                            <label for="adminAge">Age</label>
                            <input id="adminAge" name="age" type="number" min="1" max="120" value="<?php echo h($selectedRegistration['age']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="adminEmail">Email</label>
                            <input id="adminEmail" name="email" type="email" value="<?php echo h($selectedRegistration['email']); ?>" autocomplete="email" required>
                        </div>

                        <div class="form-group">
                            <label for="adminContactNumber">Contact Number</label>
                            <input id="adminContactNumber" name="contact_number" type="tel" value="<?php echo h($selectedRegistration['contact_number']); ?>" autocomplete="tel" required>
                        </div>

                        <div class="form-group form-group-wide">
                            <label for="adminEventName">Event Name</label>
                            <select id="adminEventName" name="event_name" required>
                                <option value="">-- Select Event --</option>
                                <?php foreach ($events as $event) : ?>
                                    <option value="<?php echo h($event['event_name']); ?>" <?php echo $selectedRegistration['event_name'] === $event['event_name'] ? 'selected' : ''; ?>>
                                        <?php echo h($event['event_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group form-group-wide">
                            <label for="adminPreferenceAllergy">Preference/Allergy</label>
                            <input id="adminPreferenceAllergy" name="preference_allergy" type="text" value="<?php echo h($selectedRegistration['preference_allergy']); ?>">
                        </div>
                    </div>

                    <div class="admin-actions">
                        <button class="secondary-button" type="button">Delete</button>
                        <button class="primary-button" type="submit">Save Changes</button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <script src="script.js"></script>
</body>
</html>
