<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /PHP_Project/Walany/index.php?module=Auth&action=login");
    exit;
}

$currentAdminId = intval($_SESSION['manager_id'] ?? 0);

require_once __DIR__ . '/../../../core/Database.php';

try {
    $db = (new Database())->getConnection();
    
    // Extract the strict matching record properties for the active operator singleton
    $stmt = $db->prepare("SELECT id, first_name, last_name, email FROM `walania_managers` WHERE `id` = :id LIMIT 1");
    $stmt->execute(['id' => $currentAdminId]);
    $adminData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$adminData) {
        die("Critical Profiling Parameter Access Error.");
    }
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
    <title>My Profile Settings</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f4f6f9; padding: 30px;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h2>Personal Admin Profile Configurations</h2>
        <a href="/PHP_Project/Walany/index.php?module=Admin&action=view_managers" style="background: #007bff; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 0.9em;">
            Return to Main Dashboard
        </a>
        <a href="/PHP_Project/Walany/index.php?module=Auth&action=login" 
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

        <form action="/PHP_Project/Walany/index.php?module=Admin&action=update_manager" method="POST">
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