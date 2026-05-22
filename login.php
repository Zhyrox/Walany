<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Walania | Login</title>
    <link rel="stylesheet" href="style.css">
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
            <a href="index.php#registration">Register</a>
            <a href="index.php#contacts">Contacts</a>
            <a href="login.php" aria-current="page">Login</a>
        </nav>

        <button class="theme-toggle" id="themeToggle" type="button" aria-label="Switch to dark mode">
            <span class="theme-icon" aria-hidden="true">D</span>
        </button>
    </header>

    <main>
        <section class="login-section" aria-labelledby="loginTitle">
            <div class="hero-orb hero-orb-left"></div>
            <div class="hero-orb hero-orb-right"></div>

            <div class="login-layout section-inner">
                <div class="login-copy">
                    <p class="eyebrow">Welcome back</p>
                    <h1 id="loginTitle">Walania</h1>
                    <p>Sign in to manage event registrations, participants, and upcoming activities.</p>
                </div>

                <form class="login-form" action="admin.php" method="POST">
                    <h2>Login</h2>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input id="email" name="email" type="email" autocomplete="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required>
                    </div>

                    <div class="login-options">
                        <label class="remember-option" for="rememberMe">
                            <input id="rememberMe" name="remember_me" type="checkbox">
                            <span>Remember me</span>
                        </label>
                        <a href="#">Forgot password?</a>
                    </div>

                    <button class="primary-button submit-button" type="submit">Sign In</button>

                    <p class="login-note">Admin workspace opens after sign in.</p>
                </form>
            </div>
        </section>
    </main>

    <script src="script.js"></script>
</body>
</html>
