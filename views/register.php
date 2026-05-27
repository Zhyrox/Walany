<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="registration-page">
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
</body>
</html>