<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Research AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/premium-theme.css" rel="stylesheet">
    <link href="/assets/css/workspace-theme.css" rel="stylesheet">
</head>
<body>

    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <?php include __DIR__ . '/components/sidebar.php'; ?>

    <main class="main-content">
        <?php 
        $pageTitle = "Welcome back, " . ($user['first_name'] ?? 'Student') . "!";
        $pageSubtitle = "Here's what's happening with your research today.";
        include __DIR__ . '/components/topbar.php'; 
        ?>


        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <a href="/chat" style="text-decoration:none;">
                    <div class="action-card">
                        <div class="action-icon" style="background: rgba(99,102,241,0.1); color: #6366f1;">
                            <i class="fa-solid fa-message"></i>
                        </div>
                        <div>
                            <div class="action-title">Chat with AI</div>
                            <div class="action-desc">Get help from your research assistant</div>
                        </div>
                    </div>
                </a>
                
                <div class="action-card" data-bs-toggle="modal" data-bs-target="#newProjectModal" style="cursor:pointer;">
                    <div class="action-icon" style="background: rgba(168,85,247,0.1); color: #a855f7;">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <div>
                        <div class="action-title">New Project</div>
                        <div class="action-desc">Start a new research project</div>
                    </div>
                </div>

                <div class="action-card" data-bs-toggle="modal" data-bs-target="#uploadDataModal" style="cursor:pointer;">
                    <div class="action-icon" style="background: rgba(16,185,129,0.1); color: #10b981;">
                        <i class="fa-solid fa-upload"></i>
                    </div>
                    <div>
                        <div class="action-title">Upload Data</div>
                        <div class="action-desc">Add datasets for analysis</div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="glass-panel d-flex flex-column align-items-center justify-content-center text-center" style="min-height: 300px;">
                    <div style="width: 80px; height: 80px; background: rgba(99, 102, 241, 0.05); border: 1px dashed rgba(99, 102, 241, 0.3); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #6366f1; margin-bottom: 20px;">
                        <i class="fa-solid fa-folder-open"></i>
                    </div>
                    <h4 class="fw-bold mb-2">No Active Projects</h4>
                    <p class="text-muted mb-4" style="font-size: 0.95rem; max-width: 300px;">You haven't started any research projects yet. Create your first project to begin.</p>
                    <button class="btn" data-bs-toggle="modal" data-bs-target="#newProjectModal" style="background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; padding: 10px 24px; border-radius: 30px; font-weight: 600; box-shadow: 0 5px 15px var(--primary-glow); transition: transform 0.2s;">
                        <i class="fa-solid fa-plus me-2"></i> Create New Project
                    </button>
                </div>
            </div>
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

    <!-- Upload Data Modal -->
    <div class="modal fade" id="uploadDataModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-glass">
                <div class="modal-header modal-header-glass">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-cloud-arrow-up text-success me-2"></i> Upload Dataset or Document</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div style="border: 2px dashed rgba(255,255,255,0.2); border-radius: 16px; padding: 40px 20px; background: rgba(15,23,42,0.4); margin-bottom: 20px;">
                        <i class="fa-solid fa-file-arrow-up" style="font-size: 48px; color: #64748b; margin-bottom: 15px;"></i>
                        <h6 style="color: white; font-weight: 600;">Drag & Drop files here</h6>
                        <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 15px;">Supports PDF, CSV, DOCX, TXT (Max 50MB)</p>
                        <button class="btn btn-sm" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2); border-radius: 8px;">Browse Files</button>
                    </div>
                    <button type="button" class="btn w-100" data-bs-dismiss="modal" style="background: linear-gradient(135deg, #10b981, #059669); color: white; font-weight: 600; padding: 12px; border-radius: 12px;">Upload to Repository</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
