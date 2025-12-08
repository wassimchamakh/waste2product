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
                        <a href="/home">Waste2Product</a>
                    </h1>
                    <p class="text-xs text-gray-500">Tunisie Durable</p>
                </div>
            </div>
            
            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="/home" class="nav-link {{ Request::is('/') ? 'text-primary font-medium' : 'text-gray-600 hover:text-primary' }}">
                    Accueil
                </a>
                <a href="/dechets" class="nav-link {{ Request::is('dechets*') ? 'text-primary font-medium' : 'text-gray-600 hover:text-primary' }}">
                    Déchets
                </a>
                <a href="/projects" class="nav-link {{ Request::is('projects*') ? 'text-primary font-medium' : 'text-gray-600 hover:text-primary' }}">
                    Projets
                </a>
                <a href="/events" class="nav-link {{ Request::is('events*') ? 'text-primary font-medium' : 'text-gray-600 hover:text-primary' }}">
                    Événements
                </a>
                <a href="{{ route('forum.index') }}" class="nav-link {{ Request::is('forum*') ? 'text-primary font-medium' : 'text-gray-600 hover:text-primary' }}">
                     Forum
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
                        @if($unreadNotificationsCount > 0)
                            <span class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                                {{ $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount }}
                            </span>
                        @endif
                    </button>
                    
                    <!-- Notifications Dropdown -->
                                        <!-- Notification Dropdown -->
                    <div id="notifications-dropdown"
                        class="hidden absolute right-0 top-full mt-2 w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
                        style="max-height: 500px; overflow-y: auto;">
                        <!-- Header -->
                        <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-green-50 to-emerald-50">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-bell text-green-600"></i>
                                <span>Notifications</span>
                                @if($unreadNotificationsCount > 0)
                                <span class="ml-2 px-2 py-0.5 bg-green-600 text-white text-xs rounded-full">{{ $unreadNotificationsCount }}</span>
                                @endif
                            </h3>
                            @if($unreadNotificationsCount > 0)
                            <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors duration-200">
                                    <i class="fas fa-check-double mr-1"></i>
                                    Tout marquer comme lu
                                </button>
                            </form>
                            @endif
                        </div>

                        <!-- Notifications List -->
                        <div class="divide-y divide-gray-100">
                            @forelse($notifications as $notification)
                            <!-- Notification Item -->
                            <a href="{{ $notification->link ?? '#' }}" 
                               class="block p-3 hover:bg-gradient-to-r hover:from-gray-50 hover:to-{{ $notification->color ?? 'green' }}-50 transition-all duration-200 group {{ !$notification->is_read ? 'bg-blue-50' : '' }}">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-{{ $notification->color ?? 'green' }}-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                        <i class="{{ $notification->icon ?? 'fas fa-bell' }} text-{{ $notification->color ?? 'green' }}-600"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 mb-1">{{ $notification->title }}</p>
                                        <p class="text-xs text-gray-600 mb-1">{{ $notification->message }}</p>
                                        <p class="text-xs text-gray-400">
                                            <i class="far fa-clock mr-1"></i>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    @if(!$notification->is_read)
                                    <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST" class="flex-shrink-0" onclick="event.stopPropagation();">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-gray-400 hover:text-green-600 transition-colors duration-200">
                                            <i class="fas fa-check text-sm"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </a>
                            @empty
                            <!-- No Notifications -->
                            <div class="p-6 text-center">
                                <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-bell-slash text-gray-400 text-2xl"></i>
                                </div>
                                <p class="text-sm text-gray-600 font-medium mb-1">Aucune notification</p>
                                <p class="text-xs text-gray-400">Vous êtes à jour !</p>
                            </div>
                            @endforelse
                        </div>

                        <!-- Footer -->
                        @if($notifications->isNotEmpty())
                        <div class="px-4 py-3 bg-gray-50 text-center border-t border-gray-200">
                            <a href="{{ route('notifications.index') }}"
                                class="text-sm font-medium text-green-600 hover:text-green-700 transition-colors duration-200 inline-flex items-center gap-2">
                                <span>Voir toutes les notifications</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                        @endif
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
                            <a href="/home" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-tachometer-alt w-4 mr-3"></i>Dashboard
                                
                            </a>
                         
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user w-4 mr-3"></i>Mon Profil
                            </a>
                            <a href="/projects/mesprojets" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-project-diagram w-4 mr-3"></i>Mes Projets
                            </a>
                            <a href="/dechets/mesdechets" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-recycle w-4 mr-3"></i>Mes Déchets
                            </a>
                            <a href="/events/myEvents" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-chart-line w-4 mr-3"></i>Mon Evenements
                            </a>
                            <a href="/tutorials" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-cog w-4 mr-3"></i>Tutorials
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
            
            <!-- Mobile Menu Button & Notifications -->
            <div class="md:hidden flex items-center space-x-2">
                <!-- Mobile Notifications Button -->
                <a href="{{ route('notifications.index') }}" class="relative p-2">
                    <i class="fas fa-bell text-gray-600 text-lg"></i>
                    @if($unreadNotificationsCount > 0)
                        <span class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                            {{ $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount }}
                        </span>
                    @endif
                </a>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="p-2">
                    <i class="fas fa-bars text-gray-600 text-xl"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="mobile-menu md:hidden fixed inset-y-0 left-0 w-full max-w-sm bg-white shadow-xl z-50 transform -translate-x-full transition-transform duration-300 ease-in-out overflow-y-auto">
        <div class="p-4 sm:p-6">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-primary to-success rounded-lg flex items-center justify-center">
                        <i class="fas fa-recycle text-white"></i>
                    </div>
                    <span class="font-bold text-primary">Waste2Product</span>
                </div>
                <button id="close-menu" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-times text-gray-600 text-xl"></i>
                </button>
            </div>
            
            <nav class="space-y-2">
                <a href="/home" class="mobile-nav-link block py-3 px-4 rounded-lg transition-colors {{ Request::is('home') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-home w-5 mr-3"></i>Accueil
                </a>
                <a href="/dechets" class="mobile-nav-link block py-3 px-4 rounded-lg transition-colors {{ Request::is('dechets*') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-trash-alt w-5 mr-3"></i>Déchets
                </a>
                <a href="/projects" class="mobile-nav-link block py-3 px-4 rounded-lg transition-colors {{ Request::is('projects*') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-project-diagram w-5 mr-3"></i>Projets
                </a>
                <a href="/events" class="mobile-nav-link block py-3 px-4 rounded-lg transition-colors {{ Request::is('events*') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-calendar-alt w-5 mr-3"></i>Événements
                </a>
                <a href="{{ route('forum.index') }}" class="mobile-nav-link block py-3 px-4 rounded-lg transition-colors {{ Request::is('forum*') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-comments w-5 mr-3"></i>Forum
                </a>
                <a href="/tutorials" class="mobile-nav-link block py-3 px-4 rounded-lg transition-colors {{ Request::is('tutorials*') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-book w-5 mr-3"></i>Tutoriels
                </a>
            </nav>
            
            <div class="mt-8 pt-6 border-t border-gray-200">
                <!-- User Profile in Mobile -->
                <div class="flex items-center space-x-3 p-3 bg-gradient-to-r from-primary/10 to-success/10 rounded-lg mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary to-success rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-sm font-medium">{{ substr(Auth::user()->name ?? 'O', 0, 1) }}</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">Éco-Warrior 🏆</p>
                    </div>
                </div>
                
                <div class="space-y-1">
                    <a href="/home" class="w-full text-left py-3 px-4 text-gray-600 hover:bg-gray-100 rounded-lg flex items-center space-x-3 transition-colors">
                        <i class="fas fa-tachometer-alt w-5"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="w-full text-left py-3 px-4 text-gray-600 hover:bg-gray-100 rounded-lg flex items-center space-x-3 transition-colors">
                        <i class="fas fa-user w-5"></i>
                        <span>Mon Profil</span>
                    </a>
                    <a href="/projects/mesprojets" class="w-full text-left py-3 px-4 text-gray-600 hover:bg-gray-100 rounded-lg flex items-center space-x-3 transition-colors">
                        <i class="fas fa-project-diagram w-5"></i>
                        <span>Mes Projets</span>
                    </a>
                    <a href="/dechets/mesdechets" class="w-full text-left py-3 px-4 text-gray-600 hover:bg-gray-100 rounded-lg flex items-center space-x-3 transition-colors">
                        <i class="fas fa-recycle w-5"></i>
                        <span>Mes Déchets</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full text-left py-3 px-4 text-red-600 hover:bg-red-50 rounded-lg flex items-center space-x-3 transition-colors">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span>Se déconnecter</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu-overlay" class="hidden md:hidden fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity duration-300"></div>
</nav>

<script>
    // Mobile Menu Toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
    const closeMenuBtn = document.getElementById('close-menu');

    function openMobileMenu() {
        mobileMenu.classList.remove('-translate-x-full');
        mobileMenuOverlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
        mobileMenu.classList.add('-translate-x-full');
        mobileMenuOverlay.classList.add('hidden');
        document.body.style.overflow = '';
    }

    mobileMenuBtn?.addEventListener('click', openMobileMenu);
    closeMenuBtn?.addEventListener('click', closeMobileMenu);
    mobileMenuOverlay?.addEventListener('click', closeMobileMenu);

    // Close mobile menu on link click
    document.querySelectorAll('.mobile-nav-link').forEach(link => {
        link.addEventListener('click', closeMobileMenu);
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