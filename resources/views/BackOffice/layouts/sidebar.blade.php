<aside class="sidebar h-screen shadow-lg fixed z-40">
    <div class="logo">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 icon rounded-lg flex items-center justify-center">
                <i class="fas fa-recycle text-white text-lg"></i>
            </div>
            <div class="text">
                <h1 class="text-xl font-bold text-green-700 dark:text-green-400">
                    <a href="/">Waste2Product</a>
                </h1>
                <p class="text-xs text-gray-500 dark:text-gray-400">Tunisie Durable</p>
            </div>
        </div>
        <button id="toggle-sidebar" class="text-gray-600">
            <i class="fas fa-angle-left"></i>
        </button>
    </div>
    <nav class="mt-6">
        <ul>
            <li>
                <a href="/admin" class="flex items-center py-2 px-4 {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt mr-2 icon-only"></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="/admin/dechets" class="flex items-center py-2 px-4 {{ Request::is('admin/dechets') ? 'active' : '' }}">
                    <i class="fas fa-recycle mr-2 icon-only"></i>
                    <span class="text">Déchets</span>
                </a>
            </li>
            <li>
                <a href="/admin/projets" class="flex items-center py-2 px-4 {{ Request::is('admin/projets') ? 'active' : '' }}">
                    <i class="fas fa-project-diagram mr-2 icon-only"></i>
                    <span class="text">Projets</span>
                </a>
            </li>
            <li>
                <a href="/admin/evenements" class="flex items-center py-2 px-4 {{ Request::is('admin/evenements') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt mr-2 icon-only"></i>
                    <span class="text">Événements</span>
                </a>
            </li>
            <li>
                <a href="/admin/tutoriels" class="flex items-center py-2 px-4 {{ Request::is('admin/tutoriels') ? 'active' : '' }}">
                    <i class="fas fa-book mr-2 icon-only"></i>
                    <span class="text">Tutoriels</span>
                </a>
            </li>
            <li>
                <a href="/admin/settings" class="flex items-center py-2 px-4 {{ Request::is('admin/settings') ? 'active' : '' }}">
                    <i class="fas fa-cog mr-2 icon-only"></i>
                    <span class="text">Paramètres</span>
                </a>
            </li>
            <li>
                <a href="/admin/users" class="flex items-center py-2 px-4 {{ Request::is('admin/users') ? 'active' : '' }}">
                    <i class="fas fa-users mr-2 icon-only"></i>
                    <span class="text">Utilisateurs</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
