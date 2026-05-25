<?php
require_once "../models/eventModel.php";
$events = getAllEvents();
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
                                            <a class="text-button" href="../controllers/eventController.php?delete=<?= $event['id'] ?>">Delete</a>
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
</body>
</html>