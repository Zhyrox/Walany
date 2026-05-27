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
<body class="admin-page">
    <main class="admin-section">
        <div class="admin-workspace">
            <section class="admin-event-manager">
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

            <section class="admin-panel">
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
                                            




                                        <button type="button" class="text-button delete-trigger" data-id="<?= $event['id'] ?>" data-name="<?= htmlspecialchars($event['name']) ?>" style="background: none; border: none; padding: 0; color: red; cursor: pointer; font: inherit;">
                                            Delete
                                        </button>


                                        <div id="confirmPanel" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 999;">
                                            <div style="background: white; padding: 20px; border-radius: 8px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                                <h3>Are you sure?</h3>
                                                <p>You are about to delete: <strong id="deleteEventName"></strong></p>
                                                
                                                <form action="../controllers/eventController.php" method="POST">
                                                    <input type="hidden" name="id" id="modalEventId" value="">
                                                    <button type="button" id="cancelBtn" style="margin-right: 10px; padding: 8px 16px;">Cancel</button>
                                                    <button type="submit" name="delete" style="background: red; color: white; border: none; padding: 8px 16px; cursor: pointer; border-radius: 4px;">Yes, Delete It</button>
                                                </form>
                                            </div>
                                        </div>





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

    <script>
        function openUpdateModal(id, name, date, location, description) {
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
                    confirmPanel.style.display = 'flex';
                });
            });

            cancelBtn.addEventListener('click', function() {
                confirmPanel.style.display = 'none';
            });
        });
</script>
</body>
</html>