@extends('BackOffice.layouts.app')

@section('title', 'Modifier la Catégorie')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('admin.categories.index') }}" class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left text-gray-600"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Modifier la Catégorie</h1>
        </div>
        <p class="text-gray-600 ml-13">Modifiez les informations de la catégorie "{{ $category->name }}"</p>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle text-red-500 mr-3 mt-0.5"></i>
                <div>
                    <p class="font-semibold text-red-800 mb-2">Erreurs de validation:</p>
                    <ul class="list-disc list-inside text-red-700 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Main Info Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-info-circle text-blue-500"></i>
                Informations Principales
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nom de la Catégorie <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required maxlength="100" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Ex: Plastique, Bois, Métal...">
                </div>

                <!-- Icon -->
                <div>
                    <label for="icon" class="block text-sm font-semibold text-gray-700 mb-2">
                        Icône FontAwesome <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" id="icon" name="icon" value="{{ old('icon', $category->icon) }}" required maxlength="50" class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="fas fa-recycle">
                        <div id="icon-preview" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl">
                            <i class="{{ $category->icon }}"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Utilisez des classes FontAwesome (ex: fas fa-recycle)</p>
                </div>

                <!-- Color -->
                <div>
                    <label for="color" class="block text-sm font-semibold text-gray-700 mb-2">
                        Couleur <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-3">
                        <input type="color" id="color" name="color" value="{{ old('color', $category->color) }}" required class="w-16 h-12 rounded-lg border border-gray-300 cursor-pointer">
                        <input type="text" id="color-text" value="{{ old('color', $category->color) }}" maxlength="20" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="#059669">
                    </div>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description" name="description" rows="3" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Décrivez cette catégorie...">{{ old('description', $category->description) }}</textarea>
                </div>

                <!-- Tips -->
                <div class="md:col-span-2">
                    <label for="tips" class="block text-sm font-semibold text-gray-700 mb-2">
                        Conseils de Recyclage
                    </label>
                    <textarea id="tips" name="tips" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Conseils pour recycler cette catégorie...">{{ old('tips', $category->tips) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Image Upload Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-image text-purple-500"></i>
                Image de la Catégorie
            </h2>

            <div>
                <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
                    Image (optionnel)
                </label>

                @if($category->image)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Image actuelle:</p>
                        <div class="relative inline-block">
                            <img src="{{ asset('storage/categories/' . $category->image) }}" alt="{{ $category->name }}" class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200">
                            <label class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-50 rounded-lg cursor-pointer transition-all flex items-center justify-center group">
                                <span class="text-white opacity-0 group-hover:opacity-100 font-semibold">Changer</span>
                            </label>
                        </div>
                    </div>
                @endif

                <div class="flex items-start gap-4">
                    <div class="flex-1">
                        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/svg+xml,image/webp" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                        <p class="text-xs text-gray-500 mt-1">Formats acceptés: JPEG, PNG, JPG, SVG, WEBP (max 2MB)</p>
                    </div>
                    <div id="image-preview-container" class="hidden">
                        <img id="image-preview" src="" alt="Preview" class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200">
                    </div>
                </div>
            </div>
        </div>

        <!-- Certifications Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-certificate text-yellow-500"></i>
                Certifications
            </h2>

            <div id="certifications-container">
                <div class="space-y-3" id="certifications-list">
                    @if(old('certifications', $category->certifications))
                        @foreach(old('certifications', $category->certifications) as $index => $cert)
                            <div class="certification-item flex gap-2">
                                <input type="text" name="certifications[]" value="{{ $cert }}" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Ex: ISO 14001, FSC, PEFC...">
                                <button type="button" onclick="removeCertification(this)" class="px-4 py-3 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        @endforeach
                    @else
                        <div class="certification-item flex gap-2">
                            <input type="text" name="certifications[]" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Ex: ISO 14001, FSC, PEFC...">
                            <button type="button" onclick="removeCertification(this)" class="px-4 py-3 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    @endif
                </div>

                <button type="button" onclick="addCertification()" class="mt-3 inline-flex items-center gap-2 px-4 py-2 bg-yellow-50 text-yellow-700 rounded-lg font-medium hover:bg-yellow-100 transition-colors">
                    <i class="fas fa-plus"></i>
                    <span>Ajouter une Certification</span>
                </button>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-eye text-green-500"></i>
                Aperçu de la Carte
            </h2>

            <div class="max-w-sm mx-auto">
                <label id="preview-card" class="category-card relative cursor-pointer group">
                    <div class="category-card-inner bg-white border-2 border-gray-200 rounded-xl p-4 text-center hover:border-primary transition-all duration-300 hover:shadow-lg">
                        <div id="preview-icon-wrapper" class="w-16 h-16 rounded-full mx-auto mb-3 flex items-center justify-center transition-all duration-300" style="background: {{ $category->color }}20; color: {{ $category->color }}">
                            <i id="preview-icon" class="{{ $category->icon }} text-2xl"></i>
                        </div>
                        <h4 id="preview-name" class="font-bold text-gray-900 mb-2">{{ $category->name }}</h4>
                        <p id="preview-description" class="text-xs text-gray-600 line-clamp-2">{{ $category->description }}</p>
                    </div>
                </label>
            </div>
        </div>

        <!-- Stats Card -->
        <div class="bg-blue-50 rounded-xl border border-blue-200 p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-bar"></i>
                Statistiques
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-blue-700">Déchets dans cette catégorie:</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $category->dechets->count() }}</p>
                </div>
                <div>
                    <p class="text-sm text-blue-700">Créée le:</p>
                    <p class="text-sm font-semibold text-blue-900">{{ $category->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4">
            <a href="{{ route('admin.categories.index') }}" class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-colors">
                <i class="fas fa-times"></i>
                <span>Annuler</span>
            </a>
            <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-primary to-primary-dark text-white rounded-xl font-semibold hover:shadow-lg hover:scale-105 transition-all duration-300">
                <i class="fas fa-save"></i>
                <span>Enregistrer les Modifications</span>
            </button>
        </div>
    </form>
</div>

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    #color::-webkit-color-swatch-wrapper {
        padding: 0;
    }

    #color::-webkit-color-swatch {
        border: none;
        border-radius: 8px;
    }
</style>
@endpush

@push('scripts')
<script>
    // Icon preview
    document.getElementById('icon').addEventListener('input', function(e) {
        const iconClass = e.target.value;
        const preview = document.getElementById('icon-preview');
        const cardPreview = document.getElementById('preview-icon');
        preview.innerHTML = `<i class="${iconClass}"></i>`;
        cardPreview.className = iconClass + ' text-2xl';
    });

    // Color sync
    document.getElementById('color').addEventListener('input', function(e) {
        document.getElementById('color-text').value = e.target.value;
        updatePreviewColors(e.target.value);
    });

    document.getElementById('color-text').addEventListener('input', function(e) {
        const color = e.target.value;
        if (/^#[0-9A-F]{6}$/i.test(color)) {
            document.getElementById('color').value = color;
            updatePreviewColors(color);
        }
    });

    function updatePreviewColors(color) {
        const wrapper = document.getElementById('preview-icon-wrapper');
        wrapper.style.background = color + '20';
        wrapper.style.color = color;
    }

    // Name preview
    document.getElementById('name').addEventListener('input', function(e) {
        document.getElementById('preview-name').textContent = e.target.value || 'Nom de la Catégorie';
    });

    // Description preview
    document.getElementById('description').addEventListener('input', function(e) {
        document.getElementById('preview-description').textContent = e.target.value || 'Description de la catégorie...';
    });

    // Image preview
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('image-preview');
                const container = document.getElementById('image-preview-container');
                preview.src = e.target.result;
                container.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // Certifications management
    function addCertification() {
        const list = document.getElementById('certifications-list');
        const item = document.createElement('div');
        item.className = 'certification-item flex gap-2';
        item.innerHTML = `
            <input type="text" name="certifications[]" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Ex: ISO 14001, FSC, PEFC...">
            <button type="button" onclick="removeCertification(this)" class="px-4 py-3 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors">
                <i class="fas fa-trash"></i>
            </button>
        `;
        list.appendChild(item);
    }

    function removeCertification(button) {
        const list = document.getElementById('certifications-list');
        if (list.children.length > 1) {
            button.closest('.certification-item').remove();
        } else {
            button.previousElementSibling.value = '';
        }
    }
</script>
@endpush
@endsection
