<?php $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>
<nav class="sidebar">
    <div class="brand-header">
        <div class="brand-logo" style="overflow:hidden;"><img src="/assets/img/logo.png" alt="Logo" style="width:100%;height:100%;object-fit:cover;"></div>
        <h4 class="m-0 fw-bold text-white fs-5">Research AI</h4>
    </div>

    <a href="/dashboard" class="nav-item <?= $path === '/dashboard' ? 'active' : '' ?>">
        <i class="fa-solid fa-gauge-high"></i> Dashboard
    </a>
    <a href="/admin/users" class="nav-item <?= $path === '/admin/users' ? 'active' : '' ?>">
        <i class="fa-solid fa-users"></i> User Management
    </a>
    <a href="/admin/engine" class="nav-item <?= $path === '/admin/engine' ? 'active' : '' ?>">
        <i class="fa-solid fa-microchip"></i> AI Engine
    </a>
    <a href="/admin/agents" class="nav-item <?= $path === '/admin/agents' ? 'active' : '' ?>">
        <i class="fa-solid fa-robot"></i> Active Agents
    </a>
    <a href="/admin/projects" class="nav-item <?= $path === '/admin/projects' ? 'active' : '' ?>">
        <i class="fa-solid fa-folder-tree"></i> Projects
    </a>
    <a href="/admin/settings" class="nav-item <?= $path === '/admin/settings' ? 'active' : '' ?>">
        <i class="fa-solid fa-gear"></i> System Settings
    </a>

    <div class="sidebar-footer">
        <a href="/logout" class="nav-item text-danger">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
        </a>
    </div>
</nav>
