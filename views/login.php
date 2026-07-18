<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Research AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/premium-theme.css" rel="stylesheet">
    <style>
        .brand-logo { width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 1.5rem; box-shadow: 0 10px 25px var(--primary-glow); position: relative; overflow: hidden; }
        .brand-logo::after { content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0) 100%); transform: rotate(45deg); animation: shine 4s infinite; }
        @keyframes shine { 0% { transform: translateX(-100%) rotate(45deg); } 20%, 100% { transform: translateX(100%) rotate(45deg); } }

        .login-title { font-weight: 700; font-size: 38px; text-align: center; margin-top: 20px; margin-bottom: 10px; background: linear-gradient(to right, #fff, #94a3b8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .login-subtitle { text-align: center; color: var(--text-muted); font-size: 15px; font-weight: 500; margin-bottom: 30px; }
        
        .register-link { text-align: center; margin-top: 22px; color: var(--text-muted); font-size: 15px; }
        .register-link a { color: var(--primary); font-weight: 600; position: relative; text-decoration: none; transition: all 0.3s ease; }
        .register-link a:hover { color: #60a5fa; }
        .register-link a::after { content: ''; position: absolute; width: 100%; transform: scaleX(0); height: 2px; bottom: -2px; left: 0; background-color: #60a5fa; transform-origin: bottom right; transition: transform 0.25s ease-out; }
        .register-link a:hover::after { transform: scaleX(1); transform-origin: bottom left; }
    </style>
	<link rel="icon" href="/assets/img/logo.png" type="image/png">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-bg-orbs"></div>
        <div class="auth-card">
            <div class="brand-logo" style="padding:0;"><img src="/assets/img/logo.png" style="width:100%;height:100%;object-fit:cover;"></div>
            <h1 class="login-title">Research AI</h1>
            <p class="login-subtitle">Sign in to your intelligent workspace</p>

            <?php if (isset($error)): ?>
                <div class="alert-glass"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <div class="alert-success-glass"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="/login">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(Session::generateCsrfToken()) ?>">
                
                <div class="input-container" style="margin-bottom: 18px;">
                    <input type="text" name="identifier" id="identifier" class="form-control-premium has-icon" required placeholder="Enter your email or username" value="<?= htmlspecialchars($_POST['identifier'] ?? '') ?>">
                    <i class="fa-regular fa-envelope input-icon"></i>
                </div>
                <div class="input-container" style="margin-bottom: 18px;">
                    <input type="password" name="password" id="password" class="form-control-premium has-icon" required placeholder="Enter your password">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <button type="button" class="toggle-password" onclick="togglePw(this)"><i class="fa-regular fa-eye"></i></button>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3 mb-4 px-1">
                    <label class="custom-checkbox">
                        <input type="checkbox" id="rememberMe" name="remember_me" value="1">
                        <span>Remember me</span>
                    </label>
                    <a href="/forgot-password" class="text-decoration-none" style="font-size: 15px; color: var(--primary); font-weight: 500; transition: color 0.3s;" onmouseover="this.style.color='#60a5fa'" onmouseout="this.style.color='var(--primary)'">Forgot password?</a>
                </div>
                <button type="submit" class="btn-premium w-100" style="margin-top: 10px;">Sign In <i class="fa-solid fa-arrow-right ms-2"></i></button>
            </form>
            <div class="register-link">Don't have an account? <a href="/register">Create one</a></div>
        </div>
    </div>

    <script>
    function togglePw(btn) {
        const input = btn.parentElement.querySelector('input');
        const icon = btn.querySelector('i');
        if (input.type === 'password') { input.type = 'text'; icon.className = 'fa-regular fa-eye-slash'; }
        else { input.type = 'password'; icon.className = 'fa-regular fa-eye'; }
    }
    </script>
</body>
</html>
