<?php
require_once "../models/EventModel.php";
require_once "../models/Database.php";

$database = new Database();
$dbConnection = $database->getConnection();

$eventModel = new EventModel($dbConnection);
$events = $eventModel->getAllEvents();
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Manager</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="admin-page event-admin-page">
    <header class="site-header admin-header">
        <!-- I made the logo act like a shortcut to the login page so I can get back in fast. -->
        <a href="login.php" class="logo-placeholder" aria-label="Walania login">
            <img src="images/Walania.svg" alt="Walania logo">
        </a>

        <!-- I kept the top nav simple here so I can jump straight to the form, the list, or log out. -->
        <nav class="main-nav" aria-label="Event navigation">
            <a href="#event-manager">Event Manager</a>
            <a href="#event-list">Event List</a>
            <a href="../controllers/logout.php">Logout</a>
        </nav>
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
                        </div>
                    </form>
                </div>
            </section>

            <!-- I separated the list into another card so the actions stay easy to scan. -->
            <section id="event-list" class="admin-panel">
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
                                        <td><?= htmlspecialchars($event['name']) ?></td>
                                        <td><?= htmlspecialchars($event['event_date']) ?></td>
                                        <td><?= htmlspecialchars($event['location']) ?></td>
                                        <td><?= htmlspecialchars($event['description']) ?></td>
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
                    <div class="admin-actions export-button-row">
                        <a class="export-button" href="../controllers/exportXML.php">Export XML</a>
                    </div>
                </div>
            </section>
        </div>

    <!-- I use this overlay so delete actions feel deliberate instead of instant. -->
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

    <!-- I keep the update form in a modal so editing doesn't push me away from the table. -->
    <div id="updateModal" class="update-modal">
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

    <script>
        function openUpdateModal(id, name, date, location, description) {
            // I copy the row data into the modal so I can edit the same event without retyping it.
            document.getElementById("updateId").value = id;
            document.getElementById("updateName").value = name;
            document.getElementById("updateDate").value = date;
            document.getElementById("updateLocation").value = location;
            document.getElementById("updateDescription").value = description;
            document.getElementById("updateModal").classList.add('active');
        }

        function closeModal() {
            document.getElementById("updateModal").classList.remove('active');
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // I wired the delete buttons to a shared confirm overlay so the table stays uncluttered.
            const confirmPanel = document.getElementById('confirmPanel');
            const modalEventId = document.getElementById('modalEventId');
            const deleteEventName = document.getElementById('deleteEventName');
            const cancelBtn = document.getElementById('cancelBtn');

            document.querySelectorAll('.delete-trigger').forEach(button => {
                button.addEventListener('click', function() {
                    const eventId = this.getAttribute('data-id');
                    const eventName = this.getAttribute('data-name');

                    modalEventId.value = eventId;
                    deleteEventName.textContent = eventName;
                    confirmPanel.classList.add('active');
                    confirmPanel.setAttribute('aria-hidden', 'false');
                });
            });

            cancelBtn.addEventListener('click', function() {
                confirmPanel.classList.remove('active');
                confirmPanel.setAttribute('aria-hidden', 'true');
            });
        });
</script>
</body>
</html>
