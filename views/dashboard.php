<?php
require_once __DIR__ . '/../models/registrantModel.php';
require_once __DIR__ . '/../models/eventModel.php';

session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit();
}

$user = [
    'id' => $_SESSION['user_id'],
    'username' => $_SESSION['username']
];
$events = getAllEvents();
$eventsMessage = $events === [] ? 'No events available yet.' : null;

// safe defaults for view-only variables (controller may set these in a full MVC flow)
$registrationStatus = $registrationStatus ?? null;
$registrationErrors = $registrationErrors ?? [];
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Walania | Dashboard</title>
    <link rel="stylesheet" href="../style.css">
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
                <a href="../views/logout.php">Logout</a>
            <?php else : ?>
                <a href="user_login.php">User Login</a>
                <a href="login.php">Admin</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <h1>Welcome to the Dashboard</h1>
        <p>You are logged in as <?php echo htmlspecialchars($user['username'] ?? $user['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>

        <section id="events">
            <h2>Events</h2>
            <?php if ($eventsMessage !== null) : ?>
                <p><?php echo htmlspecialchars($eventsMessage, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php else : ?>
                <table class="events-table" style="width:100%;border-collapse:collapse">
                    <thead>
                        <tr>
                            <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd">Date</th>
                            <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd">Event</th>
                            <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd">Location</th>
                            <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $event) : ?>
                            <tr>
                                <td style="padding:8px;border-bottom:1px solid #f1f1f1">
                                    <?php echo htmlspecialchars($event['date'] ?? $event['event_date_label'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                                <td style="padding:8px;border-bottom:1px solid #f1f1f1">
                                    <?php echo htmlspecialchars($event['name'] ?? $event['event_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                                <td style="padding:8px;border-bottom:1px solid #f1f1f1">
                                    <?php echo htmlspecialchars($event['location'] ?? $event['event_location'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                                <td style="padding:8px;border-bottom:1px solid #f1f1f1">
                                    <?php echo nl2br(htmlspecialchars($event['description'] ?? $event['event_description'] ?? '', ENT_QUOTES, 'UTF-8')); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>

        <section id="registration">
            <h2>Event Registration</h2>
            <p><?php echo $user !== null ? 'You are logged in and ready to submit an event registration.' : 'Login or create a user account before submitting an event registration.'; ?></p>

            <?php if ($user === null) : ?>
                <div class="form-alert form-alert-info">
                    <p>You need a user account to submit this form.</p>
                    <p><a href="user_login.php">Login</a> or <a href="user_register.php">create an account</a>.</p>
                </div>
            <?php else : ?>
                <p class="form-alert form-alert-success">Logged in as <?php echo htmlspecialchars($user['username'] ?? $user['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>.</p>
            <?php endif; ?>

            <form action="../controllers/registrantController.php" method="POST">

                <label for="fullname">Full Name:</label>
                <input type="text" id="fullname" name="fullname" required>

                <br><br>

                <label for="age">Age:</label>
                <input type="number" id="age" name="age" required>

                <br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <br><br>

                <label for="contact_number">Contact Number:</label>
                <input type="text" id="contact_number" name="contact_number" required>

                <br><br>

                <label for="preference_allergy">Preferences / Allergies:</label>
                <textarea id="preference_allergy" name="preference_allergy"></textarea>

                <br><br>

                <label for="event_id">Select Event:</label>
                    <select name="event_id" id="event_id" required>

                        <?php foreach ($events as $event): ?>
                            <option value="<?= $event['id'] ?>">
                                <?= htmlspecialchars($event['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                <br><br>

                <button type="submit" name="register"> Register </button>
            </form>
        </section>
    </main>
</body>
</html>