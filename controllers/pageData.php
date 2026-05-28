<?php
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/eventModel.php';
require_once __DIR__ . '/../models/registrantModel.php';
require_once __DIR__ . '/../models/userModel.php';

function ensure_session_started(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function require_login_redirect(string $url = 'login.php'): void
{
    ensure_session_started();
    if (!isset($_SESSION['user_id'])) {
        header("Location: $url");
        exit();
    }
}

function current_user(): ?array
{
    ensure_session_started();
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    return [
        'id'       => (int)($_SESSION['user_id'] ?? 0),
        'username' => $_SESSION['username'] ?? '',
        'role'     => strtolower((string)($_SESSION['role'] ?? ''))
    ];
}

/**
 * Fetch events (works with class-based model or procedural wrappers).
 */
function fetch_events(): array
{
    try {
        // prefer class EventModel
        if (class_exists('EventModel')) {
            // support both ctor styles (with or without DB connection)
            if (class_exists('Database')) {
                $db = new Database();
                $conn = method_exists($db, 'getConnection') ? $db->getConnection() : null;
            } else {
                $conn = null;
            }

            if ($conn !== null) {
                $m = new EventModel($conn);
            } else {
                $m = new EventModel();
            }

            if (method_exists($m, 'getAllEvents')) {
                $rows = $m->getAllEvents();
                return is_array($rows) ? $rows : [];
            }
        }

        // fallback procedural function
        if (function_exists('getAllEvents')) {
            $rows = getAllEvents();
            return is_array($rows) ? $rows : [];
        }

        return [];
    } catch (Throwable $e) {
        error_log('fetch_events error: ' . $e->getMessage());
        return [];
    }
}

/**
 * Fetch registrants (works with class-based model or procedural wrappers).
 */
function fetch_registrants(): array
{
    try {
        if (class_exists('RegistrantModel')) {
            if (class_exists('Database')) {
                $db = new Database();
                $conn = method_exists($db, 'getConnection') ? $db->getConnection() : null;
            } else {
                $conn = null;
            }

            if ($conn !== null) {
                $m = new RegistrantModel($conn);
            } else {
                $m = new RegistrantModel();
            }

            if (method_exists($m, 'getAll')) {
                $rows = $m->getAll();
                return is_array($rows) ? $rows : [];
            }

            if (method_exists($m, 'getAllRegistrants')) {
                $rows = $m->getAllRegistrants();
                return is_array($rows) ? $rows : [];
            }
        }

        if (function_exists('getAllRegistrants')) {
            $rows = getAllRegistrants();
            return is_array($rows) ? $rows : [];
        }

        return [];
    } catch (Throwable $e) {
        error_log('fetch_registrants error: ' . $e->getMessage());
        return [];
    }
}

/**
 * Build and return page data array (call from views).
 * keep logic same as before: user, events, registrants, counts, messages.
 */
function get_page_data(): array
{
    ensure_session_started();

    $user = current_user();

    // fetch events & registrants
    $events = fetch_events();
    $totalEvents = count($events);
    $eventsMessage = $totalEvents === 0 ? 'No events available yet.' : null;

    $registrants = fetch_registrants();
    $totalRegistrants = count($registrants);

    return [
        'user' => $user,
        'events' => $events,
        'totalEvents' => $totalEvents,
        'eventsMessage' => $eventsMessage,
        'registrants' => $registrants,
        'totalRegistrants' => $totalRegistrants,
        'registrationStatus' => null,
        'registrationErrors' => []
    ];
}
?>