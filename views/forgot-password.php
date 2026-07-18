<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Research AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/premium-theme.css" rel="stylesheet">
    <style>
        .page-icon { width: 72px; height: 72px; background: linear-gradient(135deg, #f59e0b, #ef4444); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 1.5rem; box-shadow: 0 10px 25px rgba(245,158,11,0.3); }
        .page-title { font-weight: 700; font-size: 38px; text-align: center; margin-top: 20px; margin-bottom: 10px; }
        .page-subtitle { text-align: center; color: var(--text-muted); font-size: 15px; font-weight: 500; margin-bottom: 30px; line-height: 1.5; }

        .back-link { text-align: center; margin-top: 1.5rem; }
        .back-link a { color: var(--text-muted); text-decoration: none; font-size: 0.9rem; transition: color 0.3s; }
        .back-link a:hover { color: white; }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-bg-orbs"></div>

        <div class="auth-card" style="max-width: 440px;">
        <div class="glass-card">
            <div class="page-icon">
                <i class="fa-solid fa-key text-white"></i>
            </div>
            <h1 class="page-title">Forgot Password?</h1>
            <p class="page-subtitle">Enter your email address and we'll send you a 6-digit code to reset your password.</p>

            <?php if (isset($error)): ?>
                <div class="alert-glass"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="/forgot-password">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(Session::generateCsrfToken()) ?>">
                <div class="input-container" style="margin-bottom: 18px;">
                    <input type="email" name="email" class="form-control-premium has-icon" required placeholder="Enter your email address" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    <i class="fa-regular fa-envelope input-icon"></i>
                </div>
                <button type="submit" class="btn-premium w-100">
                    Send Reset Code <i class="fa-solid fa-paper-plane ms-2"></i>
                </button>
            </form>

            <div class="back-link">
                <a href="/login"><i class="fa-solid fa-arrow-left me-2"></i>Back to Login</a>
            </div>
        </div>
    </div>
</body>
</html>
