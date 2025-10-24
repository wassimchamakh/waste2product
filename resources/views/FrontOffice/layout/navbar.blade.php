<!-- Navigation Header -->
<<<<<<< HEAD
<nav class="bg-white dark:bg-gray-800 shadow-lg fixed w-full top-0 z-50">
=======
<nav class="bg-white shadow-lg fixed w-full top-0 z-50">
>>>>>>> tutoral-branch
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-primary to-success rounded-lg flex items-center justify-center">
                    <i class="fas fa-recycle text-white text-lg"></i>
                </div>
                <div>
<<<<<<< HEAD
                    <h1 class="text-xl font-bold text-primary dark:text-success">
                        <a href="/">Waste2Product</a>
                    </h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Tunisie Durable</p>
=======
                    <h1 class="text-xl font-bold text-primary">
                        <a href="/">Waste2Product</a>
                    </h1>
                    <p class="text-xs text-gray-500">Tunisie Durable</p>
>>>>>>> tutoral-branch
                </div>
            </div>
            
            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
<<<<<<< HEAD
                <a href="#home" class="nav-link {{ Request::is('/') ? 'text-primary font-medium' : 'text-gray-600 dark:text-gray-300 hover:text-primary' }}">
                    Accueil
                </a>
                <a href="#dechets" class="nav-link text-gray-600 dark:text-gray-300 hover:text-primary">
                    Déchets
                </a>
                <a href="#projets" class="nav-link text-gray-600 dark:text-gray-300 hover:text-primary">
                    Projets
                </a>
                <a href="#evenements" class="nav-link text-gray-600 dark:text-gray-300 hover:text-primary">
                    Événements
                </a>
                <a href="#tutoriels" class="nav-link text-gray-600 dark:text-gray-300 hover:text-primary">
                    Tutoriels
=======
                <a href="/" class="nav-link {{ Request::is('/') ? 'text-primary font-medium' : 'text-gray-600 hover:text-primary' }}">
                    Accueil
                </a>
                <a href="{{ route('dechets.index') }}" class="nav-link {{ Request::is('dechets*') ? 'text-primary font-medium' : 'text-gray-600 hover:text-primary' }}">
                    Déchets
                </a>
                <a href="{{ route('projects.index') }}" class="nav-link {{ Request::is('projects*') ? 'text-primary font-medium' : 'text-gray-600 hover:text-primary' }}">
                    Projets
                </a>
                <a href="{{ route('Events.index') }}" class="nav-link {{ Request::is('events*') ? 'text-primary font-medium' : 'text-gray-600 hover:text-primary' }}">
                    Événements
                </a>
                <a href="{{ route('forum.index') }}" class="nav-link {{ Request::is('forum*') ? 'text-primary font-medium' : 'text-gray-600 hover:text-primary' }}">
                    💬 Forum
>>>>>>> tutoral-branch
                </a>
            </div>
            
            <!-- User Actions -->
            <div class="hidden md:flex items-center space-x-4">
<<<<<<< HEAD
                <a href="/login" class="text-gray-600 dark:text-gray-300 hover:text-primary font-medium">Se connecter</a>
=======
                <a href="/login" class="text-gray-600 hover:text-primary font-medium">Se connecter</a>
>>>>>>> tutoral-branch
                <a href="/register" class="btn-primary px-4 py-2 text-sm">S'inscrire</a>
            </div>
            
            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden p-2">
<<<<<<< HEAD
                <i class="fas fa-bars text-gray-600 dark:text-gray-300"></i>
=======
                <i class="fas fa-bars text-gray-600"></i>
>>>>>>> tutoral-branch
            </button>
        </div>
    </div>
    
    <!-- Mobile Menu -->
<<<<<<< HEAD
    <div id="mobile-menu" class="mobile-menu md:hidden fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-800 shadow-xl z-50">
=======
    <div id="mobile-menu" class="mobile-menu md:hidden fixed inset-y-0 left-0 w-64 bg-white shadow-xl z-50">
>>>>>>> tutoral-branch
        <div class="p-4">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-primary to-success rounded-lg flex items-center justify-center">
                        <i class="fas fa-recycle text-white"></i>
                    </div>
<<<<<<< HEAD
                    <span class="font-bold text-primary dark:text-success">Waste2Product</span>
                </div>
                <button id="close-menu" class="p-2">
                    <i class="fas fa-times text-gray-600 dark:text-gray-300"></i>
=======
                    <span class="font-bold text-primary">Waste2Product</span>
                </div>
                <button id="close-menu" class="p-2">
                    <i class="fas fa-times text-gray-600"></i>
>>>>>>> tutoral-branch
                </button>
            </div>
            
            <nav class="space-y-4">
<<<<<<< HEAD
                <a href="#home" class="mobile-nav-link block py-3 px-4 rounded-lg {{ Request::is('/') ? 'bg-primary text-white' : 'text-gray-600 dark:text-gray-300' }}">
                    Accueil
                </a>
                <a href="#dechets" class="mobile-nav-link block py-3 px-4 rounded-lg text-gray-600 dark:text-gray-300">
                    Déchets
                </a>
                <a href="#projets" class="mobile-nav-link block py-3 px-4 rounded-lg text-gray-600 dark:text-gray-300">
                    Projets
                </a>
                <a href="#evenements" class="mobile-nav-link block py-3 px-4 rounded-lg text-gray-600 dark:text-gray-300">
                    Événements
                </a>
                <a href="#tutoriels" class="mobile-nav-link block py-3 px-4 rounded-lg text-gray-600 dark:text-gray-300">
                    Tutoriels
=======
                <a href="/" class="mobile-nav-link block py-3 px-4 rounded-lg {{ Request::is('/') ? 'bg-primary text-white' : 'text-gray-600' }}">
                    Accueil
                </a>
                <a href="{{ route('dechets.index') }}" class="mobile-nav-link block py-3 px-4 rounded-lg {{ Request::is('dechets*') ? 'bg-primary text-white' : 'text-gray-600' }}">
                    Déchets
                </a>
                <a href="{{ route('projects.index') }}" class="mobile-nav-link block py-3 px-4 rounded-lg {{ Request::is('projects*') ? 'bg-primary text-white' : 'text-gray-600' }}">
                    Projets
                </a>
                <a href="{{ route('Events.index') }}" class="mobile-nav-link block py-3 px-4 rounded-lg {{ Request::is('events*') ? 'bg-primary text-white' : 'text-gray-600' }}">
                    Événements
                </a>
                <a href="{{ route('forum.index') }}" class="mobile-nav-link block py-3 px-4 rounded-lg {{ Request::is('forum*') ? 'bg-primary text-white' : 'text-gray-600' }}">
                    💬 Forum
>>>>>>> tutoral-branch
                </a>
            </nav>
            
            <div class="mt-8 space-y-3">
                <a href="/login" class="w-full btn-secondary py-3 block text-center">Se connecter</a>
                <a href="/register" class="w-full btn-primary py-3 block text-center">S'inscrire</a>
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