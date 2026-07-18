<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Administrator') { header("Location: /dashboard"); exit; }
$user = $_SESSION['user'];

require_once __DIR__ . '/../core/DirectAuth.php';
$response = DirectAuth::getUsers();
$users = $response['users'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Research AI</title>
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

        .role-badge { padding: 4px 12px; border-radius: 12px; font-size: 0.75rem; font-weight: 600; }
        .role-admin { background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); }
        .role-student { background: rgba(16,185,129,0.15); color: #10b981; border: 1px solid rgba(16,185,129,0.3); }
        .role-faculty { background: rgba(99,102,241,0.15); color: #6366f1; border: 1px solid rgba(99,102,241,0.3); }

        .btn-action { background: rgba(255,255,255,0.05); color: white; border: 1px solid var(--glass-border); border-radius: 8px; padding: 6px 12px; transition: 0.3s; }
        .btn-action:hover { background: rgba(255,255,255,0.1); }
    </style>
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <?php include __DIR__ . '/components/sidebar.php'; ?>

    <main class="main-content">
        <?php 
        $pageTitle = "User Management";
        $pageSubtitle = "Manage registered users, roles, and permissions.";
        include __DIR__ . '/components/topbar.php'; 
        ?>

        <div class="glass-panel">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="input-group" style="width: 300px;">
                    <span class="input-group-text" style="background: rgba(15,23,42,0.6); border: 1px solid var(--glass-border); border-right: none; color: #94a3b8;"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" class="form-control" placeholder="Search users by name or email..." style="background: rgba(15,23,42,0.6); border: 1px solid var(--glass-border); border-left: none; color: white;">
                </div>
                <!-- Removed Add User Button since Registration handles new accounts -->
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr class="data-row">
                            <td colspan="5" class="text-center text-muted py-4">No users found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $u): ?>
                            <?php
                                $roleClass = 'role-student';
                                if ($u['role'] === 'Administrator') $roleClass = 'role-admin';
                                else if ($u['role'] === 'Faculty') $roleClass = 'role-faculty';
                                
                                $joined = date('M d, Y', strtotime($u['created_at']));
                                $fullName = htmlspecialchars($u['first_name'] . ' ' . $u['last_name']);
                            ?>
                            <tr class="data-row">
                                <td class="fw-bold"><?= $fullName ?></td>
                                <td class="text-muted"><?= htmlspecialchars($u['email']) ?></td>
                                <td><span class="role-badge <?= $roleClass ?>"><?= htmlspecialchars($u['role']) ?></span></td>
                                <td class="text-muted"><?= $joined ?></td>
                                <td>
                                    <?php if ($u['status'] === 'Banned'): ?>
                                        <span class="badge bg-danger">Banned</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn-action" title="Edit Info" data-bs-toggle="modal" data-bs-target="#editUserModal" onclick="populateEditModal(<?= htmlspecialchars(json_encode($u)) ?>)"><i class="fa-solid fa-pen"></i></button>
                                        <button class="btn-action" title="Reset Password" style="color: #f59e0b; border-color: rgba(245,158,11,0.2);" data-bs-toggle="modal" data-bs-target="#resetPasswordModal" onclick="populateResetModal('<?= htmlspecialchars($u['email']) ?>')"><i class="fa-solid fa-key"></i></button>
                                        
                                        <?php if ($u['role'] !== 'Administrator'): ?>
                                            <?php if ($u['status'] === 'Banned'): ?>
                                                <form method="POST" action="/admin/unban" class="m-0 p-0 d-inline">
                                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(Session::generateCsrfToken()) ?>">
                                                    <input type="hidden" name="id" value="<?= htmlspecialchars($u['id']) ?>">
                                                    <button type="submit" class="btn-action" title="Unban User" style="color: #10b981; border-color: rgba(16,185,129,0.2);"><i class="fa-solid fa-check"></i></button>
                                                </form>
                                            <?php else: ?>
                                                <form method="POST" action="/admin/ban" class="m-0 p-0 d-inline" onsubmit="return confirm('Are you sure you want to ban this user?');">
                                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(Session::generateCsrfToken()) ?>">
                                                    <input type="hidden" name="id" value="<?= htmlspecialchars($u['id']) ?>">
                                                    <button type="submit" class="btn-action" title="Ban User" style="color: #ef4444; border-color: rgba(239,68,68,0.2);"><i class="fa-solid fa-ban"></i></button>
                                                </form>
                                            <?php endif; ?>
                                            
                                            <form method="POST" action="/admin/delete" class="m-0 p-0 d-inline" onsubmit="return confirm('Are you sure you want to permanently delete this user? This action cannot be undone.');">
                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(Session::generateCsrfToken()) ?>">
                                                <input type="hidden" name="id" value="<?= htmlspecialchars($u['id']) ?>">
                                                <button type="submit" class="btn-action" title="Delete User" style="color: #ef4444; border-color: rgba(239,68,68,0.2);"><i class="fa-regular fa-trash-can"></i></button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: #111827; border: 1px solid rgba(255,255,255,0.1); border-radius: 20px;">
                <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <h5 class="modal-title" style="font-weight: 700;"><i class="fa-solid fa-user-pen text-primary me-2"></i> Edit User Info</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form>
                        <div class="mb-3">
                            <label class="form-label" style="color: #94a3b8; font-size: 0.9rem;">Full Name</label>
                            <input type="text" class="form-control" value="John Paul Gardoce" style="background: rgba(15,23,42,0.6); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 12px; padding: 12px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="color: #94a3b8; font-size: 0.9rem;">Email Address</label>
                            <input type="email" class="form-control" value="johnpaulgardoce1289@gmail.com" style="background: rgba(15,23,42,0.6); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 12px; padding: 12px;">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" style="color: #94a3b8; font-size: 0.9rem;">Role</label>
                            <select class="form-select" style="background: rgba(15,23,42,0.6); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 12px; padding: 12px;">
                                <option value="Student" selected>Student</option>
                                <option value="Faculty">Faculty</option>
                                <option value="Administrator">Administrator</option>
                            </select>
                        </div>
                        <button type="button" class="btn w-100" data-bs-dismiss="modal" style="background: var(--primary); color: white; font-weight: 600; padding: 12px; border-radius: 12px;">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: #111827; border: 1px solid rgba(255,255,255,0.1); border-radius: 20px;">
                <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <h5 class="modal-title" style="font-weight: 700;"><i class="fa-solid fa-key text-warning me-2"></i> Reset Password</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 20px;">Set a new password for this user. They will be notified of this change.</p>
                    <form>
                        <div class="mb-4">
                            <label class="form-label" style="color: #94a3b8; font-size: 0.9rem;">New Password</label>
                            <input type="password" class="form-control" placeholder="Enter new password" style="background: rgba(15,23,42,0.6); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 12px; padding: 12px;">
                        </div>
                        <button type="button" class="btn w-100" data-bs-dismiss="modal" style="background: #f59e0b; color: white; font-weight: 600; padding: 12px; border-radius: 12px;">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Ban User Modal -->
    <div class="modal fade" id="banUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: #111827; border: 1px solid rgba(239,68,68,0.2); border-radius: 20px;">
                <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <h5 class="modal-title text-danger" style="font-weight: 700;"><i class="fa-solid fa-ban me-2"></i> Ban or Delete Account</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div style="width: 60px; height: 60px; background: rgba(239,68,68,0.1); color: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 20px;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <h6 class="fw-bold mb-3">Are you sure?</h6>
                    <p style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 25px;">Banning this user will immediately revoke their access to the platform. This action cannot be undone easily.</p>
                    <div class="d-flex gap-3">
                        <button type="button" class="btn w-50" data-bs-dismiss="modal" style="background: rgba(255,255,255,0.1); color: white; font-weight: 600; padding: 12px; border-radius: 12px;">Cancel</button>
                        <button type="button" class="btn w-50" data-bs-dismiss="modal" id="confirmBanBtn" style="background: #ef4444; color: white; font-weight: 600; padding: 12px; border-radius: 12px;">Ban User</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let rowToDelete = null;
        
        // Add click event to all ban buttons
        document.querySelectorAll('[data-bs-target="#banUserModal"]').forEach(btn => {
            btn.addEventListener('click', function() {
                rowToDelete = this.closest('tr');
            });
        });

        // Handle the actual ban confirmation
        document.getElementById('confirmBanBtn').addEventListener('click', function() {
            if (rowToDelete) {
                rowToDelete.style.transition = 'opacity 0.4s ease';
                rowToDelete.style.opacity = '0';
                setTimeout(() => {
                    rowToDelete.remove();
                    rowToDelete = null;
                }, 400);
            }
        });
    </script>
</body>
</html>
