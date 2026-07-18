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
    <title>Global Projects - Research AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/premium-theme.css" rel="stylesheet">
    <link href="/assets/css/workspace-theme.css" rel="stylesheet">
    <style>
        .data-table { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
        .data-table th { color: #94a3b8; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; padding: 0 20px 10px; border: none; }
        .data-row { background: rgba(15,23,42,0.6); border: 1px solid var(--glass-border); transition: all 0.3s; }
        .data-row:hover { background: rgba(30,41,59,0.8); }
        .data-row td { padding: 16px 20px; border-top: 1px solid var(--glass-border); border-bottom: 1px solid var(--glass-border); font-size: 0.95rem; }
        .data-row td:first-child { border-left: 1px solid var(--glass-border); border-radius: 12px 0 0 12px; }
        .data-row td:last-child { border-right: 1px solid var(--glass-border); border-radius: 0 12px 12px 0; }

        .status-badge { padding: 4px 12px; border-radius: 12px; font-size: 0.75rem; font-weight: 600; }
        .status-active { background: rgba(59,130,246,0.15); color: #60a5fa; border: 1px solid rgba(59,130,246,0.3); }
        .status-completed { background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.3); }
    </style>
	<link rel="icon" href="/assets/img/logo.png" type="image/png">
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <?php include __DIR__ . '/components/sidebar.php'; ?>

    <main class="main-content">
        <?php 
        $pageTitle = "Global Projects";
        $pageSubtitle = "Overview of all active and completed research projects across the platform.";
        include __DIR__ . '/components/topbar.php'; 
        ?>

        <div class="glass-panel">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="input-group" style="width: 300px;">
                    <span class="input-group-text" style="background: rgba(15,23,42,0.6); border: 1px solid var(--glass-border); border-right: none; color: #94a3b8;"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" class="form-control" placeholder="Search projects..." style="background: rgba(15,23,42,0.6); border: 1px solid var(--glass-border); border-left: none; color: white;">
                </div>
            </div>

            <div class="d-flex flex-column align-items-center justify-content-center text-center mt-4" style="min-height: 400px; background: rgba(17, 24, 39, 0.2); border: 1px dashed var(--glass-border); border-radius: 20px;">
                <div style="width: 80px; height: 80px; background: rgba(99, 102, 241, 0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #94a3b8; margin-bottom: 20px;">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
                <h4 class="fw-bold mb-2">No Projects Found</h4>
                <p class="text-muted" style="max-width: 400px;">There are currently no active projects created by students or faculty in the system. When users create projects, they will appear here.</p>
            </div>
        </div>
    </main>
</body>
</html>
