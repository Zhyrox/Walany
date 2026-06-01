<?php
// 1. ALWAYS start the session first if you are tracking success/error alerts
session_start();

// 2. FIXED: Added the missing forward slashes '/' right after __DIR__
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/FeedbackModel.php';

// Guard check: Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../views/FeedbackForm.php#feedback-submission");
    exit();
}

// 3. Core Initializations
$database = new Database();
$dbConn = $database->getConnection();
$feedbackModel = new FeedbackModel($dbConn);

// 4. Identify the incoming form action (defaults to 'create' if not specified)
$action = $_POST['action'] ?? 'create';

switch ($action) {
    
    // ACTION: CREATE NEW FEEDBACK
    case 'create':
        $comment = trim($_POST['comment'] ?? '');
        $rating  = (int)($_POST['rating'] ?? 0);
        $eventId = (int)($_POST['event_id'] ?? 0);
        
        if (empty($comment) || $rating < 1 || $rating > 5 || $eventId <= 0) {
            $_SESSION['feedback_error'] = "All fields are required. Please provide a valid rating and comment.";
            header("Location: ../views/FeedbackForm.php#feedback-submission");
            exit();
        }

        $saveData = [
            'user_id'  => !empty($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null,
            'event_id' => $eventId,
            'comment'  => htmlspecialchars($comment, ENT_QUOTES, 'UTF-8'),
            'rating'   => $rating
        ];

        if ($feedbackModel->saveFeedback($saveData)) {
            $_SESSION['feedback_success'] = "Thank you for submitting your event feedback!";
        } else {
            $_SESSION['feedback_error'] = "Something went wrong saving your feedback.";
        }
        break;

    // ACTION: UPDATE EXISTING FEEDBACK
    case 'edit':
        $feedbackId = (int)($_POST['feedback_id'] ?? 0);
        $comment    = trim($_POST['comment'] ?? '');
        $rating     = (int)($_POST['rating'] ?? 0);
        
        if ($feedbackId > 0 && !empty($comment) && $rating >= 1 && $rating <= 5) {
            $updateData = [
                'id'      => $feedbackId,
                'comment' => htmlspecialchars($comment, ENT_QUOTES, 'UTF-8'),
                'rating'  => $rating
            ];
            
            if ($feedbackModel->updateFeedback($updateData)) {
                $_SESSION['feedback_success'] = "Comment updated successfully!";
            } else {
                $_SESSION['feedback_error'] = "Failed to update comment.";
            }
        } else {
            $_SESSION['feedback_error'] = "Invalid input values.";
        }
        break;

    // ACTION: DELETE FEEDBACK
    case 'delete':
        $feedbackId = (int)($_POST['feedback_id'] ?? 0);
        if ($feedbackId > 0 && $feedbackModel->deleteFeedback($feedbackId)) {
            $_SESSION['feedback_success'] = "Feedback deleted successfully.";
        } else {
            $_SESSION['feedback_error'] = "Failed to delete feedback.";
        }
        break;
}

// Back to feedback page
header("Location: ../views/FeedbackForm.php#feedback-submission");
exit();
?>