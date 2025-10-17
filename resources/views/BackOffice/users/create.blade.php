@extends('BackOffice.layouts.app')

@section('title', 'Créer un utilisateur')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Créer un utilisateur</h1>
            <p class="text-gray-600 mt-1">Ajouter un nouvel utilisateur à la plateforme</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            Retour
        </a>
    </div>

    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nom complet <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
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
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Mot de passe <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" id="password" required
                           class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirmer le mot de passe <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Téléphone
                    </label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
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
                    <input type="text" name="city" id="city" value="{{ old('city') }}"
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
                    <input type="text" name="address" id="address" value="{{ old('address') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 @error('address') border-red-500 @enderror">
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Avatar -->
                <div class="md:col-span-2">
                    <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">
                        Photo de profil
                    </label>
                    <input type="file" name="avatar" id="avatar" accept="image/*"
                           class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 @error('avatar') border-red-500 @enderror">
                    @error('avatar')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Formats acceptés: JPG, PNG, GIF (max 2MB)</p>
                </div>

                <!-- Is Admin -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_admin" id="is_admin" value="1" {{ old('is_admin') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <label for="is_admin" class="ml-2 text-sm font-medium text-gray-700">
                            Accorder les privilèges administrateur
                        </label>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Les administrateurs ont accès au backoffice et peuvent gérer la plateforme</p>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg">
                    Annuler
                </a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-save mr-2"></i>Créer l'utilisateur
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
