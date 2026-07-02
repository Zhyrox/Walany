<?php
session_start();
$namepass_error = $_SESSION['namepass_error'] ?? '';
$login_error = $_SESSION['login_error'] ?? '';


unset($_SESSION['namepass_error']);
unset($_SESSION['login_error']);
?>

<?php
// Configuration Switch: Set to true to display the popup, false to hide it without deleting code
$show_patch_notes = true;

$latest_version = "";
$release_date = "";
$status = "";
$latest_entry = null;

if ($show_patch_notes && file_exists('assets/patch_notes.xml')) {
    $xml = simplexml_load_file('assets/patch_notes.xml');
    
    // Select the absolute latest <release> entry at the bottom of the XML file
    if ($xml && $xml->release->count() > 0) {
        $latest_entry = $xml->release[$xml->release->count() - 1];
        $latest_version = (string)$latest_entry->version;
        $release_date = (string)$latest_entry->date;
        $status = (string)$latest_entry->status;
    }
} else {
    echo "HELLO THIS IS AN ERROR";
}
?>



<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
    <base href="<?php echo BASE_URL; ?>">
    <link rel="icon" type="image/x-icon" href="assets/images/Walania.svg">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="login-page">
    
    <header class="site-header login-header">
        <a href="" class="logo-placeholder" aria-label="Refresh page">
            <img src="assets/images/Walania.svg" alt="Walania logo">
        </a>
        <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle dark mode">
            <img class="theme-toggle-icon" data-theme-icon src="assets/images/LightModeIcon.svg" alt="" aria-hidden="true">
        </button>
    </header>

    <main class="login-section">
        <div class="login-layout">
            <div class="login-visual" aria-hidden="true">
                <div class="login-slideshow">
                    <div class="login-slide is-active">
                        <img class="login-slide-image" src="assets/images/Event_Image (1).jpg" alt="Login slide 1 placeholder">
                        <div class="slide-caption">
                            <p>Capture the moment</p>
                            <h2>Events that feel alive</h2>
                        </div>
                    </div>
                    <div class="login-slide">
                        <img class="login-slide-image" src="assets/images/Event_Image (2).jpg" alt="Login slide 2 placeholder">
                        <div class="slide-caption">
                            <p>Where events come together</p>
                            <h2>For every event worth keeping</h2>
                        </div>
                    </div>
                    <div class="login-slide">
                        <img class="login-slide-image" src="assets/images/Event_Image (3).jpg" alt="Login slide 3 placeholder">
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


    <?php if ($show_patch_notes && $latest_entry): ?>
<div id="patchNotesModal" class="no-scrollbar" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: color-mix(in srgb, var(--background) 75%, transparent); display: flex; justify-content: center; align-items: center; z-index: 9999; backdrop-filter: blur(18px); padding: 16px;">
    
    <div style="background: linear-gradient(180deg, color-mix(in srgb, var(--background) 84%, var(--surface)) 0%, color-mix(in srgb, var(--secondary) 14%, var(--surface)) 100%); width: min(100%, 480px); max-height: 85vh; padding: 26px; border: 1px solid var(--border); border-radius: 26px; box-shadow: var(--shadow); display: flex; flex-direction: column;">
        
        <div style="margin-bottom: 18px;">
            <div class="eyebrow" style="margin-bottom: 4px;">SYSTEM LOGS</div>
            <h2 style="margin: 0; font-family: 'ArchivoCondensedExtraBold'; font-size: 2.2rem; line-height: 1.1; background: linear-gradient(135deg, #9fcfce 0%, #6ca7a6 48%, #468181 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                Patch Notes
            </h2>
            
            <div style="margin-top: 8px; font-family: 'ArchivoBold'; font-size: 0.85rem; color: var(--muted); display: flex; gap: 12px; align-items: center;">
                <span>VERSION: <span style="color: var(--text);"><?php echo htmlspecialchars($latest_version); ?></span></span>
                <span>•</span>
                <span>RELEASED: <span style="color: var(--text);"><?php echo htmlspecialchars($release_date); ?></span></span>
            </div>
            
            <div style="margin-top: 6px; font-family: 'ArchivoCondensedMedium'; font-size: 0.9rem;">
                STATUS:
                <span style="color: <?php echo ($status === 'In Progress') ? 'var(--primary)' : 'var(--accent)'; ?>; font-weight: 900; text-transform: uppercase; letter-spacing: 0.05em;">
                    <?php echo htmlspecialchars($status); ?>
                </span>
            </div>
        </div>
        
        <div class="no-scrollbar" style="flex: 1; overflow-y: auto; margin-bottom: 20px; padding-right: 2px;">
            <?php if ($latest_entry->changes->change->count() > 0): ?>
                <div style="display: grid; gap: 10px;">
                    <?php foreach ($latest_entry->changes->change as $change): ?>
                        <?php
                            $type = isset($change['type']) ? strtolower((string)$change['type']) : 'feature';
                            
                            // Color mapping strictly derived from your system palette variables
                            switch($type) {
                                case 'feature':
                                    $badge_border = 'var(--primary)';
                                    $badge_bg = 'color-mix(in srgb, var(--primary) 20%, var(--surface))';
                                    break;
                                case 'change':
                                    $badge_border = 'var(--accent)';
                                    $badge_bg = 'color-mix(in srgb, var(--accent) 20%, var(--surface))';
                                    break;
                                case 'fix':
                                    $badge_border = '#e04c4c';
                                    $badge_bg = 'color-mix(in srgb, #e04c4c 15%, var(--surface))';
                                    break;
                                default:
                                    $badge_border = 'var(--border)';
                                    $badge_bg = 'var(--surface)';
                            }
                        ?>
                        <div style="display: flex; align-items: flex-start; gap: 12px; padding: 12px 14px; border: 1px solid var(--border); border-radius: 14px; background: var(--surface);">
                            <span style="background: <?php echo $badge_bg; ?>; color: var(--text); border: 1px solid <?php echo $badge_border; ?>; font-family: 'ArchivoCondensedExtraBold'; font-size: 0.72rem; padding: 2px 8px; border-radius: 999px; letter-spacing: 0.06em; flex-shrink: 0; margin-top: 1px;">
                                <?php echo strtoupper($type); ?>
                            </span>
                            <div style="font-family: 'ArchivoLight'; font-size: 0.92rem; line-height: 1.4; color: var(--text); word-break: break-word;">
                                <?php echo htmlspecialchars((string)$change); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="padding: 24px; border: 1px dashed var(--border); border-radius: 14px; text-align: center; background: var(--surface);">
                    <p style="margin: 0; font-family: 'ArchivoBold'; font-size: 0.95rem; color: var(--muted);">
                        No updates logged for this milestone cycle. Building in progress...
                    </p>
                </div>
            <?php endif; ?>
        </div>
        
        <button onclick="document.getElementById('patchNotesModal').style.display='none'" class="primary-button" style="width: 100%; padding: 14px; font-size: 0.95rem; letter-spacing: 0.02em;">
            Acknowledge & Proceed
        </button>
    </div>
</div>
<?php endif; ?>


    <script src="assets/script.js"></script>
</body>
</html>
