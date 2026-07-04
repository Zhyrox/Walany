<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect back home immediately if someone tries to access this page directly without an active transaction
if (!isset($_SESSION['pending_reference_number'])) {
    header("Location: landing");
    exit;
}

$referenceNumber = $_SESSION['pending_reference_number'];
$transactionDate = isset($_SESSION['success_timestamp']) ? $_SESSION['success_timestamp'] : date('F d, Y h:i A');

// Clear the sensitive verification session variables now that the operation is finished successfully
unset($_SESSION['pending_verification_email']);
unset($_SESSION['pending_reference_number']);
unset($_SESSION['last_otp_request_time']);
unset($_SESSION['current_backoff_cooldown']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Confirmed</title>
    <style>
        body { background-color: #f8f9fa; font-family: system-ui, -apple-system, sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .success-card { max-width: 500px; width: 100%; background: #ffffff; padding: 40px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05); text-align: center; box-sizing: border-box; }
        .success-icon { width: 72px; height: 72px; background: #d1e7dd; color: #0f5132; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 36px; margin: 0 auto 24px; }
        h1 { color: #212529; font-size: 26px; margin: 0 0 12px; font-weight: 700; }
        p { color: #6c757d; font-size: 15px; line-height: 1.6; margin: 0 0 24px; }
        .details-box { background: #f1f3f5; border-radius: 12px; padding: 20px; text-align: left; margin-bottom: 30px; border: 1px solid #e9ecef; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; }
        .detail-row:last-child { margin-bottom: 0; }
        .detail-label { color: #6c757d; font-weight: 500; }
        .detail-value { color: #212529; font-weight: 600; font-family: monospace; font-size: 15px; }
        .btn-home { display: inline-block; width: 100%; padding: 14px; background: #198754; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; transition: background 0.2s; border: none; cursor: pointer; box-sizing: border-box; }
        .btn-home:hover { background: #157347; }
        .timer-text { margin-top: 20px; font-size: 13px; color: #adb5bd; }
        .timer-count { color: #dc3545; font-weight: 600; }
    </style>
</head>
<body>

<div class="success-card">
    <div class="success-icon">✓</div>
    <h1>Transaction Complete!</h1>
    <p>Your secure digital ticket and entry pass QR code have been compiled and sent directly to your verified email address. Please present it at the gate check-in desk.</p>
    
    <div class="details-box">
        <div class="detail-row">
            <span class="detail-label">Reference ID:</span>
            <span class="detail-value" style="color: #0d6efd; font-size: 16px; letter-spacing: 0.5px;"><?php echo htmlspecialchars($referenceNumber); ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Transaction Date:</span>
            <span class="detail-value"><?php echo htmlspecialchars($transactionDate); ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Status:</span>
            <span class="detail-value" style="color: #198754;">Verified Account</span>
        </div>
    </div>

    <a href="../../../index.php" class="btn-home">Return to Home Screen</a>
    
    <div class="timer-text">
        System idling. Redirecting automatically in <span id="countdown" class="timer-count">60</span> seconds...
    </div>
</div>

<script>
    // Initialize the 60-second redirect countdown timer
    let secondsLeft = 60;
    const countdownElement = document.getElementById('countdown');

    const timerInterval = setInterval(() => {
        secondsLeft--;
        countdownElement.textContent = secondsLeft;

        if (secondsLeft <= 0) {
            clearInterval(timerInterval);
            window.location.href = '../../../index.php'; // Redirect back to landing page route string
        }
    }, 1000);
</script>
</body>
</html>