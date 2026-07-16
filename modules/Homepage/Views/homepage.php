<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Events - Walania</title>
    <link rel="icon" type="image/x-icon" href="/Walany/assets/images/Walania.svg">
    <link rel="stylesheet" href="/Walany/assets/style.css">
</head>
<body class="home-events-page">

    <!-- Fix the address of the style.css -->
    <div class="connection-warning" style="background-color: #ffcccc; color: #cc0000; border: 2px solid #cc0000; padding: 20px; font-size: 24px; font-family: sans-serif; font-weight: bold; text-align: center; position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 100; width: 90%; max-width: 600px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    If you are seeing this, paki ayos nung href nung scripts, assets, and stylesheet files. For easy fix move your project folder inside the htdocs
    </div>

    <header class="site-header login-header headbar">
        <a href="/Walany/index.php?module=Home" class="logo-placeholder" aria-label="Walania home">
            <img src="/Walany/assets/images/Walania.svg" alt="Walania logo">
        </a>
        <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle dark mode">
            <img class="theme-toggle-icon" data-theme-icon src="/Walany/assets/images/LightModeIcon.svg" alt="" aria-hidden="true">
        </button>
    </header>

    <main class="events-container">
        <section class="events-search-panel" aria-label="Search campus events">
            <label for="eventSearch">Search events</label>
            <div class="events-search-control">
                <input type="search" id="eventSearch" placeholder="Search by event title..." autocomplete="off">
            </div>
        </section>

        <h2>Available Campus Events</h2>

        <?php if (empty($events)): ?>
            <p class="events-empty">No active events scheduled at this time. Check back later!</p>
        <?php else: ?>
            <p id="noEventsMessage" class="events-empty events-no-results">No events match your search.</p>
            <div class="events-grid">
                <?php foreach ($events as $event): ?>

                    <?php
                        $eventName = $event['name'] ?? 'Campus Event';
                        
                        // If the database has a path, use it directly. Otherwise, use a default fallback path string.
                        $eventImage = !empty($event['thumbnail'])
                            ? $event['thumbnail']
                            : '/Walany/assets/images/Event_Image%20(1).jpg';
                    ?>

                    <article class="event-card" data-event-title="<?= htmlspecialchars(strtolower($eventName)) ?>">
                        <!-- This block now processes your new database column value cleanly -->
                        <div class="event-card-image">
                            <!-- PHP just outputs the exact string it fetched from the database table -->
                            <img src="<?= htmlspecialchars($eventImage) ?>" alt="<?= htmlspecialchars($eventName) ?> event image">
                        </div>
                        <div class="event-card-badge">Upcoming</div>
                        <h3><?= htmlspecialchars($eventName) ?></h3>
                        <p class="card-date"><strong>Date:</strong> <?= htmlspecialchars($event['event_date'] ?? '') ?></p>
                        <p class="card-location"><strong>Location:</strong> <?= htmlspecialchars($event['location'] ?? '') ?></p>
                        <p class="card-description"><?= htmlspecialchars($event['description'] ?? '') ?></p>

                        <div class="actions">
                            <a href="/Walany/modules/Registrants/Views/registration-form.php?event_id=<?= htmlspecialchars((string) ($event['id'] ?? 0)) ?>" class="btn btn-primary">
                                Register Now
                            </a>

                            <a href="/Walany/modules/Events/Views/evaluate.php?event_id=<?= htmlspecialchars((string) ($event['id'] ?? 0)) ?>" class="btn btn-secondary">
                                Give Feedback
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <script src="/Walany/assets/script.js"></script>
</body>
</html>
