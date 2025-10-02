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
        .sidebar { width: 250px; background-color: #fff; transition: width 0.3s; }
        .sidebar.collapsed { width: 80px; }
        .sidebar a { color: #2E7D47; transition: background 0.2s; }
        .sidebar a:hover, .sidebar a.active { background-color: #e6f4ea; font-weight: 600; }
        .sidebar .text { transition: opacity 0.3s; }
        .sidebar.collapsed .text { opacity: 0; pointer-events: none; }
        .sidebar.collapsed .icon-only { font-size: 1.5rem; }
        .main-area { background-color: #f9f9f9; padding: 20px; flex: 1; transition: margin-left 0.3s; margin-left: 250px; }
        .sidebar.collapsed ~ .main-area { margin-left: 80px; }
        .notification-badge { position: absolute; top: -5px; right: -10px; background: red; color: white; border-radius: 50%; padding: 2px 5px; font-size: 0.75rem; }
        .admin-name { font-size: 1rem; font-weight: 600; color: #2E7D47; }
        .search-bar { border: 1px solid #2E7D47; border-radius: 8px; padding: 0.5rem; width: 200px; }
        .navbar { background-color: #2E7D47; padding: 10px 20px; color: white; }
        #toggle-sidebar i { transition: transform 0.3s ease; }
        .sidebar.collapsed #toggle-sidebar i { transform: rotate(180deg); }
        @media (max-width: 768px) { .sidebar { position: fixed; height: 100%; z-index: 50; transform: translateX(-100%); } .sidebar.active { transform: translateX(0); } .main-area { margin-left: 0 !important; } }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900 flex">

    @include('BackOffice.layouts.sidebar')

    <main class="main-area flex-1">
        @include('BackOffice.layouts.navbar')
        @yield('content')
    </main>

    <script>
        const sidebar = document.querySelector('.sidebar');
        const toggleSidebarBtn = document.getElementById('toggle-sidebar');

        toggleSidebarBtn?.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });

        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        }
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            document.documentElement.classList.toggle('dark', e.matches);
        });
    </script>

    @stack('scripts')
</body>
</html>
