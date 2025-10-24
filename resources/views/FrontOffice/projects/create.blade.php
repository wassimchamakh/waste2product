@extends('FrontOffice.layout1.app')

@section('title', 'Créer un Projet - Waste2Product')

@section('content')
<!-- Hero Section -->
<div class="gradient-hero text-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">
                <i class="fas fa-plus-circle mr-3"></i>Créer un Projet DIY
            </h1>
            <p class="text-lg opacity-90">
                Partagez votre créativité et inspirez la communauté avec votre projet
            </p>
        </div>
    </div>
</div>

<!-- Form Section -->
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
        
        <!-- Navigation -->
        <div class="mb-8">
            <a 
                href="{{ route('projects.index') }}" 
                class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors"
            >
                <i class="fas fa-arrow-left"></i>
                Retour aux projets
            </a>
        </div>

        <!-- Messages d'erreur -->
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

        <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data" id="project-form">
            @csrf

            <!-- Project Basic Info -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                    <i class="fas fa-info-circle text-primary"></i>
                    Informations de base
                </h2>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Titre du projet <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="title" 
                                name="title" 
                                value="{{ old('title') }}"
                                placeholder="Ex: Table basse à partir de palettes..."
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-colors text-base"
                                required
                            >
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Catégorie <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="category_id" 
                                name="category_id" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-colors text-base"
                                required
                            >
                                <option value="">Sélectionnez une catégorie</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Difficulty -->
<div>
    <label for="difficulty_level" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
        Niveau de difficulté <span class="text-red-500">*</span>
    </label>
    <select 
        id="difficulty_level" 
        name="difficulty_level" 
        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-colors text-base"
        required
    >
        <option value="">Sélectionnez la difficulté</option>
        <option value="facile" {{ old('difficulty_level') == 'facile' ? 'selected' : '' }}>
            🟢 Facile - Débutant
        </option>
        <option value="intermédiaire" {{ old('difficulty_level') == 'intermédiaire' ? 'selected' : '' }}>
            🟡 Intermédiaire - Niveau moyen
        </option>
        <option value="difficile" {{ old('difficulty_level') == 'difficile' ? 'selected' : '' }}>
            🔴 Difficile - Expert
        </option>
    </select>
</div>

                        <!-- Estimated Time -->
                        <div>
                            <label for="estimated_time" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Temps estimé <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="estimated_time" 
                                name="estimated_time" 
                                value="{{ old('estimated_time') }}"
                                placeholder="Ex: 3 heures, 1 week-end, 2 jours..."
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-colors text-base"
                                required
                            >
                        </div>

                        <!-- Impact Score -->
                        <div>
                            <label for="impact_score" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Score d'impact environnemental <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center gap-4">
                                <input 
                                    type="range" 
                                    id="impact_score" 
                                    name="impact_score" 
                                    min="1" 
                                    max="10" 
                                    value="{{ old('impact_score', 5) }}"
                                    class="flex-1"
                                    oninput="updateImpactScore(this.value)"
                                >
                                <span id="impact-value" class="w-12 text-center font-bold text-primary text-lg">5</span>
                                <span class="text-sm text-gray-500">/10</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                1 = Faible impact, 10 = Très bon pour l'environnement
                            </p>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Photo -->
                        <div>
                            <label for="photo" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Photo principale du projet
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-primary transition-colors">
                                <div class="space-y-2 text-center">
                                    <div id="preview-container" class="hidden mb-4">
                                        <img id="preview-image" src="" alt="Aperçu" class="mx-auto max-h-48 rounded-lg shadow-lg">
                                    </div>
                                    
                                    <div id="upload-placeholder">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                        <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                            <label for="photo" class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-primary hover:text-green-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary px-3 py-1">
                                                <span>Télécharger une photo</span>
                                                <input 
                                                    id="photo" 
                                                    name="photo" 
                                                    type="file" 
                                                    class="sr-only" 
                                                    accept="image/jpeg,image/png,image/jpg,image/gif"
                                                    onchange="previewImage(event)"
                                                >
                                            </label>
                                            <p class="pl-1">ou glisser-déposer</p>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                            PNG, JPG, GIF jusqu'à 2MB
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Description détaillée <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="description" 
                                name="description"
                                rows="8"
                                placeholder="Décrivez votre projet : objectif, résultat attendu, conseils généraux..."
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-colors text-base"
                                required
                            >{{ old('description') }}</textarea>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Minimum 50 caractères
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project Steps -->
            <div class="mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <i class="fas fa-list-ol text-primary"></i>
                        Étapes du projet
                        <span class="text-red-500">*</span>
                    </h2>
<<<<<<< HEAD
                    <button 
                        type="button" 
                        onclick="addStep()" 
                        class="inline-flex items-center gap-2 bg-secondary hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                    >
                        <i class="fas fa-plus"></i>
                        Ajouter une étape
                    </button>
=======
                    <div class="flex gap-2">
                        <button 
                            type="button" 
                            onclick="addStep()" 
                            class="inline-flex items-center gap-2 bg-secondary hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                        >
                            <i class="fas fa-plus"></i>
                            Ajouter une étape
                        </button>
                        <button 
                            type="button" 
                            onclick="suggestStep()" 
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700 transition-colors"
                        >
                            <i class="fas fa-magic"></i> Suggérer une étape
                        </button>
                    </div>
>>>>>>> tutoral-branch
                </div>

                <div id="steps-container">
                    <!-- Initial step -->
                    <div class="step-item bg-gray-50 dark:bg-gray-700 rounded-xl p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <span class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white font-bold text-sm step-number">1</span>
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

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div class="space-y-4">
                                <!-- Step Title -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Titre de l'étape <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        name="steps[0][title]" 
                                        placeholder="Ex: Préparer les matériaux..."
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent text-base"
                                        required
                                    >
                                </div>

                                <!-- Duration -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Durée estimée
                                    </label>
                                    <input 
                                        type="text" 
                                        name="steps[0][duration]" 
                                        placeholder="Ex: 30 minutes, 1 heure..."
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent text-base"
                                    >
                                </div>

                                <!-- Materials -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Matériaux nécessaires
                                    </label>
                                    <textarea 
                                        name="steps[0][materials_needed]" 
                                        rows="3"
                                        placeholder="Liste des matériaux pour cette étape..."
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent text-base"
                                    ></textarea>
                                </div>

                                <!-- Tools -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Outils requis
                                    </label>
                                    <textarea 
                                        name="steps[0][tools_required]" 
                                        rows="3"
                                        placeholder="Liste des outils pour cette étape..."
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent text-base"
                                    ></textarea>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div>
                                <!-- Step Description -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Description détaillée <span class="text-red-500">*</span>
                                    </label>
                                    <textarea 
                                        name="steps[0][description]" 
                                        rows="10"
                                        placeholder="Expliquez en détail comment réaliser cette étape..."
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent text-base"
                                        required
                                    ></textarea>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Minimum 20 caractères
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 border-l-4 border-blue-500 p-4 rounded-lg">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-lightbulb text-blue-500 text-xl mt-0.5"></i>
                        <div>
                            <h3 class="text-blue-800 dark:text-blue-300 font-semibold mb-1">Conseils pour les étapes</h3>
                            <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                                <li>• Soyez précis et détaillé dans vos descriptions</li>
                                <li>• Listez tous les matériaux et outils nécessaires</li>
                                <li>• Indiquez les temps de réalisation pour planifier</li>
<<<<<<< HEAD
=======
                               
>>>>>>> tutoral-branch
                                <li>• Pensez aux débutants qui suivront votre projet</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t dark:border-gray-700">
                <button 
                    type="submit" 
                    class="flex-1 bg-gradient-to-r from-primary to-green-700 hover:from-green-700 hover:to-primary text-white px-8 py-4 rounded-lg font-semibold text-lg shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-3"
                >
                    <i class="fas fa-rocket text-xl"></i>
                    Publier le projet
                </button>
                
                <a 
                    href="{{ route('projects.index') }}" 
                    class="flex-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all flex items-center justify-center gap-3"
                >
                    <i class="fas fa-times-circle text-xl"></i>
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
<<<<<<< HEAD
=======
// Suggestions d'étapes types
const stepSuggestions = [
    {
        title: "Préparer les matériaux",
        duration: "10 min",
        materials_needed: "Liste des matériaux nécessaires",
        tools_required: "Liste des outils",
        description: "Rassemblez tous les matériaux et outils nécessaires avant de commencer."
    },
    {
        title: "Assembler les pièces",
        duration: "20 min",
        materials_needed: "Pièces à assembler",
        tools_required: "Tournevis, marteau",
        description: "Suivez les instructions pour assembler les différentes parties du projet."
    },
    {
        title: "Vérifier la solidité",
        duration: "5 min",
        materials_needed: "",
        tools_required: "",
        description: "Testez la solidité et la stabilité de votre réalisation."
    },
    {
        title: "Nettoyer la zone de travail",
        duration: "5 min",
        materials_needed: "Chiffon, balai",
        tools_required: "",
        description: "Nettoyez la zone de travail et rangez les outils."
    }
];

function suggestStep() {
    // Afficher le loader
    const container = document.getElementById('steps-container');
    const loaderId = 'suggestion-loader';
    let loader = document.getElementById(loaderId);
    if (!loader) {
        loader = document.createElement('div');
        loader.id = loaderId;
        loader.className = 'flex items-center justify-center my-4';
        loader.innerHTML = '<span class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-primary mr-2"></span><span class="text-primary font-semibold">Génération de la suggestion...</span>';
        container.parentNode.insertBefore(loader, container);
    }
    // Attendre 5 secondes puis afficher la suggestion
    setTimeout(function() {
        loader.remove();
        const suggestion = stepSuggestions[Math.floor(Math.random() * stepSuggestions.length)];
        const index = container.children.length;
        const newStep = document.createElement('div');
        newStep.className = 'step-item bg-gray-50 dark:bg-gray-700 rounded-xl p-6 mb-6';
        newStep.innerHTML = `
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white font-bold text-sm step-number">${index + 1}</span>
                    Étape ${index + 1}
                </h3>
                <button type="button" onclick="removeStep(this)" class="text-red-500 hover:text-red-700 remove-step" title="Supprimer cette étape">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Titre de l'étape <span class="text-red-500">*</span></label>
                    <input type="text" name="steps[${index}][title]" class="form-input w-full rounded-lg" value="${suggestion.title}" required>
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Durée estimée</label>
                    <input type="text" name="steps[${index}][duration]" class="form-input w-full rounded-lg" value="${suggestion.duration}">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Matériaux nécessaires</label>
                    <textarea name="steps[${index}][materials_needed]" class="form-textarea w-full rounded-lg" rows="2">${suggestion.materials_needed}</textarea>
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Outils requis</label>
                    <textarea name="steps[${index}][tools_required]" class="form-textarea w-full rounded-lg" rows="2">${suggestion.tools_required}</textarea>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Description de l'étape</label>
                <textarea name="steps[${index}][description]" class="form-textarea w-full rounded-lg" rows="3" required>${suggestion.description}</textarea>
            </div>
        `;
        container.appendChild(newStep);
    }, 5000);
}
>>>>>>> tutoral-branch
let stepCount = 1;

function updateImpactScore(value) {
    document.getElementById('impact-value').textContent = value;
}

function previewImage(event) {
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
    newStep.className = 'step-item bg-gray-50 dark:bg-gray-700 rounded-xl p-6 mb-6';
    
    newStep.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white font-bold text-sm step-number">${stepCount + 1}</span>
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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Titre de l'étape <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="steps[${stepCount}][title]" 
                        placeholder="Ex: Préparer les matériaux..."
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent text-base"
                        required
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Durée estimée
                    </label>
                    <input 
                        type="text" 
                        name="steps[${stepCount}][duration]" 
                        placeholder="Ex: 30 minutes, 1 heure..."
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent text-base"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Matériaux nécessaires
                    </label>
                    <textarea 
                        name="steps[${stepCount}][materials_needed]" 
                        rows="3"
                        placeholder="Liste des matériaux pour cette étape..."
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent text-base"
                    ></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Outils requis
                    </label>
                    <textarea 
                        name="steps[${stepCount}][tools_required]" 
                        rows="3"
                        placeholder="Liste des outils pour cette étape..."
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent text-base"
                    ></textarea>
                </div>
            </div>

            

            <div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Description détaillée <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="steps[${stepCount}][description]" 
                        rows="10"
                        placeholder="Expliquez en détail comment réaliser cette étape..."
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent text-base"
                        required
                    ></textarea>
                </div>
            </div>
        </div>
    `;
    
    container.appendChild(newStep);
    stepCount++;
    updateRemoveButtons();
}

function removeStep(button) {
    const stepItem = button.closest('.step-item');
    stepItem.remove();
    updateStepNumbers();
    updateRemoveButtons();
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
            <span class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white font-bold text-sm step-number">${index + 1}</span>
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

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateRemoveButtons();
});
</script>
@endsection