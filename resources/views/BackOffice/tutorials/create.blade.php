@extends('BackOffice.layouts.app')

@section('title', 'Créer un Tutoriel')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.tutorials.index') }}" class="text-gray-600 hover:text-gray-800">
                ← Retour
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Créer un Nouveau Tutoriel</h1>
        </div>
    </div>

    <form action="{{ route('admin.tutorials.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Title -->
            <div class="md:col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Titre du Tutoriel <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea name="description" id="description" rows="3" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div class="md:col-span-2">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    Contenu Détaillé
                </label>
                <textarea name="content" id="content" rows="8"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Thumbnail Image -->
            <div>
                <label for="thumbnail_image" class="block text-sm font-medium text-gray-700 mb-2">
                    Image Miniature
                </label>
                <input type="file" name="thumbnail_image" id="thumbnail_image" accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('thumbnail_image') border-red-500 @enderror">
                @error('thumbnail_image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">JPEG, PNG, JPG, GIF - Max 2MB</p>
            </div>

            <!-- Intro Video URL -->
            <div>
                <label for="intro_video_url" class="block text-sm font-medium text-gray-700 mb-2">
                    URL Vidéo d'Introduction
                </label>
                <input type="url" name="intro_video_url" id="intro_video_url" value="{{ old('intro_video_url') }}"
                    placeholder="https://youtube.com/watch?v=..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('intro_video_url') border-red-500 @enderror">
                @error('intro_video_url')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Difficulty Level -->
            <div>
                <label for="difficulty_level" class="block text-sm font-medium text-gray-700 mb-2">
                    Niveau de Difficulté <span class="text-red-500">*</span>
                </label>
                <select name="difficulty_level" id="difficulty_level" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('difficulty_level') border-red-500 @enderror">
                    <option value="">Sélectionner...</option>
                    <option value="Beginner" {{ old('difficulty_level') == 'Beginner' ? 'selected' : '' }}>Débutant</option>
                    <option value="Intermediate" {{ old('difficulty_level') == 'Intermediate' ? 'selected' : '' }}>Intermédiaire</option>
                    <option value="Advanced" {{ old('difficulty_level') == 'Advanced' ? 'selected' : '' }}>Avancé</option>
                    <option value="Expert" {{ old('difficulty_level') == 'Expert' ? 'selected' : '' }}>Expert</option>
                </select>
                @error('difficulty_level')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                    Catégorie <span class="text-red-500">*</span>
                </label>
                <select name="category" id="category" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('category') border-red-500 @enderror">
                    <option value="">Sélectionner...</option>
                    <option value="Recycling" {{ old('category') == 'Recycling' ? 'selected' : '' }}>Recyclage</option>
                    <option value="Composting" {{ old('category') == 'Composting' ? 'selected' : '' }}>Compostage</option>
                    <option value="Energy" {{ old('category') == 'Energy' ? 'selected' : '' }}>Énergie</option>
                    <option value="Water" {{ old('category') == 'Water' ? 'selected' : '' }}>Eau</option>
                    <option value="Waste Reduction" {{ old('category') == 'Waste Reduction' ? 'selected' : '' }}>Réduction des Déchets</option>
                    <option value="Gardening" {{ old('category') == 'Gardening' ? 'selected' : '' }}>Jardinage</option>
                    <option value="DIY" {{ old('category') == 'DIY' ? 'selected' : '' }}>DIY</option>
                    <option value="General" {{ old('category') == 'General' ? 'selected' : '' }}>Général</option>
                </select>
                @error('category')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Estimated Duration -->
            <div>
                <label for="estimated_duration" class="block text-sm font-medium text-gray-700 mb-2">
                    Durée Estimée (minutes)
                </label>
                <input type="number" name="estimated_duration" id="estimated_duration" value="{{ old('estimated_duration') }}" min="1"
                    placeholder="Ex: 30"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('estimated_duration') border-red-500 @enderror">
                @error('estimated_duration')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Creator -->
            <div>
                <label for="created_by" class="block text-sm font-medium text-gray-700 mb-2">
                    Créateur <span class="text-red-500">*</span>
                </label>
                <select name="created_by" id="created_by" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('created_by') border-red-500 @enderror">
                    <option value="">Sélectionner un utilisateur...</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('created_by') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('created_by')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Prerequisites -->
            <div class="md:col-span-2">
                <label for="prerequisites" class="block text-sm font-medium text-gray-700 mb-2">
                    Prérequis
                </label>
                <textarea name="prerequisites" id="prerequisites" rows="3"
                    placeholder="Liste des connaissances ou matériels requis avant de commencer..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('prerequisites') border-red-500 @enderror">{{ old('prerequisites') }}</textarea>
                @error('prerequisites')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Learning Outcomes -->
            <div class="md:col-span-2">
                <label for="learning_outcomes" class="block text-sm font-medium text-gray-700 mb-2">
                    Objectifs d'Apprentissage
                </label>
                <textarea name="learning_outcomes" id="learning_outcomes" rows="3"
                    placeholder="Ce que les utilisateurs apprendront en complétant ce tutoriel..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('learning_outcomes') border-red-500 @enderror">{{ old('learning_outcomes') }}</textarea>
                @error('learning_outcomes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tags -->
            <div class="md:col-span-2">
                <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                    Tags
                </label>
                <input type="text" name="tags" id="tags" value="{{ old('tags') }}"
                    placeholder="recyclage, diy, écologie (séparés par des virgules)"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('tags') border-red-500 @enderror">
                @error('tags')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">Séparez les tags par des virgules</p>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Statut <span class="text-red-500">*</span>
                </label>
                <select name="status" id="status" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                    <option value="Draft" {{ old('status') == 'Draft' ? 'selected' : '' }}>Brouillon</option>
                    <option value="Published" {{ old('status') == 'Published' ? 'selected' : '' }}>Publié</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Is Featured -->
            <div class="flex items-center">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label for="is_featured" class="ml-2 text-sm font-medium text-gray-700">
                    Mettre en vedette
                </label>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex gap-4 mt-8">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                ✓ Créer le Tutoriel
            </button>
            <a href="{{ route('admin.tutorials.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition">
                Annuler
            </a>
        </div>
    </form>
</div>

<script>
// Preview thumbnail image
document.getElementById('thumbnail_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // You can add image preview here if needed
            console.log('Image selected:', file.name);
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
