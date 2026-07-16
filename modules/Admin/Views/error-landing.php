<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$errorMessage = htmlspecialchars($_GET['message'] ?? 'An unexpected environmental infrastructure variance has occurred.');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Environment Notice</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f8f9fa; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; padding: 20px; box-sizing: border-box;">

    <!-- Fix the address of the style.css -->
    <div class="connection-warning" style="background-color: #ffcccc; color: #cc0000; border: 2px solid #cc0000; padding: 20px; font-size: 24px; font-family: sans-serif; font-weight: bold; text-align: center; position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 100; width: 90%; max-width: 600px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    If you are seeing this, paki ayos nung href nung scripts, assets, and stylesheet files. For easy fix move your project folder inside the htdocs
    </div>

    <div style="background: #ffffff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); max-width: 550px; text-align: center; border-top: 5px solid #dc3545;">
        <div style="font-size: 50px; margin-bottom: 20px;">⚠️</div>
        <h2 style="color: #343a40; margin-top: 0;">System Interruption</h2>
        <p style="color: #6c757d; line-height: 1.6; font-size: 1.05em; margin-bottom: 25px;">
            The application encountered a data integrity exception while processing your request. The technical diagnostics have been captured securely for review.
        </p>
        
        <div style="background: #fff5f5; border: 1px solid #ffe3e3; padding: 15px; border-radius: 4px; text-align: left; font-family: monospace; color: #c92a2a; font-size: 0.9em; margin-bottom: 30px; word-break: break-word;">
            <strong>Context Summary:</strong><br>
            <?= $errorMessage ?>
        </div>

        <?php if (isset($_SESSION['role'])): ?>
            <?php
            $fallbackUrl = "/PHP_Project/Walany/index.php?module=Admin&action=view_managers";
            if ($_SESSION['role'] === 'registrar') {
                $fallbackUrl = "/PHP_Project/Walany/index.php?module=Admin&action=registrar_dashboard";
            } elseif ($_SESSION['role'] === 'planner') {
                $fallbackUrl = "/PHP_Project/Walany/index.php?module=Admin&action=planner_dashboard";
            }
            ?>
            <a href="<?= $fallbackUrl ?>" style="background: #007bff; color: white; padding: 12px 24px; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 0.95em; display: inline-block;">
                Return to Workspace
            </a>
        <?php else: ?>
            <a href="/PHP_Project/Walany/index.php?module=Auth&action=login" style="background: #6c757d; color: white; padding: 12px 24px; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 0.95em; display: inline-block;">
                Go to Login Gate
            </a>
        <?php endif; ?>
    </div>

</body>
</html>