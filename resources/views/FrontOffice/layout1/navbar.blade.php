<!-- Navigation Header -->
<nav class="bg-white shadow-lg fixed w-full top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-primary to-success rounded-lg flex items-center justify-center">
                    <i class="fas fa-recycle text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-primary">
                        <a href="/">Waste2Product</a>
                    </h1>
                    <p class="text-xs text-gray-500">Tunisie Durable</p>
                </div>
            </div>
            
            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="/" class="nav-link {{ Request::is('/') ? 'text-primary font-medium' : 'text-gray-600 hover:text-primary' }}">
                    Accueil
                </a>
                <a href="/dechets" class="nav-link {{ Request::is('dechets*') ? 'text-primary font-medium' : 'text-gray-600 hover:text-primary' }}">
                    Déchets
                </a>
                <a href="/projects" class="nav-link {{ Request::is('projets*') ? 'text-primary font-medium' : 'text-gray-600 hover:text-primary' }}">
                    Projets
                </a>
                <a href="/events" class="nav-link {{ Request::is('evenements*') ? 'text-primary font-medium' : 'text-gray-600 hover:text-primary' }}">
                    Événements
                </a>
                <a href="/tutorials" class="nav-link {{ Request::is('tutorials*') ? 'text-primary font-medium' : 'text-gray-600 hover:text-primary' }}">
                    Tutoriels
                </a>
            </div>
            
            <!-- User Actions (Connected User) -->
            <div class="hidden md:flex items-center space-x-4">
                <!-- Notifications -->
                <div class="relative">
                    <button id="notifications-btn" class="text-gray-600 hover:text-primary p-2 relative">
                        <i class="fas fa-bell text-lg"></i>
                        <div class="notification-dot"></div>
                    </button>
                    
                    <!-- Notifications Dropdown -->
                    <div id="notifications-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-900">Notifications</h3>
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            <div class="p-3 hover:bg-gray-50 border-b border-gray-100">
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 bg-success rounded-full flex items-center justify-center">
                                        <i class="fas fa-check text-white text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-900">Votre projet "Table Basse" a été approuvé !</p>
                                        <p class="text-xs text-gray-500">Il y a 2 heures</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 hover:bg-gray-50 border-b border-gray-100">
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 bg-secondary rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-900">Ahmed souhaite récupérer vos palettes</p>
                                        <p class="text-xs text-gray-500">Il y a 4 heures</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 hover:bg-gray-50">
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 bg-accent rounded-full flex items-center justify-center">
                                        <i class="fas fa-calendar text-white text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-900">Rappel: Repair Café demain à 14h</p>
                                        <p class="text-xs text-gray-500">Il y a 6 heures</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 text-center border-t border-gray-200">
                            <a href="/notifications" class="text-primary text-sm font-medium">Voir toutes les notifications</a>
                        </div>
                    </div>
                </div>
                
                <!-- User Menu -->
                <div class="relative">
                    <button id="user-menu-btn" class="flex items-center space-x-3 p-1 rounded-lg hover:bg-gray-100">
                        <div class="w-8 h-8 bg-gradient-to-br from-primary to-success rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-medium">{{ substr(Auth::user()->name ?? 'O', 0, 1) }}</span>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">Éco-Warrior 🏆</p>
                        </div>
                        <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                    </button>
                    
                    <!-- User Dropdown -->
                    <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                        <div class="py-1">
                            <a href="/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-tachometer-alt w-4 mr-3"></i>Dashboard
                                
                            </a>
                         
                             <form method="POST" action="{{ route('profile.edit') }}">
                                @csrf
                                <button type="submit" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user w-4 mr-3"></i>Mon Profil
                                </button>
                            </form>
                            <a href="/mes-projets" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-project-diagram w-4 mr-3"></i>Mes Projets
                            </a>
                            <a href="/dechets/mesdechets" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-recycle w-4 mr-3"></i>Mes Déchets
                            </a>
                            <a href="/impact" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-chart-line w-4 mr-3"></i>Mon Impact
                            </a>
                            <a href="/parametres" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-cog w-4 mr-3"></i>Paramètres
                            </a>
                            <hr class="my-1 border-gray-200">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt w-4 mr-3"></i>Se déconnecter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden p-2">
                <i class="fas fa-bars text-gray-600"></i>
            </button>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="mobile-menu md:hidden fixed inset-y-0 left-0 w-64 bg-white shadow-xl z-50">
        <div class="p-4">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-primary to-success rounded-lg flex items-center justify-center">
                        <i class="fas fa-recycle text-white"></i>
                    </div>
                    <span class="font-bold text-primary">Waste2Product</span>
                </div>
                <button id="close-menu" class="p-2">
                    <i class="fas fa-times text-gray-600"></i>
                </button>
            </div>
            
            <nav class="space-y-4">
                <a href="/" class="mobile-nav-link block py-3 px-4 rounded-lg {{ Request::is('/') ? 'bg-primary text-white' : 'text-gray-600' }}">
                    Accueil
                </a>
                <a href="/dechets" class="mobile-nav-link block py-3 px-4 rounded-lg {{ Request::is('dechets*') ? 'bg-primary text-white' : 'text-gray-600' }}">
                    Déchets
                </a>
                <a href="/projets" class="mobile-nav-link block py-3 px-4 rounded-lg {{ Request::is('projets*') ? 'bg-primary text-white' : 'text-gray-600' }}">
                    Projets
                </a>
                <a href="/evenements" class="mobile-nav-link block py-3 px-4 rounded-lg {{ Request::is('evenements*') ? 'bg-primary text-white' : 'text-gray-600' }}">
                    Événements
                </a>
                <a href="/tutoriels" class="mobile-nav-link block py-3 px-4 rounded-lg {{ Request::is('tutoriels*') ? 'bg-primary text-white' : 'text-gray-600' }}">
                    Tutoriels
                </a>
            </nav>
            
            <div class="mt-8">
                <!-- User Profile in Mobile -->
                <div class="flex items-center space-x-3 p-3 bg-gray-100 rounded-lg mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary to-success rounded-full flex items-center justify-center">
                        <span class="text-white text-sm font-medium">AM</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Ahmed Mansouri</p>
                        <p class="text-xs text-gray-500">Éco-Warrior 🏆</p>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <a href="/dashboard" class="w-full text-left py-3 px-4 text-gray-600 hover:bg-gray-100 rounded-lg flex items-center space-x-3">
                        <i class="fas fa-tachometer-alt w-4"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="/profile" class="w-full text-left py-3 px-4 text-gray-600 hover:bg-gray-100 rounded-lg flex items-center space-x-3">
                        <i class="fas fa-user w-4"></i>
                        <span>Mon Profil</span>
                    </a>
                    <a href="/mes-projets" class="w-full text-left py-3 px-4 text-gray-600 hover:bg-gray-100 rounded-lg flex items-center space-x-3">
                        <i class="fas fa-project-diagram w-4"></i>
                        <span>Mes Projets</span>
                    </a>
                    <a href="/notifications" class="w-full text-left py-3 px-4 text-gray-600 hover:bg-gray-100 rounded-lg flex items-center space-x-3">
                        <i class="fas fa-bell w-4"></i>
                        <span>Notifications</span>
                        <span class="ml-auto bg-accent text-white text-xs px-2 py-1 rounded-full">3</span>
                    </a>
                    <a href="/logout" class="w-full text-left py-3 px-4 text-red-600 hover:bg-gray-100 rounded-lg flex items-center space-x-3">
                        <i class="fas fa-sign-out-alt w-4"></i>
                        <span>Se déconnecter</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    // Mobile Menu Toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const closeMenuBtn = document.getElementById('close-menu');

    mobileMenuBtn?.addEventListener('click', () => {
        mobileMenu.classList.add('open');
    });

    closeMenuBtn?.addEventListener('click', () => {
        mobileMenu.classList.remove('open');
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', (e) => {
        if (mobileMenu && !mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
            mobileMenu.classList.remove('open');
        }
    });

    // Dropdown toggles for connected user
    const notificationsBtn = document.getElementById('notifications-btn');
    const notificationsDropdown = document.getElementById('notifications-dropdown');
    const userMenuBtn = document.getElementById('user-menu-btn');
    const userDropdown = document.getElementById('user-dropdown');

    notificationsBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        notificationsDropdown.classList.toggle('hidden');
        userDropdown?.classList.add('hidden');
    });

    userMenuBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        userDropdown.classList.toggle('hidden');
        notificationsDropdown?.classList.add('hidden');
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', () => {
        notificationsDropdown?.classList.add('hidden');
        userDropdown?.classList.add('hidden');
    });

    // Prevent dropdown close when clicking inside
    notificationsDropdown?.addEventListener('click', (e) => e.stopPropagation());
    userDropdown?.addEventListener('click', (e) => e.stopPropagation());
</script>