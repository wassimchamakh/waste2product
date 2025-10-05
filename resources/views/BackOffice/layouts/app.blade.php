<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Waste2Product Admin')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #059669;
            --primary-dark: #047857;
            --primary-light: #10b981;
            --secondary-color: #f59e0b;
            --accent-color: #3b82f6;
            --sidebar-width: 280px;
            --sidebar-collapsed: 80px;
            --navbar-height: 70px;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border-right: 1px solid #e2e8f0;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            position: fixed;
            height: 100vh;
            z-index: 40;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        .sidebar-header {
            height: var(--navbar-height);
            padding: 0 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            overflow: hidden;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            flex-shrink: 0;
        }

        .logo-text {
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .sidebar.collapsed .logo-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .toggle-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 8px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            flex-shrink: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        .toggle-btn i {
            transition: transform 0.3s ease;
        }

        .sidebar.collapsed .toggle-btn i {
            transform: rotate(180deg);
        }

        .sidebar-nav {
            padding: 1.5rem 0;
            height: calc(100vh - var(--navbar-height));
            overflow-y: auto;
            overflow-x: hidden;
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-section-title {
            padding: 0 1.5rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            transition: all 0.3s ease;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar.collapsed .nav-section-title {
            opacity: 0;
            height: 0;
            padding: 0;
            margin: 0;
        }

        .nav-item {
            margin: 0 1rem 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            color: #475569;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
            position: relative;
            overflow: hidden;
            white-space: nowrap;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-color) 100%);
            transition: all 0.3s ease;
            z-index: -1;
        }

        .nav-link:hover::before,
        .nav-link.active::before {
            left: 0;
        }

        .nav-link:hover,
        .nav-link.active {
            color: white;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }

        .sidebar.collapsed .nav-link:hover,
        .sidebar.collapsed .nav-link.active {
            transform: translateX(0);
        }

        .nav-icon {
            width: 20px;
            margin-right: 0.75rem;
            transition: all 0.3s ease;
            z-index: 1;
            flex-shrink: 0;
            text-align: center;
        }

        .sidebar.collapsed .nav-icon {
            margin-right: 0;
            font-size: 1.25rem;
        }

        .nav-text {
            transition: all 0.3s ease;
            z-index: 1;
            overflow: hidden;
            white-space: nowrap;
        }

        .sidebar.collapsed .nav-text {
            opacity: 0;
            width: 0;
        }

        .nav-badge {
            margin-left: auto;
            background: var(--secondary-color);
            color: white;
            font-size: 0.75rem;
            padding: 0.125rem 0.5rem;
            border-radius: 12px;
            font-weight: 600;
            z-index: 1;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .nav-badge {
            opacity: 0;
            width: 0;
            padding: 0;
            margin: 0;
        }

        .main-area {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
            background: #f8fafc;
        }

        .sidebar.collapsed ~ .main-area {
            margin-left: var(--sidebar-collapsed);
        }

        .navbar {
            height: var(--navbar-height);
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-bottom: 1px solid #e2e8f0;
            padding: 0 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .search-container {
            position: relative;
            width: 320px;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background: #f8fafc;
            color: #374151;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notification-btn {
            position: relative;
            background: #f3f4f6;
            border: none;
            padding: 0.75rem;
            border-radius: 10px;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .notification-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: scale(1.05);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            background: #f3f4f6;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .admin-profile:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }

        .admin-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .admin-info h4 {
            font-weight: 600;
            color: #374151;
            margin: 0;
            font-size: 0.875rem;
        }

        .admin-info p {
            color: #6b7280;
            margin: 0;
            font-size: 0.75rem;
        }

        .admin-profile:hover .admin-info h4,
        .admin-profile:hover .admin-info p {
            color: white;
        }

        .content-area {
            padding: 2rem;
        }

        /* Mobile styles */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width) !important;
            }
            
            .sidebar.mobile-active {
                transform: translateX(0);
            }
            
            .sidebar.collapsed {
                transform: translateX(-100%);
            }
            
            .main-area {
                margin-left: 0 !important;
            }

            .navbar {
                padding: 0 1rem;
            }

            .content-area {
                padding: 1rem;
            }
        }

        /* Dark mode styles */
        .dark .sidebar {
            background: linear-gradient(180deg, #1f2937 0%, #111827 100%);
            border-right-color: #374151;
        }

        .dark .sidebar-header {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            border-bottom-color: #374151;
        }

        .dark .nav-link {
            color: #d1d5db;
        }

        .dark .nav-section-title {
            color: #9ca3af;
        }

        .dark .main-area {
            background: #111827;
        }

        .dark .navbar {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            border-bottom-color: #374151;
        }

        /* Force light mode - disable all dark mode styles */
        body, html {
            background-color: #f9fafb !important;
        }

        /* Override dark mode backgrounds */
        .dark\:bg-gray-900,
        .dark\:bg-gray-800,
        .dark\:bg-gray-700,
        .dark\:bg-gray-600 {
            background-color: white !important;
        }

        /* Force dark text on white backgrounds */
        .dark\:text-white {
            color: #1f2937 !important;
        }

        .dark\:text-gray-300,
        .dark\:text-gray-400 {
            color: #4b5563 !important;
        }

        /* Override dark mode borders */
        .dark\:border-gray-600,
        .dark\:border-gray-700 {
            border-color: #e5e7eb !important;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50">

    @include('BackOffice.layouts.sidebar')

    <main class="main-area">
        @include('BackOffice.layouts.navbar')
        <div class="content-area">
            @yield('content')
        </div>
    </main>

    <!-- Mobile overlay -->
    <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const toggleSidebarBtn = document.getElementById('toggle-sidebar');
            const mobileOverlay = document.getElementById('mobile-overlay');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');

            // Desktop sidebar toggle
            if (toggleSidebarBtn) {
                toggleSidebarBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    if (window.innerWidth > 768) {
                        // Desktop toggle
                        sidebar.classList.toggle('collapsed');
                        
                        // Save state to localStorage
                        if (sidebar.classList.contains('collapsed')) {
                            localStorage.setItem('sidebarCollapsed', 'true');
                        } else {
                            localStorage.setItem('sidebarCollapsed', 'false');
                        }
                    } else {
                        // Mobile toggle
                        sidebar.classList.toggle('mobile-active');
                        mobileOverlay.classList.toggle('hidden');
                    }
                });
            }

            // Mobile menu button
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    sidebar.classList.toggle('mobile-active');
                    mobileOverlay.classList.toggle('hidden');
                });
            }

            // Mobile overlay click
            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('mobile-active');
                    mobileOverlay.classList.add('hidden');
                });
            }

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('mobile-active');
                    mobileOverlay.classList.add('hidden');
                    
                    // Restore desktop sidebar state
                    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                    if (isCollapsed) {
                        sidebar.classList.add('collapsed');
                    } else {
                        sidebar.classList.remove('collapsed');
                    }
                } else {
                    sidebar.classList.remove('collapsed');
                }
            });

            // Restore sidebar state on page load (desktop only)
            if (window.innerWidth > 768) {
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (isCollapsed) {
                    sidebar.classList.add('collapsed');
                }
            }

            // Force light mode (dark mode disabled)
            document.documentElement.classList.remove('dark');
        });
    </script>

    @stack('scripts')
</body>
</html>