<?php
require_once __DIR__ . '../../models/db.php';
require_once __DIR__ . '../../controllers/auth.php';

// Initialization
$loginErrors = [];
$loginStatus = null;
$email = '';

function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Redirects logged in users straight to event registration
if (user_is_logged_in()) {
    header('Location: index.php#registration');
    exit;
}

// Login Form Handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $loginErrors[] = 'Please enter a valid email address.';
    }

    if ($password === '') {
        $loginErrors[] = 'Password is required.';
    }

    if ($loginErrors === []) {
        try {
            // Checks record by email
            $statement = walania_db()->prepare(
                'SELECT user_id, full_name, email, password_hash
                 FROM user_accounts
                 WHERE email = ?
                 LIMIT 1'
            );
            $statement->bind_param('s', $email);
            $statement->execute();
            $user = $statement->get_result()->fetch_assoc();

            if ($user !== null && password_verify($password, $user['password_hash'])) {
                // starts session if login details are correct
                login_user($user);
                header('Location: index.php#registration');
                exit;
            }

            $loginErrors[] = 'Invalid email or password.';
        } catch (Throwable $error) {
            $loginErrors[] = 'Login is not available yet. Please import user_accounts.sql first.';
        }
    }

    $loginStatus = 'error';
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Walania | User Login</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="images/Walania.svg">
</head>
<body class="login-page">
    <!-- User Login -->
    <header class="site-header">
        <a href="index.php#home" class="logo-placeholder" aria-label="Walania home">
            <img src="images/Walania.svg" alt="Walania logo">
        </a>

        <nav class="main-nav" aria-label="Main navigation">
            <a href="index.php#events">Events</a>
            <a href="index.php#registration">Register Event</a>
            <a href="user_register.php">Create Account</a>
            <a href="login.php">Admin</a>
        </nav>

        <button class="theme-toggle" id="themeToggle" type="button" aria-label="Switch to dark mode">
            <span class="theme-icon" aria-hidden="true">D</span>
        </button>
    </header>

    <main>
        <!-- User Login Hero Section -->
        <section class="login-section" aria-labelledby="loginTitle">
            <div class="hero-orb hero-orb-left"></div>
            <div class="hero-orb hero-orb-right"></div>

            <div class="login-layout section-inner">
                <div class="login-copy">
                    <p class="eyebrow">User Access</p>
                    <h1 id="loginTitle">Walania</h1>
                    <p>Login to submit your event registration form.</p>
                </div>

                <!-- Displays user credentials for authentication -->
                <form class="login-form" action="user_login.php" method="POST">
                    <h2>User Login</h2>

                    <?php if ($loginStatus === 'error') : ?>
                        <div class="form-alert form-alert-error">
                            <?php foreach ($loginErrors as $loginError) : ?>
                                <p><?php echo h($loginError); ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input id="email" name="email" type="email" value="<?php echo h($email); ?>" autocomplete="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required>
                    </div>

                    <button class="primary-button submit-button" type="submit">Login</button>

                    <p class="login-note">No account yet? <a href="user_register.php">Create one here</a></p>
                </form>
            </div>
        </section>
    </main>

    <script src="script.js"></script>
</body>
</html>
