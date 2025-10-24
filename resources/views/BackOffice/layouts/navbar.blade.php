<<<<<<< HEAD
<nav class="navbar flex items-center justify-between">
    <div class="flex items-center">
        <!-- Mobile menu button -->
        <button id="mobile-menu-btn" class="lg:hidden mr-4 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
            <i class="fas fa-bars text-gray-600 dark:text-gray-300"></i>
        </button>

        <div class="search-container">
            <div class="search-icon">
                <i class="fas fa-search"></i>
            </div>
            <input 
                type="text" 
                placeholder="Rechercher..." 
                class="search-input"
                id="global-search"
            />
        </div>
    </div>

    <div class="navbar-actions">
        <!-- Quick actions -->
        <button class="notification-btn" title="Notifications">
            <i class="fas fa-bell"></i>
            <span class="notification-badge">3</span>
        </button>

        <button class="notification-btn" title="Messages">
            <i class="fas fa-envelope"></i>
            <span class="notification-badge">5</span>
        </button>

        <!-- Admin profile -->
        <div class="admin-profile">
            <div class="admin-avatar">
                AM
            </div>
            <div class="admin-info">
                <h4>Ahmed M.</h4>
                <p>Administrateur</p>
            </div>
            <i class="fas fa-chevron-down text-xs ml-2"></i>
        </div>
    </div>
</nav>
=======
<!-- Navigation Header -->
<nav class="navbar bg-white shadow-lg">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Search Bar -->
            <div class="flex-1 max-w-2xl">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input 
                        type="text" 
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm" 
                        placeholder="Rechercher..."
                    >
                </div>
            </div>
            
            <!-- User Actions -->
            <div class="hidden md:flex items-center space-x-4">
                <!-- Notifications -->
                <div class="relative">
                    <button id="notifications-btn" class="text-gray-600 hover:text-primary p-2 relative">
                        <i class="fas fa-bell text-lg"></i>
                        @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full">
                                {{ $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount }}
                            </span>
                        @endif
                    </button>
                    
                    <!-- Notifications Dropdown -->
                    <div id="notifications-dropdown" class="hidden absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900">Notifications</h3>
                            @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                                <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs text-blue-600 hover:text-blue-800">
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
                                            @else bg-gray-100
                                            @endif">
                                            <i class="fas {{ $notification->icon }}
                                                @if($notification->color === 'blue') text-blue-600
                                                @elseif($notification->color === 'green') text-green-600
                                                @elseif($notification->color === 'purple') text-purple-600
                                                @elseif($notification->color === 'orange') text-orange-600
                                                @elseif($notification->color === 'red') text-red-600
                                                @else text-gray-600
                                                @endif text-sm"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                                            <p class="text-xs text-gray-600 mt-1">{{ $notification->message }}</p>
                                            <div class="flex items-center justify-between mt-2">
                                                <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                                                @if(!$notification->is_read)
                                                    <form action="{{ route('admin.notifications.mark-read', $notification->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-xs text-blue-600 hover:text-blue-800">
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
                            <a href="{{ route('admin.notifications.index') }}" class="text-primary text-sm font-medium hover:text-primary-dark">
                                Voir toutes les notifications
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- User Menu -->
                <div class="relative">
                    <button id="user-menu-btn" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-9 h-9 bg-gradient-to-br from-primary to-success rounded-full flex items-center justify-center shadow-sm">
                            <span class="text-white text-sm font-semibold">{{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}</span>
                        </div>
                        <div class="text-left hidden sm:block">
                            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name ?? 'Admin' }}</p>
                            <p class="text-xs text-gray-500">
                                @if(Auth::user()->is_admin ?? false)
                                    Administrateur
                                @else
                                    Utilisateur
                                @endif
                            </p>
                        </div>
                        <i class="fas fa-chevron-down text-gray-400 text-xs hidden sm:block"></i>
                    </button>
                    
                    <!-- User Dropdown -->
                    <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                        <div class="py-1">
                            <a href="/admin/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-user w-4 mr-3 text-gray-400"></i>Mon Profil
                            </a>
                            <a href="/admin" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-tachometer-alt w-4 mr-3 text-gray-400"></i>Dashboard
                            </a>
                            
                            <hr class="my-1 border-gray-200">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                    <i class="fas fa-sign-out-alt w-4 mr-3"></i>Se déconnecter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.querySelector('.sidebar');
        const mobileOverlay = document.getElementById('mobile-overlay');
        const notificationsBtn = document.getElementById('notifications-btn');
        const notificationsDropdown = document.getElementById('notifications-dropdown');
        const userMenuBtn = document.getElementById('user-menu-btn');
        const userDropdown = document.getElementById('user-dropdown');

        // Toggle sidebar on mobile
        mobileMenuBtn?.addEventListener('click', function(e) {
            e.preventDefault();
            sidebar?.classList.toggle('mobile-active');
            mobileOverlay?.classList.toggle('hidden');
        });

        // Notifications and user dropdown
        notificationsBtn?.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationsDropdown?.classList.toggle('hidden');
            userDropdown?.classList.add('hidden');
        });

        userMenuBtn?.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown?.classList.toggle('hidden');
            notificationsDropdown?.classList.add('hidden');
        });

        document.addEventListener('click', function() {
            notificationsDropdown?.classList.add('hidden');
            userDropdown?.classList.add('hidden');
        });

        notificationsDropdown?.addEventListener('click', function(e) { e.stopPropagation(); });
        userDropdown?.addEventListener('click', function(e) { e.stopPropagation(); });
    });
</script>
>>>>>>> tutoral-branch
