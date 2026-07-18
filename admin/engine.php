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
    <title>AI Engine - Research AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/premium-theme.css" rel="stylesheet">
    <link href="/assets/css/workspace-theme.css" rel="stylesheet">
    <style>
        .terminal-box { background: #000; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 20px; font-family: 'Courier New', monospace; font-size: 0.85rem; color: #10b981; height: 300px; overflow-y: auto; }
        .terminal-line { margin-bottom: 5px; }
        .terminal-time { color: #64748b; margin-right: 10px; }
        .terminal-info { color: #3b82f6; }
        .terminal-warn { color: #f59e0b; }
        
        .stat-card { background: rgba(15,23,42,0.6); border: 1px solid var(--glass-border); border-radius: 16px; padding: 20px; display: flex; align-items: center; gap: 20px; }
        .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
    </style>
	<link rel="icon" href="/assets/img/logo.png" type="image/png">
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <?php include __DIR__ . '/components/sidebar.php'; ?>

    <main class="main-content">
        <?php 
        $pageTitle = "AI Engine Status";
        $pageSubtitle = "Monitor the Rust backend and Local LLM performance.";
        include __DIR__ . '/components/topbar.php'; 
        ?>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(16,185,129,0.15); color: #10b981;"><i class="fa-solid fa-server"></i></div>
                    <div>
                        <div class="text-muted" style="font-size: 0.85rem; text-transform: uppercase;">Engine Status</div>
                        <h4 class="fw-bold m-0">Online (v1.0)</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(245,158,11,0.15); color: #f59e0b;"><i class="fa-solid fa-memory"></i></div>
                    <div>
                        <div class="text-muted" style="font-size: 0.85rem; text-transform: uppercase;">Memory Usage</div>
                        <h4 class="fw-bold m-0">2.4 GB / 8 GB</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(59,130,246,0.15); color: #3b82f6;"><i class="fa-solid fa-bolt"></i></div>
                    <div>
                        <div class="text-muted" style="font-size: 0.85rem; text-transform: uppercase;">Avg Latency</div>
                        <h4 class="fw-bold m-0">124 ms</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-panel">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0"><i class="fa-solid fa-terminal text-primary me-2"></i> Engine Logs</h5>
                <button class="btn btn-sm btn-outline-light" style="border-radius: 8px;"><i class="fa-solid fa-rotate-right"></i> Refresh</button>
            </div>
            <div class="terminal-box">
                <div class="terminal-line"><span class="terminal-time">[2026-07-18 20:54:12]</span> <span class="terminal-info">INFO:</span> Starting Research AI Engine on 127.0.0.1:8080</div>
                <div class="terminal-line"><span class="terminal-time">[2026-07-18 20:54:12]</span> <span class="terminal-info">INFO:</span> Connected to PostgreSQL database (local)</div>
                <div class="terminal-line"><span class="terminal-time">[2026-07-18 20:54:15]</span> <span class="terminal-info">INFO:</span> Local LLM loaded successfully (Llama-3-8B)</div>
                <div class="terminal-line"><span class="terminal-time">[2026-07-18 20:55:02]</span> <span class="terminal-info">HTTP:</span> POST /api/auth/register [201 Created] - 45ms</div>
                <div class="terminal-line"><span class="terminal-time">[2026-07-18 20:56:30]</span> <span class="terminal-info">HTTP:</span> POST /api/auth/login [200 OK] - 82ms</div>
                <div class="terminal-line"><span class="terminal-time">[2026-07-18 21:00:15]</span> <span class="terminal-info">HTTP:</span> POST /api/chat [200 OK] - 845ms</div>
                <div class="terminal-line"><span class="terminal-time">[2026-07-18 21:05:22]</span> <span class="terminal-info">HTTP:</span> GET /api/stats [200 OK] - 12ms</div>
                <div class="terminal-line"><span class="terminal-time">[2026-07-18 21:08:45]</span> <span class="terminal-warn">WARN:</span> High memory pressure detected in Agent Pool (85%)</div>
                <div class="terminal-line"><span class="terminal-time">[2026-07-18 21:08:50]</span> <span class="terminal-info">INFO:</span> Garbage collection completed. Freed 420MB.</div>
            </div>
        </div>
    </main>
</body>
</html>
