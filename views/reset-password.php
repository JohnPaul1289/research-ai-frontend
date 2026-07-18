<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Research AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/premium-theme.css" rel="stylesheet">
    <style>
        .page-icon { width: 72px; height: 72px; background: linear-gradient(135deg, #10b981, #06b6d4); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 1.5rem; box-shadow: 0 10px 25px rgba(16,185,129,0.3); }
        .page-title { font-weight: 700; font-size: 38px; text-align: center; margin-top: 20px; margin-bottom: 10px; }
        .page-subtitle { text-align: center; color: var(--text-muted); font-size: 15px; font-weight: 500; margin-bottom: 30px; }
        .email-display { text-align: center; color: var(--primary); font-weight: 600; }

        .section-divider { border-top: 1px solid var(--glass-border); margin: 1.5rem 0; }
        .section-label { color: var(--text-muted); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1rem; }
    </style>
	<link rel="icon" href="/assets/img/logo.png" type="image/png">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-bg-orbs"></div>

        <div class="auth-card" style="max-width: 460px;">
            <div class="page-icon">
                <i class="fa-solid fa-lock-open text-white"></i>
            </div>
            <h1 class="page-title">Reset Password</h1>
            <p class="page-subtitle">Enter the code sent to your email and choose a new password.</p>
            <p class="email-display"><?= htmlspecialchars(Session::get('reset_email') ?? '') ?></p>

            <?php if (isset($error)): ?>
                <div class="alert-glass"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="/reset-password" id="resetForm">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(Session::generateCsrfToken()) ?>">
                <div class="section-label">Verification Code</div>
                <div class="otp-container">
                    <input type="text" maxlength="1" class="otp-digit" data-index="0" inputmode="numeric" autofocus>
                    <input type="text" maxlength="1" class="otp-digit" data-index="1" inputmode="numeric">
                    <input type="text" maxlength="1" class="otp-digit" data-index="2" inputmode="numeric">
                    <input type="text" maxlength="1" class="otp-digit" data-index="3" inputmode="numeric">
                    <input type="text" maxlength="1" class="otp-digit" data-index="4" inputmode="numeric">
                    <input type="text" maxlength="1" class="otp-digit" data-index="5" inputmode="numeric">
                </div>
                <input type="hidden" name="code" id="fullCode">

                <div class="section-divider"></div>
                <div class="section-label">New Password</div>

                <div class="input-container" style="margin-bottom: 18px;">
                    <input type="password" name="password" id="newPassword" class="form-control-premium has-icon" required placeholder="New password (min 8 chars)" minlength="8">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <button type="button" class="toggle-password" onclick="togglePw(this)"><i class="fa-regular fa-eye"></i></button>
                </div>
                <div class="password-strength" id="strengthMeter">
                    <div class="strength-segment" id="seg1"></div>
                    <div class="strength-segment" id="seg2"></div>
                    <div class="strength-segment" id="seg3"></div>
                    <div class="strength-segment" id="seg4"></div>
                </div>
                <div id="strengthText" style="font-size: 0.75rem; color: #8f9bba; margin-top: 6px; margin-bottom: 18px; text-align: right; display: none; font-weight: 500;">Password strength</div>

                <div class="input-container" style="margin-bottom: 28px;">
                    <input type="password" name="confirm_password" class="form-control-premium has-icon" required placeholder="Confirm new password" minlength="8">
                    <i class="fa-solid fa-shield-halved input-icon"></i>
                    <button type="button" class="toggle-password" onclick="togglePw(this)"><i class="fa-regular fa-eye"></i></button>
                </div>

                <button type="submit" class="btn-premium w-100">Reset Password <i class="fa-solid fa-check ms-2"></i></button>
            </form>
        </div>
    </div>

    <script>
    // OTP logic
    const digits = document.querySelectorAll('.otp-digit');
    const fullCode = document.getElementById('fullCode');
    digits.forEach((d, i) => {
        d.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value && i < 5) digits[i+1].focus();
            this.classList.toggle('filled', !!this.value);
            fullCode.value = Array.from(digits).map(x => x.value).join('');
        });
        d.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !this.value && i > 0) { digits[i-1].focus(); digits[i-1].value = ''; digits[i-1].classList.remove('filled'); }
        });
        d.addEventListener('paste', function(e) {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
            for (let j = 0; j < 6 && j < paste.length; j++) { digits[j].value = paste[j]; digits[j].classList.add('filled'); }
            fullCode.value = Array.from(digits).map(x => x.value).join('');
        });
    });

    // Toggle password
    function togglePw(btn) {
        const input = btn.parentElement.querySelector('input');
        const icon = btn.querySelector('i');
        if (input.type === 'password') { input.type = 'text'; icon.className = 'fa-regular fa-eye-slash'; }
        else { input.type = 'password'; icon.className = 'fa-regular fa-eye'; }
    }

    // Strength bar
    document.getElementById('newPassword')?.addEventListener('input', function() {
        const val = this.value;
        const s1 = document.getElementById('seg1');
        const s2 = document.getElementById('seg2');
        const s3 = document.getElementById('seg3');
        const s4 = document.getElementById('seg4');
        const st = document.getElementById('strengthText');
        
        let strength = 0;
        if (val.length >= 8) strength++;
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

    document.getElementById('resetForm').addEventListener('submit', function(e) {
        fullCode.value = Array.from(digits).map(x => x.value).join('');
        if (fullCode.value.length !== 6) { e.preventDefault(); digits[0].focus(); }
    });
    </script>
</body>
</html>
