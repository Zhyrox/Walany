<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - Walania Events</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .otp-container { max-width: 450px; margin-top: 80px; }
        .otp-input {
            width: 50px;
            height: 55px;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin: 0 5px;
            border: 2px solid #ced4da;
            border-radius: 8px;
        }
        .otp-input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            outline: none;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center">
    <div class="card p-4 shadow-sm otp-container bg-white rounded-4">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark">Security Verification</h3>
            <p class="text-muted small">We sent a 6-digit verification code to your email address. Please input the passphrase tokens below.</p>
        </div>

        <!-- System Alerts Box (For errors or resend status) -->
        <div id="alertBox" class="alert d-none" role="alert"></div>

        <!-- Main Submission Form -->
        <form id="otpForm" method="POST">
            <div class="d-flex justify-content-center mb-4">
                <!-- Grouped array elements naming structure matches $POST['otp'] controller expectation -->
                <input type="text" name="otp[]" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required autocomplete="off">
                <input type="text" name="otp[]" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required autocomplete="off">
                <input type="text" name="otp[]" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required autocomplete="off">
                <input type="text" name="otp[]" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required autocomplete="off">
                <input type="text" name="otp[]" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required autocomplete="off">
                <input type="text" name="otp[]" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required autocomplete="off">
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2.5 mb-3 fw-semibold rounded-3">Verify & Claim Ticket</button>
        </form>

        <div class="text-center">
            <p class="text-muted small mb-0">Didn't receive the email token?</p>
            <button type="button" id="resendBtn" class="btn btn-link">Resend Code</button>
        </div>
    </div>
</div>

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
</body>
</html>