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
                                        <p class="text-sm text-gray-900">Leila souhaite récupérer vos palettes</p>
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