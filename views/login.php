<?php
session_start();
$namepass_error = $_SESSION['namepass_error'] ?? '';
$login_error = $_SESSION['login_error'] ?? '';


unset($_SESSION['namepass_error']);
unset($_SESSION['login_error']);
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/x-icon" href="../images/Walania.svg">
    <link rel="stylesheet" href="../style.css">
</head>
<body class="login-page">
    <header class="site-header login-header">
        <a href="" class="logo-placeholder" aria-label="Refresh page">
            <img src="../images/Walania.svg" alt="Walania logo">
        </a>
        <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle dark mode">
            <img class="theme-toggle-icon" data-theme-icon src="../images/LightModeIcon.svg" alt="" aria-hidden="true">
        </button>
    </header>

    <main class="login-section">
        <div class="login-layout">
            <div class="login-visual" aria-hidden="true">
                <div class="login-slideshow">
                    <div class="login-slide is-active">
                        <img class="login-slide-image" src="../images/Event_Image (1).jpg" alt="Login slide 1 placeholder">
                        <div class="slide-caption">
                            <p>Capture the moment</p>
                            <h2>Events that feel alive</h2>
                        </div>
                    </div>
                    <div class="login-slide">
                        <img class="login-slide-image" src="../images/Event_Image (2).jpg" alt="Login slide 2 placeholder">
                        <div class="slide-caption">
                            <p>Where events come together</p>
                            <h2>For every event worth keeping</h2>
                        </div>
                    </div>
                    <div class="login-slide">
                        <img class="login-slide-image" src="../images/Event_Image (3).jpg" alt="Login slide 3 placeholder">
                        <div class="slide-caption">
                            <p>Your event, your story</p>
                            <h2>Made to feel personal</h2>
                        </div>
                    </div>
                </div>
                <div class="slide-dots">
                    <span class="is-active"></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            <article class="login-form" aria-labelledby="loginTitle">
                <h2 id="loginTitle">Sign in</h2>

                <?php if ($namepass_error): ?>
                    <p class="error-message"><?php echo $namepass_error; ?></p>
                <?php endif; ?>

                <?php if ($login_error): ?>
                    <p class="error-message"><?php echo $login_error; ?></p>
                <?php endif; ?>

                <form action="../controllers/LoginController.php" method="POST" novalidate>
                    <div class="form-group">
                        <label for="login-username">Username</label>
                        <input id="login-username" name="username" type="text" required autocomplete="username" />
                    </div>

                    <div class="form-group">
                        <label for="login-password">Password</label>
                        <div class="field">
                            <input id="login-password" name="password" type="password" required autocomplete="current-password" />
                            <button type="button" class="toggle-password" data-target="login-password" aria-label="Show password">Show</button>
                        </div>
                    </div>

                    <button class="primary-button submit-button" type="submit" name="login">Login</button>

                    <p class="login-note note">
                        Don't have an account yet? <a href="register.php">Sign up</a>
                    </p>

                    <p class="login-note note">By logging in you can submit and manage your event registrations.</p>
                </form>
            </article>
        </div>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 Walania. All rights reserved.</p>
    </footer>

    <script src="../script.js"></script>
</body>
</html>
