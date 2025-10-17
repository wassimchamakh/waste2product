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
                        @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                            <span class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                                {{ $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount }}
                            </span>
                        @endif
                    </button>
                    
                    <!-- Notifications Dropdown -->
                    <div id="notifications-dropdown" class="hidden absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900">Notifications</h3>
                            @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                                <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs text-primary hover:text-primary-dark font-medium">
                                        Tout marquer comme lu
                                    </button>
                                </form>
                            @endif
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            @forelse($notifications ?? [] as $notification)
                                <div class="p-3 hover:bg-gray-50 border-b border-gray-100 {{ $notification->is_read ? 'bg-white' : 'bg-blue-50' }}">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                                            @if($notification->color === 'blue') bg-blue-100
                                            @elseif($notification->color === 'green') bg-green-100
                                            @elseif($notification->color === 'purple') bg-purple-100
                                            @elseif($notification->color === 'orange') bg-orange-100
                                            @elseif($notification->color === 'red') bg-red-100
                                            @elseif($notification->color === 'yellow') bg-yellow-100
                                            @else bg-gray-100
                                            @endif">
                                            <i class="fas {{ $notification->icon }}
                                                @if($notification->color === 'blue') text-blue-600
                                                @elseif($notification->color === 'green') text-green-600
                                                @elseif($notification->color === 'purple') text-purple-600
                                                @elseif($notification->color === 'orange') text-orange-600
                                                @elseif($notification->color === 'red') text-red-600
                                                @elseif($notification->color === 'yellow') text-yellow-600
                                                @else text-gray-600
                                                @endif text-sm"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            @if($notification->link)
                                                <a href="{{ $notification->link }}" class="block">
                                                    <p class="text-sm font-medium text-gray-900 hover:text-primary">{{ $notification->title }}</p>
                                                    <p class="text-xs text-gray-600 mt-1">{{ $notification->message }}</p>
                                                </a>
                                            @else
                                                <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                                                <p class="text-xs text-gray-600 mt-1">{{ $notification->message }}</p>
                                            @endif
                                            <div class="flex items-center justify-between mt-2">
                                                <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                                                @if(!$notification->is_read)
                                                    <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-xs text-primary hover:text-primary-dark font-medium">
                                                            Marquer comme lu
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-gray-500">
                                    <i class="fas fa-bell-slash text-4xl mb-2 text-gray-300"></i>
                                    <p class="text-sm">Aucune notification</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="p-3 text-center border-t border-gray-200">
                            <a href="{{ route('notifications.index') }}" class="text-primary text-sm font-medium hover:text-primary-dark">
                                Voir toutes les notifications
                            </a>
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
                         
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user w-4 mr-3"></i>Mon Profil
                            </a>
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
                    <a href="{{ route('notifications.index') }}" class="w-full text-left py-3 px-4 text-gray-600 hover:bg-gray-100 rounded-lg flex items-center space-x-3">
                        <i class="fas fa-bell w-4"></i>
                        <span>Notifications</span>
                        @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                            <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full font-bold">
                                {{ $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount }}
                            </span>
                        @endif
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