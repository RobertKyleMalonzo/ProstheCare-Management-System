<?php
session_start();

// Already signed in? Go to the dashboard.
if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $token    = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        $error = 'Your session expired. Please try again.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid email address.';
    } elseif ($password === '') {
        $error = 'Enter your password.';
    } else {
        /*
         * TODO: replace with your real database lookup, e.g.
         *
         * require 'config/db.php'; // defines $pdo
         * $stmt = $pdo->prepare('SELECT id, password_hash FROM users WHERE email = ?');
         * $stmt->execute([$email]);
         * $user = $stmt->fetch(PDO::FETCH_ASSOC);
         *
         * if ($user && password_verify($password, $user['password_hash'])) {
         *     session_regenerate_id(true);
         *     $_SESSION['user_id'] = $user['id'];
         *     header('Location: dashboard.php');
         *     exit;
         * }
         */
        $error = 'Incorrect email or password.';
    }
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in | ProstheCare</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<main class="auth">

    <!-- Left: brand panel -->
    <section class="auth__brand">
        <div class="brand">
            <span class="brand__logo" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
            </span>
            <span class="brand__text">
                <span class="brand__name">ProstheCare</span>
                <span class="brand__tag">Management System</span>
            </span>
        </div>

        <div class="auth__pitch">
            <p class="eyebrow">Patient-centered care</p>
            <h2 class="auth__headline">Care coordination, made simpler.</h2>
            <p class="auth__copy">
                A secure workspace built to help prosthetic care teams stay
                organized, connected, and focused on every client.
            </p>
        </div>

        <div class="orbit" aria-hidden="true">
            <span class="orbit__ring orbit__ring--3"></span>
            <span class="orbit__ring orbit__ring--2"></span>
            <span class="orbit__ring orbit__ring--1">
                <svg viewBox="0 0 24 24" width="34" height="34" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
            </span>
            <span class="orbit__dot"></span>
        </div>

        <p class="auth__secure">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 3l8 3v6c0 4.5-3.2 8-8 9-4.8-1-8-4.5-8-9V6l8-3z"/>
                <path d="M8.5 12l2.5 2.5 4.5-5"/>
            </svg>
            Your information is encrypted and secure
        </p>
    </section>

    <!-- Right: form panel -->
    <section class="auth__form-wrap">
        <div class="auth__form">
            <p class="eyebrow eyebrow--teal">Welcome back</p>
            <h1 class="auth__title">Sign in to your account</h1>
            <p class="auth__subtitle">Enter your details to access your ProstheCare workspace.</p>

            <?php if ($error !== ''): ?>
                <div class="alert" role="alert"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="post" action="login.php" novalidate>
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

                <div class="field">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email"
                           placeholder="you@example.com"
                           value="<?= e($email) ?>"
                           autocomplete="email" required>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="field__password">
                        <input type="password" id="password" name="password"
                               placeholder="Enter your password"
                               autocomplete="current-password" required>
                        <button type="button" class="toggle-password" id="togglePassword"
                                aria-label="Show password" aria-pressed="false">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    <a class="forgot" href="forgot-password.php">Forgot password?</a>
                </div>

                <button type="submit" class="btn">Sign in</button>
            </form>

            <p class="auth__footer">
                New to ProstheCare? <a href="register.php">Create an account</a>
            </p>
        </div>
    </section>

</main>

<script src="js/login.js"></script>
</body>
</html>