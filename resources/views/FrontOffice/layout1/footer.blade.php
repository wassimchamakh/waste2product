<!-- Footer -->
<footer class="bg-neutral text-white py-8 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
            <!-- Brand -->
            <div class="text-center sm:text-left">
                <div class="flex items-center justify-center sm:justify-start space-x-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary to-success rounded-lg flex items-center justify-center">
                        <i class="fas fa-recycle text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-lg md:text-xl font-bold">Waste2Product</h1>
                        <p class="text-xs opacity-80">Tunisie Durable</p>
                    </div>
                </div>
                <p class="text-sm opacity-80 mb-4 max-w-xs mx-auto sm:mx-0">
                    Plateforme tunisienne de valorisation des déchets et économie circulaire.
                </p>
                <div class="flex justify-center sm:justify-start space-x-3">
                    <a href="#" class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-opacity-30 transition-colors">
                        <i class="fab fa-facebook text-sm"></i>
                    </a>
                    <a href="#" class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-opacity-30 transition-colors">
                        <i class="fab fa-instagram text-sm"></i>
                    </a>
                    <a href="https://www.linkedin.com/in/wassimchamakh/" class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-opacity-30 transition-colors">
                        <i class="fab fa-linkedin text-sm"></i>
                    </a>
                </div>
            </div>
            
            <!-- Navigation -->
            <div class="text-center sm:text-left">
                <h3 class="font-semibold mb-3 md:mb-4 text-sm md:text-base">Navigation</h3>
                <ul class="space-y-2 text-sm opacity-80">
                    <li><a href="/home" class="hover:opacity-100 transition-opacity">Accueil</a></li>
                    <li><a href="{{ route('dechets.index') }}" class="hover:opacity-100 transition-opacity">Déchets</a></li>
                    <li><a href="{{ route('projects.index') }}" class="hover:opacity-100 transition-opacity">Projets</a></li>
                    <li><a href="{{ route('Events.index') }}" class="hover:opacity-100 transition-opacity">Événements</a></li>
                    <li><a href="{{ route('tutorials.index') }}" class="hover:opacity-100 transition-opacity">Tutoriels</a></li>
                </ul>
            </div>
            
            <div class="text-center sm:text-left">
                <h3 class="font-semibold mb-3 md:mb-4 text-sm md:text-base">Compte</h3>
                <ul class="space-y-2 text-sm opacity-80">
                    <li><a href="{{ route('profile.edit') }}" class="hover:opacity-100 transition-opacity">Mon Profil</a></li>
                    <li><a href="{{ route('forum.index') }}" class="hover:opacity-100 transition-opacity">Forum</a></li>
                    <li><a href="{{ route('dechets.my') }}" class="hover:opacity-100 transition-opacity">Mes Déchets</a></li>
                    <li><a href="{{ route('projects.my') }}" class="hover:opacity-100 transition-opacity">Mes Projets</a></li>
                    <li><a href="{{ route('Events.mes-Events') }}" class="hover:opacity-100 transition-opacity">Mes Événements</a></li>
                </ul>
            </div>
            
            <div class="text-center sm:text-left">
                <h3 class="font-semibold mb-3 md:mb-4 text-sm md:text-base">Support</h3>
                <ul class="space-y-2 text-sm opacity-80">
                    <li><a href="{{ route('aide') }}" class="hover:opacity-100 transition-opacity">Aide</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:opacity-100 transition-opacity">Contact</a></li>
                    <li><a href="{{ route('mobile-app') }}" class="hover:opacity-100 transition-opacity">Mobile App</a></li>
                    <li><a href="{{ route('confidentialite') }}" class="hover:opacity-100 transition-opacity">Confidentialité</a></li>
                    <li><a href="{{ route('cgu') }}" class="hover:opacity-100 transition-opacity">CGU</a></li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-white border-opacity-20 mt-6 md:mt-8 pt-6 md:pt-8 text-center">
            <p class="text-xs md:text-sm opacity-80 px-4">
                © {{ date('Y') }} Waste2Product Tunisie. Tous droits réservés. 
                <span class="block sm:inline mx-2 my-2 sm:my-0">PENTAGOS 🇹🇳</span>
                <span class="block sm:inline">Fait avec ❤️ pour l'environnement</span>
            </p>
        </div>
    </div>
</footer>