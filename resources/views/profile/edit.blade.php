@extends('FrontOffice.layout1.app')

@section('title', 'Mon Profil - Waste2Product')

@section('additional-styles')
body { 
    background: linear-gradient(135deg, #2E7D47 0%, #06D6A0 100%);
    min-height: 100vh;
}
@endsection

@section('content')
<div class="min-h-screen flex items-center justify-center py-6 sm:py-8 md:py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl w-full space-y-4 sm:space-y-6">
        
        <!-- Profile Information Section -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-2xl p-4 sm:p-6 md:p-8">
            <div class="flex items-center mb-4 sm:mb-6">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-primary to-success rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user text-white text-base sm:text-lg md:text-xl"></i>
                </div>
                <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                    <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 truncate">Informations du profil</h2>
                    <p class="text-xs sm:text-sm text-gray-600 hidden sm:block">Mettez à jour les informations de votre compte</p>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-3 sm:space-y-4">
                @csrf
                @method('patch')

                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs sm:text-sm font-medium text-gray-700">
                        Nom Complet
                    </label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus
                           class="mt-1 appearance-none relative block w-full px-3 py-2 sm:py-3 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-sm sm:text-base @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-2 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs sm:text-sm font-medium text-gray-700">
                        Adresse e-mail
                    </label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                           class="mt-1 appearance-none relative block w-full px-3 py-2 sm:py-3 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-sm sm:text-base @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                            <p class="text-sm text-yellow-800">
                                Votre adresse e-mail n'est pas vérifiée.
                                <button form="send-verification" class="underline text-yellow-900 hover:text-yellow-700 font-medium">
                                    Cliquez ici pour renvoyer l'e-mail de vérification
                                </button>
                            </p>
                        </div>
                    @endif

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-green-600 font-medium">
                            Un nouveau lien de vérification a été envoyé à votre adresse e-mail.
                        </p>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="flex flex-col sm:flex-row items-center gap-3 sm:gap-4 pt-2">
                    <button type="submit" id="profile-btn"
                            class="w-full sm:w-auto group relative flex justify-center py-2 sm:py-3 px-4 sm:px-6 border border-transparent text-sm sm:text-base font-medium rounded-md text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        <span class="hidden sm:inline">Enregistrer les modifications</span><span class="sm:hidden">Enregistrer</span>
                    </button>

                    @if (session('status') === 'profile-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                           class="text-sm text-green-600 font-medium">
                            <i class="fas fa-check-circle mr-1"></i> Enregistré avec succès
                        </p>
                    @endif
                </div>
            </form>

            <!-- Hidden verification form -->
            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                @csrf
            </form>
        </div>

        <!-- Update Password Section -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-2xl p-4 sm:p-6 md:p-8">
            <div class="flex items-center mb-4 sm:mb-6">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-primary to-success rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-lock text-white text-base sm:text-lg md:text-xl"></i>
                </div>
                <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                    <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 truncate">Modifier le mot de passe</h2>
                    <p class="text-xs sm:text-sm text-gray-600 hidden sm:block">Assurez-vous d'utiliser un mot de passe long et sécurisé</p>
                </div>
            </div>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-3 sm:space-y-4">
                @csrf
                @method('put')

                <!-- Current Password -->
                <div>
                    <label for="update_password_current_password" class="block text-xs sm:text-sm font-medium text-gray-700">
                        Mot de passe actuel
                    </label>
                    <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                           class="mt-1 appearance-none relative block w-full px-3 py-2 sm:py-3 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-sm sm:text-base @error('current_password', 'updatePassword') border-red-500 @enderror"
                           placeholder="••••••••">
                    @error('current_password', 'updatePassword')
                        <p class="mt-2 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="update_password_password" class="block text-xs sm:text-sm font-medium text-gray-700">
                        Nouveau mot de passe
                    </label>
                    <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                           class="mt-1 appearance-none relative block w-full px-3 py-2 sm:py-3 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-sm sm:text-base @error('password', 'updatePassword') border-red-500 @enderror"
                           placeholder="••••••••">
                    @error('password', 'updatePassword')
                        <p class="mt-2 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="update_password_password_confirmation" class="block text-xs sm:text-sm font-medium text-gray-700">
                        Confirmer le nouveau mot de passe
                    </label>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                           class="mt-1 appearance-none relative block w-full px-3 py-2 sm:py-3 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 text-sm sm:text-base"
                           placeholder="••••••••">
                </div>

                <!-- Submit Button -->
                <div class="flex flex-col sm:flex-row items-center gap-3 sm:gap-4 pt-2">
                    <button type="submit" id="password-btn"
                            class="w-full sm:w-auto group relative flex justify-center py-2 sm:py-3 px-4 sm:px-6 border border-transparent text-sm sm:text-base font-medium rounded-md text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        <i class="fas fa-key mr-2"></i>
                        <span class="hidden sm:inline">Mettre à jour le mot de passe</span><span class="sm:hidden">Mettre à jour</span>
                    </button>

                    @if (session('status') === 'password-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                           class="text-sm text-green-600 font-medium">
                            <i class="fas fa-check-circle mr-1"></i> Mot de passe mis à jour
                        </p>
                    @endif
                </div>
            </form>
        </div>

        <!-- Delete Account Section -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-2xl p-4 sm:p-6 md:p-8">
            <div class="flex items-center mb-4 sm:mb-6">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-white text-base sm:text-lg md:text-xl"></i>
                </div>
                <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                    <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 truncate">Supprimer le compte</h2>
                    <p class="text-xs sm:text-sm text-gray-600 line-clamp-2">Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées</p>
                </div>
            </div>

            <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                    class="w-full sm:w-auto group relative flex justify-center py-2 sm:py-3 px-4 sm:px-6 border border-transparent text-sm sm:text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                <i class="fas fa-trash-alt mr-2"></i>
                <span class="hidden sm:inline">Supprimer mon compte</span><span class="sm:hidden">Supprimer</span>
            </button>

            <!-- Delete Confirmation Modal -->
            <div x-data="{ show: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }" 
                 x-on:open-modal.window="$event.detail === 'confirm-user-deletion' ? show = true : null"
                 x-on:close.window="show = false"
                 x-show="show"
                 x-cloak
                 class="fixed inset-0 z-50 overflow-y-auto"
                 style="display: none;">
                
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <!-- Background overlay -->
                    <div x-show="show" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"></div>

                    <!-- Modal panel -->
                    <div x-show="show"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 mx-4">
                        
                        <form method="POST" action="{{ route('profile.destroy') }}">
                            @csrf
                            @method('delete')

                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-red-100 sm:mx-0">
                                    <i class="fas fa-exclamation-triangle text-red-600 text-base sm:text-lg"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-base sm:text-lg leading-6 font-medium text-gray-900">
                                        Êtes-vous sûr de vouloir supprimer votre compte ?
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-xs sm:text-sm text-gray-500">
                                            Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. Veuillez entrer votre mot de passe pour confirmer la suppression.
                                        </p>
                                    </div>

                                    <div class="mt-4">
                                        <label for="password" class="sr-only">Mot de passe</label>
                                        <input id="password" name="password" type="password" autocomplete="current-password"
                                               class="appearance-none relative block w-full px-3 py-2 sm:py-3 border border-gray-300 placeholder-gray-500 text-gray-900 bg-white rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500 text-sm sm:text-base"
                                               placeholder="Mot de passe">
                                        @if($errors->userDeletion->has('password'))
                                            <p class="mt-2 text-xs sm:text-sm text-red-600">{{ $errors->userDeletion->first('password') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 sm:mt-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3">
                                <button type="button" x-on:click="show = false"
                                        class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm sm:text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:w-auto">
                                    Annuler
                                </button>
                                <button type="submit"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-sm sm:text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto">
                                    Supprimer le compte
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back to Home Link -->
        <div class="text-center">
            <a href="/" class="text-sm text-white hover:text-gray-200 font-medium">
                ← Retour à l'accueil
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Form submission enhancement
const profileForm = document.querySelector('form[action*="profile.update"]');
profileForm?.addEventListener('submit', function(e) {
    const btn = document.getElementById('profile-btn');
    if (profileForm.checkValidity()) {
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Enregistrement...';
        btn.disabled = true;
    }
});

const passwordForm = document.querySelector('form[action*="password.update"]');
passwordForm?.addEventListener('submit', function(e) {
    const btn = document.getElementById('password-btn');
    if (passwordForm.checkValidity()) {
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mise à jour...';
        btn.disabled = true;
    }
});

// Password match validation
const newPassword = document.getElementById('update_password_password');
const confirmPassword = document.getElementById('update_password_password_confirmation');

confirmPassword?.addEventListener('input', function() {
    if (newPassword.value !== this.value) {
        this.setCustomValidity('Les mots de passe ne correspondent pas');
    } else {
        this.setCustomValidity('');
    }
});
</script>
@endsection