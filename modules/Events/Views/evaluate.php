<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Evaluation - Walania</title>
    <link rel="icon" type="image/svg+xml" href="/Walany/assets/images/Walania.svg">
    <link rel="stylesheet" href="/Walany/assets/style.css">
</head>
<body class="evaluation-page">
    <header class="site-header login-header headbar">
        <a href="/Walany/index.php?module=Home" class="logo-placeholder" aria-label="Walania home">
            <img src="/Walany/assets/images/Walania.svg" alt="Walania logo">
        </a>
        <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle dark mode">
            <img class="theme-toggle-icon" data-theme-icon src="/Walany/assets/images/LightModeIcon.svg" alt="" aria-hidden="true">
        </button>
    </header>

    <main class="evaluation-main">
        <section class="evaluation-container" aria-labelledby="evaluation-title">
            <div class="evaluation-intro">
                <p class="evaluation-eyebrow">Event feedback</p>
                <h1 id="evaluation-title">Event Evaluation</h1>
                <p>Please provide your registration reference ID and share your event experience.</p>
            </div>

            <form class="evaluation-form" action="/Walany/index.php?module=Events&amp;action=submit_evaluation" method="POST">
                <input type="hidden" name="event_id" value="<?= htmlspecialchars($_GET['event_id'] ?? '1', ENT_QUOTES, 'UTF-8') ?>">

                <div class="evaluation-field">
                    <label for="reference_id">Registration Reference ID</label>
                    <input type="text" id="reference_id" name="reference_id" required placeholder="ABCD-1234" maxlength="9" pattern="[A-Za-z]{4}-[0-9]{4}" autocomplete="off" style="text-transform: uppercase;">
                    <small>Use the 8-character reference code from your registration.</small>
                </div>

                <div class="evaluation-field">
                    <label for="rating_metric">Overall Rating</label>
                    <select id="rating_metric" name="rating_metric" required oninvalid="this.setCustomValidity('Please select a rating from the list')" oninput="setCustomValidity('')"> 
                    <option value="">Select a rating</option>
                        <option value="5">5 ⭐⭐⭐⭐⭐ - Excellent</option>
                        <option value="4">4 ⭐⭐⭐⭐ - Very Good</option>
                        <option value="3">3 ⭐⭐⭐ - Good</option>
                        <option value="2">2 ⭐⭐ - Fair</option>
                        <option value="1">1 ⭐ - Poor</option>
                    </select>
                </div>

                <div class="evaluation-field">
                    <label for="feedback_text">Comments and Suggestions</label>
                    <textarea id="feedback_text" name="feedback_text" rows="5" required placeholder="Share your thoughts about the event experience..."></textarea>
                </div>

                <button type="submit" class="evaluation-submit">Submit Feedback</button>
            </form>
        </section>
    </main>

    <script src="/Walany/assets/script.js"></script>
</body>
</html>
