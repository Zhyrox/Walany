<?php
// Author Elmer

class FeedbackModel{
    private $db;

    public function __construct($dbConnection){
        $this->db = $dbConnection;
    }

    public function saveFeedback(array $data): bool
    {
        $stmt = $this->db->prepare("INSERT INTO walania_event_feedback (user_id, event_id, comment, rating) VALUES (:user_id, :event_id, :comment, :rating)");
        return $stmt->execute([
            ':user_id'   => !empty($data['user_id']) ? (int)$data['user_id'] : null,
            ':event_id'  => !empty($data['event_id']) ? (int)$data['event_id'] : null,
            ':comment'   => $data['comment'] ?? '',
            ':rating'    => !empty($data['rating']) ? (int)$data['rating'] : 0
        ]);
    }

    public function getAllComments(){
        $stmt = $this->db->query("SELECT f.*, u.username, e.name AS event_name FROM walania_event_feedback f LEFT JOIN walania_user u ON f.user_id = u.id LEFT JOIN walania_event e ON f.event_id = e.id ORDER BY f.id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateFeedback(array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE walania_event_feedback SET comment = :comment, rating = :rating WHERE id = :id");
        return $stmt->execute([
            ':id' => (int)$data['id'],
            ':comment' => $data['comment'] ?? '',
            ':rating' => !empty($data['rating']) ? (int)$data['rating'] : 0
        ]);
    }

    public function deleteFeedback(int $id) : bool
    {
        $stmt = $this->db->prepare("DELETE FROM walania_event_feedback WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function deleteFeedbackSecure($feedbackId, $userId, $role) {
    //If the user is an admin, let them delete any feedback record
    if ($role === 'admin') {
        $query = "DELETE FROM walania_event_feedback WHERE id = :feedback_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':feedback_id', $feedbackId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    //If standard user, they MUST own the feedback record
    if ($userId === null) {
        return false; // Unauthenticated guest cannot delete anything
    }

    $query = "DELETE FROM walania_event_feedback WHERE id = :feedback_id AND user_id = :user_id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':feedback_id', $feedbackId, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    
    return $stmt->execute();
}
}
?>