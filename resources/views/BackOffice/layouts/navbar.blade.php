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