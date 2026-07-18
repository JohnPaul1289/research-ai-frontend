<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>
<div class="sidebar">
    <div class="brand-header">
        <div class="brand-logo" style="overflow:hidden;">
            <img src="/assets/img/logo.png" alt="Logo" style="width:100%;height:100%;object-fit:cover;">
        </div>
        <h4 class="brand-text">Research AI</h4>
    </div>

    <nav class="nav-menu">
        <a href="/dashboard" class="nav-item <?= $path === '/dashboard' ? 'active' : '' ?>">
            <i class="fa-solid fa-chart-pie nav-icon"></i>
            Dashboard
        </a>
        <a href="/projects" class="nav-item <?= $path === '/projects' ? 'active' : '' ?>">
            <i class="fa-solid fa-folder-open nav-icon"></i>
            My Projects
        </a>
        <a href="/chat" class="nav-item <?= $path === '/chat' ? 'active' : '' ?>">
            <i class="fa-solid fa-robot nav-icon"></i>
            AI Chat
        </a>
        <a href="/repository" class="nav-item <?= $path === '/repository' ? 'active' : '' ?>">
            <i class="fa-solid fa-book-bookmark nav-icon"></i>
            Repository
        </a>
        <a href="/settings" class="nav-item <?= $path === '/settings' ? 'active' : '' ?>">
            <i class="fa-solid fa-gear nav-icon"></i>
            Settings
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="/logout" class="logout-btn">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
        </a>
    </div>
</div>
