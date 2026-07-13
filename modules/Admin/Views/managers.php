<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /PHP_Project/Walany/index.php?module=Auth&action=login");
    exit;
}

$currentAdminId = intval($_SESSION['user_id'] ?? 0);

require_once __DIR__ . '/../../../core/Database.php';
try {
    $db = (new Database())->getConnection();
    
    // Fetch all accounts
    $stmt = $db->query("SELECT id, first_name, last_name, email, role, temp_password, forgot_request, created_at FROM `walania_managers` ORDER BY id DESC");
    $managers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch access logs
    $logStmt = $db->query("SELECT id, actor_name, action_details, ip_address, logged_at FROM `walania_system_access_logs` ORDER BY id DESC LIMIT 25");
    $logs = $logStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // 1. Log the absolute descriptive raw traceback details to XAMPP error logs for the server administrator
    error_log("CRITICAL SYSTEM INTEGRITY FAULT: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());

    // 2. Safely redirect the user to the generic error container view without leaking structure schemas
    header("Location: /PHP_Project/Walany/index.php?module=Admin&action=system_error&message=" . urlencode("Database connectivity or operational schema fault."));
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Admin Control Panel</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f4f6f9; padding: 30px;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h2>System Environment Administration Matrix</h2>
        <!-- NAVIGATION TO SELF-SERVICE SETTINGS -->
        <a href="/PHP_Project/Walany/index.php?module=Admin&action=profile-settings" style="background: #6c757d; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 0.9em;">
            My Profile Settings
        </a>
        <a href="/PHP_Project/Walany/index.php?module=Auth&action=login" 
        onclick="return confirm('Are you sure you want to log out of the system?');" 
        style="background: #dc3545; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 0.9em; display: inline-block;">
            Logout
        </a>
    </div>
    <hr style="margin-bottom: 25px;">

    <?php if (isset($_GET['status'])): ?>
        <div style="padding: 12px; margin-bottom: 20px; background: <?= $_GET['status'] === 'success' ? '#d4edda; color: #155724;' : '#f8d7da; color: #721c24;' ?>; border-radius: 4px;">
            <?= htmlspecialchars($_GET['message'] ?? '') ?>
        </div>
    <?php endif; ?>

    <div style="display: flex; gap: 30px; margin-bottom: 30px;">
        <!-- PROVISIONING INTERFACE WORKSPACE FORM -->
        <div style="flex: 1; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); height: max-content;">
            <h3 style="margin-top:0;">Create New Environment Account</h3>
            <p style="font-size:0.8em; color:#6c757d; margin-top:-10px; margin-bottom:20px;">
                Provision system workspace execution privileges for Planners, Registrars, or new Administrators.
            </p>
            <form id="managerForm" action="/PHP_Project/Walany/index.php?module=Admin&action=create_manager" method="POST">
                
                <div style="margin-bottom: 15px;">
                    <label style="font-size: 0.9em; font-weight: bold;">First Name</label>
                    <input type="text" id="first_name" name="first_name" required style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="font-size: 0.9em; font-weight: bold;">Last Name</label>
                    <input type="text" id="last_name" name="last_name" required style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="font-size: 0.9em; font-weight: bold;">Email Address</label>
                    <input type="email" id="email" name="email" required style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="font-size: 0.9em; font-weight: bold;">Environment Access Role</label>
                    <select id="role" name="role" required style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
                        <option value="planner">Planner</option>
                        <option value="registrar">Registrar</option>
                        <option value="admin">System Administrator (Admin)</option>
                    </select>
                </div>
                
                <button type="submit" style="background: #28a745; color: #fff; border: 0; padding: 10px 15px; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%;">
                    Provision System Account
                </button>
            </form>
        </div>

        <!-- COORDINATORS LIST STACK DISPLAY -->
        <div style="flex: 2; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow-x: auto;">
            <h3 style="margin-top:0;">Active Managers Stack</h3>
            <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: #e9ecef;">
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Temporary Key Password</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($managers as $mgr): ?>
                    <?php 
                        $isSelf = (intval($mgr['id']) === $currentAdminId); 
                        $hasForgotRequest = (intval($mgr['forgot_request'] ?? 0) === 1);
                    ?>
                    <tr style="<?= $isSelf ? 'background: #e3f2fd; font-weight: 500;' : '' ?>">
                        <td>
                            <?= htmlspecialchars($mgr['first_name'] . ' ' . $mgr['last_name']) ?>
                            <?= $isSelf ? ' <span style="font-size:0.7em; background:#007bff; color:#fff; padding:2px 4px; border-radius:3px; font-weight:bold;">YOU</span>' : '' ?>
                        </td>
                        <td><?= htmlspecialchars($mgr['email']) ?></td>
                        <td><span style="text-transform: uppercase; font-size: 0.8em; font-weight: bold; background: #e9ecef; padding: 3px 6px; border-radius: 3px;"><?= htmlspecialchars($mgr['role']) ?></span></td>
                        <td>
                            <?php if (!empty($mgr['temp_password'])): ?>
                                <code style="background: #fff3cd; color: #856404; padding: 4px 8px; border-radius: 4px; font-weight: bold;"><?= htmlspecialchars($mgr['temp_password']) ?></code>
                            <?php else: ?>
                                <span style="color: #6c757d; font-size: 0.85em; font-style: italic;">Verified Active Profile</span>
                            <?php endif; ?>
                            
                            <?php if ($hasForgotRequest): ?>
                                <span style="display:block; margin-top:4px; font-size:0.75em; background:#dc3545; color:white; padding:2px 5px; border-radius:3px; width:max-content; font-weight:bold;">⚠️ FORGOT REQUEST</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($isSelf): ?>
                                <span style="color:#6c757d; font-size:0.85em; font-style:italic;">Managed in Settings</span>
                            <?php else: ?>
                                <?php if ($hasForgotRequest): ?>
                                    <a href="/PHP_Project/Walany/index.php?module=Admin&action=regenerate_key&id=<?= $mgr['id'] ?>" 
                                       onclick="return confirm('Process and approve structural temporary replacement key for this coordinator profile?');" 
                                       style="background: #dc3545; color: white; padding: 5px 10px; border-radius:3px; text-decoration:none; font-size: 0.85em; display: inline-block; font-weight: bold;">
                                        Reset Key
                                    </a>
                                <?php else: ?>
                                    <button disabled style="background: #e0e0e0; color: #a0a0a0; padding: 5px 10px; border-radius:3px; border:0; font-size: 0.85em; cursor: not-allowed; font-weight:bold;" title="No active forgot request flagged.">
                                        Reset Locked
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- LOGGER ENGINE RECORD PANEL -->
    <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-top: 20px;">
        <h3 style="margin-top:0;">📋 Environment Security & System Access Logs</h3>
        <div style="max-height: 250px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 4px;">
            <table border="0" cellpadding="10" cellspacing="0" style="width: 100%; text-align: left; font-size: 0.9em;">
                <thead>
                    <tr style="background: #343a40; color: #fff; position: sticky; top: 0;">
                        <th>Timestamp</th>
                        <th>Administrator Operator</th>
                        <th>Action Log Parameters</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                    <tr style="border-bottom: 1px solid #e9ecef;">
                        <td><?= $log['logged_at'] ?></td>
                        <td style="color: #007bff; font-weight: bold;"><?= htmlspecialchars($log['actor_name']) ?></td>
                        <td style="font-family: monospace;"><?= htmlspecialchars($log['action_details']) ?></td>
                        <td><?= htmlspecialchars($log['ip_address']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>