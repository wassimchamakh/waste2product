@extends('FrontOffice.layout1.app')

@section('title', 'Créer un Tutoriel')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-green-600 to-blue-600 text-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">
                <i class="fas fa-plus-circle mr-3"></i>Créer un Nouveau Tutoriel
            </h1>
            <p class="text-lg opacity-90">
                Partagez vos connaissances et aidez les autres à apprendre des pratiques durables
            </p>
        </div>
    </div>
</div>

<!-- Form Section -->
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-2xl shadow-xl p-8">
        
        <!-- Navigation -->
        <div class="mb-8">
            <a 
                href="{{ route('tutorials.index') }}" 
                class="inline-flex items-center gap-2 text-gray-600 hover:text-green-600 transition-colors"
            >
                <i class="fas fa-arrow-left"></i>
                Retour aux tutoriels
            </a>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
            <div class="flex items-start gap-3">
                <i class="fas fa-exclamation-circle text-red-500 text-xl mt-0.5"></i>
                <div class="flex-1">
                    <h3 class="text-red-800 font-semibold mb-2">Erreurs de validation</h3>
                    <ul class="list-disc list-inside space-y-1 text-red-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <form action="{{ route('tutorials.store') }}" method="POST" enctype="multipart/form-data" id="tutorial-form">
            @csrf

            <!-- Tutorial Basic Info -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                    <i class="fas fa-info-circle text-green-600"></i>
                    Informations de base
                </h2>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                                Titre du tutoriel <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="title" 
                                name="title" 
                                value="{{ old('title') }}"
                                placeholder="Ex: Comment débuter le compostage à la maison"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent transition-colors text-base"
                                required
                            >
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">
                                Catégorie <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="category" 
                                name="category" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent transition-colors text-base"
                                required
                            >
                                <option value="">Sélectionnez une catégorie</option>
                                <option value="Recycling" {{ old('category') == 'Recycling' ? 'selected' : '' }}>♻️ Recyclage</option>
                                <option value="Composting" {{ old('category') == 'Composting' ? 'selected' : '' }}>🌱 Compostage</option>
                                <option value="Energy" {{ old('category') == 'Energy' ? 'selected' : '' }}>⚡ Énergie</option>
                                <option value="Water" {{ old('category') == 'Water' ? 'selected' : '' }}>💧 Eau</option>
                                <option value="Waste Reduction" {{ old('category') == 'Waste Reduction' ? 'selected' : '' }}>🗑️ Réduction des déchets</option>
                                <option value="Gardening" {{ old('category') == 'Gardening' ? 'selected' : '' }}>🌿 Jardinage</option>
                                <option value="DIY" {{ old('category') == 'DIY' ? 'selected' : '' }}>🔨 Bricolage</option>
                                <option value="General" {{ old('category') == 'General' ? 'selected' : '' }}>📚 Général</option>
                            </select>
                        </div>

                        <!-- Difficulty -->
                        <div>
                            <label for="difficulty_level" class="block text-sm font-semibold text-gray-700 mb-2">
                                Niveau de difficulté <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="difficulty_level" 
                                name="difficulty_level" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent transition-colors text-base"
                                required
                            >
                                <option value="">Sélectionnez la difficulté</option>
                                <option value="Beginner" {{ old('difficulty_level') == 'Beginner' ? 'selected' : '' }}>
                                    🟢 Débutant
                                </option>
                                <option value="Intermediate" {{ old('difficulty_level') == 'Intermediate' ? 'selected' : '' }}>
                                    🟡 Intermédiaire
                                </option>
                                <option value="Advanced" {{ old('difficulty_level') == 'Advanced' ? 'selected' : '' }}>
                                    🟠 Avancé
                                </option>
                                <option value="Expert" {{ old('difficulty_level') == 'Expert' ? 'selected' : '' }}>
                                    🔴 Expert
                                </option>
                            </select>
                        </div>

                        <!-- Estimated Duration (Auto-calculated) -->
                        <div>
                            <label for="estimated_duration_display" class="block text-sm font-semibold text-gray-700 mb-2">
                                Durée estimée totale
                            </label>
                            <div class="w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-50 text-gray-700 text-base flex items-center gap-2">
                                <i class="fas fa-clock text-green-600"></i>
                                <span id="estimated_duration_display">0 minutes</span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                ⚡ Calculé automatiquement à partir des étapes
                            </p>
                            <!-- Hidden field for submission -->
                            <input type="hidden" id="estimated_duration" name="estimated_duration" value="0">
                        </div>

                        <!-- Tags -->
                        <div>
                            <label for="tags" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tags (séparés par des virgules)
                            </label>
                            <input 
                                type="text" 
                                id="tags" 
                                name="tags" 
                                value="{{ old('tags') }}"
                                placeholder="compostage, organique, durable"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent transition-colors text-base"
                            >
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Statut <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input 
                                        type="radio" 
                                        name="status" 
                                        value="Draft"
                                        {{ old('status', 'Draft') == 'Draft' ? 'checked' : '' }}
                                        class="mr-2"
                                        required
                                    >
                                    📝 Brouillon
                                </label>
                                <label class="flex items-center">
                                    <input 
                                        type="radio" 
                                        name="status" 
                                        value="Published"
                                        {{ old('status') == 'Published' ? 'checked' : '' }}
                                        class="mr-2"
                                    >
                                    ✅ Publié
                                </label>
                            </div>
                        </div>

                        <!-- Featured -->
                        <div>
                            <label class="flex items-center cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="is_featured" 
                                    value="1"
                                    {{ old('is_featured') ? 'checked' : '' }}
                                    class="mr-3"
                                >
                                <div>
                                    <span class="font-semibold text-gray-700">⭐ Mettre en avant ce tutoriel</span>
                                    <p class="text-sm text-gray-500">Afficher sur la page d'accueil et la section en vedette</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Thumbnail -->
                        <div>
                            <label for="thumbnail_image" class="block text-sm font-semibold text-gray-700 mb-2">
                                Image miniature
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-green-600 transition-colors">
                                <div class="space-y-2 text-center">
                                    <div id="preview-container" class="hidden mb-4">
                                        <img id="preview-image" src="" alt="Aperçu" class="mx-auto max-h-48 rounded-lg shadow-lg">
                                    </div>
                                    
                                    <div id="upload-placeholder">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="thumbnail_image" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-600 px-3 py-1">
                                                <span>Télécharger une photo</span>
                                                <input 
                                                    id="thumbnail_image" 
                                                    name="thumbnail_image" 
                                                    type="file" 
                                                    class="sr-only" 
                                                    accept="image/jpeg,image/png,image/jpg,image/gif"
                                                    onchange="previewThumbnail(event)"
                                                >
                                            </label>
                                            <p class="pl-1">ou glisser-déposer</p>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2">
                                            PNG, JPG, GIF jusqu'à 2MB
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                Description courte <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="description" 
                                name="description"
                                rows="4"
                                maxlength="500"
                                placeholder="Bref aperçu du tutoriel (max 500 caractères)..."
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent transition-colors text-base"
                                required
                            >{{ old('description') }}</textarea>
                            <p class="text-sm text-gray-600 mt-1">
                                Maximum 500 caractères
                            </p>
                        </div>

                        <!-- Content -->
                        <div>
                            <label for="content" class="block text-sm font-semibold text-gray-700 mb-2">
                                Contenu complet <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="content" 
                                name="content"
                                rows="8"
                                placeholder="Introduction détaillée et aperçu du tutoriel..."
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent transition-colors text-base"
                                required
                            >{{ old('content') }}</textarea>
                        </div>

                        <!-- Prerequisites -->
                        <div>
                            <label for="prerequisites" class="block text-sm font-semibold text-gray-700 mb-2">
                                Prérequis
                            </label>
                            <textarea 
                                id="prerequisites" 
                                name="prerequisites"
                                rows="3"
                                placeholder="Ce que les utilisateurs doivent savoir avant de commencer..."
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent transition-colors text-base"
                            >{{ old('prerequisites') }}</textarea>
                        </div>

                        <!-- Learning Outcomes -->
                        <div>
                            <label for="learning_outcomes" class="block text-sm font-semibold text-gray-700 mb-2">
                                Objectifs d'apprentissage
                            </label>
                            <textarea 
                                id="learning_outcomes" 
                                name="learning_outcomes"
                                rows="4"
                                placeholder="Qu'apprendront les utilisateurs ? Un objectif par ligne..."
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent transition-colors text-base"
                            >{{ old('learning_outcomes') }}</textarea>
                        </div>

                        <!-- Intro Video URL -->
                        <div>
                            <label for="intro_video_url" class="block text-sm font-semibold text-gray-700 mb-2">
                                URL de la vidéo d'introduction
                            </label>
                            <input 
                                type="url" 
                                id="intro_video_url" 
                                name="intro_video_url" 
                                value="{{ old('intro_video_url') }}"
                                placeholder="https://youtube.com/watch?v=..."
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent transition-colors text-base"
                            >
                            <p class="text-sm text-gray-600 mt-1">
                                Liens YouTube ou Vimeo supportés
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tutorial Steps -->
            <div class="mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                        <i class="fas fa-list-ol text-green-600"></i>
                        Étapes du tutoriel
                        <span class="text-sm text-gray-500 font-normal">(Optionnel - peut être ajouté plus tard)</span>
                    </h2>
                    <button 
                        type="button" 
                        onclick="addStep()" 
                        class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                    >
                        <i class="fas fa-plus"></i>
                        Ajouter une étape
                    </button>
                </div>

                <div id="steps-container">
                    <!-- Initial step (optional) -->
                    <div class="step-item bg-gray-50 rounded-xl p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <span class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white font-bold text-sm step-number">1</span>
                                Étape 1
                            </h3>
                            <button 
                                type="button" 
                                onclick="removeStep(this)" 
                                class="text-red-500 hover:text-red-700 remove-step hidden"
                                title="Supprimer cette étape"
                            >
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Step Title -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Titre de l'étape
                                </label>
                                <input 
                                    type="text" 
                                    name="steps[0][title]" 
                                    placeholder="Ex: Préparez vos matériaux"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent text-base"
                                >
                            </div>

                            <!-- Step Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Description courte de l'étape
                                </label>
                                <input 
                                    type="text" 
                                    name="steps[0][description]" 
                                    placeholder="Bref résumé de cette étape..."
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent text-base"
                                >
                            </div>

                            <!-- Step Content -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Instructions détaillées de l'étape
                                </label>
                                <textarea 
                                    name="steps[0][content]" 
                                    rows="4"
                                    placeholder="Instructions détaillées pour cette étape..."
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent text-base"
                                ></textarea>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <!-- Duration -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Durée estimée (minutes)
                                    </label>
                                    <input 
                                        type="number" 
                                        name="steps[0][estimated_time]" 
                                        placeholder="10"
                                        min="0"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent text-base"
                                    >
                                </div>

                                <!-- Video URL -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        URL de la vidéo
                                    </label>
                                    <input 
                                        type="url" 
                                        name="steps[0][video_url]" 
                                        placeholder="https://youtube.com/watch?v=..."
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent text-base"
                                    >
                                </div>
                            </div>

                            <!-- Step Image -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-image text-green-600 mr-1"></i>
                                    Image de l'étape (optionnel)
                                </label>
                                <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-green-600 transition-colors">
                                    <div class="space-y-2 text-center">
                                        <div class="step-image-preview hidden mb-4">
                                            <img src="" alt="Aperçu" class="mx-auto max-h-48 rounded-lg shadow-lg">
                                        </div>
                                        <svg class="mx-auto h-12 w-12 text-gray-400 step-upload-icon" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-600 px-3 py-1">
                                                <span>Télécharger une image</span>
                                                <input 
                                                    type="file" 
                                                    name="steps[0][image]" 
                                                    accept="image/*"
                                                    class="sr-only step-image-input"
                                                    onchange="previewStepImage(this)"
                                                >
                                            </label>
                                            <p class="pl-1">ou glisser-déposer</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF jusqu'à 10MB</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Required Materials -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Matériaux nécessaires
                                </label>
                                <textarea 
                                    name="steps[0][required_materials]" 
                                    rows="2"
                                    placeholder="Liste des matériaux et outils nécessaires pour cette étape..."
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent text-base"
                                ></textarea>
                            </div>

                            <!-- Tips -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Astuces & Conseils
                                </label>
                                <textarea 
                                    name="steps[0][tips]" 
                                    rows="2"
                                    placeholder="Conseils utiles pour réussir cette étape..."
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent text-base"
                                ></textarea>
                            </div>

                            <!-- Common Mistakes -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Erreurs courantes à éviter
                                </label>
                                <textarea 
                                    name="steps[0][common_mistakes]" 
                                    rows="2"
                                    placeholder="Les erreurs fréquentes et comment les éviter..."
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent text-base"
                                ></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-lightbulb text-blue-500 text-xl mt-0.5"></i>
                        <div>
                            <h3 class="text-blue-800 font-semibold mb-1">Conseils pour les étapes</h3>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Soyez précis et détaillé dans vos descriptions</li>
                                <li>• Incluez des conseils utiles et des avertissements</li>
                                <li>• Ajoutez des vidéos pour rendre les étapes plus claires</li>
                                <li>• Pensez aux débutants qui suivront votre tutoriel</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t">
                <button 
                    type="submit" 
                    class="flex-1 bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white px-8 py-4 rounded-lg font-semibold text-lg shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-3"
                >
                    <i class="fas fa-rocket text-xl"></i>
                    Créer le tutoriel
                </button>
                
                <a 
                    href="{{ route('tutorials.index') }}" 
                    class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-8 py-4 rounded-lg font-semibold text-lg transition-all flex items-center justify-center gap-3"
                >
                    <i class="fas fa-times-circle text-xl"></i>
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
let stepCount = 1;

function previewThumbnail(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
            document.getElementById('preview-container').classList.remove('hidden');
            document.getElementById('upload-placeholder').classList.add('hidden');
        }
        reader.readAsDataURL(file);
    }
}

function addStep() {
    const container = document.getElementById('steps-container');
    const newStep = document.createElement('div');
    newStep.className = 'step-item bg-gray-50 rounded-xl p-6 mb-6';
    
    newStep.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <span class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white font-bold text-sm step-number">${stepCount + 1}</span>
                Étape ${stepCount + 1}
            </h3>
            <button 
                type="button" 
                onclick="removeStep(this)" 
                class="text-red-500 hover:text-red-700 remove-step"
                title="Supprimer cette étape"
            >
                <i class="fas fa-trash"></i>
            </button>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Titre de l'étape
                </label>
                <input 
                    type="text" 
                    name="steps[${stepCount}][title]" 
                    placeholder="Ex: Préparez vos matériaux"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent text-base"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Description courte de l'étape
                </label>
                <input 
                    type="text" 
                    name="steps[${stepCount}][description]" 
                    placeholder="Bref résumé de cette étape..."
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent text-base"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Instructions détaillées de l'étape
                </label>
                <textarea 
                    name="steps[${stepCount}][content]" 
                    rows="4"
                    placeholder="Instructions détaillées pour cette étape..."
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent text-base"
                ></textarea>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Durée estimée (minutes)
                    </label>
                    <input 
                        type="number" 
                        name="steps[${stepCount}][estimated_time]" 
                        placeholder="10"
                        min="0"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent text-base"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        URL de la vidéo
                    </label>
                    <input 
                        type="url" 
                        name="steps[${stepCount}][video_url]" 
                        placeholder="https://youtube.com/watch?v=..."
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent text-base"
                    >
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-image text-green-600 mr-1"></i>
                    Image de l'étape (optionnel)
                </label>
                <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-green-600 transition-colors">
                    <div class="space-y-2 text-center">
                        <div class="step-image-preview hidden mb-4">
                            <img src="" alt="Aperçu" class="mx-auto max-h-48 rounded-lg shadow-lg">
                        </div>
                        <svg class="mx-auto h-12 w-12 text-gray-400 step-upload-icon" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-600 px-3 py-1">
                                <span>Télécharger une image</span>
                                <input 
                                    type="file" 
                                    name="steps[${stepCount}][image]" 
                                    accept="image/*"
                                    class="sr-only step-image-input"
                                    onchange="previewStepImage(this)"
                                >
                            </label>
                            <p class="pl-1">ou glisser-déposer</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF jusqu'à 10MB</p>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Matériaux nécessaires
                </label>
                <textarea 
                    name="steps[${stepCount}][required_materials]" 
                    rows="2"
                    placeholder="Liste des matériaux et outils nécessaires pour cette étape..."
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent text-base"
                ></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Astuces & Conseils
                </label>
                <textarea 
                    name="steps[${stepCount}][tips]" 
                    rows="2"
                    placeholder="Conseils utiles pour réussir cette étape..."
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent text-base"
                ></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Erreurs courantes à éviter
                </label>
                <textarea 
                    name="steps[${stepCount}][common_mistakes]" 
                    rows="2"
                    placeholder="Les erreurs fréquentes et comment les éviter..."
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-600 focus:border-transparent text-base"
                ></textarea>
            </div>
        </div>
    `;
    
    container.appendChild(newStep);
    stepCount++;
    updateRemoveButtons();
    attachDurationListeners();
    calculateTotalDuration();
}

function removeStep(button) {
    const stepItem = button.closest('.step-item');
    stepItem.remove();
    updateStepNumbers();
    updateRemoveButtons();
    calculateTotalDuration();
}

function updateStepNumbers() {
    const steps = document.querySelectorAll('.step-item');
    stepCount = steps.length;
    
    steps.forEach((step, index) => {
        const stepNumber = step.querySelector('.step-number');
        const stepTitle = step.querySelector('h3');
        const inputs = step.querySelectorAll('input, textarea');
        
        stepNumber.textContent = index + 1;
        stepTitle.innerHTML = `
            <span class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white font-bold text-sm step-number">${index + 1}</span>
            Étape ${index + 1}
        `;
        
        // Update input names
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name && name.includes('steps[')) {
                const newName = name.replace(/steps\[\d+\]/, `steps[${index}]`);
                input.setAttribute('name', newName);
            }
        });
    });
}

function updateRemoveButtons() {
    const removeButtons = document.querySelectorAll('.remove-step');
    removeButtons.forEach(button => {
        if (stepCount > 1) {
            button.classList.remove('hidden');
        } else {
            button.classList.add('hidden');
        }
    });
}

function calculateTotalDuration() {
    let total = 0;
    const estimatedTimeInputs = document.querySelectorAll('input[name*="[estimated_time]"]');
    
    estimatedTimeInputs.forEach(input => {
        const value = parseInt(input.value) || 0;
        total += value;
    });
    
    // Update display
    const display = document.getElementById('estimated_duration_display');
    const hiddenInput = document.getElementById('estimated_duration');
    
    if (display) {
        if (total === 0) {
            display.innerHTML = '<span class="text-gray-500">0 minutes (ajoutez des durées aux étapes)</span>';
        } else if (total < 60) {
            display.textContent = total + ' minutes';
        } else {
            const hours = Math.floor(total / 60);
            const minutes = total % 60;
            display.textContent = hours + 'h ' + (minutes > 0 ? minutes + 'min' : '');
        }
    }
    
    if (hiddenInput) {
        hiddenInput.value = total;
    }
}

// Add event listener to all estimated_time inputs
function attachDurationListeners() {
    const estimatedTimeInputs = document.querySelectorAll('input[name*="[estimated_time]"]');
    estimatedTimeInputs.forEach(input => {
        input.addEventListener('input', calculateTotalDuration);
        input.addEventListener('change', calculateTotalDuration);
    });
}

// Preview step image
function previewStepImage(input) {
    const stepItem = input.closest('.step-item');
    if (!stepItem) return;
    
    const previewContainer = stepItem.querySelector('.step-image-preview');
    const previewImage = previewContainer?.querySelector('img');
    const uploadIcon = stepItem.querySelector('.step-upload-icon');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            if (previewImage) {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('hidden');
                if (uploadIcon) uploadIcon.classList.add('hidden');
            }
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('Formulaire de création de tutoriel initialisé');
    updateRemoveButtons();
    attachDurationListeners();
    calculateTotalDuration();
});
</script>
@endsection
