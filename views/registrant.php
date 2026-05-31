<?php

require_once __DIR__ . '../../models/Database.php';
require_once __DIR__ . '../../models/EventModel.php';
require_once __DIR__ . '../../models/RegistrantModel.php';
require_once __DIR__ . '../../controllers/PageData.php';
require_once __DIR__ . '../../models/attendanceModel.php';


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
    <title>Registrant Manager</title>
    <link rel="icon" type="image/x-icon" href="../images/Walania.svg">
    <link rel="stylesheet" href="../style.css?v=registrant-actions-small">
</head>
<body class="admin-page event-admin-page registrant-admin-page">

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
        <nav class="main-nav" aria-label="Registrant navigation">
            <a href="#registrant-manager">Registrant Manager</a>
            <a href="#registrant-list">Registrant List</a>
            <a href="../controllers/logout.php">Logout</a>
        </nav>
        <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle dark mode">
            <img class="theme-toggle-icon" data-theme-icon src="../images/LightModeIcon.svg" alt="" aria-hidden="true">
        </button>
    </header>

    <main class="admin-section">
        <div class="admin-workspace">
            <!-- I turned the event form into its own card so the page feels cleaner and less cramped. -->
            <section id="registrant-manager" class="admin-event-manager admin-registrant-manager">
                <div class="admin-section-card">
                    <div class="admin-panel-heading">
                        <h1>Registrant Manager</h1>
                    </div>
                    <form id="registrantForm" class="admin-event-form" action="../controllers/registrantController.php" method="POST">
                        <input type="hidden" name="id" id="registrantId" value="">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" id="fullname" name="fullname" required>
                            </div>
                            <div class="form-group">
                                <label for="event_date">Age</label>
                                <input type="text" id="age" name="age" required>
                            </div>
                            <div class="form-group">
                                <label for="location">Email</label>
                                <input type="text" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="contact_number">Contact Number</label>
                                <input type="text" id="contact_number" name="contact_number" required>
                            </div>
                            <div class="form-group form-group-wide">
                                <label for="preference_allergy">Preferences / Allergies</label>
                                <textarea class="preferencebox" id="preference_allergy" name="preference_allergy"></textarea>
                            </div>
                            <div class="form-group form-group-wide">
                                <label for="event_id">Select Event</label>
                                <select name="event_id" id="event_id" required>
                                    <?php foreach ($events as $event): ?>
                                        <option value="<?= $event['id'] ?>"><?= htmlspecialchars($event['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="admin-actions">
                            <button class="primary-button submit-button" type="submit" name="add">Add Registrant</button>
                            <button class="secondary-button submit-button" type="submit" name="update" id="updateRegistrantButton" disabled>Update Registrant</button>
                            <button class="secondary-button submit-button" type="button" id="cancelRegistrantUpdate" onclick="resetRegistrantForm()" hidden>Cancel</button>
                        </div>
                    </form>
                    <form id="import-form" action="../controllers/XML.php" method="POST" enctype="multipart/form-data" hidden>
                        <input type="hidden" name="action" id="import-action">
                        <input type="file" id="import-file" name="xml_file" accept=".xml" onchange="if (this.files.length) document.getElementById('import-form').submit();">
                    </form>
                </div>
            </section>

            <section id="registrant-list" class="admin-panel">
                <div class="admin-section-card">
                    <div class="admin-panel-heading">
                        <h1>Registrant List</h1>
                    </div>
                    <div class="admin-table-wrap">
                        <table class="admin-table registrant-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Email</th>
                                    <th>Contact #</th>
                                    <th>Preferences</th>
                                    <th>Event</th>
                                    <th>Attendance</th>
                                    <th>Delete</th>
                                    <th>Update</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($registrants as $registrant): ?>
                                <tr>
                                    <td data-label="Name"><?= htmlspecialchars($registrant['full_name']) ?></td>
                                    <td data-label="Age"><?= htmlspecialchars($registrant['age']) ?></td>
                                    <td data-label="Email"><?= htmlspecialchars($registrant['email']) ?></td>
                                    <td data-label="Contact"><?= htmlspecialchars($registrant['contact_number']) ?></td>
                                    <td data-label="Preferences"><?= htmlspecialchars($registrant['preference_allergy']) ?></td>
                                    <td data-label="Event"><?= htmlspecialchars($registrant['event_name'] ?? 'N/A') ?></td>
                                    <td data-label="Attendance">
                                        <form action="../controllers/registrantController.php" method="POST">
                                            <input type="hidden" name="registrant_id" value="<?= $registrant['id'] ?>">
                                            <input type="hidden" name="attendance_update" value="1">
                                            <?php $status = strtolower($registrant['attendance_status'] ?? 'n/a'); ?>
                                            <select name="attendance_status" onchange="this.form.submit()">
                                                <option value="N/A" <?= $status === 'n/a' ? 'selected' : '' ?>>N/A</option>
                                                <option value="present" <?= $status === 'present' ? 'selected' : '' ?>>Present</option>
                                                <option value="absent" <?= $status === 'absent' ? 'selected' : '' ?>>Absent</option>
                                                <option value="late" <?= $status === 'late' ? 'selected' : '' ?>>Late</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td data-label="Delete">
                                        <button type="button" class="text-button delete-trigger" data-id="<?= $registrant['id'] ?>" data-name="<?= htmlspecialchars($registrant['full_name']) ?>">
                                            Delete
                                        </button>
                                    </td>
                                    <td data-label="Update">
                                        <button class="secondary-button" type="button" onclick="populateRegistrantManagerForm(
                                            '<?= $registrant['id'] ?>',
                                            '<?= htmlspecialchars($registrant['full_name'], ENT_QUOTES) ?>',
                                            '<?= htmlspecialchars($registrant['age'], ENT_QUOTES) ?>',
                                            '<?= htmlspecialchars($registrant['email'], ENT_QUOTES) ?>',
                                            '<?= htmlspecialchars($registrant['contact_number'], ENT_QUOTES) ?>',
                                            '<?= htmlspecialchars($registrant['preference_allergy'], ENT_QUOTES) ?>',
                                            '<?= htmlspecialchars($registrant['event_id'], ENT_QUOTES) ?>'
                                        )">
                                            Update
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="admin-actions export-button-row">
                        <button class="primary-button export-button" type="button" onclick="exportXml('registrants')">Export Registrants</button>
                        <button class="primary-button export-button" type="button" onclick="startImport('import_registrants')">Import Registrants</button>
                    </div>
                </div>
            </section>
        </div>

    </main>

    <footer class="site-footer">
        <p>&copy; 2026 Walania. All rights reserved.</p>
    </footer>

    <div id="confirmPanel" class="confirm-panel" aria-hidden="true">
        <div class="confirm-dialog" role="dialog" aria-modal="true" aria-labelledby="confirmTitle">
            <h3 id="confirmTitle">Are you sure?</h3>
            <p>You are about to delete: <strong id="deleteEventName"></strong></p>

            <form action="../controllers/registrantController.php" method="POST">
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
