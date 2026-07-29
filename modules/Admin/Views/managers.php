<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /Walany/index.php?module=Auth&action=login");
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
    header("Location: /Walany/index.php?module=Admin&action=system_error&message=" . urlencode("Database connectivity or operational schema fault."));
    exit;
}

?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Admin Control Panel</title>
    <link rel="stylesheet" href="/Walany/assets/style.css">
     <link rel="icon" type="image/svg+xml" href="/Walany/assets/images/Walania.svg">
</head>
<body class="managers-page">

    <!-- Site header (shared) -->
    <header class="site-header login-header headbar">
        <a href="/Walany/index.php" class="logo-placeholder" aria-label="Walania home">
            <img src="/Walany/assets/images/Walania.svg" alt="Walania logo">
        </a>
        <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle dark mode">
            <img class="theme-toggle-icon" data-theme-icon src="/Walany/assets/images/LightModeIcon.svg" alt="" aria-hidden="true">
        </button>
    </header>

    <!-- Fix the address of the style.css -->
    <div class="connection-warning">
    If you are seeing this, paki ayos nung href nung scripts, assets, and stylesheet files. For easy fix move your project folder inside the htdocs
    </div>

    <div class="managers-shell">
        <div class="managers-topbar">
            <div>
                <h2>System Environment Administration Matrix</h2>
                <p class="managers-subtitle">Provision administrative roles, manage access, and review system audit logs.</p>
            </div>
            <div class="managers-actions">
                <a href="/Walany/index.php?module=Admin&action=profile_settings" class="btn btn-secondary">
                    My Profile Settings
                </a>
                <a href="/Walany/index.php?module=Auth&action=login"
                   onclick="return confirm('Are you sure you want to log out of the system?');"
                   class="btn btn-danger">
                    Logout
                </a>
            </div>
        </div>
    <hr class="managers-divider">

    <?php if (isset($_GET['status'])): ?>
        <div class="managers-alert <?= $_GET['status'] === 'success' ? 'managers-alert--success' : 'managers-alert--error' ?>">
            <?= htmlspecialchars($_GET['message'] ?? '') ?>
        </div>
    <?php endif; ?>

    <div class="managers-grid">
        <!-- PROVISIONING INTERFACE WORKSPACE FORM -->
        <div class="managers-card managers-card--narrow">
            <h3 class="managers-section-title">Create New Environment Account</h3>
            <p class="managers-card-description">
                Provision system workspace execution privileges for Planners, Registrars, or new Administrators.
            </p>
            <form id="managerForm" action="/Walany/index.php?module=Admin&action=create_manager" method="POST">
                
                <div class="managers-form-group">
                    <label>First Name</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                <div class="managers-form-group">
                    <label>Last Name</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
                <div class="managers-form-group">
                    <label>Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="managers-form-group">
                    <label>Environment Access Role</label>
                    <select id="role" name="role" required>
                        <option value="planner">Planner</option>
                        <option value="registrar">Registrar</option>
                        <option value="admin">System Administrator (Admin)</option>
                    </select>
                </div>
                
                <button type="submit" class="managers-button">
                    Provision System Account
                </button>
            </form>
        </div>

        <!-- COORDINATORS LIST STACK DISPLAY -->
        <div class="managers-card managers-card--wide">
            <h3 class="managers-section-title">Active Managers Stack</h3>
            <table class="managers-table" border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr class="managers-table-head-row">
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
                    <?= $isSelf ? '<tr class="managers-row--self">' : '<tr>' ?>
                        <td>
                            <?= htmlspecialchars($mgr['first_name'] . ' ' . $mgr['last_name']) ?>
                            <?= $isSelf ? ' <span class="managers-you-badge">YOU</span>' : '' ?>
                        </td>
                        <td><?= htmlspecialchars($mgr['email']) ?></td>
                        <td><span class="managers-role-badge"><?= htmlspecialchars($mgr['role']) ?></span></td>
                        <td>
                            <?php if (!empty($mgr['temp_password'])): ?>
                                <code class="managers-temp-password"><?= htmlspecialchars($mgr['temp_password']) ?></code>
                            <?php else: ?>
                                <span class="managers-status-text">Verified Active Profile</span>
                            <?php endif; ?>
                            
                            <?php if ($hasForgotRequest): ?>
                                <span class="managers-request-badge">⚠️ FORGOT REQUEST</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($isSelf): ?>
                                <span class="managers-status-text managers-status-text--muted">Managed in Settings</span>
                            <?php else: ?>
                                <?php if ($hasForgotRequest): ?>
                                    <a href="/Walany/index.php?module=Admin&action=regenerate_key&id=<?= $mgr['id'] ?>" 
                                       onclick="return confirm('Process and approve structural temporary replacement key for this coordinator profile?');" 
                                       class="managers-action-link managers-action-link--danger">
                                        Reset Key
                                    </a>
                                <?php else: ?>
                                    <button disabled class="managers-action-button managers-action-button--disabled" title="No active forgot request flagged.">
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
    <div class="managers-card managers-log-panel">
        <h3 class="managers-section-title">📋 Environment Security & System Access Logs</h3>
        <div class="managers-log-wrapper">
            <table class="managers-log-table" border="0" cellpadding="10" cellspacing="0">
                <thead>
                    <tr class="managers-log-table-head-row">
                        <th>Timestamp</th>
                        <th>Administrator Operator</th>
                        <th>Action Log Parameters</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                    <tr class="managers-log-row">
                        <td><?= $log['logged_at'] ?></td>
                        <td class="managers-actor-name"><?= htmlspecialchars($log['actor_name']) ?></td>
                        <td class="managers-log-detail"><?= htmlspecialchars($log['action_details']) ?></td>
                        <td><?= htmlspecialchars($log['ip_address']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>

    <script src="/Walany/assets/script.js"></script>
</body>
</html>