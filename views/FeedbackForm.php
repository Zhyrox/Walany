<?php
session_start();

require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/EventModel.php';
require_once __DIR__ . '/../models/RegistrantModel.php';
require_once __DIR__ . '/../controllers/PageData.php';
require_once __DIR__ . '/../models/FeedbackModel.php';

$database = new Database();
$conn = $database->getConnection();

$pageController = new PageDataController($conn);
$data = $pageController->getPageData();

$user = $data['user'];
$events = $data['events'];

$feedbackModel = new FeedbackModel($conn);
$allComments = $feedbackModel->getAllComments();
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Walania | Feedback</title>
    <link rel="icon" type="image/x-icon" href="../images/Walania.svg">
    <link rel="stylesheet" href="../style.css">
</head>
<body class="dashboard-page feedback-page">
    <header class="site-header">
        <a href="dashboard.php" class="logo-placeholder" aria-label="Walania dashboard">
            <img src="../images/Walania.svg" alt="Walania logo">
        </a>

        <p>Welcome, <?= htmlspecialchars($user['username'] ?? 'Guest', ENT_QUOTES, 'UTF-8'); ?>!</p>

        <nav class="main-nav" aria-label="Main navigation">
            <a href="dashboard.php">Dashboard</a>
            <a href="#feedback-submission">Leave Feedback</a>
            <a href="#feedback-reviews">Reviews</a>
            <?php if (!empty($user)) : ?>
                <a href="../controllers/logout.php">Logout</a>
            <?php else : ?>
                <a href="login.php">Admin</a>
            <?php endif; ?>
        </nav>

        <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle dark mode">
            <img class="theme-toggle-icon" data-theme-icon src="../images/LightModeIcon.svg" alt="" aria-hidden="true">
        </button>
    </header>

    <main>
        <section id="feedback-submission" class="feedback-section registration-section">
            <div class="registration-layout feedback-layout">
                <div class="section-heading">
                    <p class="eyebrow">Review</p>
                    <h1>Leave Event Feedback</h1>
                    <div class="contact-copy">
                        <p>Tell us how the event went so we can keep improving the experience for everyone.</p>
                    </div>

                    <?php if (!empty($_SESSION['feedback_success'])): ?>
                        <div class="form-alert form-alert-success">
                            <p><?= htmlspecialchars($_SESSION['feedback_success'], ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                        <?php unset($_SESSION['feedback_success']); ?>
                    <?php endif; ?>

                    <?php if (!empty($_SESSION['feedback_error'])): ?>
                        <div class="form-alert form-alert-error">
                            <p><?= htmlspecialchars($_SESSION['feedback_error'], ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                        <?php unset($_SESSION['feedback_error']); ?>
                    <?php endif; ?>
                </div>

                <form class="registration-form feedback-form" action="../controllers/FeedbackController.php" method="POST">
                    <input type="hidden" name="action" value="create">

                    <div class="form-alert form-alert-info">
                        <p>Logged in as: <strong><?= htmlspecialchars($user['username'] ?? 'Guest', ENT_QUOTES, 'UTF-8'); ?></strong></p>
                    </div>

                    <div class="form-grid">
                        <div class="form-group form-group-wide">
                            <label for="event_id">Select Event</label>
                            <select name="event_id" id="event_id" required>
                                <option value="" disabled selected>Choose an event you attended</option>
                                <?php foreach ($events as $event) : ?>
                                    <?php $eventData = (array) $event; ?>
                                    <option value="<?= (int)($eventData['id'] ?? 0); ?>">
                                        <?= htmlspecialchars($eventData['name'] ?? $eventData['event_name'] ?? 'Unnamed Event', ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group form-group-wide">
                            <label>Your Rating</label>
                            <div class="star-rating-group">
                                <label for="star1"><input type="radio" name="rating" id="star1" value="1" required> <span aria-hidden="true">&#9733;</span><span>1</span></label>
                                <label for="star2"><input type="radio" name="rating" id="star2" value="2"> <span aria-hidden="true">&#9733;&#9733;</span><span>2</span></label>
                                <label for="star3"><input type="radio" name="rating" id="star3" value="3"> <span aria-hidden="true">&#9733;&#9733;&#9733;</span><span>3</span></label>
                                <label for="star4"><input type="radio" name="rating" id="star4" value="4"> <span aria-hidden="true">&#9733;&#9733;&#9733;&#9733;</span><span>4</span></label>
                                <label for="star5"><input type="radio" name="rating" id="star5" value="5"> <span aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9733;</span><span>5</span></label>
                            </div>
                        </div>

                        <div class="form-group form-group-wide">
                            <label for="comment">Your Comment</label>
                            <textarea class="preferencebox" id="comment" name="comment" placeholder="Tell us how the event went..." required></textarea>
                        </div>
                    </div>

                    <button class="primary-button submit-button" type="submit" name="submit_feedback">Submit Feedback</button>
                </form>
            </div>
        </section>

        <section id="feedback-reviews" class="feedback-reviews-section">
            <div class="section-heading feedback-reviews-heading">
                <p class="eyebrow">Community</p>
                <h2>Feedback & Reviews</h2>
                <p>Recent comments from attendees are shown below.</p>
            </div>

            <div class="feedback-reviews-grid">
                <?php if (empty($allComments)) : ?>
                    <div class="feedback-empty-state">
                        No feedback has been submitted yet. Be the first to share a review.
                    </div>
                <?php else : ?>
                    <?php foreach ($allComments as $commentItem) : ?>
                        <article class="feedback-card">
                            <div class="feedback-card-header">
                                <div>
                                    <strong><?= htmlspecialchars($commentItem['username'] ?? 'Guest', ENT_QUOTES, 'UTF-8'); ?></strong>
                                    <span class="feedback-event-tag">Event: <?= htmlspecialchars($commentItem['event_name'] ?? 'Unknown Event', ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                                <div class="feedback-rating" aria-label="Rating <?= (int)($commentItem['rating'] ?? 0); ?> out of 5">
                                    <?= str_repeat('&#9733;', (int)($commentItem['rating'] ?? 0)); ?>
                                </div>
                            </div>

                            <p class="feedback-comment">
                                <?= nl2br(htmlspecialchars($commentItem['comment'] ?? '', ENT_QUOTES, 'UTF-8')); ?>
                            </p>

                            <div class="feedback-card-actions">
                                <?php
                                    $isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
                                    $isOwner = (isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] === (int)$commentItem['user_id']);
                                    
                                    if ($isAdmin || $isOwner) : 
                                    ?>
                                        <form action="../controllers/FeedbackController.php" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this comment?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="feedback_id" value="<?= $commentItem['id']; ?>">
                                            <button type="submit" style="background: none; border: none; color: #e74c3c; cursor: pointer; font-size: 0.85rem; padding: 0;">
                                                Delete Comment
                                            </button>
                                        </form>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 Walania. All rights reserved.</p>
    </footer>

    <script src="../script.js"></script>
</body>
</html>
