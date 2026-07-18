<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Under Maintenance - Research AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/premium-theme.css" rel="stylesheet">
    <style>
        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        
        .maintenance-container {
            text-align: center;
            z-index: 10;
            max-width: 600px;
            padding: 40px;
        }

        .icon-wrapper {
            width: 100px;
            height: 100px;
            margin: 0 auto 30px;
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: var(--primary);
            box-shadow: 0 0 30px rgba(99, 102, 241, 0.2);
            animation: pulse 2s infinite ease-in-out alternate;
        }

        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 30px rgba(99, 102, 241, 0.2); }
            100% { transform: scale(1.05); box-shadow: 0 0 50px rgba(99, 102, 241, 0.4); }
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 15px;
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        p {
            color: var(--text-muted);
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .btn-premium {
            padding: 12px 30px;
            font-size: 1rem;
            border-radius: 12px;
        }
    </style>
	<link rel="icon" href="/assets/img/logo.png" type="image/png">
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="maintenance-container">
        <div class="icon-wrapper">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <h1>System Under Maintenance</h1>
        <p>We are currently upgrading the platform to bring you a better, faster, and more intelligent experience. Our engineers are hard at work. Please check back shortly.</p>
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'Administrator'): ?>
            <a href="/dashboard" class="btn-premium text-decoration-none">Return to Admin Dashboard</a>
        <?php else: ?>
            <a href="mailto:support@research.ai" class="btn btn-outline-light" style="border-radius: 12px; padding: 12px 24px;">Contact Support</a>
        <?php endif; ?>
    </div>
</body>
</html>
