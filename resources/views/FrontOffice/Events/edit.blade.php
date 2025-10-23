@extends('FrontOffice.layout1.app')

@section('title', 'Modifier ' . $event->title . ' - Waste2Product')

@push('styles')
<style>
    .danger-zone {
        border: 2px dashed #ef4444;
        background-color: #fef2f2;
    }
    
    .participants-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
    }
    
    .input-error {
        border-color: #ef4444;
        background-color: #fef2f2;
    }
    
    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
    }
    
    .char-counter {
        font-size: 0.75rem;
        color: #6b7280;
    }
    
    .modal {
        transition: all 0.3s ease-in-out;
    }
    .modal.show {
        opacity: 1;
        pointer-events: auto;
    }
    
    .drag-drop-area {
        transition: all 0.3s ease;
        border: 2px dashed #d1d5db;
    }
    .drag-drop-area.dragover {
        border-color: #2E7D47;
        background-color: #f0fdf4;
    }
</style>
@endpush

@section('content')
<!-- Header -->
<div class="bg-gradient-to-r from-secondary to-warning text-white py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Modifier l'événement</h1>
                <p class="opacity-90">{{ $event->title }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('Events.show', $event) }}" class="bg-white/20 text-white px-4 py-2 rounded-lg hover:bg-white/30 transition-colors">
                    <i class="fas fa-eye mr-2"></i>Voir l'événement
                </a>
                <a href="{{ route('Events.mes-Events') }}" class="bg-white/20 text-white px-4 py-2 rounded-lg hover:bg-white/30 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Participants Warning -->
    @if($event->current_participants > 0)
    <div class="participants-warning text-white rounded-xl p-6 mb-8">
        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-lg mb-2">Attention !</h3>
                <p class="mb-3">
                    <strong>{{ $event->current_participants }} participants</strong> sont déjà inscrits à cet événement.
                    Les modifications importantes seront communiquées par email.
                </p>
                <div class="flex flex-wrap gap-2">
                    <span class="bg-white/20 px-3 py-1 rounded-full text-sm">
                        <i class="fas fa-envelope mr-1"></i>
                        Notification automatique
                    </span>
                    <span class="bg-white/20 px-3 py-1 rounded-full text-sm">
                        <i class="fas fa-users mr-1"></i>
                        {{ $event->current_participants }} inscrits
                    </span>
                </div>
            </div>
        </div>
        
        <div class="mt-4 pt-4 border-t border-white/20">
            <label class="flex items-center text-sm">
                <input type="checkbox" name="notify_participants" value="1" checked 
                       class="mr-2 text-white focus:ring-white">
                <span>Envoyer un email de mise à jour aux participants inscrits</span>
            </label>
        </div>
    </div>
    @endif

    <!-- Edit Form -->
    <form id="edit-form" action="{{ route('Events.update', $event) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold mb-6 text-gray-900 flex items-center">
                        <i class="fas fa-info-circle mr-3 text-primary"></i>
                        Informations générales
                    </h2>
                    
                    <!-- Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Titre de l'événement <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" required
                               value="{{ old('title', $event->title) }}"
                               class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                               maxlength="255">
                        <div class="char-counter mt-1 text-right">
                            <span id="title-count">{{ strlen($event->title) }}</span>/255 caractères
                        </div>
                        @error('title')
                            <div class="error-message mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Type d'événement <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($types as $key => $type)
                                <label>
                                 <input type="radio" name="type" value="{{ $key }}"
                                   {{ old('type', $event->type) == $key ? 'checked' : '' }}
                                   class="sr-only peer" required>

                                    <div class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-primary peer-checked:bg-primary/5 hover:border-gray-300 transition-all">
                                      <div class="flex items-center space-x-3">
                                     <i class="{{ $type['icon'] }} text-xl text-primary"></i>
                                     <div>
                                            <div class="font-medium">{{ $type['label'] }}</div>
                                     </div>
                                    </div>
                                     </div>
                            </label>
                             @endforeach

                        </div>
                        @error('type')
                            <div class="error-message mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description complète <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" id="description" rows="6" required
                                  class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                  minlength="100">{{ old('description', $event->description) }}</textarea>
                        <div class="char-counter mt-1">
                            <span id="description-count">{{ strlen($event->description ?? '') }}</span> caractères (min. 100)
                        </div>
                        @error('description')
                            <div class="error-message mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Learning Objectives -->
                    <div class="mb-6">
                        <label for="learning_objectives" class="block text-sm font-medium text-gray-700 mb-2">
                            Ce que les participants vont apprendre/faire (optionnel)
                        </label>
                        <textarea name="learning_objectives" id="learning_objectives" rows="4"
                                  class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                  placeholder="Ex: - Techniques de réparation de base&#10;- Utilisation d'outils de bricolage...">{{ old('learning_objectives', $event->learning_objectives) }}</textarea>
                        <div class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Listez les compétences ou connaissances que les participants acquerront
                        </div>
                    </div>

                    <!-- Required Materials -->
                    <div class="mb-6">
                        <label for="required_materials" class="block text-sm font-medium text-gray-700 mb-2">
                            Matériel nécessaire (optionnel)
                        </label>
                        <textarea name="required_materials" id="required_materials" rows="3"
                                  class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                  placeholder="Ex: Apportez vos objets à réparer...">{{ old('required_materials', $event->required_materials) }}</textarea>
                        <div class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Indiquez ce que les participants doivent apporter
                        </div>
                    </div>

                    <!-- Skill Level -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Niveau requis <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @foreach(['beginner' => 'Débutant', 'intermediate' => 'Intermédiaire', 'advanced' => 'Avancé', 'all' => 'Tous niveaux'] as $level => $label)
                            <label class="cursor-pointer">
                                <input type="radio" name="skill_level" value="{{ $level }}" 
                                       {{ old('skill_level', $event->skill_level ?? 'beginner') == $level ? 'checked' : '' }}
                                       class="sr-only peer" required>
                                <div class="p-3 border-2 border-gray-200 rounded-lg peer-checked:border-primary peer-checked:bg-primary/5 hover:border-gray-300 transition-all text-center">
                                    <div class="font-medium text-sm">{{ $label }}</div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Date and Location -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold mb-6 text-gray-900 flex items-center">
                        <i class="fas fa-calendar-map mr-3 text-primary"></i>
                        Date et lieu
                    </h2>

                    <!-- Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="date_start" class="block text-sm font-medium text-gray-700 mb-2">
                                Date et heure de début <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" name="date_start" id="date_start" required
                                   value="{{ old('date_start', \Carbon\Carbon::parse($event->date_start)->format('Y-m-d\TH:i')) }}"
                                   class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            @error('date_start')
                                <div class="error-message mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="date_end" class="block text-sm font-medium text-gray-700 mb-2">
                                Date et heure de fin <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" name="date_end" id="date_end" required
                                   value="{{ old('date_end', \Carbon\Carbon::parse($event->date_end)->format('Y-m-d\TH:i')) }}"
                                   class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            @error('date_end')
                                <div class="error-message mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- City -->
                    <div class="mb-6">
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                            Ville <span class="text-red-500">*</span>
                        </label>
                        <select name="city" id="city" required
                                class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="">Sélectionnez une ville</option>
                            @foreach($cities as $city)
                            <option value="{{ $city }}" {{ old('city', $event->city) == $city ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $city)) }}
                            </option>
                            @endforeach
                        </select>
                        @error('city')
                            <div class="error-message mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div class="mb-6">
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                            Adresse complète <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="location" id="location" required
                               value="{{ old('location', $event->location) }}"
                               class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        @error('location')
                            <div class="error-message mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Google Maps Link -->
                    <div class="mb-6">
                        <label for="maps_link" class="block text-sm font-medium text-gray-700 mb-2">
                            Lien Google Maps (optionnel)
                        </label>
                        <input type="url" name="maps_link" id="maps_link"
                               value="{{ old('maps_link', $event->maps_link) }}"
                               class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="https://maps.google.com/...">
                        <div class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Collez le lien partagé de Google Maps ou sélectionnez sur la carte
                        </div>
                    </div>

                    <!-- Interactive Map Picker -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Sélectionner l'emplacement sur la carte (optionnel)
                        </label>
                        <div class="text-sm text-gray-500 mb-2">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            Cliquez sur la carte pour placer le marqueur
                        </div>
                        
                        <div id="map-picker" class="rounded-lg overflow-hidden border border-gray-300" style="height: 400px;"></div>
                        
                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $event->latitude) }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $event->longitude) }}">
                        
                        <div class="mt-2 text-sm text-gray-600">
                            <span class="font-medium">Coordonnées:</span> 
                            <span id="lat-display">{{ $event->latitude ?? '-' }}</span>, 
                            <span id="lng-display">{{ $event->longitude ?? '-' }}</span>
                        </div>
                        
                        <button type="button" id="clear-map" class="mt-2 text-sm text-red-500 hover:text-red-700">
                            <i class="fas fa-times mr-1"></i>Effacer la sélection
                        </button>
                    </div>

                    <!-- Access Instructions -->
                    <div class="mb-6">
                        <label for="access_instructions" class="block text-sm font-medium text-gray-700 mb-2">
                            Instructions d'accès (optionnel)
                        </label>
                        <textarea name="access_instructions" id="access_instructions" rows="3"
                                  class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                  placeholder="Ex: Entrée par la porte principale...">{{ old('access_instructions', $event->access_instructions) }}</textarea>
                        <div class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Indiquez comment accéder au lieu
                        </div>
                    </div>

                    <!-- Location Facilities -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Installations disponibles
                        </label>
                        <div class="space-y-3">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="parking_available" value="1"
                                       {{ old('parking_available', $event->parking_available) ? 'checked' : '' }}
                                       class="mr-3 text-primary focus:ring-primary rounded">
                                <div class="flex items-center">
                                    <i class="fas fa-car text-primary mr-2"></i>
                                    <span>Parking disponible</span>
                                </div>
                            </label>
                            
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="accessible_pmr" value="1"
                                       {{ old('accessible_pmr', $event->accessible_pmr) ? 'checked' : '' }}
                                       class="mr-3 text-primary focus:ring-primary rounded">
                                <div class="flex items-center">
                                    <i class="fas fa-wheelchair text-primary mr-2"></i>
                                    <span>Accessible PMR</span>
                                </div>
                            </label>
                            
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="wifi_available" value="1"
                                       {{ old('wifi_available', $event->wifi_available) ? 'checked' : '' }}
                                       class="mr-3 text-primary focus:ring-primary rounded">
                                <div class="flex items-center">
                                    <i class="fas fa-wifi text-primary mr-2"></i>
                                    <span>WiFi gratuit</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Participants and Price -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold mb-6 text-gray-900 flex items-center">
                        <i class="fas fa-users-cog mr-3 text-primary"></i>
                        Participants et prix
                    </h2>

                    <!-- Max Participants -->
                    <div class="mb-6">
                        <label for="max_participants" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre maximum de participants <span class="text-red-500">*</span>
                        </label>
                        
                        @if($event->current_participants > 0)
                        <div class="mb-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                            <div class="flex items-center text-amber-800 text-sm">
                                <i class="fas fa-info-circle mr-2"></i>
                                <span>
                                    {{ $event->current_participants }} participants déjà inscrits. 
                                    Le maximum ne peut pas être inférieur à ce nombre.
                                </span>
                            </div>
                        </div>
                        @endif
                        
                        <input type="range" name="max_participants" id="max_participants" 
                               min="{{ max(5, $event->current_participants) }}" max="100" step="5" 
                               value="{{ old('max_participants', $event->max_participants) }}"
                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span>{{ max(5, $event->current_participants) }}</span>
                            <span>25</span>
                            <span>50</span>
                            <span>75</span>
                            <span>100</span>
                        </div>
                        <div class="mt-3 text-center">
                            <span class="text-2xl font-bold text-primary" id="participants-display">{{ $event->max_participants }}</span>
                            <span class="text-gray-600"> participants maximum</span>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Prix de participation <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="space-y-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="price_type" value="free" 
                                       {{ $event->price == 0 ? 'checked' : '' }}
                                       class="mr-3 text-primary focus:ring-primary">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-success rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-gift text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium">Gratuit</div>
                                        <div class="text-sm text-gray-500">Événement accessible à tous</div>
                                    </div>
                                </div>
                            </label>

                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="price_type" value="paid"
                                       {{ $event->price > 0 ? 'checked' : '' }}
                                       class="mr-3 text-primary focus:ring-primary">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-secondary rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-tag text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium">Payant</div>
                                        <div class="text-sm text-gray-500">Couvre les frais de matériel</div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div id="price-input" class="mt-4 {{ $event->price > 0 ? '' : 'hidden' }}">
                            <div class="relative">
                                <input type="number" name="price" id="price" min="0" step="0.01"
                                       value="{{ old('price', $event->price) }}"
                                       class="w-full pl-4 pr-12 py-3 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 font-medium">DT</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold mb-6 text-gray-900 flex items-center">
                        <i class="fas fa-image mr-3 text-primary"></i>
                        Image de l'événement
                    </h2>

                    <!-- Current Image -->
                    @if($event->image)
                    <div class="mb-6">
                        <p class="text-sm text-gray-600 mb-3">Image actuelle :</p>
                        <div class="relative inline-block">
                            <img src="{{ $event->image }}" alt="Image actuelle" class="max-w-sm rounded-lg shadow">
                            <button type="button" id="remove-current-image" class="absolute top-2 right-2 bg-accent text-white p-2 rounded-full hover:bg-red-600 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <input type="hidden" name="remove_current_image" id="remove_current_image_input" value="0">
                    </div>
                    @endif

                    <!-- New Image Upload -->
                    <div class="drag-drop-area border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-primary transition-colors cursor-pointer" id="drop-area">
                        <div id="drop-content">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                            <p class="text-lg font-medium text-gray-600 mb-2">
                                {{ $event->image ? 'Remplacer l\'image' : 'Ajouter une image' }}
                            </p>
                            <p class="text-sm text-gray-500 mb-4">Glissez votre image ici ou cliquez pour sélectionner</p>
                            <p class="text-xs text-gray-400">JPG, PNG, WebP - Max 2MB</p>
                        </div>
                        <div id="preview-content" class="hidden">
                            <img id="image-preview" class="max-w-full max-h-64 mx-auto rounded-lg shadow-lg mb-4">
                            <p class="text-sm text-gray-600 mb-2" id="file-name"></p>
                            <button type="button" id="remove-image" class="text-accent hover:text-red-600 text-sm">
                                <i class="fas fa-trash mr-1"></i>Supprimer
                            </button>
                        </div>
                    </div>
                    <input type="file" name="image" id="image-input" accept="image/*" class="hidden">
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Status -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                    <h3 class="font-bold mb-4">Statut de l'événement</h3>
                    
                    <div class="space-y-3">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="status" value="draft" 
                                   {{ old('status', $event->status) == 'draft' ? 'checked' : '' }}
                                   class="mr-3 text-primary focus:ring-primary">
                            <div>
                                <div class="font-medium">Brouillon</div>
                                <div class="text-sm text-gray-500">Visible uniquement pour vous</div>
                            </div>
                        </label>

                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="status" value="published"
                                   {{ old('status', $event->status) == 'published' ? 'checked' : '' }}
                                   class="mr-3 text-primary focus:ring-primary">
                            <div>
                                <div class="font-medium">Publié</div>
                                <div class="text-sm text-gray-500">Visible par tous</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                    <h3 class="font-bold mb-4">Statistiques</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Participants :</span>
                            <span class="font-medium">{{ $event->current_participants }}/{{ $event->max_participants }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Taux de remplissage :</span>
                            <span class="font-medium">{{ $event->max_participants > 0 ? round(($event->current_participants / $event->max_participants) * 100) : 0 }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Créé le :</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($event->created_at)->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="space-y-4">
                    <!-- Save Button -->
                    <button type="submit" class="w-full bg-primary text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors font-medium">
                        <i class="fas fa-save mr-2"></i>Sauvegarder les modifications
                    </button>

                    <!-- View Participants -->
                    @if($event->current_participants > 0)
                    <a href="{{ route('evenements.participants', $event) }}" class="w-full border border-secondary text-secondary px-6 py-3 rounded-lg hover:bg-secondary hover:text-white transition-colors font-medium text-center block">
                        <i class="fas fa-users mr-2"></i>Voir les participants
                    </a>
                    @endif

                    <!-- Duplicate -->
                    <a href="{{ route('Events.create', ['duplicate' => $event->id]) }}" class="w-full border border-success text-success px-6 py-3 rounded-lg hover:bg-success hover:text-white transition-colors font-medium text-center block">
                        <i class="fas fa-copy mr-2"></i>Dupliquer
                    </a>
                </div>

                <!-- Danger Zone -->
                <div class="danger-zone rounded-xl p-6 mt-8">
                    <h3 class="font-bold text-accent mb-4 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Zone de danger
                    </h3>
                    
                    <p class="text-sm text-gray-700 mb-4">
                        La suppression de cet événement est irréversible. 
                        @if($event->current_participants > 0)
                            Les {{ $event->current_participants }} participants seront automatiquement notifiés.
                        @endif
                    </p>
                    
                    <button type="button" onclick="showDeleteModal()" class="w-full bg-accent text-white px-4 py-3 rounded-lg hover:bg-red-600 transition-colors font-medium">
                        <i class="fas fa-trash mr-2"></i>Supprimer l'événement
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 opacity-0 pointer-events-none flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
        <div class="text-center">
            <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Supprimer l'événement ?</h3>
            <p class="text-gray-600 mb-6">
                Cette action est irréversible. 
                @if($event->current_participants > 0)
                    Les {{ $event->current_participants }} participants inscrits seront automatiquement notifiés par email.
                @endif
            </p>
            
            <div class="flex space-x-3">
                <button onclick="hideDeleteModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Annuler
                </button>
                <form action="{{ route('Events.destroy', $event) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-accent text-white rounded-lg hover:bg-red-600 transition-colors">
                        Supprimer définitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setupCharacterCounters();
        setupPriceToggle();
        setupParticipantsSlider();
        setupImageUpload();
        setupCurrentImageRemoval();
    });

    function setupCharacterCounters() {
        const titleInput = document.getElementById('title');
        const descriptionInput = document.getElementById('description');

        function updateCounters() {
            document.getElementById('title-count').textContent = titleInput.value.length;
            document.getElementById('description-count').textContent = descriptionInput.value.length;
        }

        titleInput.addEventListener('input', updateCounters);
        descriptionInput.addEventListener('input', updateCounters);
    }

    function setupPriceToggle() {
        const priceInputDiv = document.getElementById('price-input');
        const priceRadios = document.querySelectorAll('input[name="price_type"]');

        priceRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'paid') {
                    priceInputDiv.classList.remove('hidden');
                    document.getElementById('price').required = true;
                } else {
                    priceInputDiv.classList.add('hidden');
                    document.getElementById('price').required = false;
                    document.getElementById('price').value = '';
                }
            });
        });
    }

    function setupParticipantsSlider() {
        const slider = document.getElementById('max_participants');
        const display = document.getElementById('participants-display');

        slider.addEventListener('input', function() {
            display.textContent = this.value;
        });
    }

    function setupImageUpload() {
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('image-input');
        const dropContent = document.getElementById('drop-content');
        const previewContent = document.getElementById('preview-content');
        const imagePreview = document.getElementById('image-preview');
        const fileName = document.getElementById('file-name');
        const removeBtn = document.getElementById('remove-image');

        dropArea.addEventListener('click', () => fileInput.click());

        dropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropArea.classList.add('dragover');
        });

        dropArea.addEventListener('dragleave', () => {
            dropArea.classList.remove('dragover');
        });

        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            dropArea.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length) handleFile(files[0]);
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length) handleFile(e.target.files[0]);
        });

        removeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            fileInput.value = '';
            dropContent.classList.remove('hidden');
            previewContent.classList.add('hidden');
        });

        function handleFile(file) {
            if (!file.type.startsWith('image/')) {
                alert('Veuillez sélectionner un fichier image.');
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Le fichier est trop volumineux. Maximum 2MB.');
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                imagePreview.src = e.target.result;
                fileName.textContent = file.name;
                dropContent.classList.add('hidden');
                previewContent.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function setupCurrentImageRemoval() {
        const removeBtn = document.getElementById('remove-current-image');
        const removeInput = document.getElementById('remove_current_image_input');

        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                if (confirm('Supprimer l\'image actuelle ?')) {
                    this.closest('div').style.display = 'none';
                    removeInput.value = '1';
                }
            });
        }
    }

    function showDeleteModal() {
        document.getElementById('deleteModal').classList.add('show');
    }

    function hideDeleteModal() {
        document.getElementById('deleteModal').classList.remove('show');
    }

    // Form submission
    document.getElementById('edit-form').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sauvegarde...';
        submitBtn.disabled = true;
    });

    // Dark mode detection
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.documentElement.classList.add('dark');
    }
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
        if (event.matches) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    });

    // Leaflet Map Picker (OpenStreetMap - Free, No API Key Required)
    let map;
    let marker;
    const defaultLat = 36.8065;
    const defaultLng = 10.1815;

    // Initialize map when page loads
    document.addEventListener('DOMContentLoaded', function() {
        initLeafletMap();
    });

    function initLeafletMap() {
        const initialLat = parseFloat(document.getElementById('latitude').value) || defaultLat;
        const initialLng = parseFloat(document.getElementById('longitude').value) || defaultLng;
        
        // Create map
        map = L.map('map-picker').setView([initialLat, initialLng], 13);
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Add marker if coordinates exist
        if (document.getElementById('latitude').value && document.getElementById('longitude').value) {
            addMarker([initialLat, initialLng]);
        }

        // Click event to place marker
        map.on('click', function(e) {
            addMarker([e.latlng.lat, e.latlng.lng]);
            updateCoordinates(e.latlng.lat, e.latlng.lng);
        });
    }

    function addMarker(latlng) {
        // Remove existing marker
        if (marker) {
            map.removeLayer(marker);
        }

        // Add new marker
        marker = L.marker(latlng, {
            draggable: true,
            title: "Emplacement de l'événement"
        }).addTo(map);

        // Update coordinates when marker is dragged
        marker.on('dragend', function(e) {
            const position = e.target.getLatLng();
            updateCoordinates(position.lat, position.lng);
        });
    }

    function updateCoordinates(lat, lng) {
        document.getElementById('latitude').value = lat.toFixed(7);
        document.getElementById('longitude').value = lng.toFixed(7);
        document.getElementById('lat-display').textContent = lat.toFixed(7);
        document.getElementById('lng-display').textContent = lng.toFixed(7);
    }

    // Clear map selection
    document.getElementById('clear-map').addEventListener('click', function() {
        if (marker) {
            map.removeLayer(marker);
            marker = null;
        }
        document.getElementById('latitude').value = '';
        document.getElementById('longitude').value = '';
        document.getElementById('lat-display').textContent = '-';
        document.getElementById('lng-display').textContent = '-';
    });

    // Try to geocode address when location field changes (using Nominatim - OSM free geocoding)
    document.getElementById('location').addEventListener('blur', function() {
        const address = this.value;
        if (address && !document.getElementById('latitude').value) {
            geocodeAddress(address);
        }
    });

    function geocodeAddress(address) {
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address + ', Tunisia')}&limit=1`)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lng = parseFloat(data[0].lon);
                    map.setView([lat, lng], 15);
                    addMarker([lat, lng]);
                    updateCoordinates(lat, lng);
                }
            })
            .catch(error => {
                console.log('Geocoding error:', error);
            });
    }
</script>

<!-- Leaflet CSS and JS for Map Picker (OpenStreetMap - No API Key Required) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
      crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
        crossorigin=""></script>
@endpush