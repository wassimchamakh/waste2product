

@extends('FrontOffice.layout.app')

@section('title', 'Mot de passe oublié - Waste2Product')

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
                    <i class="fas fa-key text-white text-2xl"></i>
                </div>
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
                Mot de passe oublié ?
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Pas de problème ! Indiquez-nous votre adresse e-mail et nous vous enverrons un lien de réinitialisation de mot de passe qui vous permettra d'en choisir un nouveau.
            </p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-primary text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-primary">
                            {{ session('status') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-6">
            @csrf

            <div class="space-y-4">
                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Adresse e-mail
                    </label>
                    <input id="email" name="email" type="email" :value="old('email')" required autofocus
                           class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-base @error('email') border-red-500 @enderror"
                           placeholder="votre@email.com">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="space-y-4">
                <button type="submit" id="reset-btn"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-base font-medium rounded-md text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Envoyer le lien de réinitialisation
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
