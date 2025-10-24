@extends('BackOffice.layouts.app')

@section('title', 'Nouvelle Étape')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.tutorials.steps', $tutorial->id) }}" class="text-gray-600 hover:text-gray-800">
                ← Retour aux Étapes
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Nouvelle Étape</h1>
        </div>
    </div>

    <div class="bg-gray-100 p-4 rounded-lg mb-6">
        <h2 class="text-lg font-semibold text-gray-800">{{ $tutorial->title }}</h2>
        <p class="text-sm text-gray-600">{{ $tutorial->steps->count() }} étapes existantes</p>
    </div>

    <form action="{{ route('admin.tutorials.steps.store', $tutorial->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Step Number -->
            <div>
                <label for="step_number" class="block text-sm font-medium text-gray-700 mb-2">
                    Numéro d'Étape <span class="text-red-500">*</span>
                </label>
                <input type="number" name="step_number" id="step_number" value="{{ old('step_number', $nextStepNumber) }}" min="1" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('step_number') border-red-500 @enderror">
                @error('step_number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">Numéro suggéré: {{ $nextStepNumber }}</p>
            </div>

            <!-- Estimated Time -->
            <div>
                <label for="estimated_time" class="block text-sm font-medium text-gray-700 mb-2">
                    Temps Estimé (minutes)
                </label>
                <input type="number" name="estimated_time" id="estimated_time" value="{{ old('estimated_time') }}" min="1"
                    placeholder="Ex: 15"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('estimated_time') border-red-500 @enderror">
                @error('estimated_time')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div class="md:col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Titre de l'Étape <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    placeholder="Ex: Préparation des matériaux"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description Courte
                </label>
                <textarea name="description" id="description" rows="2"
                    placeholder="Résumé bref de cette étape..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div class="md:col-span-2">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    Contenu Détaillé <span class="text-red-500">*</span>
                </label>
                <textarea name="content" id="content" rows="8" required
                    placeholder="Instructions détaillées pour cette étape..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image -->
            <div>
                <label for="image_url" class="block text-sm font-medium text-gray-700 mb-2">
                    Image de l'Étape
                </label>
                <input type="file" name="image_url" id="image_url" accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('image_url') border-red-500 @enderror">
                @error('image_url')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">JPEG, PNG, JPG, GIF - Max 2MB</p>
            </div>

            <!-- Video URL -->
            <div>
                <label for="video_url" class="block text-sm font-medium text-gray-700 mb-2">
                    URL Vidéo
                </label>
                <input type="url" name="video_url" id="video_url" value="{{ old('video_url') }}"
                    placeholder="https://youtube.com/watch?v=..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('video_url') border-red-500 @enderror">
                @error('video_url')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Required Materials -->
            <div class="md:col-span-2">
                <label for="required_materials" class="block text-sm font-medium text-gray-700 mb-2">
                    Matériaux Requis
                </label>
                <textarea name="required_materials" id="required_materials" rows="3"
                    placeholder="Liste des matériaux nécessaires pour cette étape..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('required_materials') border-red-500 @enderror">{{ old('required_materials') }}</textarea>
                @error('required_materials')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tips -->
            <div class="md:col-span-2">
                <label for="tips" class="block text-sm font-medium text-gray-700 mb-2">
                    Conseils & Astuces
                </label>
                <textarea name="tips" id="tips" rows="3"
                    placeholder="Conseils utiles pour réussir cette étape..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('tips') border-red-500 @enderror">{{ old('tips') }}</textarea>
                @error('tips')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Common Mistakes -->
            <div class="md:col-span-2">
                <label for="common_mistakes" class="block text-sm font-medium text-gray-700 mb-2">
                    Erreurs Courantes à Éviter
                </label>
                <textarea name="common_mistakes" id="common_mistakes" rows="3"
                    placeholder="Les erreurs fréquentes et comment les éviter..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('common_mistakes') border-red-500 @enderror">{{ old('common_mistakes') }}</textarea>
                @error('common_mistakes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex gap-4 mt-8">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                ✓ Créer l'Étape
            </button>
            <a href="{{ route('admin.tutorials.steps', $tutorial->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
