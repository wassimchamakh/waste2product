<aside class="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <div class="logo-content">
            <div class="logo-icon">
                <i class="fas fa-recycle text-white text-xl"></i>
            </div>
            <div class="logo-text">
                <h1 class="text-lg font-bold text-white">
                    Waste2Product
                </h1>
                <p class="text-xs text-green-100">Administration</p>
            </div>
        </div>
        <button id="toggle-sidebar" class="toggle-btn" type="button">
            <i class="fas fa-angle-left"></i>
        </button>
    </div>

    <!-- Sidebar Navigation -->
    <nav class="sidebar-nav">
        <!-- Main Section -->
        <div class="nav-section">
            <h3 class="nav-section-title">Principal</h3>
            <ul>
                <li class="nav-item">
                    <a href="/admin" class="nav-link {{ Request::is('admin') || Request::is('admin/dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt nav-icon"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/analytics" class="nav-link {{ Request::is('admin/analytics') ? 'active' : '' }}">
                        <i class="fas fa-chart-line nav-icon"></i>
                        <span class="nav-text">Analytics</span>
                        <span class="nav-badge">Nouveau</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Content Management -->
        <div class="nav-section">
            <h3 class="nav-section-title">Gestion de contenu</h3>
            <ul>
                <li class="nav-item">
                    <a href="/admin/projects" class="nav-link {{ Request::is('admin/projects*') ? 'active' : '' }}">
                        <i class="fas fa-project-diagram nav-icon"></i>
                        <span class="nav-text">Projets</span>
                        <span class="nav-badge">12</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/dechets" class="nav-link {{ Request::is('admin/dechets*') ? 'active' : '' }}">
                        <i class="fas fa-recycle nav-icon"></i>
                        <span class="nav-text">Déchets</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/events" class="nav-link {{ Request::is('admin/evenements*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt nav-icon"></i>
                        <span class="nav-text">Événements</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/tutoriels" class="nav-link {{ Request::is('admin/tutoriels*') ? 'active' : '' }}">
                        <i class="fas fa-book nav-icon"></i>
                        <span class="nav-text">Tutoriels</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- User Management -->
        <div class="nav-section">
            <h3 class="nav-section-title">Utilisateurs</h3>
            <ul>
                <li class="nav-item">
                    <a href="/admin/users" class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}">
                        <i class="fas fa-users nav-icon"></i>
                        <span class="nav-text">Utilisateurs</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/roles" class="nav-link {{ Request::is('admin/roles*') ? 'active' : '' }}">
                        <i class="fas fa-user-shield nav-icon"></i>
                        <span class="nav-text">Rôles & Permissions</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- System -->
        <div class="nav-section">
            <h3 class="nav-section-title">Système</h3>
            <ul>
                <li class="nav-item">
                    <a href="/admin/settings" class="nav-link {{ Request::is('admin/settings*') ? 'active' : '' }}">
                        <i class="fas fa-cog nav-icon"></i>
                        <span class="nav-text">Paramètres</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/logs" class="nav-link {{ Request::is('admin/logs*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt nav-icon"></i>
                        <span class="nav-text">Logs</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/logout" class="nav-link">
                        <i class="fas fa-sign-out-alt nav-icon"></i>
                        <span class="nav-text">Déconnexion</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</aside>