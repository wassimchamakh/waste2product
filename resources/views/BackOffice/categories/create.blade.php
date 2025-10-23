@extends('BackOffice.layouts.app')

@section('title', 'Créer une Catégorie')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('admin.categories.index') }}" class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left text-gray-600"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Créer une Nouvelle Catégorie</h1>
        </div>
        <p class="text-gray-600 ml-13">Ajoutez une nouvelle catégorie pour classifier les déchets</p>
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

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

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
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="100" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Ex: Plastique, Bois, Métal...">
                </div>

                <!-- Icon -->
                <div>
                    <label for="icon" class="block text-sm font-semibold text-gray-700 mb-2">
                        Icône <span class="text-red-500">*</span>
                    </label>
                    <select id="icon" name="icon" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                        <option value="">Choisissez une icône...</option>
                        <optgroup label="♻️ Recyclage & Environnement">
                            <option value="fas fa-recycle" selected>♻️ Recycle</option>
                            <option value="fas fa-leaf">🍃 Feuille</option>
                            <option value="fas fa-seedling">🌱 Plante</option>
                            <option value="fas fa-tree">🌳 Arbre</option>
                            <option value="fas fa-globe">🌍 Globe</option>
                            <option value="fas fa-water">💧 Eau</option>
                        </optgroup>
                        <optgroup label="📦 Matériaux">
                            <option value="fas fa-box">📦 Boîte</option>
                            <option value="fas fa-cubes">🧊 Cubes</option>
                            <option value="fas fa-drum">🛢️ Baril</option>
                            <option value="fas fa-trash">🗑️ Poubelle</option>
                            <option value="fas fa-dumpster">🚮 Conteneur</option>
                        </optgroup>
                        <optgroup label="🔧 Électronique & Métaux">
                            <option value="fas fa-microchip">🖥️ Puce</option>
                            <option value="fas fa-mobile-alt">📱 Téléphone</option>
                            <option value="fas fa-laptop">💻 Ordinateur</option>
                            <option value="fas fa-plug">🔌 Prise</option>
                            <option value="fas fa-battery-full">🔋 Batterie</option>
                            <option value="fas fa-cog">⚙️ Engrenage</option>
                        </optgroup>
                        <optgroup label="🏗️ Construction & Bois">
                            <option value="fas fa-hammer">🔨 Marteau</option>
                            <option value="fas fa-wrench">🔧 Clé</option>
                            <option value="fas fa-toolbox">🧰 Boîte à outils</option>
                            <option value="fas fa-hard-hat">👷 Casque</option>
                        </optgroup>
                        <optgroup label="🧪 Chimique & Dangereux">
                            <option value="fas fa-flask">🧪 Flacon</option>
                            <option value="fas fa-biohazard">☣️ Biohazard</option>
                            <option value="fas fa-radiation">☢️ Radiation</option>
                            <option value="fas fa-fire">🔥 Feu</option>
                        </optgroup>
                        <optgroup label="🍽️ Alimentaire & Organique">
                            <option value="fas fa-apple-alt">🍎 Pomme</option>
                            <option value="fas fa-wine-bottle">🍾 Bouteille</option>
                            <option value="fas fa-glass-martini">🍷 Verre</option>
                            <option value="fas fa-utensils">🍴 Couverts</option>
                        </optgroup>
                        <optgroup label="👔 Textile & Vêtements">
                            <option value="fas fa-tshirt">👕 T-shirt</option>
                            <option value="fas fa-socks">🧦 Chaussettes</option>
                            <option value="fas fa-shoe-prints">👟 Chaussures</option>
                        </optgroup>
                        <optgroup label="💡 Lumière & Énergie">
                            <option value="fas fa-lightbulb">💡 Ampoule</option>
                            <option value="fas fa-sun">☀️ Soleil</option>
                            <option value="fas fa-bolt">⚡ Éclair</option>
                        </optgroup>
                        <optgroup label="🚗 Véhicules & Pièces">
                            <option value="fas fa-car">🚗 Voiture</option>
                            <option value="fas fa-motorcycle">🏍️ Moto</option>
                            <option value="fas fa-tire">🛞 Pneu</option>
                            <option value="fas fa-oil-can">🛢️ Huile</option>
                        </optgroup>
                        <optgroup label="🏠 Maison & Construction">
                            <option value="fas fa-home">🏠 Maison</option>
                            <option value="fas fa-paint-roller">🖌️ Rouleau</option>
                            <option value="fas fa-screwdriver">🔩 Tournevis</option>
                        </optgroup>
                        <optgroup label="📚 Autres">
                            <option value="fas fa-book">📚 Livre</option>
                            <option value="fas fa-newspaper">📰 Journal</option>
                            <option value="fas fa-question-circle">❓ Autre</option>
                        </optgroup>
                    </select>
                </div>

                <!-- Color -->
                <div>
                    <label for="color" class="block text-sm font-semibold text-gray-700 mb-2">
                        Couleur <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-3">
                        <input type="color" id="color" name="color" value="{{ old('color', '#059669') }}" required class="w-16 h-12 rounded-lg border border-gray-300 cursor-pointer">
                        <input type="text" id="color-text" value="{{ old('color', '#059669') }}" maxlength="20" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="#059669">
                    </div>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description" name="description" rows="3" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Décrivez cette catégorie...">{{ old('description') }}</textarea>
                </div>

                <!-- Tips -->
                <div class="md:col-span-2">
                    <label for="tips" class="block text-sm font-semibold text-gray-700 mb-2">
                        Conseils de Recyclage
                    </label>
                    <textarea id="tips" name="tips" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Conseils pour recycler cette catégorie...">{{ old('tips') }}</textarea>
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
                <div class="flex gap-2">
                    <button type="button" onclick="suggestCertifications()" class="px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-lg hover:from-purple-600 hover:to-indigo-600 transition-all font-semibold text-sm shadow-md hover:shadow-lg">
                        <i class="fas fa-sparkles mr-2"></i> IA: Suggérer
                    </button>
                    <button type="button" onclick="addCertification()" class="px-4 py-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors font-semibold text-sm">
                        <i class="fas fa-plus mr-2"></i> Ajouter
                    </button>
                </div>
            </div>

            <div id="certifications-container">
                <div class="space-y-3" id="certifications-list">
                    @if(old('certifications'))
                        @foreach(old('certifications') as $index => $cert)
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
            </div>
        </div>

        <!-- Preview Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-eye text-indigo-500"></i>
                Aperçu
            </h2>

            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8">
                <div class="flex items-center gap-4">
                    <div id="preview-icon-wrapper" class="w-16 h-16 rounded-full flex items-center justify-center" style="background: #05966920; color: #059669;">
                        <i id="preview-icon" class="fas fa-recycle text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 id="preview-name" class="text-xl font-bold text-gray-900 mb-1">Nom de la Catégorie</h3>
                        <p id="preview-description" class="text-sm text-gray-600 line-clamp-2">Description de la catégorie...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-4">
            <button type="submit" class="flex-1 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold py-4 px-6 rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                <i class="fas fa-check"></i>
                Créer la Catégorie
            </button>
            <a href="{{ route('admin.categories.index') }}" class="px-6 py-4 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                Annuler
            </a>
        </div>
    </form>
</div>

<!-- AI Certifications Modal -->
<div id="ai-certifications-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[80vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-sparkles text-purple-500"></i>
                    Certifications Suggérées par l'IA
                </h3>
                <button onclick="closeAIModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="p-6">
            <div id="ai-certifications-content" class="space-y-3">
                <!-- AI suggestions will appear here -->
            </div>
        </div>
        <div class="p-6 bg-gray-50 border-t border-gray-200 flex gap-3">
            <button onclick="applyAICertifications()" class="flex-1 bg-gradient-to-r from-purple-500 to-indigo-500 text-white px-6 py-3 rounded-lg font-bold hover:from-purple-600 hover:to-indigo-600 transition-all shadow-md hover:shadow-lg">
                <i class="fas fa-check mr-2"></i> Appliquer les Suggestions
            </button>
            <button onclick="closeAIModal()" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-colors">
                Annuler
            </button>
        </div>
    </div>
</div>

@endsection

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
    // Icon selector
    document.getElementById('icon').addEventListener('change', function(e) {
        const iconClass = e.target.value;
        const previewIcon = document.getElementById('preview-icon');
        previewIcon.className = iconClass + ' text-2xl';
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

    // AI Certification Suggestion
    let suggestedCertifications = [];

    async function suggestCertifications() {
        const name = document.getElementById('name').value;
        const description = document.getElementById('description').value;

        if (!name) {
            alert('⚠️ Veuillez d\'abord entrer un nom de catégorie');
            return;
        }

        // Show loading state
        const button = event.target.closest('button');
        const originalHTML = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> IA en cours...';

        try {
            const response = await fetch('{{ route("admin.categories.ai-suggest") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    material_type: name,
                    recycling_purpose: description || `Gestion et recyclage de ${name}`,
                    environmental_impact: 'Impact environnemental positif'
                })
            });

            const data = await response.json();

            if (data.success && data.data && data.data.certifications) {
                suggestedCertifications = data.data.certifications;
                showAIModal(suggestedCertifications);
            } else {
                alert('❌ ' + (data.message || 'Erreur lors de la suggestion'));
            }
        } catch (error) {
            console.error('AI Error:', error);
            alert('❌ Erreur: ' + error.message);
        } finally {
            button.disabled = false;
            button.innerHTML = originalHTML;
        }
    }

    function showAIModal(certifications) {
        const content = document.getElementById('ai-certifications-content');
        content.innerHTML = '';

        if (certifications.length === 0) {
            content.innerHTML = '<p class="text-gray-500 text-center py-8">Aucune certification suggérée</p>';
        } else {
            certifications.forEach(cert => {
                const div = document.createElement('div');
                div.className = 'bg-purple-50 border-2 border-purple-200 rounded-lg p-4 flex items-center gap-3';
                div.innerHTML = `
                    <i class="fas fa-certificate text-purple-500 text-2xl"></i>
                    <div class="flex-1">
                        <p class="font-bold text-gray-900">${cert}</p>
                        <p class="text-sm text-gray-600">Suggéré par l'IA Gemini</p>
                    </div>
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                `;
                content.appendChild(div);
            });
        }

        document.getElementById('ai-certifications-modal').classList.remove('hidden');
    }

    function closeAIModal() {
        document.getElementById('ai-certifications-modal').classList.add('hidden');
    }

    function applyAICertifications() {
        if (suggestedCertifications.length === 0) {
            closeAIModal();
            return;
        }

        // Clear existing certifications
        const certList = document.getElementById('certifications-list');
        certList.innerHTML = '';

        // Add each suggested certification
        suggestedCertifications.forEach((cert, index) => {
            // Add new certification row
            addCertification();

            // Get all selects and find the one we just added
            const selects = document.querySelectorAll('#certifications-list select');
            const lastSelect = selects[selects.length - 1];

            // Try to match and select the certification
            const options = Array.from(lastSelect.options);
            const match = options.find(opt =>
                opt.value.toLowerCase().includes(cert.toLowerCase()) ||
                cert.toLowerCase().includes(opt.value.toLowerCase()) ||
                opt.text.toLowerCase().includes(cert.toLowerCase())
            );

            if (match) {
                lastSelect.value = match.value;
                // Highlight the selected row
                lastSelect.closest('.certification-item').style.backgroundColor = '#f0fdf4';
            }
        });

        // Success message
        const msg = document.createElement('div');
        msg.className = 'mt-3 p-3 bg-green-50 border-l-4 border-green-500 rounded-lg text-green-700 text-sm';
        msg.innerHTML = '<i class="fas fa-check-circle mr-2"></i> ' + suggestedCertifications.length + ' certifications appliquées avec succès !';
        document.getElementById('certifications-container').appendChild(msg);
        setTimeout(() => msg.remove(), 5000);

        closeAIModal();
    }

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAIModal();
        }
    });
</script>
@endpush
