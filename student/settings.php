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
    <title>Settings - Research AI</title>
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
        $pageTitle = "Account Settings";
        $pageSubtitle = "Update your profile and manage security.";
        include __DIR__ . '/components/topbar.php'; 
        ?>
        <div class="row justify-content-center mt-3">
            <div class="col-lg-8">
                
                <div class="glass-panel mb-4">
                    <h5 class="fw-bold mb-4"><i class="fa-solid fa-user text-primary me-2"></i> Profile Information</h5>
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label form-label-glass">First Name</label>
                                <input type="text" class="form-control form-control-glass" value="<?= htmlspecialchars($user['first_name']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label form-label-glass">Last Name</label>
                                <input type="text" class="form-control form-control-glass" value="<?= htmlspecialchars($user['last_name']) ?>">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label form-label-glass">Email Address</label>
                                <input type="email" class="form-control form-control-glass" value="<?= htmlspecialchars($user['email']) ?>" disabled style="opacity: 0.7;">
                            </div>
                            <div class="col-md-12 mt-4">
                                <button type="button" class="btn-premium">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="glass-panel">
                    <h5 class="fw-bold mb-4"><i class="fa-solid fa-lock text-secondary me-2"></i> Security</h5>
                    <form>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label form-label-glass">Current Password</label>
                                <input type="password" class="form-control form-control-glass" placeholder="Enter current password">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label form-label-glass">New Password</label>
                                <input type="password" class="form-control form-control-glass" placeholder="New password">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label form-label-glass">Confirm New Password</label>
                                <input type="password" class="form-control form-control-glass" placeholder="Confirm new password">
                            </div>
                            <div class="col-md-12 mt-4">
                                <button type="button" class="btn btn-outline-light" style="border-radius: 12px; padding: 12px 24px;">Update Password</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
