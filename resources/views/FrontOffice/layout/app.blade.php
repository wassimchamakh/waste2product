<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Waste2Product - Valorisation des Déchets en Tunisie')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2E7D47',
                        secondary: '#F4A261',
                        accent: '#E76F51',
                        neutral: '#264653',
                        success: '#06D6A0',
                        warning: '#FFD60A'
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif']
                    }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
        
        .gradient-hero {
            background: linear-gradient(135deg, #2E7D47 0%, #06D6A0 100%);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .counter-animation {
            animation: countUp 2s ease-out;
        }
        
        @keyframes countUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .floating-icon {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .mobile-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .mobile-menu.open {
            transform: translateX(0);
        }

        @yield('additional-styles')
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    @unless(Request::is('login') || Request::is('register'))
        @include('FrontOffice.layout.navbar')
    @endunless

    @yield('content')

    @unless(Request::is('login') || Request::is('register'))
        @include('FrontOffice.layout.footer')
    @endunless

    <script>
        // Dark Mode Detection
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        }
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
            if (event.matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });

        // Custom CSS classes for buttons
        const style = document.createElement('style');
        style.textContent = `
            .btn-primary {
                @apply bg-primary hover:bg-opacity-90 text-white rounded-lg font-medium transition-all duration-200 hover:shadow-lg;
            }
            .btn-secondary {
                @apply bg-secondary hover:bg-opacity-90 text-white rounded-lg font-medium transition-all duration-200 hover:shadow-lg;
            }
            .btn-accent {
                @apply bg-accent hover:bg-opacity-90 text-white rounded-lg font-medium transition-all duration-200 hover:shadow-lg;
            }
            .animate-fade-in {
                animation: fadeIn 0.6s ease-out forwards;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }
        `;
        document.head.appendChild(style);

        @yield('scripts')
    </script>
</body>
</html>