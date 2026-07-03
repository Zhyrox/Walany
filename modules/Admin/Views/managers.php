<?php
// modules/Admin/Views/managers.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'System Manager') {
    header("Location: /PHP_Project/Walany/index.php?module=Auth&action=login");
    exit;
}

require_once __DIR__ . '/../../../core/Database.php';
try {
    $db = (new Database())->getConnection();
    $stmt = $db->query("SELECT id, first_name, last_name, email, created_at FROM `walania_managers` ORDER BY id DESC");
    $managers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Data loading fault: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Manager Administration Panel</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f4f6f9; padding: 30px;">

    <h2>🛡️ System Manager Management</h2>
    <hr>

    <?php if (isset($_GET['status'])): ?>
        <div style="padding: 12px; margin-bottom: 20px; background: <?= $_GET['status'] === 'success' ? '#d4edda; color: #155724;' : '#f8d7da; color: #721c24;' ?>; border-radius: 4px;">
            <?= htmlspecialchars($_GET['message'] ?? '') ?>
        </div>
    <?php endif; ?>

    <div style="display: flex; gap: 30px;">
        <div style="flex: 1; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 id="form-title">Create New Administrative Account</h3>
            
            <form id="managerForm" action="/PHP_Project/Walany/index.php?module=Admin&action=create_manager" method="POST">
                <input type="hidden" id="manager_id" name="manager_id" value="">
                
                <div style="margin-bottom: 15px;">
                    <label>First Name</label>
                    <input type="text" id="first_name" name="first_name" required style="width: 100%; padding: 8px; margin-top: 5px;">
                </div>
                
                <div style="margin-bottom: 15px;">
                    <label>Last Name</label>
                    <input type="text" id="last_name" name="last_name" required style="width: 100%; padding: 8px; margin-top: 5px;">
                </div>
                
                <div style="margin-bottom: 15px;">
                    <label>Email Address</label>
                    <input type="email" id="email" name="email" required style="width: 100%; padding: 8px; margin-top: 5px;">
                </div>
                
                <div style="margin-bottom: 15px;">
                    <label id="pass-label">Password</label>
                    <input type="password" id="password" name="password" required style="width: 100%; padding: 8px; margin-top: 5px;">
                    <small id="pass-help" style="color: #666; display:none;">Leave blank to preserve current credentials.</small>
                </div>
                
                <button type="submit" id="submitBtn" style="background: #28a745; color: #fff; border: 0; padding: 10px 15px; border-radius: 4px; cursor: pointer;">
                    Provision Manager
                </button>
                <button type="button" id="cancelBtn" onclick="resetFormState()" style="display: none; background: #6c757d; color: #fff; border: 0; padding: 10px 15px; border-radius: 4px; cursor: pointer; margin-left: 5px;">
                    Cancel Edit
                </button>
            </form>
        </div>

        <div style="flex: 2; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3>Active Managers Stack</h3>
            <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: #e9ecef;">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Provisioned Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($managers as $mgr): ?>
                    <tr>
                        <td><?= $mgr['id'] ?></td>
                        <td><?= htmlspecialchars($mgr['first_name'] . ' ' . $mgr['last_name']) ?></td>
                        <td><?= htmlspecialchars($mgr['email']) ?></td>
                        <td><?= $mgr['created_at'] ?></td>
                        <td>
                            <button onclick="populateEditForm(<?= htmlspecialchars(json_encode($mgr)) ?>)" style="background: #007bff; color: white; border:0; padding: 5px 10px; border-radius:3px; cursor:pointer;">
                                Modify Profile
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function populateEditForm(manager) {
            document.getElementById('form-title').innerText = "Modify Administrative Account #" + manager.id;
            document.getElementById('manager_id').value = manager.id;
            document.getElementById('first_name').value = manager.first_name;
            document.getElementById('last_name').value = manager.last_name;
            document.getElementById('email').value = manager.email;
            
            document.getElementById('password').required = false;
            document.getElementById('pass-help').style.display = 'block';
            document.getElementById('pass-label').innerText = "Change Password (Optional)";
            
            document.getElementById('managerForm').action = "/PHP_Project/Walany/index.php?module=Admin&action=update_manager";
            document.getElementById('submitBtn').innerText = "Update Account Matrix";
            document.getElementById('submitBtn').style.background = "#007bff";
            document.getElementById('cancelBtn').style.display = "inline-block";
        }

        function resetFormState() {
            document.getElementById('form-title').innerText = "Create New Administrative Account";
            document.getElementById('managerForm').reset();
            document.getElementById('manager_id').value = "";
            document.getElementById('password').required = true;
            document.getElementById('pass-help').style.display = 'none';
            document.getElementById('pass-label').innerText = "Password";
            
            document.getElementById('managerForm').action = "/PHP_Project/Walany/index.php?module=Admin&action=create_manager";
            document.getElementById('submitBtn').innerText = "Provision Manager";
            document.getElementById('submitBtn').style.background = "#28a745";
            document.getElementById('cancelBtn').style.display = "none";
        }
    </script>
</body>
</html>