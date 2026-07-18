<?php
// Ensure $user is available
if (!isset($user) && isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

$pageTitle = $pageTitle ?? 'Dashboard';
$pageSubtitle = $pageSubtitle ?? '';
?>
<div class="topbar">
    <div>
        <h1 class="page-title"><?= htmlspecialchars($pageTitle) ?></h1>
        <?php if ($pageSubtitle): ?>
            <p class="text-muted m-0" style="font-size: 0.9rem;"><?= htmlspecialchars($pageSubtitle) ?></p>
        <?php endif; ?>
    </div>
    <div class="d-flex align-items-center gap-3">
        <?php if (isset($topbarAction)): ?>
            <?= $topbarAction ?>
        <?php endif; ?>
        
        <div class="user-profile">
            <div class="avatar" style="background: var(--primary);"><?= strtoupper(substr($user['first_name'] ?? 'A', 0, 1)) ?></div>
            <div>
                <div class="fw-bold" style="font-size: 0.9rem;"><?= htmlspecialchars(($user['first_name'] ?? 'Admin') . ' ' . ($user['last_name'] ?? '')) ?></div>
                <div class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($user['email'] ?? 'admin@research.ai') ?></div>
            </div>
            <i class="fa-solid fa-chevron-down ms-2 text-muted" style="font-size: 0.8rem; margin-right: 8px;"></i>
        </div>
    </div>
</div>
