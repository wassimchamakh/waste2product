@extends('FrontOffice.layout1.app')

@section('title', 'Modifier le Tutoriel - Waste2Product')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50 dark:from-gray-900 dark:to-gray-800 py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-4">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Accueil</a>
                <span>/</span>
                <a href="{{ route('tutorials.index') }}" class="hover:text-primary transition-colors">Tutoriels</a>
                <span>/</span>
                <a href="{{ route('tutorials.show', $tutorial->slug) }}" class="hover:text-primary transition-colors">{{ $tutorial->title }}</a>
                <span>/</span>
                <span class="text-primary">Modifier</span>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                Modifier le Tutoriel
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Mettez à jour les informations de votre tutoriel
            </p>
        </div>

        <!-- Edit Form -->
        <form action="{{ route('tutorials.update', $tutorial->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Informations de Base
                </h2>

                <div class="space-y-5">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Titre du Tutoriel <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               value="{{ old('title', $tutorial->title) }}"
                               required
                               maxlength="255"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors"
                               placeholder="Ex: Comment créer un composteur domestique">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Description Courte <span class="text-red-500">*</span>
                            <span class="text-xs text-gray-500">(Max 500 caractères)</span>
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="3"
                                  required
                                  maxlength="500"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors resize-none"
                                  placeholder="Résumé rapide de ce que les utilisateurs apprendront">{{ old('description', $tutorial->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Contenu Complet <span class="text-red-500">*</span>
                        </label>
                        <textarea name="content" 
                                  id="content" 
                                  rows="10"
                                  required
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors resize-y"
                                  placeholder="Décrivez en détail les objectifs, le contexte et les bénéfices de ce tutoriel">{{ old('content', $tutorial->content) }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Media Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Médias
                </h2>

                <div class="space-y-5">
                    <!-- Current Thumbnail -->
                    @if($tutorial->thumbnail_image)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Image de Couverture Actuelle
                        </label>
                        <img src="{{ $tutorial->thumbnail_url }}" alt="Thumbnail" class="max-w-xs rounded-lg shadow-md">
                    </div>
                    @endif

                    <!-- Thumbnail -->
                    <div>
                        <label for="thumbnail_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nouvelle Image de Couverture
                            <span class="text-xs text-gray-500">(JPEG, PNG, JPG, GIF - Max 2MB)</span>
                        </label>
                        <input type="file" 
                               name="thumbnail_image" 
                               id="thumbnail_image" 
                               accept="image/jpeg,image/png,image/jpg,image/gif"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors">
                        @error('thumbnail_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div id="thumbnail-preview" class="mt-3 hidden">
                            <img src="" alt="Aperçu" class="max-w-xs rounded-lg shadow-md">
                        </div>
                    </div>

                    <!-- Video URL -->
                    <div>
                        <label for="intro_video_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            URL de la Vidéo d'Introduction
                            <span class="text-xs text-gray-500">(YouTube, Vimeo, etc.)</span>
                        </label>
                        <input type="url" 
                               name="intro_video_url" 
                               id="intro_video_url" 
                               value="{{ old('intro_video_url', $tutorial->intro_video_url) }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors"
                               placeholder="https://www.youtube.com/watch?v=...">
                        @error('intro_video_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Tutorial Details Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    Détails du Tutoriel
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catégorie <span class="text-red-500">*</span>
                        </label>
                        <select name="category" 
                                id="category" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors">
                            <option value="">-- Sélectionner une catégorie --</option>
                            @foreach($categories as $key => $value)
                                <option value="{{ $key }}" {{ old('category', $tutorial->category) == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Difficulty Level -->
                    <div>
                        <label for="difficulty_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Niveau de Difficulté <span class="text-red-500">*</span>
                        </label>
                        <select name="difficulty_level" 
                                id="difficulty_level" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors">
                            <option value="">-- Sélectionner un niveau --</option>
                            @foreach($difficultyLevels as $key => $value)
                                <option value="{{ $key }}" {{ old('difficulty_level', $tutorial->difficulty_level) == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        @error('difficulty_level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estimated Duration -->
                    <div>
                        <label for="estimated_duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Durée Estimée (minutes) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="estimated_duration" 
                               id="estimated_duration" 
                               value="{{ old('estimated_duration', $tutorial->estimated_duration) }}"
                               required
                               min="1"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors"
                               placeholder="Ex: 120">
                        @error('estimated_duration')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Statut <span class="text-red-500">*</span>
                        </label>
                        <select name="status" 
                                id="status" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors">
                            <option value="Draft" {{ old('status', $tutorial->status) == 'Draft' ? 'selected' : '' }}>Brouillon</option>
                            <option value="Published" {{ old('status', $tutorial->status) == 'Published' ? 'selected' : '' }}>Publié</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tags -->
                <div class="mt-5">
                    <label for="tags" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tags
                        <span class="text-xs text-gray-500">(Séparés par des virgules)</span>
                    </label>
                    <input type="text" 
                           name="tags" 
                           id="tags" 
                           value="{{ old('tags', $tutorial->tags) }}"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors"
                           placeholder="Ex: écologie, recyclage, DIY, durable">
                    @error('tags')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Featured -->
                <div class="mt-5">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" 
                               name="is_featured" 
                               id="is_featured" 
                               value="1"
                               {{ old('is_featured', $tutorial->is_featured) ? 'checked' : '' }}
                               class="w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Marquer comme tutoriel en vedette
                        </span>
                    </label>
                </div>
            </div>

            <!-- Additional Information Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Informations Complémentaires
                </h2>

                <div class="space-y-5">
                    <!-- Prerequisites -->
                    <div>
                        <label for="prerequisites" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Prérequis
                        </label>
                        <textarea name="prerequisites" 
                                  id="prerequisites" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors resize-y"
                                  placeholder="Listez les connaissances ou matériaux nécessaires avant de commencer">{{ old('prerequisites', $tutorial->prerequisites) }}</textarea>
                        @error('prerequisites')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Learning Outcomes -->
                    <div>
                        <label for="learning_outcomes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Objectifs d'Apprentissage
                        </label>
                        <textarea name="learning_outcomes" 
                                  id="learning_outcomes" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors resize-y"
                                  placeholder="Ce que les utilisateurs apprendront en suivant ce tutoriel">{{ old('learning_outcomes', $tutorial->learning_outcomes) }}</textarea>
                        @error('learning_outcomes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between gap-4 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex gap-3">
                    <a href="{{ route('tutorials.show', $tutorial->slug) }}" 
                       class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors font-medium">
                        Annuler
                    </a>
                    <button type="button" 
                            onclick="if(confirm('Êtes-vous sûr de vouloir supprimer ce tutoriel ?')) { document.getElementById('delete-form').submit(); }"
                            class="px-6 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Supprimer
                    </button>
                </div>
                <button type="submit" 
                        class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-green-600 transition-colors font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Mettre à Jour
                </button>
            </div>
        </form>

        <!-- Delete Form (Hidden) -->
        <form id="delete-form" 
              action="{{ route('tutorials.destroy', $tutorial->slug) }}" 
              method="POST" 
              class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

<script>
// Image preview
document.getElementById('thumbnail_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('thumbnail-preview');
            preview.querySelector('img').src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});

// Character counter for description
const description = document.getElementById('description');
if (description) {
    const counterDiv = document.createElement('div');
    counterDiv.className = 'text-xs text-gray-500 mt-1 text-right';
    description.parentNode.appendChild(counterDiv);
    
    const updateCounter = () => {
        const remaining = 500 - description.value.length;
        counterDiv.textContent = `${remaining} caractères restants`;
        counterDiv.style.color = remaining < 50 ? '#ef4444' : '#6b7280';
    };
    
    description.addEventListener('input', updateCounter);
    updateCounter();
}
</script>
@endsection
