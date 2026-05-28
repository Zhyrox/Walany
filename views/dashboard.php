<?php
require_once __DIR__ . '/../controllers/pageData.php';

// enforce login and get page data
require_login_redirect('login.php');
$data = get_page_data();

$user = $data['user'];
$events = $data['events'];
$eventsMessage = $data['eventsMessage'];
$registrants = $data['registrants'];
?><!DOCTYPE html>
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
            <?php endif; ?>
        </nav>
    </header>

    <main>
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
    </main>
</body>
</html>