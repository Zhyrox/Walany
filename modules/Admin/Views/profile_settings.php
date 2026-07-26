<?php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// SECURITY CHANGE: Allow any authenticated system user (Admin, Planner, Registrar)
if (!isset($_SESSION['manager_id']) || !isset($_SESSION['role'])) {
    header("Location: /Walany/index.php?module=Auth&action=login");
    exit;
}

// Resolve the correct dashboard landing page based on the active session role
$roleDashboardUrl = "/Walany/index.php?module=Auth&action=login"; // Safe fallback

if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'admin':
            $roleDashboardUrl = "/Walany/index.php?module=Admin&action=view_managers";
            break;
        case 'registrar':
            $roleDashboardUrl = "/Walany/index.php?module=Admin&action=registrar_dashboard";
            break;
        case 'planner':
            $roleDashboardUrl = "/Walany/index.php?module=Admin&action=planner_dashboard";
            break;
    }
}

$managerId = intval($_SESSION['manager_id']);
$role = $_SESSION['role'];

require_once __DIR__ . '/../../../core/Database.php';

try {
    $db = (new Database())->getConnection();
    
    // Select the manager data matching the active session ID
    $stmt = $db->prepare("SELECT * FROM `walania_managers` WHERE `id` = :id LIMIT 1");
    $stmt->execute(['id' => $managerId]);
    $adminData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$adminData) {
        die("Critical Profiling Parameter Access Error: Profile not found.");
    }
} catch (PDOException $e) {
    error_log("CRITICAL SYSTEM INTEGRITY FAULT: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
    header("Location: /Walany/index.php?module=Admin&action=system_error&message=" . urlencode("Database connectivity or operational schema fault."));
    exit;
}
?>



<!-- Rest of your HTML/CSS form content remains exactly the same! -->
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile Settings</title>
    <link rel="stylesheet" href="/Walany/assets/style.css">
</head>
<body class="profile-settings-page">

    <header class="site-header login-header headbar">
        <a href="/Walany/index.php" class="logo-placeholder" aria-label="Walania home">
            <img src="/Walany/assets/images/Walania.svg" alt="Walania logo">
        </a>
        <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle dark mode">
            <img class="theme-toggle-icon" data-theme-icon src="/Walany/assets/images/LightModeIcon.svg" alt="" aria-hidden="true">
        </button>
    </header>

    <div class="profile-settings-shell">

        <div class="connection-warning">
            If you are seeing this, paki ayos nung href nung scripts, assets, and stylesheet files. For easy fix move your project folder inside the htdocs
        </div>

        <div class="profile-settings-topbar">
            <h2>Personal Admin Profile Configurations</h2>
            <div class="profile-settings-actions">
                <a href="<?= $roleDashboardUrl ?>" class="btn btn-secondary">
                    Return to Dashboard
                </a>
                <a href="/Walany/index.php?module=Auth&action=login"
                   onclick="return confirm('Are you sure you want to log out of the system?');"
                   class="btn btn-danger">
                    Logout
                </a>
            </div>
        </div>
        <hr style="border: 0; border-top: 1px solid #dce5ec; margin: 0 0 25px;">

        <div class="profile-settings-card">
            <h3>Update Personal Credentials</h3>
            <p>
                Modify your administrative attributes data or reset your personal login security password directly.
            </p>

            <form action="/Walany/index.php?module=Admin&action=update_manager" method="POST" class="profile-settings-form">
                <input type="hidden" name="manager_id" value="<?= $adminData['id'] ?>">

                <div class="profile-field">
                    <label>First Name</label>
                    <input type="text" name="first_name" value="<?= htmlspecialchars($adminData['first_name']) ?>" required>
                </div>

                <div class="profile-field">
                    <label>Last Name</label>
                    <input type="text" name="last_name" value="<?= htmlspecialchars($adminData['last_name']) ?>" required>
                </div>

                <div class="profile-field">
                    <label>Email Address</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($adminData['email']) ?>" required>
                </div>

                <div class="profile-password-box">
                    <label>Update Account Password</label>
                    <input type="password" name="password" placeholder="Type new login password">
                    <small>Leave this field completely blank to preserve your active security configuration password.</small>
                </div>

                <button type="submit" class="btn btn-primary profile-submit">
                    Save Profile Parameters
                </button>
            </form>
        </div>

    </div>

    <script src="/Walany/assets/script.js"></script>
</body>
</html>