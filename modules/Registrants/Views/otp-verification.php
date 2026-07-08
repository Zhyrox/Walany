<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - Walania Events</title>
    <link rel="icon" type="image/x-icon" href="/PHP_Project/Walany/assets/images/Walania.svg">
    <link rel="stylesheet" href="/PHP_Project/Walany/assets/style.css">
</head>
<body class="registration-page event-registration-page otp-verification-page">
<header class="site-header login-header">
    <a href="/PHP_Project/Walany/index.php?module=Home" class="logo-placeholder" aria-label="Walania home">
        <img src="/PHP_Project/Walany/assets/images/Walania.svg" alt="Walania logo">
    </a>
    <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle dark mode">
        <img class="theme-toggle-icon" data-theme-icon src="/PHP_Project/Walany/assets/images/LightModeIcon.svg" alt="" aria-hidden="true">
    </button>
</header>

<main class="registration-section">
    <div class="otp-shell">
        <div class="otp-container">
            <div class="otp-heading">
                <h3>Security Verification</h3>
                <p>We sent a 6-digit verification code to your email address. Please input the passphrase tokens below.</p>
            </div>

            <!-- System Alerts Box (For errors or resend status) -->
            <div id="alertBox" class="otp-alert is-hidden" role="alert"></div>

            <!-- Main Submission Form -->
            <form id="otpForm" method="POST">
                <div class="otp-inputs" aria-label="Verification code">
                    <!-- Grouped array elements naming structure matches $POST['otp'] controller expectation -->
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

<!-- JavaScript Controls for UX Focus & Ajax Handlers -->
<script>
// --- 1. SMOOTH AUTO-ADVANCE INPUT FIELD LOGIC ---
// Look for any input elements inside a container with the class .otp-field or .otp-inputs
const inputs = document.querySelectorAll('.otp-field input, .otp-inputs input, input[name="otp[]"]');

inputs.forEach((input, index) => {
    // Automatically move focus to the next input cell once a number is typed
    input.addEventListener('input', (e) => {
        if (e.target.value.length === 1 && index < inputs.length - 1) {
            inputs[index + 1].focus();
        }
    });

    // Move backward to the previous cell if Backspace is pressed on an empty field
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && e.target.value.length === 0 && index > 0) {
            inputs[index - 1].focus();
        }
    });
});


// --- 2. VERIFY BUTTON SUBMISSION HANDLER ---
const form = document.getElementById('otpForm');

form.addEventListener('submit', function(e) {
    e.preventDefault(); // Stop standard page reloads
    
    const formData = new FormData(form);
    
    fetch('/PHP_Project/Walany/modules/Registrants/Controllers/RegistrantController.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            // Smoothly send the user to the verified success dashboard view layout
            window.location.href = '/PHP_Project/Walany/modules/Registrants/Views/registration-success.php'; 
        } else {
            // Display any error alerts (like expired or wrong code)
            alert(data.message);
        }
    })
    .catch((error) => {
        console.error("Verification Submission Error:", error);
        alert('System context processing failure during verification submission.');
    });
});


// --- 3. RESEND ACTION COUNTDOWN LOCKOUT TIMER ---
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

// Automatically start the 30-second lockout on structural page compilation load
document.addEventListener("DOMContentLoaded", startResendCountdown);


// --- 4. AJAX RESEND BUTTON ACTION HANDLER ---
if (resendBtn) {
    resendBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('action', 'resend');
        
        fetch('/PHP_Project/Walany/modules/Registrants/Controllers/RegistrantController.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                alert('A fresh verification code has been dispatched to your email.');
                startResendCountdown(); // Reset the 30s timer cleanly without a page reload
            } else {
                alert(data.message || 'Resend threshold limited.');
            }
        })
        .catch((error) => {
            console.error("Resend Technical Error:", error);
            alert('Error processing fallback transmission.');
        });
    });
}
</script>
<script src="/PHP_Project/Walany/assets/script.js"></script>
</body>
</html>
