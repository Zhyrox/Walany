<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        :root{
            --bg:#f6f8fb; --card:#ffffff; --accent:#2563eb; --muted:#6b7280;
            --radius:12px; --shadow:0 6px 18px rgba(15,23,42,0.08);
            font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        }
        html,body{height:100%;margin:0;}
        body{background:linear-gradient(180deg,#f8fbff 0%,var(--bg) 100%);color:#0f172a;display:flex;align-items:center;justify-content:center;}
        .wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:40px;width:100%;}
        .card{background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);padding:28px;width:100%;max-width:520px;}
        h1{margin:0 0 8px;font-size:20px}
        p.lead{margin:0 0 18px;color:var(--muted)}
        form{display:flex;flex-direction:column;gap:12px}
        label{font-size:13px;color:var(--muted);margin-bottom:6px}
        .field{display:flex;align-items:center;gap:8px;padding:10px 12px;border:1px solid #e6eef8;border-radius:8px;background:#fff}
        input[type="text"], input[type="password"], input[type="email"]{border:0;outline:0;font-size:15px;width:100%;background:transparent}
        .actions{display:flex;gap:10px;align-items:center;justify-content:space-between;margin-top:8px}
        button.primary{background:var(--accent);color:#fff;border:0;padding:10px 14px;border-radius:10px;cursor:pointer}
        a.ghost{display:inline-block;text-decoration:none;border:1px solid #e6eef8;padding:8px 12px;border-radius:10px;color:var(--muted);background:transparent}
        .toggle-password{background:transparent;border:0;color:var(--accent);cursor:pointer;font-size:13px;padding:6px;border-radius:6px}
        .note{font-size:13px;color:var(--muted);margin-top:10px}
    </style>
</head>
<body>
    <div class="wrap">
        <section class="card" aria-labelledby="loginTitle">
            <h1 id="loginTitle">Welcome Back</h1>
            <p class="lead">Sign in to continue to the registration portal.</p>

            <form action="../controllers/authController.php" method="POST" novalidate>
                <div>
                    <label for="login-username">Username</label>
                    <div class="field">
                        <input id="login-username" name="username" type="text" required autocomplete="username" />
                    </div>
                </div>

                <div>
                    <label for="login-password">Password</label>
                    <div class="field">
                        <input id="login-password" name="password" type="password" required autocomplete="current-password" />
                        <button type="button" class="toggle-password" data-target="login-password" aria-label="Show password">Show</button>
                    </div>
                </div>

                <div class="actions">
                    <button class="primary" type="submit" name="login">Login</button>
                    <a class="ghost" href="register.php">Need account?</a>
                </div>

                <p class="note">By logging in you can submit and manage your event registrations.</p>
            </form>
        </section>
    </div>

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