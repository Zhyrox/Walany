<!-- page data -->

<?php
// Include dependencies
require_once __DIR__ . '../../models/Database.php';
require_once __DIR__ . '../../models/EventModel.php';
require_once __DIR__ . '../../models/RegistrantModel.php';
require_once __DIR__ . '../../controllers/PageData.php';

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

?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Manager</title>
    <link rel="icon" type="image/x-icon" href="../images/Walania.svg">
    <link rel="stylesheet" href="../style.css">
</head>
<body class="admin-page event-admin-page">

    <!-- Restricted Access for this specific page -@elmer -->

    <?php
    if (!empty($user['role']) && $user['role'] !== 'admin'){
        header("Location: dashboard.php");
        exit();
    }
    ?>


    <header class="site-header admin-header">
        <!-- I made the logo act like a shortcut to the login page so I can get back in fast. -->
        <a href="dashboard.php" class="logo-placeholder" aria-label="Walania login">
            <img src="images/Walania.svg" alt="Walania logo">
        </a>

        <!-- I kept the top nav simple here so I can jump straight to the form, the list, or log out. -->
        <nav class="main-nav" aria-label="Event navigation">
            <a href="#event-manager">Event Manager</a>
            <a href="#event-list">Event List</a>
            <a href="../controllers/logout.php">Logout</a>
        </nav>
        <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle dark mode">
            <img class="theme-toggle-icon" data-theme-icon src="../images/LightModeIcon.svg" alt="" aria-hidden="true">
        </button>
    </header>

    <main class="admin-section">
        <div class="admin-workspace">
            <!-- I turned the event form into its own card so the page feels cleaner and less cramped. -->
            <section id="event-manager" class="admin-event-manager">
                <div class="admin-section-card">
                    <div class="admin-panel-heading">
                        <h1>Event Manager</h1>
                    </div>
                    <form class="admin-event-form" action="../controllers/eventController.php" method="POST">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="event_date">Date</label>
                                <input type="date" id="event_date" name="event_date" required>
                            </div>
                            <div class="form-group">
                                <label for="location">Location</label>
                                <input type="text" id="location" name="location" required>
                            </div>
                            <div class="form-group form-group-wide">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" required></textarea>
                            </div>
                        </div>
                        <div class="admin-actions">
                            <button class="primary-button submit-button" type="submit" name="add">Register Event</button>
                            <button class="primary-button submit-button" type="button" onclick="exportXml('events')">Export Events</button>
                            <button class="primary-button submit-button" type="button" onclick="startImport('import_events')">Import Events</button>
                        </div>
                    </form>
                    <form id="import-form" action="../controllers/XML.php" method="POST" enctype="multipart/form-data" hidden>
                        <input type="hidden" name="action" id="import-action">
                        <input type="file" id="import-file" name="xml_file" accept=".xml" onchange="if (this.files.length) document.getElementById('import-form').submit();">
                    </form>
                </div>
            </section>

            <section id="events-list" class="admin-panel">
                <div class="admin-section-card">
                    <div class="admin-panel-heading">
                        <h1>Events List</h1>
                    </div>
                    <div class="admin-table-wrap">
                        <div class="admin-table-scroll">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Location</th>
                                    <th>Description</th>
                                    <th>Delete</th>
                                    <th>Update</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($events as $event): ?>
                                    <tr>
                                        <td data-label="Name"><?= htmlspecialchars($event['name']) ?></td>
                                        <td data-label="Date"><?= htmlspecialchars($event['event_date']) ?></td>
                                        <td data-label="Location"><?= htmlspecialchars($event['location']) ?></td>
                                        <td data-label="Description"><?= htmlspecialchars($event['description']) ?></td>
                                        <td>
                                            <button type="button" class="text-button delete-trigger" data-id="<?= $event['id'] ?>" data-name="<?= htmlspecialchars($event['name']) ?>">
                                                Delete
                                            </button>
                                        </td>
                                        <td>
                                            <button class="secondary-button" type="button" onclick="openUpdateModal(
                                                '<?= $event['id'] ?>',
                                                '<?= htmlspecialchars($event['name'], ENT_QUOTES) ?>',
                                                '<?= htmlspecialchars($event['event_date'], ENT_QUOTES) ?>',
                                                '<?= htmlspecialchars($event['location'], ENT_QUOTES) ?>',
                                                '<?= htmlspecialchars($event['description'], ENT_QUOTES) ?>'
                                            )">
                                                Update
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div id="updateModal">
            <div class="admin-edit-form">
                <div class="admin-form-heading">
                    <h2>Update Event</h2>
                </div>
                <form action="../controllers/eventController.php" method="POST">
                    <input type="hidden" name="id" id="updateId">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="updateName">Name</label>
                            <input type="text" name="name" id="updateName" required>
                        </div>
                        <div class="form-group">
                            <label for="updateDate">Date</label>
                            <input type="date" name="date" id="updateDate" required>
                        </div>
                        <div class="form-group">
                            <label for="updateLocation">Location</label>
                            <input type="text" name="location" id="updateLocation" required>
                        </div>
                        <div class="form-group form-group-wide">
                            <label for="updateDescription">Description</label>
                            <textarea name="description" id="updateDescription" required></textarea>
                        </div>
                    </div>
                    <div class="admin-actions">
                        <button class="primary-button submit-button" type="submit" name="update">Save Changes</button>
                        <button class="secondary-button" type="button" onclick="closeModal()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <div id="confirmPanel" class="confirm-panel" aria-hidden="true">
        <div class="confirm-dialog" role="dialog" aria-modal="true" aria-labelledby="confirmTitle">
            <h3 id="confirmTitle">Are you sure?</h3>
            <p>You are about to delete: <strong id="deleteEventName"></strong></p>

            <form action="../controllers/eventController.php" method="POST">
                <input type="hidden" name="id" id="modalEventId" value="">
                <div class="confirm-actions">
                    <button type="button" id="cancelBtn" class="secondary-button confirm-cancel">Cancel</button>
                    <button type="submit" name="delete" class="confirm-danger">Yes, Delete It</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../script.js"></script>
</body>
</html>
