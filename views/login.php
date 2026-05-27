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
    <link rel="stylesheet" href="../style.css">
</head>
<body class="login-page">
    <main class="login-section">
        <div class="login-layout">
            <div class="login-copy">
                <h1>Welcome Back</h1>
                <p class="lead">Sign in to continue to the registration portal.</p>
            </div>
            <article class="login-form" aria-labelledby="loginTitle">
                <h2 id="loginTitle">Sign in</h2>

                <?php if ($namepass_error): ?>
                    <p class="error-message"><?php echo $namepass_error; ?></p>
                <?php endif; ?>

                <?php if ($login_error): ?>
                    <p class="error-message"><?php echo $login_error; ?></p>
                <?php endif; ?>

                <form action="../controllers/authController.php" method="POST" novalidate>
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

                    <div class="login-options">
                        <button class="primary-button submit-button" type="submit" name="login">Login</button>
                        <a href="register.php">Sign Up</a>
                    </div>

                    <p class="login-note note">By logging in you can submit and manage your event registrations.</p>
                </form>
            </article>
        </div>
    </main>

    <script>
        (function(){
            const buttons = document.querySelectorAll('.toggle-password');
            buttons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const targetId = btn.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    if (!input) return;
                    const showing = input.type === 'text';
                    input.type = showing ? 'password' : 'text';
                    btn.textContent = showing ? 'Show' : 'Hide';
                    btn.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
                });
            });
        })();
    </script>
</body>
</html>