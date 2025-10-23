@extends('FrontOffice.layout.app')

@section('title', 'Réinitialiser le mot de passe - Waste2Product')

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
                    <i class="fas fa-lock-open text-white text-2xl"></i>
                </div>
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
                Réinitialiser le mot de passe
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Choisissez un nouveau mot de passe sécurisé pour votre compte
            </p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="mt-8 space-y-6">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="space-y-4">
                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Adresse e-mail
                    </label>
                    <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                           class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-base @error('email') border-red-500 @enderror"
                           placeholder="votre@email.com">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Nouveau mot de passe
                    </label>
                    <input id="password" name="password" type="password" required autocomplete="new-password"
                           class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-base @error('password') border-red-500 @enderror"
                           placeholder="Minimum 8 caractères">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        Confirmer le mot de passe
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                           class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-base @error('password_confirmation') border-red-500 @enderror"
                           placeholder="Confirmer votre mot de passe">
                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Password Requirements -->
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-xs font-medium text-gray-700 mb-2">Votre mot de passe doit contenir :</p>
                <ul class="text-xs text-gray-600 space-y-1">
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-primary mr-2"></i>
                        Au moins 8 caractères
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-primary mr-2"></i>
                        Une combinaison de lettres et de chiffres
                    </li>
                </ul>
            </div>

            <!-- Submit Button -->
            <div class="space-y-4">
                <button type="submit" id="reset-password-btn"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-base font-medium rounded-md text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <i class="fas fa-shield-alt mr-2"></i>
                    Réinitialiser le mot de passe
                </button>
            </div>
        </form>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-primary">
                ← Retour à la connexion
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
// Form submission enhancement
const form = document.querySelector('form');
form?.addEventListener('submit', function(e) {
    const btn = document.getElementById('reset-password-btn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Réinitialisation en cours...';
    btn.disabled = true;
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