<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user'])) { header("Location: /login"); exit; }
$user = $_SESSION['user'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Projects - Research AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/premium-theme.css" rel="stylesheet">
    <link href="/assets/css/workspace-theme.css" rel="stylesheet">
    <style>
        .project-card { background: var(--bg-panel); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid var(--glass-border); border-radius: 20px; padding: 24px; transition: all 0.3s; cursor: pointer; height: 100%; display: flex; flex-direction: column; }
        .project-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.4), 0 0 20px rgba(99,102,241,0.2); border-color: rgba(99,102,241,0.4); }
        
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }
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
        $pageTitle = "My Projects";
        $pageSubtitle = "Manage your research projects and studies.";
        $topbarAction = '<button class="btn-premium" data-bs-toggle="modal" data-bs-target="#newProjectModal"><i class="fa-solid fa-plus me-2"></i>New Project</button>';
        include __DIR__ . '/components/topbar.php'; 
        ?>

    <main class="main-content">


        <div class="d-flex flex-column align-items-center justify-content-center text-center mt-2" style="min-height: 400px; background: rgba(17, 24, 39, 0.4); border: 1px solid var(--glass-border); border-radius: 20px;">
            <div style="width: 100px; height: 100px; background: rgba(99, 102, 241, 0.05); border: 1px dashed rgba(99, 102, 241, 0.3); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: #6366f1; margin-bottom: 25px;">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <h3 class="fw-bold mb-3">Your workspace is empty</h3>
            <p class="text-muted mb-4" style="font-size: 1.05rem; max-width: 400px;">Create a new research project to organize your data, literature, and analysis in one place.</p>
            <button class="btn btn-lg" data-bs-toggle="modal" data-bs-target="#newProjectModal" style="background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; padding: 12px 30px; border-radius: 30px; font-weight: 600; box-shadow: 0 5px 15px var(--primary-glow); font-size: 1rem; transition: transform 0.2s;">
                <i class="fa-solid fa-plus me-2"></i> Start New Project
            </button>
        </div>
    </main>

    <!-- New Project Modal -->
    <div class="modal fade" id="newProjectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-glass">
                <div class="modal-header modal-header-glass">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-folder-plus text-primary me-2"></i> Create New Project</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="/projects/create" method="POST">
                        <div class="mb-3">
                            <label class="form-label form-label-glass">Project Title</label>
                            <input type="text" class="form-control form-control-glass" name="title" required placeholder="e.g. AI in Education">
                        </div>
                        <div class="mb-3">
                            <label class="form-label form-label-glass">Description (Optional)</label>
                            <textarea class="form-control form-control-glass" name="description" rows="3" placeholder="Brief summary of your research..."></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label form-label-glass">Project Type</label>
                            <select class="form-select form-control-glass" name="type">
                                <option value="thesis" class="text-dark">Thesis / Dissertation</option>
                                <option value="paper" class="text-dark">Research Paper</option>
                                <option value="dataset" class="text-dark">Data Analysis</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-premium w-100">Create Project</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
