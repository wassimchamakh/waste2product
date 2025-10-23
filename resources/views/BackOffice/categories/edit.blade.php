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

                <!-- Icon Picker -->
                <div>
                    <label for="icon-picker" class="block text-sm font-semibold text-gray-700 mb-2">
                        Icône <span class="text-red-500">*</span>
                    </label>
                    <select id="icon-picker" onchange="selectIcon(this.value)" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all" required>
                        <option value="">Sélectionnez une icône...</option>
                        <option value="fas fa-recycle" {{ $category->icon == 'fas fa-recycle' ? 'selected' : '' }}>♻️ Recyclage</option>
                        <option value="fas fa-trash-alt" {{ $category->icon == 'fas fa-trash-alt' ? 'selected' : '' }}>🗑️ Déchet</option>
                        <option value="fas fa-leaf" {{ $category->icon == 'fas fa-leaf' ? 'selected' : '' }}>🍃 Organique</option>
                        <option value="fas fa-box" {{ $category->icon == 'fas fa-box' ? 'selected' : '' }}>📦 Emballage</option>
                        <option value="fas fa-bottle-water" {{ $category->icon == 'fas fa-bottle-water' ? 'selected' : '' }}>🧴 Bouteille</option>
                        <option value="fas fa-wine-bottle" {{ $category->icon == 'fas fa-wine-bottle' ? 'selected' : '' }}>🍾 Verre</option>
                        <option value="fas fa-newspaper" {{ $category->icon == 'fas fa-newspaper' ? 'selected' : '' }}>📰 Papier</option>
                        <option value="fas fa-tree" {{ $category->icon == 'fas fa-tree' ? 'selected' : '' }}>🌳 Bois</option>
                        <option value="fas fa-industry" {{ $category->icon == 'fas fa-industry' ? 'selected' : '' }}>🏭 Industriel</option>
                        <option value="fas fa-bolt" {{ $category->icon == 'fas fa-bolt' ? 'selected' : '' }}>⚡ Électronique</option>
                        <option value="fas fa-laptop" {{ $category->icon == 'fas fa-laptop' ? 'selected' : '' }}>💻 Informatique</option>
                        <option value="fas fa-mobile-alt" {{ $category->icon == 'fas fa-mobile-alt' ? 'selected' : '' }}>📱 Mobile</option>
                        <option value="fas fa-battery-full" {{ $category->icon == 'fas fa-battery-full' ? 'selected' : '' }}>🔋 Batterie</option>
                        <option value="fas fa-lightbulb" {{ $category->icon == 'fas fa-lightbulb' ? 'selected' : '' }}>💡 Ampoule</option>
                        <option value="fas fa-couch" {{ $category->icon == 'fas fa-couch' ? 'selected' : '' }}>🛋️ Mobilier</option>
                        <option value="fas fa-tshirt" {{ $category->icon == 'fas fa-tshirt' ? 'selected' : '' }}>👕 Textile</option>
                        <option value="fas fa-car" {{ $category->icon == 'fas fa-car' ? 'selected' : '' }}>🚗 Automobile</option>
                        <option value="fas fa-oil-can" {{ $category->icon == 'fas fa-oil-can' ? 'selected' : '' }}>🛢️ Huile</option>
                        <option value="fas fa-paint-roller" {{ $category->icon == 'fas fa-paint-roller' ? 'selected' : '' }}>🎨 Peinture</option>
                        <option value="fas fa-prescription-bottle" {{ $category->icon == 'fas fa-prescription-bottle' ? 'selected' : '' }}>💊 Médicament</option>
                        <option value="fas fa-seedling" {{ $category->icon == 'fas fa-seedling' ? 'selected' : '' }}>🌱 Compost</option>
                        <option value="fas fa-water" {{ $category->icon == 'fas fa-water' ? 'selected' : '' }}>💧 Liquide</option>
                        <option value="fas fa-gas-pump" {{ $category->icon == 'fas fa-gas-pump' ? 'selected' : '' }}>⛽ Carburant</option>
                        <option value="fas fa-skull-crossbones" {{ $category->icon == 'fas fa-skull-crossbones' ? 'selected' : '' }}>☠️ Dangereux</option>
                        <option value="fas fa-radiation" {{ $category->icon == 'fas fa-radiation' ? 'selected' : '' }}>☢️ Radioactif</option>
                        <option value="fas fa-flask" {{ $category->icon == 'fas fa-flask' ? 'selected' : '' }}>🧪 Chimique</option>
                        <option value="fas fa-cubes" {{ $category->icon == 'fas fa-cubes' ? 'selected' : '' }}>📦 Matériaux</option>
                        <option value="fas fa-hammer" {{ $category->icon == 'fas fa-hammer' ? 'selected' : '' }}>🔨 Construction</option>
                        <option value="fas fa-home" {{ $category->icon == 'fas fa-home' ? 'selected' : '' }}>🏠 Domestique</option>
                        <option value="fas fa-utensils" {{ $category->icon == 'fas fa-utensils' ? 'selected' : '' }}>🍴 Alimentaire</option>
                    </select>
                    <input type="hidden" id="icon" name="icon" value="{{ old('icon', $category->icon) }}" required>
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

        <!-- Certifications Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-certificate text-yellow-500"></i>
                    Certifications & Normes
                </h2>
                <button type="button" onclick="suggestCertificationsAI()" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-lg font-medium hover:shadow-lg transition-all">
                    <i class="fas fa-magic"></i>
                    <span>IA: Suggérer des certifications</span>
                </button>
            </div>

            <div id="certifications-container">
                <div class="space-y-3" id="certifications-list">
                    @if(old('certifications', $category->certifications))
                        @foreach(old('certifications', $category->certifications) as $index => $cert)
                            <div class="certification-item flex gap-2">
                                <select name="certifications[]" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                                    <option value="">Sélectionnez une certification</option>
                                    <optgroup label="ISO - Normes Environnementales">
                                        <option value="ISO 14001" {{ $cert == 'ISO 14001' ? 'selected' : '' }}>ISO 14001 - Management Environnemental</option>
                                        <option value="ISO 14040" {{ $cert == 'ISO 14040' ? 'selected' : '' }}>ISO 14040 - Analyse du Cycle de Vie</option>
                                        <option value="ISO 14044" {{ $cert == 'ISO 14044' ? 'selected' : '' }}>ISO 14044 - ACV: Exigences et Lignes Directrices</option>
                                        <option value="ISO 14046" {{ $cert == 'ISO 14046' ? 'selected' : '' }}>ISO 14046 - Empreinte Eau</option>
                                        <option value="ISO 14064" {{ $cert == 'ISO 14064' ? 'selected' : '' }}>ISO 14064 - Gaz à Effet de Serre</option>
                                    </optgroup>
                                    <optgroup label="Certifications Forestières">
                                        <option value="FSC" {{ $cert == 'FSC' ? 'selected' : '' }}>FSC - Forest Stewardship Council</option>
                                        <option value="PEFC" {{ $cert == 'PEFC' ? 'selected' : '' }}>PEFC - Programme for Endorsement of Forest Certification</option>
                                    </optgroup>
                                    <optgroup label="Recyclage & Économie Circulaire">
                                        <option value="Green Dot" {{ $cert == 'Green Dot' ? 'selected' : '' }}>Point Vert (Der Grüne Punkt)</option>
                                        <option value="Cradle to Cradle" {{ $cert == 'Cradle to Cradle' ? 'selected' : '' }}>Cradle to Cradle Certified</option>
                                        <option value="EU Ecolabel" {{ $cert == 'EU Ecolabel' ? 'selected' : '' }}>Écolabel Européen</option>
                                    </optgroup>
                                    <optgroup label="Qualité & Sécurité">
                                        <option value="ISO 9001" {{ $cert == 'ISO 9001' ? 'selected' : '' }}>ISO 9001 - Management de la Qualité</option>
                                        <option value="ISO 45001" {{ $cert == 'ISO 45001' ? 'selected' : '' }}>ISO 45001 - Santé et Sécurité au Travail</option>
                                    </optgroup>
                                    <optgroup label="Autres">
                                        <option value="GRS" {{ $cert == 'GRS' ? 'selected' : '' }}>GRS - Global Recycled Standard</option>
                                        <option value="RCS" {{ $cert == 'RCS' ? 'selected' : '' }}>RCS - Recycled Claim Standard</option>
                                        <option value="Autre" {{ $cert == 'Autre' ? 'selected' : '' }}>Autre (préciser en description)</option>
                                    </optgroup>
                                </select>
                                <button type="button" onclick="removeCertification(this)" class="px-4 py-3 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        @endforeach
                    @else
                        <div class="certification-item flex gap-2">
                            <select name="certifications[]" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                                <option value="">Sélectionnez une certification</option>
                                <optgroup label="ISO - Normes Environnementales">
                                    <option value="ISO 14001">ISO 14001 - Management Environnemental</option>
                                    <option value="ISO 14040">ISO 14040 - Analyse du Cycle de Vie</option>
                                    <option value="ISO 14044">ISO 14044 - ACV: Exigences et Lignes Directrices</option>
                                    <option value="ISO 14046">ISO 14046 - Empreinte Eau</option>
                                    <option value="ISO 14064">ISO 14064 - Gaz à Effet de Serre</option>
                                </optgroup>
                                <optgroup label="Certifications Forestières">
                                    <option value="FSC">FSC - Forest Stewardship Council</option>
                                    <option value="PEFC">PEFC - Programme for Endorsement of Forest Certification</option>
                                </optgroup>
                                <optgroup label="Recyclage & Économie Circulaire">
                                    <option value="Green Dot">Point Vert (Der Grüne Punkt)</option>
                                    <option value="Cradle to Cradle">Cradle to Cradle Certified</option>
                                    <option value="EU Ecolabel">Écolabel Européen</option>
                                </optgroup>
                                <optgroup label="Qualité & Sécurité">
                                    <option value="ISO 9001">ISO 9001 - Management de la Qualité</option>
                                    <option value="ISO 45001">ISO 45001 - Santé et Sécurité au Travail</option>
                                </optgroup>
                                <optgroup label="Autres">
                                    <option value="GRS">GRS - Global Recycled Standard</option>
                                    <option value="RCS">RCS - Recycled Claim Standard</option>
                                    <option value="Autre">Autre (préciser en description)</option>
                                </optgroup>
                            </select>
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
            <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg font-bold hover:from-blue-600 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl">
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
    // Icon selector function
    function selectIcon(iconClass) {
        const iconInput = document.getElementById('icon');
        const previewIcon = document.getElementById('preview-icon');
        
        iconInput.value = iconClass;
        previewIcon.className = iconClass + ' text-2xl';
    }

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
            <select name="certifications[]" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                <option value="">Sélectionnez une certification</option>
                <optgroup label="ISO - Normes Environnementales">
                    <option value="ISO 14001">ISO 14001 - Management Environnemental</option>
                    <option value="ISO 14040">ISO 14040 - Analyse du Cycle de Vie</option>
                    <option value="ISO 14044">ISO 14044 - ACV: Exigences et Lignes Directrices</option>
                    <option value="ISO 14046">ISO 14046 - Empreinte Eau</option>
                    <option value="ISO 14064">ISO 14064 - Gaz à Effet de Serre</option>
                </optgroup>
                <optgroup label="Certifications Forestières">
                    <option value="FSC">FSC - Forest Stewardship Council</option>
                    <option value="PEFC">PEFC - Programme for Endorsement of Forest Certification</option>
                </optgroup>
                <optgroup label="Recyclage & Économie Circulaire">
                    <option value="Green Dot">Point Vert (Der Grüne Punkt)</option>
                    <option value="Cradle to Cradle">Cradle to Cradle Certified</option>
                    <option value="EU Ecolabel">Écolabel Européen</option>
                </optgroup>
                <optgroup label="Qualité & Sécurité">
                    <option value="ISO 9001">ISO 9001 - Management de la Qualité</option>
                    <option value="ISO 45001">ISO 45001 - Santé et Sécurité au Travail</option>
                </optgroup>
                <optgroup label="Autres">
                    <option value="GRS">GRS - Global Recycled Standard</option>
                    <option value="RCS">RCS - Recycled Claim Standard</option>
                    <option value="Autre">Autre (préciser en description)</option>
                </optgroup>
            </select>
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

    // AI Certification Suggestions using Gemini API
    async function suggestCertificationsAI() {
        const categoryName = document.getElementById('name').value || 'Non spécifié';
        const categoryDescription = document.getElementById('description').value || '';
        
        if (!categoryName || categoryName === 'Non spécifié') {
            alert('⚠️ Veuillez d\'abord entrer un nom de catégorie');
            return;
        }

        const button = event.target.closest('button');
        const originalHTML = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>IA en cours...</span>';

        try {
            const response = await fetch('{{ route("admin.categories.ai-suggest") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    name: categoryName,
                    description: categoryDescription
                })
            });

            const data = await response.json();

            if (data.success && data.certifications) {
                // Clear existing certifications
                document.getElementById('certifications-list').innerHTML = '';
                
                // Add suggested certifications
                data.certifications.forEach((cert, index) => {
                    if (index > 0) addCertification();
                    const selects = document.querySelectorAll('#certifications-list select');
                    const lastSelect = selects[selects.length - 1];
                    
                    // Try to select the suggested certification
                    const options = Array.from(lastSelect.options);
                    const matchingOption = options.find(opt => 
                        opt.value.toLowerCase().includes(cert.toLowerCase()) ||
                        cert.toLowerCase().includes(opt.value.toLowerCase())
                    );
                    
                    if (matchingOption) {
                        lastSelect.value = matchingOption.value;
                    }
                });

                // Show success message
                const messageDiv = document.createElement('div');
                messageDiv.className = 'mt-3 p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm';
                messageDiv.innerHTML = `<i class="fas fa-check-circle"></i> ${data.message || 'Certifications suggérées avec succès!'}`;
                document.getElementById('certifications-container').appendChild(messageDiv);
                setTimeout(() => messageDiv.remove(), 5000);
            } else {
                throw new Error(data.message || 'Erreur lors de la suggestion');
            }
        } catch (error) {
            alert('❌ Erreur: ' + error.message);
        } finally {
            button.disabled = false;
            button.innerHTML = originalHTML;
        }
    }
</script>
@endpush
@endsection
