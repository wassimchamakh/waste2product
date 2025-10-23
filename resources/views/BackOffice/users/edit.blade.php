@extends('BackOffice.layouts.app')

@section('title', 'Modifier l\'utilisateur')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Modifier l'utilisateur</h1>
            <p class="text-gray-600 mt-1">Modifier les informations de {{ $user->name }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.users.show', $user->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i class="fas fa-eye"></i>
                Voir le profil
            </a>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                Retour
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nom complet <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Nouveau mot de passe
                    </label>
                    <input type="password" name="password" id="password"
                           class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Laisser vide pour conserver le mot de passe actuel</p>
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirmer le nouveau mot de passe
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Téléphone
                    </label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- City -->
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                        Ville
                    </label>
                    <input type="text" name="city" id="city" value="{{ old('city', $user->city) }}"
                           class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 @error('city') border-red-500 @enderror">
                    @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        Adresse
                    </label>
                    <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}"
                           class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 @error('address') border-red-500 @enderror">
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Avatar -->
                @if($user->avatar)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Photo de profil actuelle
                    </label>
                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="h-32 w-32 rounded-lg object-cover">
                </div>
                @endif

                <!-- Avatar -->
                <div class="md:col-span-2">
                    <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $user->avatar ? 'Changer la photo de profil' : 'Photo de profil' }}
                    </label>
                    <input type="file" name="avatar" id="avatar" accept="image/*"
                           class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 @error('avatar') border-red-500 @enderror">
                    @error('avatar')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Formats acceptés: JPG, PNG, GIF (max 2MB)</p>
                </div>

                <!-- Is Admin -->
                @if($user->id !== auth()->id())
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_admin" id="is_admin" value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <label for="is_admin" class="ml-2 text-sm font-medium text-gray-700">
                            Accorder les privilèges administrateur
                        </label>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Les administrateurs ont accès au backoffice et peuvent gérer la plateforme</p>
                </div>
                @else
                <div class="md:col-span-2 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-sm text-yellow-800">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Vous ne pouvez pas modifier votre propre rôle d'administrateur
                    </p>
                </div>
                @endif
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users.show', $user->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg">
                    Annuler
                </a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-save mr-2"></i>Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
        <!-- Toggle Admin -->
        @if($user->id !== auth()->id())
        <form action="{{ route('admin.users.toggle-admin', $user->id) }}" method="POST" class="bg-white rounded-lg shadow p-4">
            @csrf
            @method('PATCH')
            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                <i class="fas fa-shield-alt text-purple-600 mr-2"></i>Changer le rôle
            </h3>
            <p class="text-sm text-gray-600 mb-4">
                Actuellement: <span class="font-semibold">{{ $user->is_admin ? 'Administrateur' : 'Utilisateur' }}</span>
            </p>
            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
                {{ $user->is_admin ? 'Révoquer admin' : 'Promouvoir admin' }}
            </button>
        </form>
        @endif

        <!-- Toggle Verification -->
        <form action="{{ route('admin.users.toggle-verification', $user->id) }}" method="POST" class="bg-white rounded-lg shadow p-4">
            @csrf
            @method('PATCH')
            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                <i class="fas fa-check-circle text-green-600 mr-2"></i>Vérification email
            </h3>
            <p class="text-sm text-gray-600 mb-4">
                Statut: <span class="font-semibold">{{ $user->email_verified_at ? 'Vérifié' : 'Non vérifié' }}</span>
            </p>
            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                {{ $user->email_verified_at ? 'Retirer la vérification' : 'Vérifier l\'email' }}
            </button>
        </form>

        <!-- Delete User -->
        @if($user->id !== auth()->id())
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                <i class="fas fa-trash text-red-600 mr-2"></i>Supprimer l'utilisateur
            </h3>
            <p class="text-sm text-gray-600 mb-4">
                Cette action est irréversible
            </p>
            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Êtes-vous absolument sûr de vouloir supprimer cet utilisateur? Cette action est irréversible.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                    Supprimer définitivement
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
