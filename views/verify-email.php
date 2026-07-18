<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Research AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/premium-theme.css" rel="stylesheet">
    <style>
        .verify-icon { width: 72px; height: 72px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 1.5rem; box-shadow: 0 10px 25px var(--primary-glow); }
        .page-title { font-weight: 800; font-size: 1.6rem; text-align: center; margin-bottom: 0.5rem; }
        .page-subtitle { text-align: center; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.3rem; }
        .email-display { text-align: center; color: var(--primary); font-weight: 600; font-size: 0.95rem; margin-bottom: 2rem; }

        .resend-section { text-align: center; margin-top: 1.5rem; color: var(--text-muted); font-size: 0.9rem; }
        .resend-btn { color: var(--primary); text-decoration: none; font-weight: 600; cursor: pointer; border: none; background: none; font-size: 0.9rem; font-family: inherit; }
        .resend-btn:hover { color: var(--secondary); }
        .resend-btn:disabled { color: var(--text-muted); cursor: not-allowed; opacity: 0.5; }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-bg-orbs"></div>

        <div class="auth-card" style="max-width: 460px;">
            <div class="verify-icon">
                <i class="fa-solid fa-envelope-circle-check text-white"></i>
            </div>
            <h1 class="page-title">Check Your Email</h1>
            <p class="page-subtitle">We sent a 6-digit verification code to</p>
            <p class="email-display"><?= htmlspecialchars(Session::get('pending_email') ?? '') ?></p>

            <?php if (isset($error)): ?>
                <div class="alert-glass"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (isset($resent)): ?>
                <div class="alert-success-glass"><i class="fa-solid fa-circle-check"></i> A new code has been sent!</div>
            <?php endif; ?>

            <form method="POST" action="/verify-email" id="otpForm">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(Session::generateCsrfToken()) ?>">
                <div class="otp-container">
                    <input type="text" maxlength="1" class="otp-digit" data-index="0" inputmode="numeric" pattern="[0-9]" autofocus>
                    <input type="text" maxlength="1" class="otp-digit" data-index="1" inputmode="numeric" pattern="[0-9]">
                    <input type="text" maxlength="1" class="otp-digit" data-index="2" inputmode="numeric" pattern="[0-9]">
                    <input type="text" maxlength="1" class="otp-digit" data-index="3" inputmode="numeric" pattern="[0-9]">
                    <input type="text" maxlength="1" class="otp-digit" data-index="4" inputmode="numeric" pattern="[0-9]">
                    <input type="text" maxlength="1" class="otp-digit" data-index="5" inputmode="numeric" pattern="[0-9]">
                </div>
                <input type="hidden" name="code" id="fullCode">
                <button type="submit" class="btn-premium w-100">Verify Email <i class="fa-solid fa-check ms-2"></i></button>
            </form>

            <div class="resend-section">
                Didn't receive the code?
                <form method="POST" action="/resend-code" style="display:inline;" id="resendForm">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(Session::generateCsrfToken()) ?>">
                    <button type="submit" class="resend-btn" id="resendBtn" disabled>Resend Code</button>
                </form>
                <span id="cooldown" style="color: var(--primary); font-weight: 600; margin-left: 5px;">(60s)</span>
            </div>
        </div>
    </div>

    <script>
    // OTP digit auto-advance logic
    const digits = document.querySelectorAll('.otp-digit');
    const fullCode = document.getElementById('fullCode');

    digits.forEach((digit, idx) => {
        digit.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value && idx < 5) digits[idx + 1].focus();
            if (this.value) this.classList.add('filled');
            else this.classList.remove('filled');
            updateFullCode();
        });

        digit.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !this.value && idx > 0) {
                digits[idx - 1].focus();
                digits[idx - 1].value = '';
                digits[idx - 1].classList.remove('filled');
            }
        });

        // Support paste of full code
        digit.addEventListener('paste', function(e) {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
            for (let i = 0; i < 6 && i < paste.length; i++) {
                digits[i].value = paste[i];
                digits[i].classList.add('filled');
            }
            if (paste.length >= 6) digits[5].focus();
            updateFullCode();
        });
    });

    function updateFullCode() {
        fullCode.value = Array.from(digits).map(d => d.value).join('');
    }

    document.getElementById('otpForm').addEventListener('submit', function(e) {
        updateFullCode();
        if (fullCode.value.length !== 6) { e.preventDefault(); digits[0].focus(); }
    });

    // Countdown Timer Logic
    const resendBtn = document.getElementById('resendBtn');
    const cooldownSpan = document.getElementById('cooldown');
    let timeLeft = 60;
    
    // Check if there is an active session storage timer
    const savedTime = sessionStorage.getItem('resendCooldown');
    const timestamp = sessionStorage.getItem('resendTimestamp');
    if (savedTime && timestamp) {
        const elapsed = Math.floor((Date.now() - timestamp) / 1000);
        if (elapsed < savedTime) {
            timeLeft = savedTime - elapsed;
        } else {
            timeLeft = 0;
        }
    }

    function updateTimer() {
        if (timeLeft > 0) {
            resendBtn.disabled = true;
            cooldownSpan.style.display = 'inline';
            cooldownSpan.innerText = `(${timeLeft}s)`;
            timeLeft--;
            sessionStorage.setItem('resendCooldown', timeLeft);
            sessionStorage.setItem('resendTimestamp', Date.now());
            setTimeout(updateTimer, 1000);
        } else {
            resendBtn.disabled = false;
            cooldownSpan.style.display = 'none';
        }
    }
    
    updateTimer();

    document.getElementById('resendForm').addEventListener('submit', function() {
        sessionStorage.setItem('resendCooldown', 60);
        sessionStorage.setItem('resendTimestamp', Date.now());
    });
    </script>
</body>
</html>
