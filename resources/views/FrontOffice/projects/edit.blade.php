@extends('FrontOffice.layout1.app')

@section('title', 'Modifier le Projet - Waste2Product')

@section('content')
<!-- Hero Section -->
<div class="gradient-hero text-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">
                <i class="fas fa-edit mr-3"></i>Modifier le Projet
            </h1>
            <p class="text-lg opacity-90">
                Mettez à jour votre projet et améliorez son contenu
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
                href="{{ route('projects.show', $project->id) }}" 
                class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors"
            >
                <i class="fas fa-arrow-left"></i>
                Retour au projet
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

        <form action="{{ route('projects.update', $project->id) }}" method="POST" enctype="multipart/form-data" id="project-form">
            @csrf
            @method('PUT')

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
                                value="{{ old('title', $project->title) }}"
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
                                    <option value="{{ $category->id }}" {{ old('category_id', $project->category_id) == $category->id ? 'selected' : '' }}>
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
                                <option value="facile" {{ old('difficulty_level', $project->difficulty_level) == 'facile' ? 'selected' : '' }}>
                                    🟢 Facile - Débutant
                                </option>
                                <option value="intermédiaire" {{ old('difficulty_level', $project->difficulty_level) == 'intermédiaire' ? 'selected' : '' }}>
                                    🟡 Intermédiaire - Niveau moyen
                                </option>
                                <option value="difficile" {{ old('difficulty_level', $project->difficulty_level) == 'difficile' ? 'selected' : '' }}>
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
                                value="{{ old('estimated_time', $project->estimated_time) }}"
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
                                    value="{{ old('impact_score', $project->impact_score ?? 5) }}"
                                    class="flex-1"
                                    oninput="updateImpactScore(this.value)"
                                >
                                <span id="impact-value" class="w-12 text-center font-bold text-primary text-lg">{{ old('impact_score', $project->impact_score ?? 5) }}</span>
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
                            
                            <!-- Current Photo Preview -->
                            @if($project->photo)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Photo actuelle :</p>
                                <img 
                                    src="{{ asset('uploads/projects/' . $project->photo) }}" 
                                    alt="{{ $project->title }}" 
                                    class="max-h-48 rounded-lg shadow-lg"
                                    id="current-image"
                                >
                            </div>
                            @endif

                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-primary transition-colors">
                                <div class="space-y-2 text-center">
                                    <div id="preview-container" class="hidden mb-4">
                                        <img id="preview-image" src="" alt="Aperçu" class="mx-auto max-h-48 rounded-lg shadow-lg">
                                    </div>
                                    
                                    <div id="upload-placeholder">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                        <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                            <label for="photo" class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-primary hover:text-green-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary px-3 py-1">
                                                <span>{{ $project->photo ? 'Changer la photo' : 'Télécharger une photo' }}</span>
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
                            >{{ old('description', $project->description) }}</textarea>
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
                    <button 
                        type="button" 
                        onclick="addStep()" 
                        class="inline-flex items-center gap-2 bg-secondary hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                    >
                        <i class="fas fa-plus"></i>
                        Ajouter une étape
                    </button>
<<<<<<< HEAD
=======
                    <button 
                        type="button" 
                        onclick="suggestStep()" 
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700 transition-colors ml-2"
                    >
                        <i class="fas fa-magic"></i> Suggérer une étape
                    </button>
                    <button 
                        type="button" 
                        onclick="suggestAllSteps()" 
                        class="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-800 transition-colors ml-2"
                    >
                        <i class="fas fa-magic"></i> Suggérer toutes les étapes
                    </button>
>>>>>>> tutoral-branch
                </div>

                <div id="steps-container">
                    @forelse($project->steps as $index => $step)
                    <!-- Existing step -->
                    <div class="step-item bg-gray-50 dark:bg-gray-700 rounded-xl p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <span class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white font-bold text-sm step-number">{{ $index + 1 }}</span>
                                Étape {{ $index + 1 }}
                            </h3>
                            <button 
                                type="button" 
                                onclick="removeStep(this)" 
                                class="text-red-500 hover:text-red-700 remove-step {{ $project->steps->count() <= 1 ? 'hidden' : '' }}"
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
                                        name="steps[{{ $index }}][title]" 
                                        value="{{ old('steps.' . $index . '.title', $step->title) }}"
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
                                        name="steps[{{ $index }}][duration]" 
                                        value="{{ old('steps.' . $index . '.duration', $step->duration) }}"
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
                                        name="steps[{{ $index }}][materials_needed]" 
                                        rows="3"
                                        placeholder="Liste des matériaux pour cette étape..."
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent text-base"
                                    >{{ old('steps.' . $index . '.materials_needed', $step->materials_needed) }}</textarea>
                                </div>

                                <!-- Tools -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Outils requis
                                    </label>
                                    <textarea 
                                        name="steps[{{ $index }}][tools_required]" 
                                        rows="3"
                                        placeholder="Liste des outils pour cette étape..."
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent text-base"
                                    >{{ old('steps.' . $index . '.tools_required', $step->tools_required) }}</textarea>
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
                                        name="steps[{{ $index }}][description]" 
                                        rows="10"
                                        placeholder="Expliquez en détail comment réaliser cette étape..."
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent text-base"
                                        required
                                    >{{ old('steps.' . $index . '.description', $step->description) }}</textarea>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Minimum 20 caractères
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <!-- Default empty step if no steps exist -->
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
                            <div class="space-y-4">
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

                            <div>
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
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforelse
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
                    <i class="fas fa-save text-xl"></i>
                    Enregistrer les modifications
                </button>
                
                <a 
                    href="{{ route('projects.show', $project->id) }}" 
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
let stepCount = {{ $project->steps->count() > 0 ? $project->steps->count() : 1 }};

=======
// Suggérer toute la séquence d'étapes pour la catégorie sélectionnée
function suggestAllSteps() {
    const container = document.getElementById('steps-container');
    const loaderId = 'suggestion-all-loader';
    let loader = document.getElementById(loaderId);
    if (!loader) {
        loader = document.createElement('div');
        loader.id = loaderId;
        loader.className = 'flex items-center justify-center my-4';
        loader.innerHTML = '<span class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-green-600 mr-2"></span><span class="text-green-600 font-semibold">Génération de la suggestion complète...</span>';
        container.parentNode.insertBefore(loader, container);
    }
    setTimeout(function() {
        if (loader) loader.remove();
        const categorySelect = document.getElementById('category_id');
        let categoryName = '';
        if (categorySelect && categorySelect.selectedIndex > 0) {
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            categoryName = selectedOption.text.trim();
        }
        let sequence = categoryStepSequences[categoryName];
        if (!sequence || !Array.isArray(sequence) || sequence.length === 0) {
            alert('Aucune séquence d’étapes disponible pour cette catégorie.');
            return;
        }
        // Récupérer les titres des étapes déjà présentes
        const currentSteps = Array.from(container.children).map(stepDiv => {
            const input = stepDiv.querySelector('input[name^="steps"][name$="[title]"]');
            return input ? input.value.trim() : null;
        });
        let added = 0;
        sequence.forEach(function(suggestion) {
            if (!currentSteps.includes(suggestion.title)) {
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
                        <div class="text-xs text-green-600 dark:text-green-300 mt-2">Suggestion complète générée automatiquement selon la catégorie sélectionnée.</div>
                    </div>
                `;
                container.appendChild(newStep);
                added++;
            }
        });
        updateRemoveButtons();
        if (added === 0) {
            alert('Toutes les étapes de la séquence sont déjà présentes dans le projet.');
        }
    }, 15000);
}
let stepCount = {{ $project->steps->count() > 0 ? $project->steps->count() : 1 }};

// Suggestions d'étapes par catégorie
const categoryStepSuggestions = {
    'Bois & Palettes': [
        { title: "Fabriquer une étagère à partir de palettes", duration: "40 minutes", materials_needed: "Palette en bois, vis, peinture", tools_required: "Scie, tournevis, pinceau", description: "Démontez la palette, découpez les planches, assemblez-les pour former une étagère et peignez selon vos goûts." },
        { title: "Créer un banc de jardin avec des chutes de bois", duration: "60 minutes", materials_needed: "Planches de bois, clous, vernis", tools_required: "Marteau, scie, pinceau", description: "Assemblez les planches pour former le banc, fixez-les avec des clous et appliquez du vernis pour la finition." },
        { title: "Transformer une caisse en bois en potager urbain", duration: "30 minutes", materials_needed: "Caisse en bois, terre, graines", tools_required: "Pelle, arrosoir", description: "Remplissez la caisse de terre, plantez les graines et arrosez régulièrement." },
        { title: "Réaliser un porte-manteau mural avec des palettes", duration: "25 minutes", materials_needed: "Palette, crochets, peinture", tools_required: "Tournevis, pinceau", description: "Découpez et peignez la palette, fixez les crochets et installez le porte-manteau au mur." },
        { title: "Construire une table basse à partir de palettes recyclées", duration: "50 minutes", materials_needed: "Palette, roulettes, vernis", tools_required: "Scie, tournevis, pinceau", description: "Assemblez les palettes, fixez les roulettes et appliquez du vernis pour obtenir une table basse mobile." }
    ],
    'Plastique': [
        { title: "Fabriquer un pot de fleurs avec une bouteille plastique", duration: "15 minutes", materials_needed: "Bouteille plastique, peinture, terre, plante", tools_required: "Ciseaux, pinceau", description: "Découpez la bouteille, peignez-la, remplissez de terre et plantez une fleur." },
        { title: "Créer un arrosoir à partir d’un bidon plastique", duration: "10 minutes", materials_needed: "Bidon plastique, clou, eau", tools_required: "Clou, marteau", description: "Percez des trous dans le bouchon du bidon pour obtenir un arrosoir simple." },
        { title: "Transformer des bouchons en dessous de verre", duration: "20 minutes", materials_needed: "Bouchons plastique, colle forte", tools_required: "Colle", description: "Collez les bouchons ensemble pour former un dessous de verre original." },
        { title: "Réaliser une mangeoire à oiseaux avec une bouteille", duration: "25 minutes", materials_needed: "Bouteille plastique, ficelle, graines", tools_required: "Ciseaux", description: "Découpez la bouteille, fixez la ficelle et remplissez de graines pour les oiseaux." },
        { title: "Créer un porte-stylo avec un flacon recyclé", duration: "10 minutes", materials_needed: "Flacon plastique, stickers, peinture", tools_required: "Ciseaux, pinceau", description: "Découpez le flacon, décorez-le et utilisez-le comme porte-stylo." }
    ],
    'Métal': [
        { title: "Transformer une boîte de conserve en pot à crayons", duration: "20 minutes", materials_needed: "Boîte de conserve vide, peinture acrylique, papier de verre", tools_required: "Pinceau, ciseaux, colle forte", description: "Nettoyez la boîte de conserve, poncez les bords pour éviter les coupures, puis décorez-la avec de la peinture. Ajoutez des éléments décoratifs si souhaité." },
        { title: "Créer une lampe à partir d’un vieux tuyau métallique", duration: "40 minutes", materials_needed: "Tuyau métallique, douille, ampoule LED, câble électrique", tools_required: "Scie à métaux, tournevis, pince coupante", description: "Découpez le tuyau à la bonne longueur, installez la douille et le câble, puis fixez l’ampoule. Testez la lampe pour vérifier le fonctionnement." },
        { title: "Fabriquer un porte-serviettes avec des chutes de métal", duration: "30 minutes", materials_needed: "Barres de métal, vis, chevilles", tools_required: "Perceuse, tournevis, mètre", description: "Assemblez les barres de métal pour former le porte-serviettes, percez les trous pour la fixation et installez-le au mur." },
        { title: "Réaliser un support de téléphone avec une plaque métallique", duration: "25 minutes", materials_needed: "Plaque métallique, feutrine, colle", tools_required: "Ciseaux, lime, colle forte", description: "Découpez et limez la plaque pour obtenir la forme souhaitée, collez la feutrine pour protéger le téléphone, puis assemblez le support." },
        { title: "Transformer un vieux cadenas en porte-clés original", duration: "15 minutes", materials_needed: "Cadenas usagé, anneau porte-clés, peinture", tools_required: "Tournevis, pince, pinceau", description: "Nettoyez le cadenas, décorez-le avec de la peinture, puis fixez l’anneau pour obtenir un porte-clés unique." }
    ],
    'Textile': [
        { title: "Créer un tote bag à partir d’un vieux t-shirt", duration: "30 minutes", materials_needed: "T-shirt, fil, aiguille", tools_required: "Ciseaux, aiguille", description: "Découpez le t-shirt, cousez le fond et les anses pour obtenir un sac réutilisable." },
        { title: "Fabriquer une pochette avec des chutes de tissu", duration: "20 minutes", materials_needed: "Chutes de tissu, fermeture éclair, fil", tools_required: "Machine à coudre, ciseaux", description: "Assemblez les chutes de tissu, cousez la fermeture et réalisez une pochette pratique." },
        { title: "Transformer un jean en coussin décoratif", duration: "25 minutes", materials_needed: "Jean usagé, rembourrage, fil", tools_required: "Ciseaux, machine à coudre", description: "Découpez le jean, assemblez les morceaux et remplissez pour obtenir un coussin." },
        { title: "Réaliser un bandeau avec un vieux foulard", duration: "10 minutes", materials_needed: "Foulard, fil", tools_required: "Aiguille, ciseaux", description: "Découpez et cousez le foulard pour obtenir un bandeau tendance." },
        { title: "Créer un tapis avec des chutes de tissu", duration: "40 minutes", materials_needed: "Chutes de tissu, fil", tools_required: "Aiguille, ciseaux", description: "Assemblez et cousez les chutes pour former un tapis coloré." }
    ],
    'Jardin': [
        { title: "Fabriquer un composteur avec une caisse en bois", duration: "30 minutes", materials_needed: "Caisse en bois, grillage, vis", tools_required: "Tournevis, pince, scie", description: "Assemblez la caisse, fixez le grillage et installez le composteur dans le jardin." },
        { title: "Créer un arrosage goutte-à-goutte avec des bouteilles plastique", duration: "15 minutes", materials_needed: "Bouteilles plastique, clou, ficelle", tools_required: "Clou, marteau, ciseaux", description: "Percez les bouteilles, installez-les près des plantes et reliez avec la ficelle pour un arrosage régulier." },
        { title: "Transformer un pneu en bac à fleurs", duration: "25 minutes", materials_needed: "Pneu usagé, terre, plantes", tools_required: "Cutter, pelle", description: "Nettoyez le pneu, remplissez-le de terre et plantez des fleurs pour décorer le jardin." },
        { title: "Réaliser une bordure de jardin avec des briques recyclées", duration: "20 minutes", materials_needed: "Briques, corde, sable", tools_required: "Pelle, corde", description: "Disposez les briques, fixez-les avec la corde et le sable pour délimiter les espaces." },
        { title: "Créer un hôtel à insectes avec des matériaux recyclés", duration: "35 minutes", materials_needed: "Bois, paille, briques, ficelle", tools_required: "Scie, marteau, colle", description: "Assemblez les matériaux pour former des abris à insectes et installez-les dans le jardin." }
    ]
    // Ajoutez d'autres catégories si besoin
};

const categoryStepSequences = {
    'Mobilier': [
        { title: "Préparer l'espace de travail", duration: "10 minutes", materials_needed: "Table, nappe, gants", tools_required: "Gants, chiffon", description: "Installez votre espace pour travailler le mobilier en toute sécurité." },
        { title: "Nettoyer et préparer le mobilier", duration: "15 minutes", materials_needed: "Mobilier, produit nettoyant", tools_required: "Chiffon, brosse", description: "Nettoyez le mobilier pour enlever la poussière et les résidus." },
        { title: "Réparer ou renforcer le mobilier", duration: "20 minutes", materials_needed: "Vis, colle, pièces de rechange", tools_required: "Tournevis, marteau", description: "Réparez ou renforcez les parties abîmées du mobilier." },
        { title: "Personnaliser et décorer", duration: "15 minutes", materials_needed: "Peinture, stickers, vernis", tools_required: "Pinceau, ruban adhésif", description: "Personnalisez le mobilier selon vos envies." },
        { title: "Installer le mobilier", duration: "10 minutes", materials_needed: "Mobilier prêt", tools_required: "Gants", description: "Installez le mobilier à l'endroit souhaité." }
    ],
    'Pneus': [
        { title: "Préparer le pneu", duration: "10 minutes", materials_needed: "Pneu, eau, savon", tools_required: "Brosse, chiffon", description: "Nettoyez le pneu pour enlever les saletés." },
        { title: "Découper ou façonner le pneu", duration: "15 minutes", materials_needed: "Pneu propre", tools_required: "Cutter, ciseaux", description: "Découpez ou façonnez le pneu selon le projet." },
        { title: "Assembler les éléments", duration: "20 minutes", materials_needed: "Pneu, colle forte, accessoires", tools_required: "Colle, tournevis", description: "Assemblez les éléments pour former l'objet final." },
        { title: "Décorer le projet", duration: "10 minutes", materials_needed: "Peinture, stickers", tools_required: "Pinceau", description: "Personnalisez le pneu avec des décorations." },
        { title: "Installer le projet fini", duration: "10 minutes", materials_needed: "Projet fini", tools_required: "Gants", description: "Installez le projet à l'endroit souhaité." }
    ],
    'Papier Carton': [
        { title: "Préparer le papier/carton", duration: "10 minutes", materials_needed: "Papier, carton, ciseaux", tools_required: "Ciseaux, règle", description: "Découpez le papier ou carton selon le projet." },
        { title: "Assembler les pièces", duration: "15 minutes", materials_needed: "Papier, carton, colle", tools_required: "Colle, pinceau", description: "Assemblez les pièces pour former la structure." },
        { title: "Renforcer la structure", duration: "10 minutes", materials_needed: "Ruban adhésif, carton épais", tools_required: "Ruban adhésif", description: "Renforcez la structure pour plus de solidité." },
        { title: "Décorer le projet", duration: "15 minutes", materials_needed: "Peinture, stickers", tools_required: "Pinceau, feutres", description: "Décorez le projet selon vos envies." },
        { title: "Utiliser ou exposer le projet", duration: "10 minutes", materials_needed: "Projet fini", tools_required: "Gants", description: "Utilisez ou exposez le projet réalisé." }
    ],
    'Verre': [
        { title: "Préparer le verre", duration: "10 minutes", materials_needed: "Bouteilles, bocaux, eau", tools_required: "Chiffon, brosse", description: "Nettoyez le verre pour enlever les impuretés." },
        { title: "Découper ou façonner le verre", duration: "15 minutes", materials_needed: "Verre propre", tools_required: "Cutter spécial verre, gants", description: "Découpez ou façonnez le verre selon le projet." },
        { title: "Assembler les éléments", duration: "20 minutes", materials_needed: "Verre, colle spéciale", tools_required: "Colle, pinceau", description: "Assemblez les éléments pour former l'objet final." },
        { title: "Décorer le projet", duration: "10 minutes", materials_needed: "Peinture spéciale verre, stickers", tools_required: "Pinceau", description: "Décorez le projet en verre selon vos envies." },
        { title: "Installer ou utiliser le projet", duration: "10 minutes", materials_needed: "Projet fini", tools_required: "Gants", description: "Installez ou utilisez le projet en verre." }
    ],
    'Electronique': [
        { title: "Préparer les composants électroniques", duration: "10 minutes", materials_needed: "Composants, carte, fer à souder", tools_required: "Fer à souder, pince", description: "Préparez tous les composants nécessaires au projet." },
        { title: "Assembler les composants", duration: "20 minutes", materials_needed: "Composants, fils, carte", tools_required: "Fer à souder, tournevis", description: "Soudez et assemblez les composants sur la carte." },
        { title: "Tester le montage", duration: "15 minutes", materials_needed: "Montage fini, multimètre", tools_required: "Multimètre", description: "Testez le montage pour vérifier le bon fonctionnement." },
        { title: "Protéger et finaliser le projet", duration: "10 minutes", materials_needed: "Boîtier, vis", tools_required: "Tournevis, colle", description: "Protégez le montage dans un boîtier adapté." },
        { title: "Installer ou utiliser le projet électronique", duration: "10 minutes", materials_needed: "Projet fini", tools_required: "Gants", description: "Installez ou utilisez le projet électronique réalisé." }
    ],
    'Plastique': [
        {
            title: "Définir l'environnement de travail",
            duration: "15 minutes",
            materials_needed: "Table, nappe de protection, gants",
            tools_required: "Gants, chiffon",
            description: "Préparez votre espace de travail pour manipuler le plastique en toute sécurité."
        },
        {
            title: "Nettoyer et préparer le plastique",
            duration: "10 minutes",
            materials_needed: "Bouteilles ou bidons plastique, eau, savon",
            tools_required: "Bassine, éponge",
            description: "Lavez soigneusement le plastique pour enlever les impuretés et résidus."
        },
        {
            title: "Découper et façonner le plastique",
            duration: "20 minutes",
            materials_needed: "Plastique propre",
            tools_required: "Ciseaux, cutter",
            description: "Découpez le plastique selon la forme souhaitée pour votre projet."
        },
        {
            title: "Assembler les éléments",
            duration: "15 minutes",
            materials_needed: "Morceaux de plastique, colle forte",
            tools_required: "Colle, ruban adhésif",
            description: "Collez ou assemblez les morceaux pour former l'objet final."
        },
        {
            title: "Décorer et finaliser le projet",
            duration: "10 minutes",
            materials_needed: "Peinture, stickers, vernis",
            tools_required: "Pinceau, feutres",
            description: "Personnalisez et protégez votre création avec des décorations et du vernis."
        }
    ],
    'Métal': [
        {
            title: "Définir l'environnement de travail",
            duration: "15 minutes",
            materials_needed: "Table solide, gants, lunettes de protection",
            tools_required: "Gants, lunettes",
            description: "Préparez votre espace pour travailler le métal en toute sécurité."
        },
        {
            title: "Nettoyer et préparer le métal",
            duration: "10 minutes",
            materials_needed: "Pièces métalliques, dégraissant",
            tools_required: "Chiffon, brosse métallique",
            description: "Nettoyez et dégraissez les pièces métalliques avant transformation."
        },
        {
            title: "Découper ou façonner le métal",
            duration: "20 minutes",
            materials_needed: "Pièces métalliques",
            tools_required: "Scie à métaux, lime",
            description: "Découpez ou limez le métal selon la forme souhaitée."
        },
        {
            title: "Assembler ou souder les éléments",
            duration: "25 minutes",
            materials_needed: "Pièces métalliques, baguettes de soudure",
            tools_required: "Poste à souder, tournevis",
            description: "Assemblez ou soudez les pièces pour former l'objet final."
        },
        {
            title: "Finitions et décoration",
            duration: "10 minutes",
            materials_needed: "Peinture spéciale métal, vernis",
            tools_required: "Pinceau, chiffon",
            description: "Appliquez une finition et décorez votre création métallique."
        }
    ],
    'Bois & Palettes': [
        {
            title: "Définir l'environnement de travail",
            duration: "10 minutes",
            materials_needed: "Espace dégagé, bâche, gants",
            tools_required: "Gants, balayette",
            description: "Préparez votre espace pour travailler le bois en toute sécurité."
        },
        {
            title: "Nettoyer et préparer le bois",
            duration: "10 minutes",
            materials_needed: "Planches, palette, papier de verre",
            tools_required: "Ponceuse, chiffon",
            description: "Nettoyez et poncez le bois pour enlever les aspérités."
        },
        {
            title: "Découper et assembler le bois",
            duration: "20 minutes",
            materials_needed: "Planches, vis, équerres",
            tools_required: "Scie, tournevis",
            description: "Découpez et assemblez les planches pour former la structure de l'objet."
        },
        {
            title: "Fixer et stabiliser la création",
            duration: "15 minutes",
            materials_needed: "Vis, colle à bois",
            tools_required: "Tournevis, pince",
            description: "Fixez et stabilisez les éléments pour garantir la solidité."
        },
        {
            title: "Finitions et décoration",
            duration: "10 minutes",
            materials_needed: "Vernis, peinture, pinceau",
            tools_required: "Pinceau, chiffon",
            description: "Appliquez une finition et décorez votre création en bois."
        }
    ],
    'Textile': [
        {
            title: "Définir l'environnement de travail",
            duration: "10 minutes",
            materials_needed: "Table, nappe, épingles",
            tools_required: "Épingles, ciseaux",
            description: "Préparez votre espace pour travailler le textile proprement."
        },
        {
            title: "Découper le tissu",
            duration: "15 minutes",
            materials_needed: "Tissu, craie",
            tools_required: "Ciseaux, règle",
            description: "Découpez le tissu selon le patron ou la forme souhaitée."
        },
        {
            title: "Assembler et coudre les pièces",
            duration: "20 minutes",
            materials_needed: "Tissu, fil, aiguille",
            tools_required: "Machine à coudre, épingles",
            description: "Assemblez et cousez les pièces pour former l'objet textile."
        },
        {
            title: "Faire les finitions",
            duration: "10 minutes",
            materials_needed: "Fil, aiguille",
            tools_required: "Aiguille, fer à repasser",
            description: "Réalisez les ourlets et finitions pour une meilleure présentation."
        },
        {
            title: "Décorer le projet textile",
            duration: "10 minutes",
            materials_needed: "Boutons, rubans, peinture textile",
            tools_required: "Pinceau, colle textile",
            description: "Ajoutez des décorations pour personnaliser votre création."
        }
    ],
    'Jardin': [
        {
            title: "Définir l'emplacement et préparer le sol",
            duration: "15 minutes",
            materials_needed: "Bêche, terre, compost",
            tools_required: "Bêche, râteau",
            description: "Choisissez l'emplacement et préparez le sol pour la plantation ou l'installation."
        },
        {
            title: "Installer les éléments recyclés",
            duration: "20 minutes",
            materials_needed: "Bouteilles plastique, pneus, briques",
            tools_required: "Cutter, pelle, corde",
            description: "Installez les éléments recyclés pour créer des bacs, bordures ou arrosage." 
        },
        {
            title: "Planter ou semer",
            duration: "15 minutes",
            materials_needed: "Graines, plantes, eau",
            tools_required: "Plantoir, arrosoir",
            description: "Plantez ou semez dans les bacs ou le sol préparé."
        },
        {
            title: "Arroser et entretenir",
            duration: "10 minutes",
            materials_needed: "Eau, engrais",
            tools_required: "Arrosoir, gants",
            description: "Arrosez et entretenez régulièrement pour favoriser la croissance."
        },
        {
            title: "Décorer et valoriser l'espace",
            duration: "10 minutes",
            materials_needed: "Peinture, galets, objets recyclés",
            tools_required: "Pinceau, gants",
            description: "Décorez et valorisez l'espace avec des objets ou matériaux recyclés."
        }
    ]
    // Ajoutez d'autres catégories si besoin
};

function suggestStep() {
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

    setTimeout(function() {
        if (loader) loader.remove();
        const categorySelect = document.getElementById('category_id');
        let categoryName = '';
        if (categorySelect && categorySelect.selectedIndex > 0) {
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            categoryName = selectedOption.text.trim();
        }
        let sequence = categoryStepSequences[categoryName];
        if (!sequence || !Array.isArray(sequence) || sequence.length === 0) {
            sequence = [
                { title: "Préparer l'environnement de travail", duration: "10 min", materials_needed: "Matériaux nécessaires", tools_required: "Outils requis", description: "Préparez votre espace pour commencer le projet." }
            ];
        }
        // Récupérer les titres des étapes déjà présentes
        const currentSteps = Array.from(container.children).map(stepDiv => {
            const input = stepDiv.querySelector('input[name^="steps"][name$="[title]"]');
            return input ? input.value.trim() : null;
        });
        // Trouver la première suggestion non utilisée
        let suggestion = null;
        for (let i = 0; i < sequence.length; i++) {
            if (!currentSteps.includes(sequence[i].title)) {
                suggestion = sequence[i];
                break;
            }
        }
        if (!suggestion) {
            // Toutes les suggestions ont été utilisées
            alert('Toutes les suggestions pour cette catégorie ont déjà été ajoutées.');
            return;
        }
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
                <div class="text-xs text-blue-600 dark:text-blue-300 mt-2">Suggestion générée automatiquement selon la catégorie sélectionnée.</div>
            </div>
        `;
        container.appendChild(newStep);
        updateRemoveButtons();
    }, 1500);
}

>>>>>>> tutoral-branch
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
            
            // Hide current image if exists
            const currentImage = document.getElementById('current-image');
            if (currentImage) {
                currentImage.style.display = 'none';
            }
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