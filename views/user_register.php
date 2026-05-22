<?php
require_once __DIR__ . '../../models/db.php';
require_once __DIR__ . '../../controllers/auth.php';

$registerErrors = [];
$registerStatus = null;
$fullName = '';
$email = '';

function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

if (user_is_logged_in()) {
    header('Location: index.php#registration');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($fullName === '') {
        $registerErrors[] = 'Full name is required.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $registerErrors[] = 'Please enter a valid email address.';
    }

    if (strlen($password) < 6) {
        $registerErrors[] = 'Password must be at least 6 characters.';
    }

    if ($password !== $confirmPassword) {
        $registerErrors[] = 'Passwords do not match.';
    }

    if ($registerErrors === []) {
        try {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $statement = walania_db()->prepare(
                'INSERT INTO user_accounts (full_name, email, password_hash)
                 VALUES (?, ?, ?)'
            );
            $statement->bind_param('sss', $fullName, $email, $passwordHash);
            $statement->execute();

            $userId = walania_db()->insert_id;
            login_user([
                'user_id' => $userId,
                'full_name' => $fullName,
                'email' => $email,
            ]);

            header('Location: index.php#registration');
            exit;
        } catch (mysqli_sql_exception $error) {
            $registerErrors[] = $error->getCode() === 1062
                ? 'An account with that email already exists.'
                : 'Account could not be created. Please import user_accounts.sql first.';
        } catch (Throwable $error) {
            $registerErrors[] = 'Account could not be created. Please import user_accounts.sql first.';
        }
    }

    $registerStatus = 'error';
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Walania | User Registration</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="images/Walania.svg">
</head>
<body class="login-page">
    <header class="site-header">
        <a href="index.php#home" class="logo-placeholder" aria-label="Walania home">
            <img src="images/Walania.svg" alt="Walania logo">
        </a>

        <nav class="main-nav" aria-label="Main navigation">
            <a href="index.php#events">Events</a>
            <a href="index.php#registration">Register Event</a>
            <a href="user_login.php">User Login</a>
            <a href="login.php">Admin</a>
        </nav>

        <button class="theme-toggle" id="themeToggle" type="button" aria-label="Switch to dark mode">
            <span class="theme-icon" aria-hidden="true">D</span>
        </button>
    </header>

    <main>
        <section class="login-section" aria-labelledby="registerTitle">
            <div class="hero-orb hero-orb-left"></div>
            <div class="hero-orb hero-orb-right"></div>

            <div class="login-layout section-inner">
                <div class="login-copy">
                    <p class="eyebrow">Create Account</p>
                    <h1 id="registerTitle">Walania</h1>
                    <p>Register as a user before submitting an event registration form.</p>
                </div>

                <form class="login-form" action="user_register.php" method="POST">
                    <h2>User Registration</h2>

                    <?php if ($registerStatus === 'error') : ?>
                        <div class="form-alert form-alert-error">
                            <?php foreach ($registerErrors as $registerError) : ?>
                                <p><?php echo h($registerError); ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="fullName">Full Name</label>
                        <input id="fullName" name="full_name" type="text" value="<?php echo h($fullName); ?>" autocomplete="name" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input id="email" name="email" type="email" value="<?php echo h($email); ?>" autocomplete="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" autocomplete="new-password" required>
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password</label>
                        <input id="confirmPassword" name="confirm_password" type="password" autocomplete="new-password" required>
                    </div>

                    <button class="primary-button submit-button" type="submit">Create Account</button>

                    <p class="login-note">Already have an account? <a href="user_login.php">Login here</a></p>
                </form>
            </div>
        </section>
    </main>

    <script src="script.js"></script>
</body>
</html>
