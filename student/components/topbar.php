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
            <div class="avatar">
                <?= strtoupper(substr($user['first_name'] ?? 'S', 0, 1)) ?>
            </div>
            <div>
                <div class="fw-bold" style="font-size: 0.85rem;">
                    <?= htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?>
                </div>
                <div class="text-muted" style="font-size: 0.7rem;">
                    <?= htmlspecialchars($user['role'] ?? 'Student') ?>
                </div>
            </div>
        </div>
    </div>
</div>
