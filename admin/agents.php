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
    <title>Active Agents - Research AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/premium-theme.css" rel="stylesheet">
    <link href="/assets/css/workspace-theme.css" rel="stylesheet">
    <style>
        .agent-card { background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: 20px; padding: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); margin-bottom: 20px; }
        
        .pulse-dot { width: 10px; height: 10px; background: #10b981; border-radius: 50%; box-shadow: 0 0 10px #10b981; animation: pulse 2s infinite; }
        @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(16,185,129,0.7); } 70% { box-shadow: 0 0 0 10px rgba(16,185,129,0); } 100% { box-shadow: 0 0 0 0 rgba(16,185,129,0); } }
    </style>
	<link rel="icon" href="/assets/img/logo.png" type="image/png">
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <?php include __DIR__ . '/components/sidebar.php'; ?>

    <main class="main-content">
        <?php 
        $pageTitle = "Active Agents";
        $pageSubtitle = "Monitor long-running AI research tasks.";
        include __DIR__ . '/components/topbar.php'; 
        ?>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="agent-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-microscope text-primary fs-4"></i>
                            <h5 class="fw-bold m-0">LitReview-Agent-092</h5>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-success" style="font-size: 0.85rem; font-weight: 600;">Processing</span>
                            <div class="pulse-dot"></div>
                        </div>
                    </div>
                    <p style="color: #cbd5e1; font-size: 0.95rem; margin-bottom: 2rem;">Task: Extracting methodologies from 42 PDF documents.</p>
                    <div class="d-flex justify-content-between mb-2" style="font-size: 0.85rem; color: #94a3b8;">
                        <span>Progress (28/42)</span>
                        <span>66%</span>
                    </div>
                    <div class="progress bg-dark mb-4" style="height: 6px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width: 66%;"></div>
                    </div>
                    <div class="d-flex justify-content-between border-top pt-3" style="border-color: rgba(255,255,255,0.05) !important; font-size: 0.85rem; color: #94a3b8;">
                        <span>Owner: Administrator</span>
                        <span>Started: 14 mins ago</span>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="agent-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-chart-line text-secondary fs-4"></i>
                            <h5 class="fw-bold m-0">Data-Analyst-014</h5>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-success" style="font-size: 0.85rem; font-weight: 600;">Processing</span>
                            <div class="pulse-dot"></div>
                        </div>
                    </div>
                    <p style="color: #cbd5e1; font-size: 0.95rem; margin-bottom: 2rem;">Task: Performing linear regression on climate_data.csv.</p>
                    <div class="d-flex justify-content-between mb-2" style="font-size: 0.85rem; color: #94a3b8;">
                        <span>Model Training</span>
                        <span>89%</span>
                    </div>
                    <div class="progress bg-dark mb-4" style="height: 6px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated text-bg-secondary" style="width: 89%;"></div>
                    </div>
                    <div class="d-flex justify-content-between border-top pt-3" style="border-color: rgba(255,255,255,0.05) !important; font-size: 0.85rem; color: #94a3b8;">
                        <span>Owner: Administrator</span>
                        <span>Started: 4 mins ago</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
