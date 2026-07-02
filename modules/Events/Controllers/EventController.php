<?php
class EventController {

    public function handleEvaluation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['status' => 'error', 'message' => 'Invalid request delivery context.'];
        }

        // Clean user input metrics
        $eventId      = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;
        $referenceId  = isset($_POST['reference_id']) ? strtoupper(trim($_POST['reference_id'])) : '';
        $ratingMetric = isset($_POST['rating_metric']) ? intval($_POST['rating_metric']) : 0;
        $feedbackText = isset($_POST['feedback_text']) ? trim($_POST['feedback_text']) : '';

        // Basic formatting checks
        if (!preg_match('/^[A-Z]{4}-\d{4}$/', $referenceId)) {
            return ['status' => 'validation_failed', 'message' => 'Reference ID format must match ABCD-1234 pattern.'];
        }

        if ($ratingMetric < 1 || $ratingMetric > 5) {
            return ['status' => 'validation_failed', 'message' => 'Please choose a valid rating score metric.'];
        }

        if (empty($feedbackText)) {
            return ['status' => 'validation_failed', 'message' => 'Feedback content can not be blank.'];
        }

        $feedbackText = htmlspecialchars($feedbackText, ENT_QUOTES, 'UTF-8');

        require_once __DIR__ . '/../Models/EventFeedbackModel.php';
        $model = new EventFeedback();

        $saveSuccess = $model->saveFeedback([
            'event_id'     => $eventId,
            'reference_id' => $referenceId,
            'comment'      => $feedbackText,
            'rating'       => $ratingMetric
        ]);

        if ($saveSuccess) {
            return [
                'status' => 'success',
                'message' => 'Thank you! Your event feedback has been submitted successfully.'
            ];
        } else {
            return [
                'status' => 'database_error',
                'message' => 'Submission failed. Please confirm your Tracking Reference ID matches this specific event.'
            ];
        }
    }
}