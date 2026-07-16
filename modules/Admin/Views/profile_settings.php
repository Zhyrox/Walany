<?php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// SECURITY CHANGE: Allow any authenticated system user (Admin, Planner, Registrar)
if (!isset($_SESSION['manager_id']) || !isset($_SESSION['role'])) {
    header("Location: /PHP_Project/Walany/index.php?module=Auth&action=login");
    exit;
}

// Resolve the correct dashboard landing page based on the active session role
$roleDashboardUrl = "/PHP_Project/Walany/index.php?module=Auth&action=login"; // Safe fallback

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
    header("Location: /PHP_Project/Walany/index.php?module=Admin&action=system_error&message=" . urlencode("Database connectivity or operational schema fault."));
    exit;
}
?>



<!-- Rest of your HTML/CSS form content remains exactly the same! -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile Settings</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f4f6f9; padding: 30px;">

    <!-- Fix the address of the style.css -->
    <div class="connection-warning" style="background-color: #ffcccc; color: #cc0000; border: 2px solid #cc0000; padding: 20px; font-size: 24px; font-family: sans-serif; font-weight: bold; text-align: center; position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 100; width: 90%; max-width: 600px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    If you are seeing this, paki ayos nung href nung scripts, assets, and stylesheet files. For easy fix move your project folder inside the htdocs
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h2>Personal Admin Profile Configurations</h2>
        <a href="<?= $roleDashboardUrl ?>" class="btn btn-secondary" style="padding: 10px 20px; border-radius: 6px; text-decoration: none; display: inline-block;">
            Return to Dashboard
        </a>
        <a href="/Walany/index.php?module=Auth&action=login" 
   onclick="return confirm('Are you sure you want to log out of the system?');" 
   style="background: #dc3545; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 0.9em; display: inline-block;">
            Logout
        </a>
    </div>
    <hr style="margin-bottom: 25px;">

    <div style="max-width: 600px; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin: 0 auto;">
        <h3 style="margin-top:0; color: #343a40;">Update Personal Credentials</h3>
        <p style="font-size:0.85em; color:#6c757d; margin-bottom:25px;">
            Modify your administrative attributes data or reset your personal login security password directly.
        </p>

        <form action="/Walany/index.php?module=Admin&action=update_manager" method="POST">
            <!-- Strict structural assignment mapping context anchor id -->
            <input type="hidden" name="manager_id" value="<?= $adminData['id'] ?>">
            
            <div style="margin-bottom: 20px;">
                <label style="font-size: 0.9em; font-weight: bold; display:block; margin-bottom: 5px;">First Name</label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($adminData['first_name']) ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius:4px; box-sizing: border-box;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="font-size: 0.9em; font-weight: bold; display:block; margin-bottom: 5px;">Last Name</label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($adminData['last_name']) ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius:4px; box-sizing: border-box;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="font-size: 0.9em; font-weight: bold; display:block; margin-bottom: 5px;">Email Address</label>
                <input type="email" name="email" value="<?= htmlspecialchars($adminData['email']) ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius:4px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 25px; padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 4px;">
                <label style="font-size: 0.9em; font-weight: bold; display:block; margin-bottom: 5px; color: #856404;">Update Account Password</label>
                <input type="password" name="password" style="width: 100%; padding: 10px; border: 1px solid #ffeb3b; border-radius:4px; box-sizing: border-box; background: #fff;" placeholder="Type new login password">
                <small style="color:#6c757d; display:block; margin-top:5px;">Leave this field completely blank to preserve your active security configuration password.</small>
            </div>
            
            <button type="submit" style="background: #007bff; color: #fff; border: 0; padding: 12px 20px; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; font-size: 1em;">
                Save Profile Parameters
            </button>
        </form>
    </div>

</body>
</html>