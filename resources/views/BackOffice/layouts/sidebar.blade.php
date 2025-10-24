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
<<<<<<< HEAD
=======
                <!--
>>>>>>> tutoral-branch
                <li class="nav-item">
                    <a href="/admin/analytics" class="nav-link {{ Request::is('admin/analytics') ? 'active' : '' }}">
                        <i class="fas fa-chart-line nav-icon"></i>
                        <span class="nav-text">Analytics</span>
                        <span class="nav-badge">Nouveau</span>
                    </a>
                </li>
<<<<<<< HEAD
=======
                !-->
>>>>>>> tutoral-branch
            </ul>
        </div>

        <!-- Content Management -->
        <div class="nav-section">
            <h3 class="nav-section-title">Gestion de contenu</h3>
            <ul>
<<<<<<< HEAD
                <li class="nav-item">
=======
                 <li class="nav-item">
>>>>>>> tutoral-branch
                    <a href="/admin/projects" class="nav-link {{ Request::is('admin/projects*') ? 'active' : '' }}">
                        <i class="fas fa-project-diagram nav-icon"></i>
                        <span class="nav-text">Projets</span>
                        <span class="nav-badge">12</span>
                    </a>
                </li>
                <li class="nav-item">
<<<<<<< HEAD
=======
                    <a href="{{ route('admin.categories.index') }}" class="nav-link {{ Request::is('admin/categories*') ? 'active' : '' }}">
                        <i class="fas fa-tags nav-icon"></i>
                        <span class="nav-text">Catégories</span>
                    </a>
                </li>
                <li class="nav-item">
>>>>>>> tutoral-branch
                    <a href="/admin/dechets" class="nav-link {{ Request::is('admin/dechets*') ? 'active' : '' }}">
                        <i class="fas fa-recycle nav-icon"></i>
                        <span class="nav-text">Déchets</span>
                    </a>
                </li>
                <li class="nav-item">
<<<<<<< HEAD
                    <a href="/admin/events" class="nav-link {{ Request::is('admin/evenements*') ? 'active' : '' }}">
=======
                    <a href="/admin/events" class="nav-link {{ Request::is('admin/events*') ? 'active' : '' }}">
>>>>>>> tutoral-branch
                        <i class="fas fa-calendar-alt nav-icon"></i>
                        <span class="nav-text">Événements</span>
                    </a>
                </li>
                <li class="nav-item">
<<<<<<< HEAD
                    <a href="/admin/tutoriels" class="nav-link {{ Request::is('admin/tutoriels*') ? 'active' : '' }}">
=======
                    <a href="{{ route('admin.forum.index') }}" class="nav-link {{ Request::is('admin/forum*') ? 'active' : '' }}">
                        <i class="fas fa-comments nav-icon"></i>
                        <span class="nav-text">Forum</span>
                        <span class="nav-badge">Nouveau</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/tutorials" class="nav-link {{ Request::is('admin/tutorials*') ? 'active' : '' }}">
>>>>>>> tutoral-branch
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
<<<<<<< HEAD
                <li class="nav-item">
=======
                <!--<li class="nav-item">
>>>>>>> tutoral-branch
                    <a href="/admin/roles" class="nav-link {{ Request::is('admin/roles*') ? 'active' : '' }}">
                        <i class="fas fa-user-shield nav-icon"></i>
                        <span class="nav-text">Rôles & Permissions</span>
                    </a>
<<<<<<< HEAD
                </li>
=======
                </li>!-->
>>>>>>> tutoral-branch
            </ul>
        </div>

        <!-- System -->
        <div class="nav-section">
            <h3 class="nav-section-title">Système</h3>
            <ul>
                <li class="nav-item">
<<<<<<< HEAD
                    <a href="/admin/settings" class="nav-link {{ Request::is('admin/settings*') ? 'active' : '' }}">
=======
                    <a href="/admin/profile" class="nav-link {{ Request::is('admin/profile*') ? 'active' : '' }}">
>>>>>>> tutoral-branch
                        <i class="fas fa-cog nav-icon"></i>
                        <span class="nav-text">Paramètres</span>
                    </a>
                </li>
<<<<<<< HEAD
=======
                <!--
>>>>>>> tutoral-branch
                <li class="nav-item">
                    <a href="/admin/logs" class="nav-link {{ Request::is('admin/logs*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt nav-icon"></i>
                        <span class="nav-text">Logs</span>
                    </a>
                </li>
<<<<<<< HEAD
                <li class="nav-item">
                    <a href="/logout" class="nav-link">
                        <i class="fas fa-sign-out-alt nav-icon"></i>
                        <span class="nav-text">Déconnexion</span>
                    </a>
                </li>
=======
                !-->
                <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link w-full text-left">
                            <i class="fas fa-sign-out-alt nav-icon"></i>
                            <span class="nav-text">Déconnexion</span>
                        </button>
                    </form>
                </li>
    

>>>>>>> tutoral-branch
            </ul>
        </div>
    </nav>
</aside>