<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Waste2Product')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            darkMode: 'class', // Disable auto dark mode
            theme: {
                extend: {
                    colors: {
                        primary: '#2E7D47',
                        secondary: '#F4A261',
                        accent: '#E76F51',
                        neutral: '#264653',
                        success: '#06D6A0',
                        warning: '#FFD60A'
                    }
                }
            }
        }
    </script>
    
    <style>
        /* Force light mode - override ALL dark classes */
        * {
            --tw-bg-opacity: 1 !important;
        }

        /* Ensure white backgrounds everywhere */
        body, html {
            background-color: white !important;
        }

        .gradient-hero {
            background: linear-gradient(135deg, #2E7D47 0%, #06D6A0 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .floating-icon {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .progress-ring {
            transform: rotate(-90deg);
        }
        .progress-ring-fill {
            transition: stroke-dasharray 0.3s ease;
        }

        /* Override dark mode backgrounds to white */
        .dark\:bg-gray-900,
        .dark\:bg-gray-800,
        .dark\:bg-gray-700,
        .dark\:bg-gray-600 {
            background-color: white !important;
        }

        /* Force dark text on white backgrounds */
        .dark\:text-white {
            color: #1f2937 !important; /* gray-800 */
        }

        .dark\:text-gray-300,
        .dark\:text-gray-400 {
            color: #4b5563 !important; /* gray-600 */
        }

        /* Override dark mode borders */
        .dark\:border-gray-600,
        .dark\:border-gray-700 {
            border-color: #e5e7eb !important;
        }

        /* Ensure all cards have proper text color */
        .bg-white, .bg-gray-50 {
            color: #1f2937;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-white">

    @include('FrontOffice.layout1.navbar')

    <main class="pt-16 min-h-screen bg-white">
        @yield('content')
    </main>

    @include('FrontOffice.layout1.footer')
    
    <script>
        // Force light mode (dark mode disabled)
        document.documentElement.classList.remove('dark');
    </script>
    
    @stack('scripts')
    @yield('scripts')
</body>
</html>