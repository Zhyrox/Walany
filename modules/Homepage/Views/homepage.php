<div class="events-container">
    <h2>Available Campus Events</h2>
    
    <?php if (empty($events)): ?>
        <p>No active events scheduled at this time. Check back later!</p>
    <?php else: ?>
        <div class="events-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <?php foreach ($events as $event): ?>
                <div class="event-card" style="border: 1px solid #ccc; padding: 20px; border-radius: 8px;">
                    <h3><?= htmlspecialchars($event['name']) ?></h3>
                    <p><strong>📅 Date:</strong> <?= htmlspecialchars($event['event_date']) ?></p>
                    <p><strong>📍 Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
                    <p><?= htmlspecialchars($event['description']) ?></p>
                    
                    <hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;">
                    
<div class="actions" style="display: flex; gap: 10px;">
    <!-- Registration Button -->
    <a href="/PHP_Project/Walany/modules/Registrants/Views/registration-form.php?event_id=<?= $event['id'] ?>" class="btn btn-primary" style="padding: 8px 12px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;">
        Register Now
    </a>
    
    <!-- Feedback Button -->
    <a href="/PHP_Project/Walany/modules/Events/Views/evaluate.php?event_id=<?= $event['id'] ?>" class="btn btn-secondary" style="padding: 8px 12px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px;">
        Give Feedback
    </a>
</div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>