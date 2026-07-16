<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - Walania Events</title>
    <link rel="icon" type="image/x-icon" href="/Walany/assets/images/Walania.svg">
    <link rel="stylesheet" href="/Walany/assets/style.css">
    <style>
        .otp-alert { padding: 12px; margin-bottom: 20px; border-radius: 6px; font-size: 14px; text-align: left; }
        .otp-alert.error { background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; }
        .otp-alert.success { background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
        .is-hidden { display: none !important; }
    </style>
</head>
<body class="registration-page event-registration-page otp-verification-page">

<!-- Fix the address of the style.css -->
<div class="connection-warning" style="background-color: #ffcccc; color: #cc0000; border: 2px solid #cc0000; padding: 20px; font-size: 24px; font-family: sans-serif; font-weight: bold; text-align: center; position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 100; width: 90%; max-width: 600px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
If you are seeing this, paki ayos nung href nung scripts, assets, and stylesheet files. For easy fix move your project folder inside the htdocs
</div>

<header class="site-header login-header">
    <a href="/Walany/index.php?module=Home" class="logo-placeholder" aria-label="Walania home">
        <img src="/Walany/assets/images/Walania.svg" alt="Walania logo">
    </a>
    <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle dark mode">
        <img class="theme-toggle-icon" data-theme-icon src="/Walany/assets/images/LightModeIcon.svg" alt="" aria-hidden="true">
    </button>
</header>

<main class="registration-section">
    <div class="otp-shell">
        <div class="otp-container">
            <div class="otp-heading">
                <h3>Security Verification</h3>
                <p>We sent a 6-digit verification code to your email address. Please input the passphrase tokens below.</p>
            </div>

            <!-- Enhanced System Alerts Box -->
            <div id="alertBox" class="otp-alert is-hidden" role="alert"></div>

            <form id="otpForm" method="POST">
                <div class="otp-inputs" aria-label="Verification code">
                    <input type="text" name="otp[]" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required autocomplete="off" aria-label="Digit 1">
                    <input type="text" name="otp[]" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required autocomplete="off" aria-label="Digit 2">
                    <input type="text" name="otp[]" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required autocomplete="off" aria-label="Digit 3">
                    <input type="text" name="otp[]" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required autocomplete="off" aria-label="Digit 4">
                    <input type="text" name="otp[]" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required autocomplete="off" aria-label="Digit 5">
                    <input type="text" name="otp[]" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required autocomplete="off" aria-label="Digit 6">
                </div>

                <button type="submit" class="primary-button submit-button">Verify & Claim Ticket</button>
            </form>

            <div class="otp-resend">
                <p>Didn't receive the email token?</p>
                <button type="button" id="resendBtn" class="text-button">Resend Code</button>
            </div>
        </div>
    </div>
</main>

<footer class="site-footer">
    <p>&copy; 2026 Walania. All rights reserved.</p>
</footer>

<script>
const inputs = document.querySelectorAll('.otp-inputs input, input[name="otp[]"]');
const alertBox = document.getElementById('alertBox');

const endpoint = '/Walany/index.php?module=Registrants&action=verify_otp';
const resendEndpoint = '/Walany/index.php?module=Registrants&action=resend_otp'; // Adjust action string as per backend setup

function displayAlert(type, message) {
    alertBox.className = `otp-alert ${type}`;
    alertBox.textContent = message;
    alertBox.classList.remove('is-hidden');
}

// --- 1. FIELD AUTO-ADVANCE FOCUS SYSTEM ---
inputs.forEach((input, index) => {
    input.addEventListener('input', (e) => {
        if (e.target.value.length === 1 && index < inputs.length - 1) {
            inputs[index + 1].focus();
        }
    });

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && e.target.value.length === 0 && index > 0) {
            inputs[index - 1].focus();
        }
    });
});

// --- 2. VERIFY TOKEN HANDLER ---
const form = document.getElementById('otpForm');
form.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(form);
    
    fetch(endpoint, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            // 🛠️ FIX 2: Check if payload specifies the PayMongo workflow intercept
            if (data.redirect === 'process-payment') {
                window.location.href = '/Walany/index.php?module=Registrants&action=process_payment';
            } else {
                // Fallback destination page view
                window.location.href = '/Walany/index.php?module=Registrants&action=registration_success';
            }
        } else {
            displayAlert('error', data.message || 'Verification calculation failed.');
        }
    })
    .catch((error) => {
        console.error("Verification Submission Error:", error);
        displayAlert('error', 'System handling fault processing OTP entry token.');
    });
});

// --- 3. COOLDOWN BACKOFF CLOCK TIMER ---
let cooldownSeconds = 30;
const resendBtn = document.getElementById('resendBtn');

function startResendCountdown() {
    if (!resendBtn) return;
    resendBtn.disabled = true;
    let currentSeconds = cooldownSeconds;
    resendBtn.textContent = `Resend Code (${currentSeconds}s)`;
    
    const countdownInterval = setInterval(() => {
        currentSeconds--;
        resendBtn.textContent = `Resend Code (${currentSeconds}s)`;
        if (currentSeconds <= 0) {
            clearInterval(countdownInterval);
            resendBtn.textContent = "Resend Code";
            resendBtn.disabled = false;
        }
    }, 1000);
}

document.addEventListener("DOMContentLoaded", startResendCountdown);

// --- 4. AJAX RESEND ACTIONS DISPATCHER ---
if (resendBtn) {
    resendBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const formData = new FormData();
        
        fetch(resendEndpoint, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                displayAlert('success', 'A fresh validation pass key has been routed to your mailbox.');
                startResendCountdown();
            } else {
                displayAlert('error', data.message || 'Security limit threshold hit.');
            }
        })
        .catch((error) => {
            console.error("Resend Technical Error:", error);
            displayAlert('error', 'Transmission transport broken while requesting code.');
        });
    });
}
</script>
<script src="/Walany/assets/script.js"></script>
</body>
</html>