@extends('FrontOffice.layout.app')

@section('title', 'Se connecter - Waste2Product')

@section('additional-styles')
body { 
    background: linear-gradient(135deg, #2E7D47 0%, #06D6A0 100%);
    min-height: 100vh;
}
@endsection

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
<<<<<<< HEAD
    <div class="max-w-md w-full space-y-8 bg-white dark:bg-gray-800 rounded-xl shadow-2xl p-8">
=======
    <div class="max-w-md w-full space-y-8 bg-white rounded-xl shadow-2xl p-8">
>>>>>>> tutoral-branch
        <div>
            <div class="flex justify-center">
                <div class="w-16 h-16 bg-gradient-to-br from-primary to-success rounded-full flex items-center justify-center">
                    <i class="fas fa-recycle text-white text-2xl"></i>
                </div>
            </div>
<<<<<<< HEAD
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900 dark:text-white">
                Se connecter
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                Ou
                <a href="/register" class="font-medium text-primary hover:text-primary/80">
=======
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
                Se connecter
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Ou
                <a href="{{ route('register') }}" class="font-medium text-primary hover:text-primary/80">
>>>>>>> tutoral-branch
                    créer un nouveau compte
                </a>
            </p>
        </div>

        <form method="POST" action="/login" class="mt-8 space-y-6">
            <div class="space-y-4">
                <div>
<<<<<<< HEAD
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Adresse e-mail
                    </label>
                    <input id="email" name="email" type="email" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white bg-white dark:bg-gray-700 rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-base"
                           placeholder="votre@email.com">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Mot de passe
                    </label>
                    <input id="password" name="password" type="password" required
                           class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white bg-white dark:bg-gray-700 rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-base"
=======
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        {{ __('Email') }}
                    </label>
                    <input id="email" name="email" type="email" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-base"
                           placeholder="votre@email.com">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Mot de passe
                    </label>
                    <input id="password" name="password" type="password" required
                           class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-base"
>>>>>>> tutoral-branch
                           placeholder="Votre mot de passe">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
<<<<<<< HEAD
                           class="h-4 w-4 text-primary focus:ring-primary border-gray-300 dark:border-gray-600 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
=======
                           class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-900">
>>>>>>> tutoral-branch
                        Se souvenir de moi
                    </label>
                </div>
                <div class="text-sm">
                    <a href="/mot-de-passe-oublie" class="font-medium text-primary hover:text-primary/80">
                        Mot de passe oublié ?
                    </a>
                </div>
            </div>

            <div class="space-y-4">
                <button type="submit" id="signin-btn"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-base font-medium rounded-md text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    Se connecter
                </button>

                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
<<<<<<< HEAD
                        <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white dark:bg-gray-800 text-gray-500">Ou continuer avec</span>
=======
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Ou continuer avec</span>
>>>>>>> tutoral-branch
                    </div>
                </div>

                <a href="/auth/google" id="google-signin-btn"
<<<<<<< HEAD
                   class="group relative w-full flex justify-center items-center py-3 px-4 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
=======
                   class="group relative w-full flex justify-center items-center py-3 px-4 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
>>>>>>> tutoral-branch
                    <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continuer avec Google
                </a>
            </div>
        </form>

        <div class="text-center">
<<<<<<< HEAD
            <a href="/" class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary">
=======
            <a href="/" class="text-sm text-gray-600 hover:text-primary">
>>>>>>> tutoral-branch
                ← Retour à l'accueil
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
// Form submission enhancement
document.getElementById('signin-btn').addEventListener('click', function(e) {
    const form = this.closest('form');
    if (form.checkValidity()) {
        this.textContent = '⏳ Connexion en cours...';
        this.disabled = true;
        
        // Simuler une connexion réussie après 2 secondes
        setTimeout(() => {
            this.textContent = '✅ Connexion réussie !';
            setTimeout(() => {
                window.location.href = '/';
            }, 1000);
        }, 2000);
    }
});
@endsection