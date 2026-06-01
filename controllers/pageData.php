<?php
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/eventModel.php';
require_once __DIR__ . '/../models/registrantModel.php';
require_once __DIR__ . '/../models/userModel.php';

class PageDataController{

    private $db;
    private $eventModel;
    private $registrantModel;

    public function __construct($dbConnection){
        $this->db = $dbConnection;

        $this->eventModel = new eventModel($this->db);
        $this->registrantModel = new registrantModel($this->db);
    }

    public function getPageData(): array
    {
        //former ensure session started function
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        //former require login
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }

        //former current user function
        $user = null;
        if(isset($_SESSION['user_id'])){
            $user = [
                'id'        => (int)$_SESSION['user_id'],
                'username'  => $_SESSION['username'] ?? '',
                'role'      => strtolower((string)($_SESSION['role'] ?? ''))
            ];
        }

        // Fetch data safely through our pre-initialized OOP models
        $events = $this->eventModel->getAllEvents() ?? [];
        $totalEvents = count($events);
        $eventsMessage = $totalEvents === 0 ? 'No events available yet.' : null;

        $registrants = $this->registrantModel->getAllRegistrants() ?? [];
        $totalRegistrants = count($registrants);

        return[
            'user'               => $user,
            'events'             => $events,
            'totalEvents'        => $totalEvents,
            'eventsMessage'      => $eventsMessage,
            'registrants'        => $registrants,
            'totalRegistrants'   => $totalRegistrants,
            'registrationStatus' => null,
            'registrationErrors' => []
        ];
    }
}
?>