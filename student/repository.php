<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user'])) { header("Location: /login"); exit; }
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repository - Research AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/premium-theme.css" rel="stylesheet">
    <link href="/assets/css/workspace-theme.css" rel="stylesheet">
    <style>
        .table-glass { color: var(--text-main); margin-bottom: 0; }
        .table-glass th { background: rgba(15,23,42,0.6); color: var(--text-muted); font-weight: 600; border-bottom: 1px solid var(--glass-border); padding: 16px; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .table-glass td { background: transparent; color: var(--text-main); border-bottom: 1px solid rgba(255,255,255,0.03); padding: 16px; vertical-align: middle; font-size: 0.95rem; }
        .table-glass tr:last-child td { border-bottom: none; }
        .table-glass tbody tr { transition: all 0.3s; }
        .table-glass tbody tr:hover { background: rgba(255,255,255,0.03); }

        .file-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
        .icon-pdf { background: rgba(239,68,68,0.1); color: #ef4444; }
        .icon-csv { background: rgba(16,185,129,0.1); color: #10b981; }
        .icon-doc { background: rgba(59,130,246,0.1); color: #3b82f6; }

        .search-box { position: relative; width: 300px; }
        .search-box input { background: rgba(15,23,42,0.4); border: 1px solid var(--glass-border); border-radius: 20px; padding: 10px 15px 10px 40px; color: white; width: 100%; transition: all 0.3s; }
        .search-box input:focus { background: rgba(15,23,42,0.8); border-color: var(--primary); outline: none; box-shadow: 0 0 0 4px rgba(99,102,241,0.15); }
        .search-box i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
    </style>
</head>
<body>

    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <?php include __DIR__ . '/components/sidebar.php'; ?>

    <main class="main-content">
        <?php 
        $pageTitle = "Data Repository";
        $pageSubtitle = "Store, manage, and access your research datasets and documents.";
        $topbarAction = '<button class="btn-premium" data-bs-toggle="modal" data-bs-target="#uploadDataModal"><i class="fa-solid fa-cloud-arrow-up me-2"></i>Upload File</button>';
        include __DIR__ . '/components/topbar.php'; 
        ?>

        <div class="table-responsive">
            <table class="table table-glass w-100 align-middle">
                <thead>
                    <tr>
                        <th style="width: 40%">Name</th>
                        <th style="width: 20%">Type</th>
                        <th style="width: 15%">Size</th>
                        <th style="width: 15%">Uploaded</th>
                        <th style="width: 10%" class="text-end">Actions</th>
                    </tr>
                </thead>
            <tbody>
                <tr class="file-row">
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div style="color: #ef4444; font-size: 20px;"><i class="fa-solid fa-file-pdf"></i></div>
                            <span class="fw-bold">AI_Education_Paper_2025.pdf</span>
                        </div>
                    </td>
                    <td class="text-muted">PDF Document</td>
                    <td class="text-muted">2.4 MB</td>
                    <td class="text-muted">2 days ago</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-secondary" style="border-radius: 8px; border-color: rgba(255,255,255,0.1); color: white;" onclick="downloadFile('AI_Education_Paper_2025.pdf')"><i class="fa-solid fa-download"></i></button>
                        <button class="btn btn-sm btn-outline-danger ms-1" style="border-radius: 8px; border-color: rgba(239,68,68,0.2);" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                <tr class="file-row">
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div style="color: #10b981; font-size: 20px;"><i class="fa-solid fa-file-csv"></i></div>
                            <span class="fw-bold">climate_data_2010_2020.csv</span>
                        </div>
                    </td>
                    <td class="text-muted">Dataset (CSV)</td>
                    <td class="text-muted">18.1 MB</td>
                    <td class="text-muted">1 week ago</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-secondary" style="border-radius: 8px; border-color: rgba(255,255,255,0.1); color: white;" onclick="downloadFile('climate_data_2010_2020.csv')"><i class="fa-solid fa-download"></i></button>
                        <button class="btn btn-sm btn-outline-danger ms-1" style="border-radius: 8px; border-color: rgba(239,68,68,0.2);" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
        </div>
    </main>

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
                    <button type="button" class="btn-premium w-100" data-bs-dismiss="modal">Upload to Repository</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function downloadFile(filename) {
            // Create a toast/alert or simulate download for mockup
            alert('Starting download for: ' + filename);
        }

        function deleteRow(btn) {
            if (confirm('Are you sure you want to delete this file from your repository?')) {
                const row = btn.closest('tr');
                row.style.transition = 'all 0.4s ease';
                row.style.opacity = '0';
                row.style.transform = 'translateX(20px)';
                setTimeout(() => {
                    row.remove();
                    
                    // Check if table is empty
                    const tbody = document.querySelector('.file-table tbody');
                    if (tbody.children.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="5" class="text-center text-muted" style="padding: 40px;">
                                    <i class="fa-solid fa-folder-open mb-3" style="font-size: 32px; opacity: 0.5;"></i>
                                    <p>Your repository is empty.</p>
                                </td>
                            </tr>
                        `;
                    }
                }, 400);
            }
        }
    </script>
</body>
</html>
