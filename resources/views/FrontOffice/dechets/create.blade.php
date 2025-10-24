@extends('FrontOffice.layout1.app')

@section('title', 'Déclarer un Déchet - Dechet2Product')

@section('content')
<!-- Hero Section -->
<div class="gradient-hero text-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">
                <i class="fas fa-plus-circle mr-3"></i>Déclarer un Déchet
            </h1>
            <p class="text-lg opacity-90">
                Partagez vos déchets pour qu'ils puissent être réutilisés et valorisés
            </p>
        </div>
    </div>
</div>

<!-- Formulaire -->
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
<<<<<<< HEAD
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
=======
    <div class="bg-white rounded-2xl shadow-xl p-8">
>>>>>>> tutoral-branch
        
        <!-- Navigation -->
        <div class="mb-8">
            <a 
                href="{{ route('dechets.index') }}" 
<<<<<<< HEAD
                class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors"
=======
                class="inline-flex items-center gap-2 text-gray-600 hover:text-primary transition-colors"
>>>>>>> tutoral-branch
            >
                <i class="fas fa-arrow-left"></i>
                Retour à la liste
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

        <form action="{{ route('dechets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Titre -->
            <div>
<<<<<<< HEAD
                <label for="title" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Titre du déchet <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    value="{{ old('title') }}"
                    placeholder="Ex: Palette en bois, Cartons propres, Bouteilles plastique..."
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
=======
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                    Titre du déchet <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title') }}"
                    placeholder="Ex: Palette en bois, Cartons propres, Bouteilles plastique..."
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
>>>>>>> tutoral-branch
                    required
                >
                @error('title')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

<<<<<<< HEAD
            <!-- Catégorie -->
            <div>
                <label for="category_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Catégorie <span class="text-red-500">*</span>
                </label>
                <select 
                    id="category_id" 
                    name="category_id" 
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                    required
                >
                    <option value="">Sélectionnez une catégorie</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
=======
            <!-- Catégorie - Visual Card Selector -->
            <div>
                <label class="block text-lg font-bold text-gray-900 mb-4">
                    Quelle catégorie de déchet ? <span class="text-red-500">*</span>
                </label>
                <p class="text-sm text-gray-600 mb-6">Sélectionnez la catégorie qui correspond le mieux à votre déchet</p>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    @foreach($categories as $category)
                        <label class="category-card relative cursor-pointer group">
                            <input
                                type="radio"
                                name="category_id"
                                value="{{ $category->id }}"
                                class="hidden category-radio"
                                {{ old('category_id') == $category->id ? 'checked' : '' }}
                                required
                            >
                            <div class="category-card-inner h-full bg-white border-2 border-gray-200 rounded-xl p-4 transition-all duration-300 hover:border-primary hover:shadow-lg hover:-translate-y-1">
                                <!-- Icon/Image -->
                                <div class="flex items-center justify-center mb-3">
                                    @if($category->image)
                                        <img src="{{ asset('storage/categories/' . $category->image) }}" alt="{{ $category->name }}" class="w-16 h-16 object-contain">
                                    @else
                                        <div class="w-16 h-16 rounded-full flex items-center justify-center text-3xl" style="background: {{ $category->color }}20; color: {{ $category->color }}">
                                            <i class="{{ $category->icon }}"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Category Name -->
                                <h4 class="text-center font-bold text-gray-900 text-sm mb-1">{{ $category->name }}</h4>

                                <!-- Description -->
                                @if($category->description)
                                    <p class="text-xs text-gray-500 text-center line-clamp-2">{{ $category->description }}</p>
                                @endif

                                <!-- Selected Indicator -->
                                <div class="category-check absolute top-2 right-2 w-6 h-6 bg-primary rounded-full items-center justify-center text-white text-xs hidden">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

                <input type="hidden" id="category_id_hidden" name="category_id_old" value="{{ old('category_id') }}">

                @error('category_id')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <style>
                .category-card input:checked ~ .category-card-inner {
                    border-color: var(--primary-color, #2E7D47);
                    box-shadow: 0 0 0 3px rgba(46, 125, 71, 0.1);
                    background: rgba(46, 125, 71, 0.02);
                }

                .category-card input:checked ~ .category-card-inner .category-check {
                    display: flex !important;
                }

                .category-card-inner:hover {
                    transform: translateY(-4px);
                }
            </style>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
>>>>>>> tutoral-branch
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea 
                    id="description" 
                    name="description"
                    rows="5"
                    placeholder="Décrivez votre déchet : état, quantité approximative, conditions de récupération..."
<<<<<<< HEAD
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
=======
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
>>>>>>> tutoral-branch
                    required
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Quantité -->
            <div>
<<<<<<< HEAD
                <label for="quantity" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
=======
                <label for="quantity" class="block text-sm font-semibold text-gray-700 mb-2">
>>>>>>> tutoral-branch
                    Quantité <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="quantity" 
                    name="quantity" 
                    value="{{ old('quantity') }}"
                    placeholder="Ex: 5 palettes, 20kg, 50 unités..."
<<<<<<< HEAD
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
=======
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
>>>>>>> tutoral-branch
                    required
                >
                @error('quantity')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Localisation -->
            <div>
<<<<<<< HEAD
                <label for="location" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
=======
                <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">
>>>>>>> tutoral-branch
                    Localisation <span class="text-red-500">*</span>
                </label>
                <select 
                    id="location" 
                    name="location" 
<<<<<<< HEAD
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
=======
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
>>>>>>> tutoral-branch
                    required
                >
                    <option value="">Sélectionnez une ville</option>
                    @foreach($tunisianCities as $city)
                        <option value="{{ $city }}" {{ old('location') == $city ? 'selected' : '' }}>
                            {{ $city }}
                        </option>
                    @endforeach
                </select>
                @error('location')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

<<<<<<< HEAD
            <!-- Photo -->
            <div>
                <label for="photo" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Photo du déchet
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
=======
            <!-- Photo with AI Recognition -->
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 border-2 border-indigo-200 rounded-xl p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-camera text-white text-lg"></i>
                    </div>
                    <div>
                        <label for="photo" class="block text-base font-bold text-gray-900">
                            Photo du déchet <span class="text-gray-400 text-xs font-normal">(optionnel)</span>
                        </label>
                        <p class="text-xs text-gray-600">Ajoutez une photo et l'IA suggèrera la catégorie</p>
                    </div>
                </div>

                <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-indigo-300 rounded-lg hover:border-indigo-500 transition-colors bg-white">
                    <div class="space-y-2 text-center w-full">
                        <div id="preview-container" class="hidden mb-4">
                            <img id="preview-image" src="" alt="Aperçu" class="mx-auto max-h-48 rounded-lg shadow-lg mb-4">
                            <button
                                type="button"
                                onclick="recognizeFromFilename()"
                                id="recognize-btn"
                                class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white px-6 py-3 rounded-lg font-bold hover:shadow-lg hover:from-indigo-600 hover:to-purple-600 transition-all"
                            >
                                <i class="fas fa-magic"></i>
                                Reconnaître la catégorie
                            </button>
                            <div id="recognition-result" class="hidden mt-4 bg-indigo-50 border-2 border-indigo-200 rounded-lg p-4">
                                <p class="text-sm font-semibold text-indigo-700 mb-2">
                                    <i class="fas fa-check-circle"></i> Catégorie suggérée :
                                </p>
                                <p id="recognized-category" class="text-lg font-bold text-indigo-900"></p>
                                <button type="button" onclick="applyRecognition()" class="mt-3 w-full bg-indigo-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors">
                                    <i class="fas fa-check mr-2"></i> Appliquer cette catégorie
                                </button>
                            </div>
                        </div>

                        <div id="upload-placeholder">
                            <i class="fas fa-cloud-upload-alt text-4xl text-indigo-400 mb-3"></i>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="photo" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 px-3 py-1">
                                    <span>Télécharger une photo</span>
                                    <input
                                        id="photo"
                                        name="photo"
                                        type="file"
                                        class="sr-only"
                                        accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                        onchange="previewImageWithRecognition(event)"
>>>>>>> tutoral-branch
                                    >
                                </label>
                                <p class="pl-1">ou glisser-déposer</p>
                            </div>
<<<<<<< HEAD
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                PNG, JPG, GIF jusqu'à 2MB
=======
                            <p class="text-xs text-gray-500 mt-2">
                                PNG, JPG, GIF, WEBP jusqu'à 5MB
>>>>>>> tutoral-branch
                            </p>
                        </div>
                    </div>
                </div>
                @error('photo')
<<<<<<< HEAD
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
=======
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
>>>>>>> tutoral-branch
                @enderror
            </div>

            <!-- Info Box -->
<<<<<<< HEAD
            <div class="bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 border-l-4 border-blue-500 p-4 rounded-lg">
                <div class="flex items-start gap-3">
                    <i class="fas fa-info-circle text-blue-500 text-xl mt-0.5"></i>
                    <div>
                        <h3 class="text-blue-800 dark:text-blue-300 font-semibold mb-1">Conseils</h3>
                        <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
=======
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                <div class="flex items-start gap-3">
                    <i class="fas fa-info-circle text-blue-500 text-xl mt-0.5"></i>
                    <div>
                        <h3 class="text-blue-800 font-semibold mb-1">Conseils</h3>
                        <ul class="text-sm text-blue-700 space-y-1">
>>>>>>> tutoral-branch
                            <li>• Soyez précis dans votre description pour faciliter la récupération</li>
                            <li>• Ajoutez une photo claire pour augmenter vos chances</li>
                            <li>• Indiquez les conditions de récupération (horaires, accès...)</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Boutons -->
<<<<<<< HEAD
            <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t dark:border-gray-700">
=======
            <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t">
>>>>>>> tutoral-branch
                <button 
                    type="submit" 
                    class="flex-1 bg-gradient-to-r from-primary to-green-700 hover:from-green-700 hover:to-primary text-white px-8 py-4 rounded-lg font-semibold text-lg shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-3"
                >
                    <i class="fas fa-check-circle text-xl"></i>
                    Déclarer le déchet
                </button>
                
                <a 
                    href="{{ route('dechets.index') }}" 
<<<<<<< HEAD
                    class="flex-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all flex items-center justify-center gap-3"
=======
                    class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-8 py-4 rounded-lg font-semibold text-lg transition-all flex items-center justify-center gap-3"
>>>>>>> tutoral-branch
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
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
=======
let recognizedCategoryName = null;
let uploadedFile = null;

function previewImageWithRecognition(event) {
    const file = event.target.files[0];
    if (file) {
        uploadedFile = file;
>>>>>>> tutoral-branch
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
            document.getElementById('preview-container').classList.remove('hidden');
            document.getElementById('upload-placeholder').classList.add('hidden');
<<<<<<< HEAD
=======
            // Reset recognition
            document.getElementById('recognition-result').classList.add('hidden');
>>>>>>> tutoral-branch
        }
        reader.readAsDataURL(file);
    }
}
<<<<<<< HEAD
=======

function recognizeFromFilename() {
    if (!uploadedFile) {
        alert('⚠️ Veuillez d\'abord télécharger une photo');
        return;
    }

    const filename = uploadedFile.name.toLowerCase();
    const suggestion = detectCategoryFromFilename(filename);

    const resultDiv = document.getElementById('recognition-result');
    const categoryDisplay = document.getElementById('recognized-category');

    if (suggestion.name === 'Non déterminé') {
        categoryDisplay.textContent = 'Non déterminé';
        recognizedCategoryName = null;
        resultDiv.classList.remove('hidden');

        setTimeout(() => {
            alert('⚠️ Impossible de déterminer la catégorie depuis le nom du fichier.\nVeuillez sélectionner manuellement.');
        }, 100);
    } else {
        categoryDisplay.textContent = `${suggestion.name} (${suggestion.confidence}% confiance)`;
        recognizedCategoryName = suggestion.name;
        resultDiv.classList.remove('hidden');
    }
}

function detectCategoryFromFilename(filename) {
    // Keyword-based detection (works without any API!)
    const keywords = {
        'Plastique': ['plastic', 'plastique', 'bottle', 'bouteille', 'pet', 'hdpe', 'sac'],
        'Papier': ['paper', 'papier', 'carton', 'cardboard', 'box', 'boite'],
        'Métal': ['metal', 'métal', 'can', 'aluminium', 'steel', 'fer', 'alumin'],
        'Verre': ['glass', 'verre', 'jar', 'bocal'],
        'Bois': ['wood', 'bois', 'pallet', 'palette'],
        'Électronique': ['electronic', 'électronique', 'phone', 'computer', 'ordinateur', 'ecran', 'cable'],
        'Textile': ['textile', 'fabric', 'tissu', 'cloth', 'vetement', 'tshirt']
    };

    for (const [category, words] of Object.entries(keywords)) {
        if (words.some(word => filename.includes(word))) {
            return { name: category, confidence: 85 };
        }
    }

    return { name: 'Non déterminé', confidence: 0 };
}

function applyRecognition() {
    if (!recognizedCategoryName) return;

    // Find matching category radio button by name
    const categoryCards = document.querySelectorAll('.category-card');
    let matched = false;

    categoryCards.forEach(card => {
        const categoryH4 = card.querySelector('h4');
        if (categoryH4) {
            const categoryName = categoryH4.textContent.trim();
            if (categoryName.toLowerCase().includes(recognizedCategoryName.toLowerCase()) ||
                recognizedCategoryName.toLowerCase().includes(categoryName.toLowerCase())) {
                // Click the radio button
                const radioInput = card.querySelector('input[type="radio"]');
                if (radioInput) {
                    radioInput.checked = true;
                    radioInput.dispatchEvent(new Event('change'));
                    matched = true;

                    // Scroll to category section
                    card.scrollIntoView({ behavior: 'smooth', block: 'center' });

                    // Flash effect
                    card.classList.add('ring-4', 'ring-indigo-400');
                    setTimeout(() => {
                        card.classList.remove('ring-4', 'ring-indigo-400');
                    }, 2000);
                }
            }
        }
    });

    if (matched) {
        alert('✅ Catégorie appliquée avec succès !');
    } else {
        alert('⚠️ Catégorie non trouvée. Veuillez sélectionner manuellement.');
    }
}
>>>>>>> tutoral-branch
</script>
@endsection