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
            <a href="/PHP_Project/Walany/index.php?module=Admin&action=view_managers" style="background: #007bff; color: white; padding: 12px 24px; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 0.95em; display: inline-block;">
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