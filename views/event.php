<?php
require_once "../models/eventModel.php";
$events = getAllEvents();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <h1>Event Creation</h1>
    <form action="../controllers/eventController.php" method="POST">

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="event_date">Date:</label>
        <input type="date" id="event_date" name="event_date" required>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <button type="submit" name="add">Register Event</button>
    </form>

    <h1>Events List</h1>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Date</th>
            <th>Location</th>
            <th>Description</th>
            <th>Delete</th>
            <th>Update</th>
        </tr>
        <?php foreach ($events as $event): ?>
        <tr>
            <td><?= htmlspecialchars($event['name']) ?></td>
            <td><?= htmlspecialchars($event['event_date']) ?></td>
            <td><?= htmlspecialchars($event['location']) ?></td>
            <td><?= htmlspecialchars($event['description']) ?></td>
            <td>
                <a href="../controllers/eventController.php?delete=<?= $event['id'] ?>">Delete</a>
                <!-- Add edit link if needed -->
            </td>
            <td>
                <button onclick="openUpdateModal(
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
    </table>

    <div id="updateModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">

        <div style="background:white; width:400px; padding:20px; margin:100px auto; border-radius:10px;">

            <h2>Update Event</h2>

            <form action="../controllers/eventController.php" method="POST">

                <input type="hidden" name="id" id="updateId">

                <label>Name:</label>
                <input type="text" name="name" id="updateName" required>

                <br><br>

                <label>Date:</label>
                <input type="date" name="date" id="updateDate" required>

                <br><br>

                <label>Location:</label>
                <input type="text" name="location" id="updateLocation" required>

                <br><br>

                <label>Description:</label>
               <textarea name="description" id="updateDescription" required></textarea>

                <br><br>

                <button type="submit" name="update">Save Changes</button>

                <button type="button" onclick="closeModal()">Cancel</button>

           </form>
        </div>
    </div>

    <script>
        function openUpdateModal(id, name, date, location, description) {

            document.getElementById("updateId").value = id;
            document.getElementById("updateName").value = name;
            document.getElementById("updateDate").value = date;
            document.getElementById("updateLocation").value = location;
            document.getElementById("updateDescription").value = description;

            document.getElementById("updateModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("updateModal").style.display = "none";
    }
    </script>
</body>
</html>