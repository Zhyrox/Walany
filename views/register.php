<?php 
session_start();
$namepass_error = $_SESSION['namepass_error'] ?? '';
$accexist_error = $_SESSION['accexist_error'] ?? '';

unset($_SESSION['namepass_error']);
unset($_SESSION['accexist_error']);
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="icon" type="image/x-icon" href="../images/Walania.svg">
    <link rel="stylesheet" href="../style.css">
</head>
<body class="registration-page">
    <header class="site-header login-header">
        <a href="" class="logo-placeholder" aria-label="Refresh page">
            <img src="../images/Walania.svg" alt="Walania logo">
        </a>
        <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle dark mode">
            <img class="theme-toggle-icon" data-theme-icon src="../images/LightModeIcon.svg" alt="" aria-hidden="true">
        </button>
    </header>

    <main class="registration-section">
        <div class="registration-layout">
            <div class="section-heading">
                <p class="eyebrow">Create account</p>
                <h2>Register</h2>
                <div class="contact-copy">
                    <p>Create your user account to register for events and access the dashboard.</p>
                </div>
            </div>
            <form class="registration-form" action="../controllers/RegisterController.php" method="POST" novalidate>
                <?php if ($namepass_error): ?>
                    <p class="error-message"><?php echo $namepass_error; ?></p>
                <?php endif; ?>
                <?php if ($accexist_error): ?>
                    <p class="error-message"><?php echo $accexist_error; ?></p>
                <?php endif; ?>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                </div>
                <button class="primary-button submit-button" type="submit" name="register">Register</button>
                <p class="login-note note">Already have an account? <a href="login.php">Login</a>.</p>
            </form>
        </div>
    </main>

    <script src="../script.js"></script>
</body>
</html>
