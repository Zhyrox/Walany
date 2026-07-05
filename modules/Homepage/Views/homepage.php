<link rel="stylesheet" href="assets/style.css">

<div class="events-container">
    <h2>Available Campus Events</h2>

    <?php if (empty($events)): ?>
        <p class="events-empty">No active events scheduled at this time. Check back later!</p>
    <?php else: ?>
        <div class="events-grid">
            <?php foreach ($events as $event): ?>
                <div class="event-card">
                    <div class="event-card-badge">Upcoming</div>
                    <h3><?= htmlspecialchars($event['name']) ?></h3>
                    <p class="card-date"><strong>📅 Date:</strong> <?= htmlspecialchars($event['event_date']) ?></p>
                    <p class="card-location"><strong>📍 Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
                    <p class="card-description"><?= htmlspecialchars($event['description']) ?></p>

                    <div class="actions">
                        <a href="/PHP_Project/Walany/modules/Registrants/Views/registration-form.php?event_id=<?= $event['id'] ?>" class="btn btn-primary">
                            Register Now
                        </a>

                        <a href="/PHP_Project/Walany/modules/Events/Views/evaluate.php?event_id=<?= $event['id'] ?>" class="btn btn-secondary">
                            Give Feedback
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>