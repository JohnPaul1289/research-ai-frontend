<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Research AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/premium-theme.css" rel="stylesheet">
    <style>
        .brand-logo { width: 56px; height: 56px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 1.2rem; box-shadow: 0 10px 25px var(--primary-glow); position: relative; overflow: hidden; }
        .brand-logo::after { content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0) 100%); transform: rotate(45deg); animation: shine 4s infinite; }
        @keyframes shine { 0% { transform: translateX(-100%) rotate(45deg); } 20%, 100% { transform: translateX(100%) rotate(45deg); } }

        .page-title { font-weight: 700; font-size: 38px; text-align: center; margin-top: 20px; margin-bottom: 10px; background: linear-gradient(to right, #fff, #94a3b8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .page-subtitle { text-align: center; color: var(--text-muted); font-size: 15px; font-weight: 500; margin-bottom: 30px; }

        .login-link { text-align: center; margin-top: 22px; color: var(--text-muted); font-size: 15px; }
        .login-link a { color: var(--primary); font-weight: 600; position: relative; text-decoration: none; transition: all 0.3s ease; }
        .login-link a:hover { color: #60a5fa; }
        .login-link a::after { content: ''; position: absolute; width: 100%; transform: scaleX(0); height: 2px; bottom: -2px; left: 0; background-color: #60a5fa; transform-origin: bottom right; transition: transform 0.25s ease-out; }
        .login-link a:hover::after { transform: scaleX(1); transform-origin: bottom left; }
        
        .row-inputs { display: flex; gap: 18px; }
        .row-inputs .input-container { flex: 1; }
    </style>
	<link rel="icon" href="/assets/img/logo.png" type="image/png">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-bg-orbs"></div>

        <div class="auth-card" style="max-width: 500px; padding: 2.5rem;">
            <div class="brand-logo" style="padding:0;"><img src="/assets/img/logo.png" style="width:100%;height:100%;object-fit:cover;"></div>
            <h1 class="page-title">Create Account</h1>
            <p class="page-subtitle">Join the Research AI platform</p>

            <?php if (isset($error)): ?>
                <div class="alert-glass"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="/register" id="registerForm">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(Session::generateCsrfToken()) ?>">
                
                <div class="row-inputs">
                    <div class="input-container" style="margin-bottom: 18px;">
                        <input type="text" name="first_name" class="form-control-premium has-icon" required placeholder="First name" value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>">
                        <i class="fa-regular fa-user input-icon"></i>
                    </div>
                    <div class="input-container" style="margin-bottom: 18px;">
                        <input type="text" name="last_name" class="form-control-premium has-icon" required placeholder="Last name" value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>">
                        <i class="fa-regular fa-user input-icon"></i>
                    </div>
                </div>
                <div class="input-container" style="margin-bottom: 18px;">
                    <input type="email" name="email" class="form-control-premium has-icon" required placeholder="Email address" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    <i class="fa-regular fa-envelope input-icon"></i>
                </div>
                <div class="input-container" style="margin-bottom: 18px;">
                    <select name="role" class="form-select-premium" required>
                        <option value="Student" selected>Student</option>
                        <option value="Faculty">Faculty</option>
                        <option value="Research Adviser">Research Adviser</option>
                    </select>
                    <i class="fa-solid fa-graduation-cap input-icon"></i>
                    <i class="fa-solid fa-chevron-down" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none; font-size: 0.8rem;"></i>
                </div>
                <div class="input-container" style="margin-bottom: 10px;">
                    <input type="password" name="password" id="regPassword" class="form-control-premium has-icon" required placeholder="Create password (min 12 chars)" minlength="12">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <button type="button" class="toggle-password" onclick="togglePw(this)"><i class="fa-regular fa-eye"></i></button>
                </div>
                <div class="password-strength" id="strengthMeter">
                    <div class="strength-segment" id="seg1"></div>
                    <div class="strength-segment" id="seg2"></div>
                    <div class="strength-segment" id="seg3"></div>
                    <div class="strength-segment" id="seg4"></div>
                </div>
                <div class="d-flex justify-content-between" style="margin-top: 6px; margin-bottom: 15px;">
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 400; max-width: 75%; line-height: 1.3;">Must be 12+ chars, include uppercase, lowercase, number & special char.</div>
                    <div id="strengthText" style="font-size: 0.75rem; color: #8f9bba; display: none; font-weight: 600;">Strength</div>
                </div>
                <div class="input-container" style="margin-bottom: 6px;">
                    <input type="password" name="confirm_password" id="confirmPassword" class="form-control-premium has-icon" required placeholder="Confirm password" minlength="12">
                    <i class="fa-solid fa-shield-halved input-icon"></i>
                    <button type="button" class="toggle-password" onclick="togglePw(this)"><i class="fa-regular fa-eye"></i></button>
                </div>
                <div id="matchError" style="font-size: 0.8rem; color: #ef4444; margin-bottom: 18px; display: none; font-weight: 500;"><i class="fa-solid fa-circle-exclamation me-1"></i> Passwords do not match.</div>
                
                <button type="submit" class="btn-premium w-100" style="margin-top: 10px;">Create Account <i class="fa-solid fa-arrow-right ms-2"></i></button>
            </form>
            <div class="login-link">Already have an account? <a href="/login">Sign in</a></div>
        </div>
    </div>

    <script>
    function togglePw(btn) {
        const input = btn.parentElement.querySelector('input');
        const icon = btn.querySelector('i');
        if (input.type === 'password') { input.type = 'text'; icon.className = 'fa-regular fa-eye-slash'; }
        else { input.type = 'password'; icon.className = 'fa-regular fa-eye'; }
    }
    document.getElementById('regPassword')?.addEventListener('input', function() {
        const val = this.value;
        const s1 = document.getElementById('seg1');
        const s2 = document.getElementById('seg2');
        const s3 = document.getElementById('seg3');
        const s4 = document.getElementById('seg4');
        const st = document.getElementById('strengthText');
        
        let strength = 0;
        if (val.length >= 12) strength++;
        if (val.match(/[A-Z]/)) strength++;
        if (val.match(/[0-9]/)) strength++;
        if (val.match(/[^a-zA-Z0-9]/)) strength++;

        st.style.display = val.length > 0 ? 'block' : 'none';
        
        const colors = ['rgba(255,255,255,0.08)', '#ef4444', '#f59e0b', '#10b981', '#3b82f6'];
        const labels = ['', 'Weak', 'Fair', 'Good', 'Strong'];
        
        s1.style.background = strength >= 1 ? colors[strength] : colors[0];
        s2.style.background = strength >= 2 ? colors[strength] : colors[0];
        s3.style.background = strength >= 3 ? colors[strength] : colors[0];
        s4.style.background = strength >= 4 ? colors[strength] : colors[0];
        if (strength > 0) st.innerText = labels[strength];
        st.style.color = colors[strength];
    });

    // Real-time password match validation
    const regPassword = document.getElementById('regPassword');
    const confPassword = document.getElementById('confirmPassword');
    const matchError = document.getElementById('matchError');
    const regForm = document.getElementById('registerForm');

    function checkMatch() {
        if (confPassword.value.length > 0 && regPassword.value !== confPassword.value) {
            matchError.style.display = 'block';
            confPassword.style.borderColor = 'rgba(239, 68, 68, 0.5)';
        } else {
            matchError.style.display = 'none';
            confPassword.style.borderColor = 'rgba(255, 255, 255, 0.08)';
        }
    }
    
    regPassword.addEventListener('input', checkMatch);
    confPassword.addEventListener('input', checkMatch);
    
    regForm.addEventListener('submit', function(e) {
        if (regPassword.value !== confPassword.value) {
            e.preventDefault();
            matchError.style.display = 'block';
            confPassword.style.borderColor = 'rgba(239, 68, 68, 0.5)';
            confPassword.focus();
        }
    });
    </script>
</body>
</html>
