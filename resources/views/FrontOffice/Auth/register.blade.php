@extends('FrontOffice.layout.app')

@section('title', 'Créer un compte - Waste2Product')

@section('additional-styles')
body { 
    background: linear-gradient(135deg, #2E7D47 0%, #06D6A0 100%);
    min-height: 100vh;
}
@endsection

@section('content')
@php
    $cities = [
        'tunis' => 'Tunis',
        'sfax' => 'Sfax',
        'sousse' => 'Sousse',
        'ariana' => 'Ariana',
        'ben-arous' => 'Ben Arous',
        'nabeul' => 'Nabeul',
        'bizerte' => 'Bizerte',
        'gabes' => 'Gabès',
        'kairouan' => 'Kairouan',
        'monastir' => 'Monastir',
    ];
@endphp

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white rounded-xl shadow-2xl p-8">
        <div>
            <div class="flex justify-center">
                <div class="w-16 h-16 bg-gradient-to-br from-primary to-success rounded-full flex items-center justify-center">
                    <i class="fas fa-recycle text-white text-2xl"></i>
                </div>
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
                Créer un compte
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Ou
                <a href="/login" class="font-medium text-primary hover:text-primary/80">
                    se connecter à un compte existant
                </a>
            </p>
        </div>

        <form method="POST" action="/register" class="mt-8 space-y-6">
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="firstname" class="block text-sm font-medium text-gray-700">
                            Prénom
                        </label>
                        <input id="firstname" name="firstname" type="text" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-base"
                               placeholder="Ahmed">
                    </div>
                    <div>
                        <label for="lastname" class="block text-sm font-medium text-gray-700">
                            Nom
                        </label>
                        <input id="lastname" name="lastname" type="text" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-base"
                               placeholder="Ben Salem">
                    </div>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Adresse e-mail
                    </label>
                    <input id="email" name="email" type="email" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-base"
                           placeholder="ahmed@email.com">
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">
                        Numéro de téléphone
                    </label>
                    <input id="phone" name="phone" type="tel" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-base"
                           placeholder="+216 XX XXX XXX">
                </div>
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700">
                        Ville
                    </label>
                    <select id="city" name="city" required
                            class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-base">
                        <option value="">Sélectionnez votre ville</option>
                        @foreach($cities as $cityValue => $cityLabel)
                            <option value="{{ $cityValue }}">{{ $cityLabel }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Mot de passe
                    </label>
                    <input id="password" name="password" type="password" required
                           class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-base"
                           placeholder="Au moins 8 caractères">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        Confirmer le mot de passe
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-base"
                           placeholder="Répétez votre mot de passe">
                </div>
            </div>

            <div class="flex items-center">
                <input id="terms" name="terms" type="checkbox" required
                       class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                <label for="terms" class="ml-2 block text-sm text-gray-900">
                    J'accepte les <a href="#" class="text-primary hover:text-primary/80">conditions d'utilisation</a> et la <a href="#" class="text-primary hover:text-primary/80">politique de confidentialité</a>
                </label>
            </div>

            <div class="space-y-4">
                <button type="submit" id="signup-btn"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-base font-medium rounded-md text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    Créer mon compte
                </button>

                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Ou s'inscrire avec</span>
                    </div>
                </div>

                <a href="/auth/google" id="google-signup-btn"
                   class="group relative w-full flex justify-center items-center py-3 px-4 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    S'inscrire avec Google
                </a>
            </div>
        </form>

        <div class="text-center">
            <a href="/" class="text-sm text-gray-600 hover:text-primary">
                ← Retour à l'accueil
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
// Form submission enhancement
document.getElementById('signup-btn').addEventListener('click', function(e) {
    const form = this.closest('form');
    if (form.checkValidity()) {
        this.textContent = '⏳ Création du compte...';
        this.disabled = true;
        
        // Simuler une inscription réussie après 2 secondes
        setTimeout(() => {
            this.textContent = '✅ Compte créé !';
            setTimeout(() => {
                window.location.href = '/';
            }, 1000);
        }, 2000);
    }
});

// Password confirmation validation
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmation = this.value;
    
    if (password !== confirmation && confirmation.length > 0) {
        this.setCustomValidity('Les mots de passe ne correspondent pas');
        this.classList.add('border-red-500');
    } else {
        this.setCustomValidity('');
        this.classList.remove('border-red-500');
    }
});
@endsection