@extends('FrontOffice.layout1.app')

@section('title', 'Modifier un Déchet - Dechet2Product')

@section('content')
<!-- Hero Section -->
<div class="gradient-hero text-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">
                <i class="fas fa-edit mr-3"></i>Modifier le Déchet
            </h1>
            <p class="text-lg opacity-90">
                Mettez à jour les informations de votre déchet
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
        <div class="mb-8 flex items-center justify-between">
            <a 
                href="{{ route('dechets.show', $Dechet->id) }}" 
<<<<<<< HEAD
                class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors"
=======
                class="inline-flex items-center gap-2 text-gray-600 hover:text-primary transition-colors"
>>>>>>> tutoral-branch
            >
                <i class="fas fa-arrow-left"></i>
                Retour au déchet
            </a>
            
            <a 
                href="{{ route('dechets.my') }}" 
<<<<<<< HEAD
                class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors"
=======
                class="inline-flex items-center gap-2 text-gray-600 hover:text-primary transition-colors"
>>>>>>> tutoral-branch
            >
                <i class="fas fa-box-open"></i>
                Mes déchets
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

        <form action="{{ route('dechets.update', $Dechet->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Titre -->
            <div>
<<<<<<< HEAD
                <label for="title" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
=======
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
>>>>>>> tutoral-branch
                    Titre du déchet <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    value="{{ old('title', $Dechet->title) }}"
                    placeholder="Ex: Palette en bois, Cartons propres, Bouteilles plastique..."
<<<<<<< HEAD
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
=======
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
>>>>>>> tutoral-branch
                    required
                >
                @error('title')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Catégorie -->
            <div>
<<<<<<< HEAD
                <label for="category_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
=======
                <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
>>>>>>> tutoral-branch
                    Catégorie <span class="text-red-500">*</span>
                </label>
                <select 
                    id="category_id" 
                    name="category_id" 
<<<<<<< HEAD
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
=======
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
>>>>>>> tutoral-branch
                    required
                >
                    <option value="">Sélectionnez une catégorie</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $Dechet->category_id) == $category->id ? 'selected' : '' }}>
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
<<<<<<< HEAD
                <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
=======
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
                >{{ old('description', $Dechet->description) }}</textarea>
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
                    value="{{ old('quantity', $Dechet->quantity) }}"
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
                        <option value="{{ $city }}" {{ old('location', $Dechet->location) == $city ? 'selected' : '' }}>
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
<<<<<<< HEAD
                <label for="photo" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
=======
                <label for="photo" class="block text-sm font-semibold text-gray-700 mb-2">
>>>>>>> tutoral-branch
                    Photo du déchet
                </label>
                
                @if($Dechet->photo)
                <div class="mb-4">
<<<<<<< HEAD
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Photo actuelle :</p>
=======
                    <p class="text-sm text-gray-600 mb-2">Photo actuelle :</p>
>>>>>>> tutoral-branch
                    <img 
                        id="current-image" 
                        src="{{ asset('uploads/dechets/' . $Dechet->photo) }}" 
                        alt="{{ $Dechet->title }}" 
                        class="max-h-48 rounded-lg shadow-lg"
                    >
                </div>
                @endif
                
<<<<<<< HEAD
                <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-primary transition-colors">
=======
                <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-primary transition-colors">
>>>>>>> tutoral-branch
                    <div class="space-y-2 text-center">
                        <div id="preview-container" class="hidden mb-4">
                            <img id="preview-image" src="" alt="Aperçu" class="mx-auto max-h-48 rounded-lg shadow-lg">
                        </div>
                        
                        <div id="upload-placeholder">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
<<<<<<< HEAD
                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                <label for="photo" class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-primary hover:text-green-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary px-3 py-1">
=======
                            <div class="flex text-sm text-gray-600">
                                <label for="photo" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-green-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary px-3 py-1">
>>>>>>> tutoral-branch
                                    <span>{{ $Dechet->photo ? 'Changer la photo' : 'Télécharger une photo' }}</span>
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
<<<<<<< HEAD
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
=======
                            <p class="text-xs text-gray-500 mt-2">
>>>>>>> tutoral-branch
                                PNG, JPG, GIF jusqu'à 2MB
                            </p>
                        </div>
                    </div>
                </div>
                @error('photo')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
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
                    <i class="fas fa-save text-xl"></i>
                    Enregistrer les modifications
                </button>
                
                <a 
                    href="{{ route('dechets.show', $Dechet->id) }}" 
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
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
            document.getElementById('preview-container').classList.remove('hidden');
            
            // Cacher l'image actuelle
            const currentImage = document.getElementById('current-image');
            if (currentImage) {
                currentImage.style.display = 'none';
            }
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection