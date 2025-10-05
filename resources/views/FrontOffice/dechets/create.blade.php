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
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
        
        <!-- Navigation -->
        <div class="mb-8">
            <a 
                href="{{ route('dechets.index') }}" 
                class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors"
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
                    required
                >
                @error('title')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

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
                <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea 
                    id="description" 
                    name="description"
                    rows="5"
                    placeholder="Décrivez votre déchet : état, quantité approximative, conditions de récupération..."
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                    required
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Quantité -->
            <div>
                <label for="quantity" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Quantité <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="quantity" 
                    name="quantity" 
                    value="{{ old('quantity') }}"
                    placeholder="Ex: 5 palettes, 20kg, 50 unités..."
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                    required
                >
                @error('quantity')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Localisation -->
            <div>
                <label for="location" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Localisation <span class="text-red-500">*</span>
                </label>
                <select 
                    id="location" 
                    name="location" 
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
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

            <!-- Photo -->
            <div>
                <label for="photo" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Photo du déchet <span class="text-gray-400 text-xs">(optionnel)</span>
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
                                        accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                        onchange="previewImage(event)"
                                    >
                                </label>
                                <p class="pl-1">ou glisser-déposer</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                PNG, JPG, GIF, WEBP jusqu'à 5MB
                            </p>
                        </div>
                    </div>
                </div>
                @error('photo')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 border-l-4 border-blue-500 p-4 rounded-lg">
                <div class="flex items-start gap-3">
                    <i class="fas fa-info-circle text-blue-500 text-xl mt-0.5"></i>
                    <div>
                        <h3 class="text-blue-800 dark:text-blue-300 font-semibold mb-1">Conseils</h3>
                        <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                            <li>• Soyez précis dans votre description pour faciliter la récupération</li>
                            <li>• Ajoutez une photo claire pour augmenter vos chances</li>
                            <li>• Indiquez les conditions de récupération (horaires, accès...)</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Boutons -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t dark:border-gray-700">
                <button 
                    type="submit" 
                    class="flex-1 bg-gradient-to-r from-primary to-green-700 hover:from-green-700 hover:to-primary text-white px-8 py-4 rounded-lg font-semibold text-lg shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-3"
                >
                    <i class="fas fa-check-circle text-xl"></i>
                    Déclarer le déchet
                </button>
                
                <a 
                    href="{{ route('dechets.index') }}" 
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
</script>
@endsection