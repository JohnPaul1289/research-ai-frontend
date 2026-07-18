<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Research AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/premium-theme.css" rel="stylesheet">
    <link href="/assets/css/workspace-theme.css" rel="stylesheet">
    <style>
        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .metric-title {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .metric-value {
            font-size: 2.5rem;
            font-weight: 800;
            margin: 0;
            display: flex;
            align-items: baseline;
            gap: 10px;
        }

        .metric-trend.positive { color: #10b981; font-size: 1rem; }
        .metric-trend.negative { color: #ef4444; font-size: 1rem; }

        /* Tables */
        .table-custom { color: var(--text-main); margin: 0; }
        .table-custom th { border-bottom: 1px solid var(--glass-border); color: var(--text-muted); font-weight: 500; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; padding: 1rem; }
        .table-custom td { border-bottom: 1px solid rgba(255,255,255,0.03); padding: 1rem; vertical-align: middle; }
        .table-custom tbody tr:hover { background: rgba(255,255,255,0.02); }

        /* Status Badges */
        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; letter-spacing: 0.5px; }
        .status-active { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(52, 211, 153, 0.2); }
        .status-processing { background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(96, 165, 250, 0.2); }
        .status-offline { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(248, 113, 113, 0.2); }

        /* Icon Wrapper */
        .icon-box.primary { color: var(--primary); background: rgba(99, 102, 241, 0.1); border-color: rgba(99, 102, 241, 0.2); }
        .icon-box.secondary { color: var(--secondary); background: rgba(168, 85, 247, 0.1); border-color: rgba(168, 85, 247, 0.2); }

        /* Custom Toggle Switch for Maintenance */
        .premium-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        .premium-switch input { opacity: 0; width: 0; height: 0; }
        .slider {
            position: absolute; cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(15, 23, 42, 0.6);
            transition: .4s;
            border-radius: 34px;
            border: 1px solid var(--glass-border);
        }
        .slider:before {
            position: absolute; content: "";
            height: 26px; width: 26px;
            left: 3px; bottom: 3px;
            background-color: #94a3b8;
            transition: .4s;
            border-radius: 50%;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }
        input:checked + .slider {
            background: linear-gradient(135deg, #ef4444, #b91c1c);
            border-color: rgba(239, 68, 68, 0.5);
            box-shadow: 0 0 15px rgba(239,68,68,0.4);
        }
        input:checked + .slider:before {
            transform: translateX(26px);
            background-color: white;
        }
    </style>
</head>
<body>

    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <!-- Sidebar Navigation -->
    <?php include __DIR__ . '/components/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <?php 
        $pageTitle = "Platform Overview";
        $pageSubtitle = "";
        include __DIR__ . '/components/topbar.php'; 
        
        require_once __DIR__ . '/../core/DirectAuth.php';
        $statsResponse = DirectAuth::getStats();
        $stats = $statsResponse ?? ['users' => 0, 'projects' => 0, 'agents' => 15, 'uptime' => '99.9%'];
        ?>

        <!-- Top Metrics Row -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="glass-panel d-flex justify-content-between align-items-center">
                    <div>
                        <div class="metric-title">Total Users</div>
                        <div class="metric-value"><?= number_format($stats['users']) ?> <span class="metric-trend positive"><i class="fa-solid fa-arrow-trend-up"></i> 12%</span></div>
                    </div>
                    <div class="icon-box primary"><i class="fa-solid fa-users"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-panel d-flex justify-content-between align-items-center">
                    <div>
                        <div class="metric-title">Active Projects</div>
                        <div class="metric-value"><?= number_format($stats['projects']) ?> <span class="metric-trend positive"><i class="fa-solid fa-arrow-trend-up"></i> 5%</span></div>
                    </div>
                    <div class="icon-box secondary"><i class="fa-solid fa-book-open"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-panel d-flex justify-content-between align-items-center">
                    <div>
                        <div class="metric-title">API Requests</div>
                        <div class="metric-value">84.2k <span class="metric-trend positive"><i class="fa-solid fa-arrow-trend-up"></i> 24%</span></div>
                    </div>
                    <div class="icon-box" style="color: #10b981; background: rgba(16, 185, 129, 0.1);"><i class="fa-solid fa-bolt"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-panel d-flex justify-content-between align-items-center">
                    <div>
                        <div class="metric-title">Avg Latency</div>
                        <div class="metric-value">124<span style="font-size:1rem; color:var(--text-muted); margin-left:5px;">ms</span></div>
                    </div>
                    <div class="icon-box" style="color: #f59e0b; background: rgba(245, 158, 11, 0.1);"><i class="fa-solid fa-stopwatch"></i></div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-8">
                <div class="glass-panel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold m-0">Platform Growth</h5>
                        <select class="form-select form-select-sm bg-transparent text-white border-secondary" style="width: 120px;">
                            <option>Last 30 Days</option>
                            <option>This Year</option>
                        </select>
                    </div>
                    <!-- Chart Canvas -->
                    <div style="height: 300px; position: relative;">
                        <canvas id="growthChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4 d-flex flex-column gap-4">
                <div class="glass-panel" style="flex: 1;">
                    <h5 class="fw-bold mb-4">Infrastructure Status</h5>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-box" style="width:40px; height:40px; font-size:1.1rem; color:#f97316; background:rgba(249, 115, 22, 0.1);"><i class="fa-brands fa-rust"></i></div>
                            <div>
                                <div class="fw-bold">Rust Engine</div>
                                <div class="text-muted" style="font-size: 0.8rem;">127.0.0.1:8080</div>
                            </div>
                        </div>
                        <span class="status-badge status-active">Online</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-box" style="width:40px; height:40px; font-size:1.1rem; color:#3b82f6; background:rgba(59, 130, 246, 0.1);"><i class="fa-solid fa-database"></i></div>
                            <div>
                                <div class="fw-bold">PostgreSQL DB</div>
                                <div class="text-muted" style="font-size: 0.8rem;">Local Network</div>
                            </div>
                        </div>
                        <span class="status-badge status-active">Online</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-box" style="width:40px; height:40px; font-size:1.1rem; color:#ec4899; background:rgba(236, 72, 153, 0.1);"><i class="fa-solid fa-brain"></i></div>
                            <div>
                                <div class="fw-bold">Local LLM</div>
                                <div class="text-muted" style="font-size: 0.8rem;">Llama-3-8B</div>
                            </div>
                        </div>
                        <span class="status-badge status-processing">Processing</span>
                    </div>

                    <div class="mt-4 pt-3 border-top" style="border-color: var(--glass-border) !important;">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted" style="font-size: 0.85rem;">System Memory Load</span>
                            <span class="text-white fw-bold" style="font-size: 0.85rem;">64%</span>
                        </div>
                        <div class="progress bg-dark" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: 64%; background: linear-gradient(90deg, var(--primary), var(--secondary));"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Agents Table -->
        <div class="row">
            <div class="col-12">
                <div class="glass-panel">
                    <h5 class="fw-bold mb-4">Active AI Agents Monitoring</h5>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Agent Name</th>
                                    <th>Assigned Project</th>
                                    <th>Current Task</th>
                                    <th>Status</th>
                                    <th>Memory Usage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><div class="d-flex align-items-center gap-2"><i class="fa-solid fa-microscope text-primary"></i> Data Analyst Agent</div></td>
                                    <td>Impact of AI in Education</td>
                                    <td class="text-muted">Analyzing CSV dataset...</td>
                                    <td><span class="status-badge status-processing">Working</span></td>
                                    <td>142 MB</td>
                                </tr>
                                <tr>
                                    <td><div class="d-flex align-items-center gap-2"><i class="fa-solid fa-pen-nib text-secondary"></i> Writing Assistant</div></td>
                                    <td>Climate Change Effects</td>
                                    <td class="text-muted">Drafting Literature Review</td>
                                    <td><span class="status-badge status-active">Idle</span></td>
                                    <td>89 MB</td>
                                </tr>
                                <tr>
                                    <td><div class="d-flex align-items-center gap-2"><i class="fa-solid fa-magnifying-glass text-info"></i> Literature Scraper</div></td>
                                    <td>Quantum Computing Trends</td>
                                    <td class="text-muted">Fetching sources from ArXiv</td>
                                    <td><span class="status-badge status-processing">Fetching</span></td>
                                    <td>210 MB</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Chart.js and Custom Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/assets/js/admin.js"></script>
</body>
</html>
