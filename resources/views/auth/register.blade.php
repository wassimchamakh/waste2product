@extends('FrontOffice.layout.app')

@section('title', 'Créer un compte - Waste2Product')

@section('additional-styles')
body { 
    background: linear-gradient(135deg, #2E7D47 0%, #06D6A0 100%);
    min-height: 100vh;
}
@endsection

@section('content')
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
                Ou <a href="{{ route('login') }}" class="font-medium text-primary hover:text-primary/80">
                    se connecter à un compte existant
                </a>
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-6">
            @csrf

            <div class="space-y-4">
                <!-- First Name and Last Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Nom Complet
                        </label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                               class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-base @error('name') border-red-500 @enderror"
                               placeholder="Foulen Ben Foulen">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Adresse e-mail
                    </label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username"
                           class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-base @error('email') border-red-500 @enderror"
                           placeholder="wassimreplay@gmail.com">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Number -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">
                        Numéro de téléphone
                    </label>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required
                           class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-base @error('phone') border-red-500 @enderror"
                           placeholder="+216 XX XXX XXX">
                    @error('phone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- City -->
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700">
                        Ville
                    </label>
                    <select id="city" name="city" required
                            class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-base @error('city') border-red-500 @enderror">
                        <option value="">Sélectionnez votre ville</option>
                        <option value="tunis" {{ old('city') == 'tunis' ? 'selected' : '' }}>Tunis</option>
                        <option value="sfax" {{ old('city') == 'sfax' ? 'selected' : '' }}>Sfax</option>
                        <option value="sousse" {{ old('city') == 'sousse' ? 'selected' : '' }}>Sousse</option>
                        <option value="kairouan" {{ old('city') == 'kairouan' ? 'selected' : '' }}>Kairouan</option>
                        <option value="bizerte" {{ old('city') == 'bizerte' ? 'selected' : '' }}>Bizerte</option>
                        <option value="gabes" {{ old('city') == 'gabes' ? 'selected' : '' }}>Gabès</option>
                        <option value="ariana" {{ old('city') == 'ariana' ? 'selected' : '' }}>Ariana</option>
                        <option value="gafsa" {{ old('city') == 'gafsa' ? 'selected' : '' }}>Gafsa</option>
                        <option value="monastir" {{ old('city') == 'monastir' ? 'selected' : '' }}>Monastir</option>
                        <option value="ben_arous" {{ old('city') == 'ben_arous' ? 'selected' : '' }}>Ben Arous</option>
                    </select>
                    @error('city')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Mot de passe
                    </label>
                    <div class="relative">
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                               class="mt-1 appearance-none relative block w-full px-3 py-3 pr-10 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-base @error('password') border-red-500 @enderror"
                               placeholder="••••••••">
                        <button type="button" onclick="togglePassword('password', 'toggle-password-icon')" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
                            <i id="toggle-password-icon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        Confirmer le mot de passe
                    </label>
                    <div class="relative">
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                           class="mt-1 appearance-none relative block w-full px-3 py-3 pr-10 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-base"
                           placeholder="Répétez votre mot de passe">
                        <button type="button" onclick="togglePassword('password_confirmation', 'toggle-password-confirm-icon')" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
                            <i id="toggle-password-confirm-icon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-start">
                    <input id="terms" name="terms" type="checkbox" required
                           class="h-4 w-4 mt-1 text-primary focus:ring-primary border-gray-300 rounded">
                    <label for="terms" class="ml-2 block text-sm text-gray-900">
                        J'accepte les <a href="/conditions" class="text-primary hover:text-primary/80 font-medium">conditions d'utilisation</a> et la <a href="/politique" class="text-primary hover:text-primary/80 font-medium">politique de confidentialité</a>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="space-y-4">
                <button type="submit" id="register-btn"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-base font-medium rounded-md text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    Créer mon compte
                </button>

                <div class="flex justify-center"></div>
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
// Toggle password visibility
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Form submission enhancement
const form = document.querySelector('form');
form?.addEventListener('submit', function(e) {
    const btn = document.getElementById('register-btn');
    if (form.checkValidity()) {
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Création du compte...';
        btn.disabled = true;
    }
});

// Password match validation
const password = document.getElementById('password');
const passwordConfirm = document.getElementById('password_confirmation');

passwordConfirm?.addEventListener('input', function() {
    if (password.value !== this.value) {
        this.setCustomValidity('Les mots de passe ne correspondent pas');
    } else {
        this.setCustomValidity('');
    }
});
@endsection