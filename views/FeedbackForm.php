<?php
// 1. ALWAYS start the session first if you are tracking success/error alerts
session_start();

// Include dependencies
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/EventModel.php';
require_once __DIR__ . '/../models/RegistrantModel.php';
require_once __DIR__ . '/../controllers/PageData.php';
require_once __DIR__ . '/../models/FeedbackModel.php';

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

// Instantiate feedback model to pull all historical reviews
$feedbackModel = new FeedbackModel($conn);
$allComments = $feedbackModel->getAllComments(); // Fetches feedback + usernames + event names
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Event Feedback</title>
</head>
<body>
    
    <section id="feedback-submission" class="feedback-section">
        <div class="registration-layout">
            <div class="section-heading">
                <p class="eyebrow">Review</p>
                <h2>Leave Event Feedback</h2>
                
                <?php if (!empty($_SESSION['feedback_success'])): ?>
                    <div class="form-alert form-alert-success">
                        <p><?= htmlspecialchars($_SESSION['feedback_success'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                    <?php unset($_SESSION['feedback_success']); ?>
                <?php endif; ?>

                <?php if (!empty($_SESSION['feedback_error'])): ?>
                    <div class="form-alert form-alert-danger">
                        <p><?= htmlspecialchars($_SESSION['feedback_error'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                    <?php unset($_SESSION['feedback_error']); ?>
                <?php endif; ?>
            </div>

            <form class="registration-form" action="../controllers/FeedbackController.php" method="POST">
                <input type="hidden" name="action" value="create">

                <div class="form-alert form-alert-info">
                    <p>Logged in as: <strong><?= htmlspecialchars($user['username'] ?? 'Guest', ENT_QUOTES, 'UTF-8'); ?></strong></p>
                </div>

                <div class="form-grid">
                    <div class="form-group form-group-wide">
                        <label for="event_id">Select Event:</label>
                        <select name="event_id" id="event_id" required>
                            <option value="" disabled selected>-- Choose an event you attended --</option>
                            <?php foreach ($events as $event) : ?>
                                <?php $eData = (array)$event; ?>
                                <option value="<?= $eData['id']; ?>">
                                    <?= htmlspecialchars($eData['name'] ?? $eData['event_name'] ?? 'Unnamed Event', ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group form-group-wide">
                        <label>Your Rating:</label>
                        <div class="star-rating-group" style="display: flex; gap: 1rem; margin-top: 0.5rem;">
                            <label for="star1"><input type="radio" name="rating" id="star1" value="1" required> ⭐</label>
                            <label for="star2"><input type="radio" name="rating" id="star2" value="2"> ⭐⭐</label>
                            <label for="star3"><input type="radio" name="rating" id="star3" value="3"> ⭐⭐⭐</label>
                            <label for="star4"><input type="radio" name="rating" id="star4" value="4"> ⭐⭐⭐⭐</label>
                            <label for="star5"><input type="radio" name="rating" id="star5" value="5"> ⭐⭐⭐⭐⭐</label>
                        </div>
                    </div>

                    <div class="form-group form-group-wide">
                        <label for="comment">Your Comment:</label>
                        <textarea class="preferencebox" id="comment" name="comment" placeholder="Tell us how the event went..." required></textarea>
                    </div>
                </div>

                <button class="primary-button submit-button" type="submit" name="submit_feedback">Submit Feedback</button>
            </form>

            <div class="comments-display-container" style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #eee;">
                <h3 style="margin-bottom: 1.5rem;">Community Feedback & Reviews</h3>

                <?php if (empty($allComments)) : ?>
                    <p style="color: #666; font-style: italic;">No feedback has been submitted yet. Be the first!</p>
                <?php else : ?>
                    <div class="comments-list" style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <?php foreach ($allComments as $commentItem) : ?>
                            <div class="comment-card" style="background: #f9f9f9; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #4CAF50;">
                                
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                                    <div>
                                        <strong style="display: block; font-size: 1.1rem;">
                                            <?= htmlspecialchars($commentItem['username'] ?? 'Guest', ENT_QUOTES, 'UTF-8'); ?>
                                        </strong>
                                        
                                        <span class="event-tag" style="display: inline-block; background: #e0f2f1; color: #004d40; font-size: 0.75rem; font-weight: bold; padding: 0.2rem 0.6rem; border-radius: 4px; margin-top: 0.25rem;">
                                            📌 Event: <?= htmlspecialchars($commentItem['event_name'] ?? 'Unknown Event', ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </div>
                                    
                                    <span style="color: #FFD700;">
                                        <?= str_repeat('⭐', (int)$commentItem['rating']); ?>
                                    </span>
                                </div>

                                <p style="margin: 1rem 0 0.5rem 0; color: #333; line-height: 1.5;">
                                    <?= nl2br(htmlspecialchars($commentItem['comment'], ENT_QUOTES, 'UTF-8')); ?>
                                </p>

                                <div style="margin-top: 1rem; display: flex; justify-content: flex-end; gap: 0.5rem; border-top: 1px solid #eee; padding-top: 0.5rem;">
                                    <form action="../controllers/FeedbackController.php" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this comment?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="feedback_id" value="<?= $commentItem['id']; ?>">
                                        <button type="submit" style="background: none; border: none; color: #e74c3c; cursor: pointer; font-size: 0.85rem; padding: 0;">
                                            Delete Comment
                                        </button>
                                    </form>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            </div>
    </section>

</body>
</html>