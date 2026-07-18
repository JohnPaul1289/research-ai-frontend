<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Administrator') { header("Location: /dashboard"); exit; }
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings - Research AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/premium-theme.css" rel="stylesheet">
    <link href="/assets/css/workspace-theme.css" rel="stylesheet">
    <style>
        .glass-panel { background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: 20px; padding: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); margin-bottom: 20px; }
        .icon-box { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); }
        
        /* Custom Toggle Switch */
        .premium-switch { position: relative; display: inline-block; width: 60px; height: 34px; }
        .premium-switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(15, 23, 42, 0.6); transition: .4s; border-radius: 34px; border: 1px solid var(--glass-border); }
        .slider:before { position: absolute; content: ""; height: 26px; width: 26px; left: 3px; bottom: 3px; background-color: #94a3b8; transition: .4s; border-radius: 50%; box-shadow: 0 2px 5px rgba(0,0,0,0.3); }
        input:checked + .slider { background: linear-gradient(135deg, #ef4444, #b91c1c); border-color: rgba(239, 68, 68, 0.5); box-shadow: 0 0 15px rgba(239,68,68,0.4); }
        input:checked + .slider:before { transform: translateX(26px); background-color: white; }
    </style>
	<link rel="icon" href="/assets/img/logo.png" type="image/png">
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <?php include __DIR__ . '/components/sidebar.php'; ?>

    <main class="main-content">
        <?php 
        $pageTitle = "System Settings";
        $pageSubtitle = "Manage global platform configurations.";
        include __DIR__ . '/components/topbar.php'; 
        ?>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="glass-panel">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-box" style="color:#ef4444; background:rgba(239, 68, 68, 0.1);"><i class="fa-solid fa-triangle-exclamation"></i></div>
                            <div>
                                <div class="fw-bold fs-5 text-white">Maintenance Mode</div>
                                <div class="text-muted" style="font-size: 0.8rem;">Disable access for users</div>
                            </div>
                        </div>
                        <label class="premium-switch">
                            <input type="checkbox" id="maintenanceToggle">
                            <span class="slider"></span>
                        </label>
                    </div>
                    <p class="text-muted mt-3" style="font-size: 0.9rem;">Turning this on will immediately block all non-admin users and display the maintenance screen. Admin sessions will remain active.</p>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('maintenanceToggle').addEventListener('change', function() {
            const isEnabled = this.checked;
            
            fetch('/api/admin/maintenance', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ enabled: isEnabled })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    alert(`Maintenance Mode ${isEnabled ? 'Enabled' : 'Disabled'}!`);
                } else {
                    alert('Error toggling maintenance mode.');
                    this.checked = !isEnabled;
                }
            })
            .catch(err => {
                console.error(err);
                alert('Network error.');
                this.checked = !isEnabled;
            });
        });
        
        fetch('/api/admin/maintenance/status')
            .then(res => res.json())
            .then(data => {
                if(data.enabled) {
                    document.getElementById('maintenanceToggle').checked = true;
                }
            });
    </script>
</body>
</html>
