<!-- Navigation Header -->
<nav class="bg-white shadow-lg fixed w-full top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <a href="{{ route('homee') }}" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary to-success rounded-lg flex items-center justify-center">
                        <i class="fas fa-recycle text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-primary">
                            Waste2Product
                        </h1>
                        <p class="text-xs text-gray-500">Tunisie Durable</p>
                    </div>
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('homee') }}" class="nav-link transition-colors {{ request()->routeIs('homee') ? 'text-primary font-semibold border-b-2 border-primary pb-1' : 'text-gray-600 hover:text-primary' }}">
                     Accueil
                </a>
                <a href="{{ route('dechets.index') }}" class="nav-link transition-colors {{ request()->routeIs('dechets.*') ? 'text-primary font-semibold border-b-2 border-primary pb-1' : 'text-gray-600 hover:text-primary' }}">
                     Déchets
                </a>
                <a href="{{ route('projects.index') }}" class="nav-link transition-colors {{ request()->routeIs('projects.*') ? 'text-primary font-semibold border-b-2 border-primary pb-1' : 'text-gray-600 hover:text-primary' }}">
                     Projets
                </a>
                <a href="{{ route('Events.index') }}" class="nav-link transition-colors {{ request()->routeIs('Events.*') || request()->routeIs('events.*') ? 'text-primary font-semibold border-b-2 border-primary pb-1' : 'text-gray-600 hover:text-primary' }}">
                     Événements
                </a>
                <a href="{{ route('tutorials.index') }}" class="nav-link transition-colors {{ request()->routeIs('tutorials.*') ? 'text-primary font-semibold border-b-2 border-primary pb-1' : 'text-gray-600 hover:text-primary' }}">
                     Tutoriels
                </a>
                <a href="{{ route('forum.index') }}" class="nav-link transition-colors {{ request()->routeIs('forum.*') ? 'text-primary font-semibold border-b-2 border-primary pb-1' : 'text-gray-600 hover:text-primary' }}">
                     Forum
                </a>
            </div>
            
            <!-- User Actions -->
            <div class="hidden md:flex items-center space-x-4">
                @auth
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-primary font-medium flex items-center gap-2">
                        <i class="fas fa-user-circle"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-red-600 font-medium">
                            <i class="fas fa-sign-out-alt mr-1"></i>Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-primary font-medium">Se connecter</a>
                    <a href="{{ route('register') }}" class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">S'inscrire</a>
                @endauth
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
                <a href="{{ route('homee') }}" class="mobile-nav-link block py-3 px-4 rounded-lg transition-colors {{ request()->routeIs('homee') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    🏠 Accueil
                </a>
                <a href="{{ route('dechets.index') }}" class="mobile-nav-link block py-3 px-4 rounded-lg transition-colors {{ request()->routeIs('dechets.*') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    ♻️ Déchets
                </a>
                <a href="{{ route('projects.index') }}" class="mobile-nav-link block py-3 px-4 rounded-lg transition-colors {{ request()->routeIs('projects.*') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    🛠️ Projets
                </a>
                <a href="{{ route('Events.index') }}" class="mobile-nav-link block py-3 px-4 rounded-lg transition-colors {{ request()->routeIs('Events.*') || request()->routeIs('events.*') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    📅 Événements
                </a>
                <a href="{{ route('tutorials.index') }}" class="mobile-nav-link block py-3 px-4 rounded-lg transition-colors {{ request()->routeIs('tutorials.*') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    📚 Tutoriels
                </a>
                <a href="{{ route('forum.index') }}" class="mobile-nav-link block py-3 px-4 rounded-lg transition-colors {{ request()->routeIs('forum.*') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    💬 Forum
                </a>
            </nav>
            
            <div class="mt-8 space-y-3">
                @auth
                    <a href="{{ route('home') }}" class="w-full bg-gray-100 text-gray-800 py-3 block text-center rounded-lg font-medium">
                        <i class="fas fa-user-circle mr-2"></i>{{ Auth::user()->name }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-3 rounded-lg font-medium transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="w-full bg-secondary hover:bg-secondary/90 text-white py-3 block text-center rounded-lg font-medium transition-colors">Se connecter</a>
                    <a href="{{ route('register') }}" class="w-full bg-primary hover:bg-primary/90 text-white py-3 block text-center rounded-lg font-medium transition-colors">S'inscrire</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
    // Mobile Menu Toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const closeMenuBtn = document.getElementById('close-menu');

    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.add('open');
    });

    closeMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.remove('open');
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
            mobileMenu.classList.remove('open');
        }
    });
</script>