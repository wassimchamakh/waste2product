@extends('FrontOffice.layout1.app')

@section('title', 'Créer un Événement - Waste2Product')

@push('styles')
<style>
    .wizard-step {
        transition: all 0.3s ease;
        display: none;
    }
    .wizard-step.active {
        display: block;
        animation: fadeInUp 0.5s ease;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .step-indicator {
        transition: all 0.3s ease;
    }
    .step-indicator.completed {
        background-color: #06D6A0;
        color: white;
    }
    .step-indicator.active {
        background-color: #2E7D47;
        color: white;
    }
    
    .progress-bar {
        transition: width 0.5s ease;
    }
    
    .preview-card {
        background: linear-gradient(135deg, #2E7D47 0%, #06D6A0 100%);
    }
    
    .drag-drop-area {
        transition: all 0.3s ease;
        border: 2px dashed #d1d5db;
    }
    .drag-drop-area.dragover {
        border-color: #2E7D47;
        background-color: #f0fdf4;
    }
    
    .char-counter {
        font-size: 0.75rem;
        color: #6b7280;
    }
    
    .input-error {
        border-color: #ef4444;
        background-color: #fef2f2;
    }
    
    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
    }
</style>
@endpush

@section('content')
<!-- Header -->
<div class="bg-gradient-to-r from-primary to-success text-white py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">
                    {{ $duplicateEvent ? 'Dupliquer un Événement' : 'Créer un Événement' }}
                </h1>
                <p class="opacity-90">Partagez votre passion pour l'économie circulaire</p>
            </div>
            <a href="{{ route('Events.index') }}" class="bg-white/20 text-white px-4 py-2 rounded-lg hover:bg-white/30 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Progress Indicator -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-4">
                <div class="step-indicator active w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center font-bold text-sm" data-step="1">1</div>
                <div class="hidden sm:block text-sm font-medium text-gray-600">Informations générales</div>
            </div>
            <div class="flex-1 mx-4">
                <div class="h-2 bg-gray-200 rounded-full">
                    <div class="progress-bar h-2 bg-primary rounded-full" style="width: 25%"></div>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="step-indicator w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center font-bold text-sm" data-step="2">2</div>
                <div class="hidden sm:block text-sm font-medium text-gray-600">Date et lieu</div>
            </div>
            <div class="flex-1 mx-4">
                <div class="h-2 bg-gray-200 rounded-full">
                    <div class="progress-bar h-2 bg-gray-200 rounded-full" style="width: 0%"></div>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="step-indicator w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center font-bold text-sm" data-step="3">3</div>
                <div class="hidden sm:block text-sm font-medium text-gray-600">Participants et prix</div>
            </div>
            <div class="flex-1 mx-4">
                <div class="h-2 bg-gray-200 rounded-full">
                    <div class="progress-bar h-2 bg-gray-200 rounded-full" style="width: 0%"></div>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="step-indicator w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center font-bold text-sm" data-step="4">4</div>
                <div class="hidden sm:block text-sm font-medium text-gray-600">Publication</div>
            </div>
        </div>
        
        <!-- Mobile Progress -->
        <div class="sm:hidden text-center">
            <span class="text-sm font-medium text-gray-600">
                Étape <span id="current-step-mobile">1</span> sur 4
            </span>
        </div>
    </div>

    <!-- Form -->
    <form id="event-form" action="{{ route('Events.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Step 1: General Information -->
        <div class="wizard-step active" data-step="1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">
                    <i class="fas fa-info-circle mr-3 text-primary"></i>
                    Informations générales
                </h2>
                
                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Titre de l'événement <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" required
                           value="{{ $duplicateEvent->title ?? old('title') }}"
                           class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Ex: Repair Café Tunis - Réparons Ensemble"
                           maxlength="255">
                    <div class="char-counter mt-1 text-right">
                        <span id="title-count">0</span>/255 caractères
                    </div>
                    <div class="error-message mt-1" id="title-error"></div>
                </div>

                <!-- Type -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Type d'événement <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($types as $key => $type)
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="{{ $key }}" 
                                   {{ ($duplicateEvent->type ?? old('type')) == $key ? 'checked' : '' }}
                                   class="sr-only peer" required>
                            <div class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-primary peer-checked:bg-primary/5 hover:border-gray-300 transition-all">
                                <div class="flex items-center space-x-3">
                                    <i class="{{ $type['icon'] }} text-xl text-primary"></i>
                                    <div>
                                        <div class="font-medium">{{ $type['label'] }}</div>
                                        <div class="text-sm text-gray-500">
                                            @if($key == 'workshop')
                                                Ateliers pratiques et créatifs
                                            @elseif($key == 'collection')
                                                Collecte et tri de déchets
                                            @elseif($key == 'training')
                                                Formation théorique et pratique
                                            @else
                                                Réparation collaborative
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    <div class="error-message mt-1" id="type-error"></div>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Description complète <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="6" required
                              class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                              placeholder="Décrivez votre événement en détail. Que vont apprendre les participants ? Quel matériel faut-il apporter ?"
                              minlength="100">{{ $duplicateEvent->description ?? old('description') }}</textarea>
                    <div class="flex justify-between mt-1">
                        <div class="char-counter">
                            Minimum 100 caractères - <span id="description-count">0</span> caractères
                        </div>
                    </div>
                    <div class="error-message mt-1" id="description-error"></div>
                </div>

                <!-- Navigation -->
                <div class="flex justify-between">
                    <div></div>
                    <button type="button" class="next-step bg-primary text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors font-medium">
                        Suivant <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 2: Date and Location -->
        <div class="wizard-step" data-step="2">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">
                    <i class="fas fa-calendar-map mr-3 text-primary"></i>
                    Date et lieu
                </h2>

                <!-- Date Start -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="date_start" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Date et heure de début <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="date_start" id="date_start" required
                               value="{{ $duplicateEvent ? \Carbon\Carbon::parse($duplicateEvent->date_start)->format('Y-m-d\TH:i') : old('date_start') }}"
                               min="{{ now()->format('Y-m-d\TH:i') }}"
                               class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <div class="error-message mt-1" id="date_start-error"></div>
                    </div>

                    <div>
                        <label for="date_end" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Date et heure de fin <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="date_end" id="date_end" required
                               value="{{ $duplicateEvent ? \Carbon\Carbon::parse($duplicateEvent->date_end)->format('Y-m-d\TH:i') : old('date_end') }}"
                               class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <div class="error-message mt-1" id="date_end-error"></div>
                    </div>
                </div>

                <!-- Duration Display -->
                <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                        <i class="fas fa-clock mr-2"></i>
                        <span>Durée de l'événement : </span>
                        <span id="duration-display" class="font-medium ml-1">Non calculée</span>
                    </div>
                </div>

                <!-- City -->
                <div class="mb-6">
                    <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Ville <span class="text-red-500">*</span>
                    </label>
                    <select name="city" id="city" required
                            class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Sélectionnez une ville</option>
                        @foreach($cities as $city)
                        <option value="{{ $city }}" {{ ($duplicateEvent->city ?? old('city')) == $city ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $city)) }}
                        </option>
                        @endforeach
                    </select>
                    <div class="error-message mt-1" id="city-error"></div>
                </div>

                <!-- Location -->
                <div class="mb-6">
                    <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Adresse complète <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="location" id="location" required
                           value="{{ $duplicateEvent->location ?? old('location') }}"
                           class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Ex: Fab Lab ENSI, Avenue Taha Hussein, La Manouba">
                    <div class="error-message mt-1" id="location-error"></div>
                </div>

                <!-- Google Maps Link -->
                <div class="mb-6">
                    <label for="maps_link" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Lien Google Maps (optionnel)
                    </label>
                    <input type="url" name="maps_link" id="maps_link"
                           value="{{ old('maps_link') }}"
                           class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="https://maps.google.com/...">
                    <div class="text-sm text-gray-500 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>
                        Facilitera l'accès pour les participants
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex justify-between">
                    <button type="button" class="prev-step border border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Précédent
                    </button>
                    <button type="button" class="next-step bg-primary text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors font-medium">
                        Suivant <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 3: Participants and Price -->
        <div class="wizard-step" data-step="3">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">
                    <i class="fas fa-users-cog mr-3 text-primary"></i>
                    Participants et prix
                </h2>

                <!-- Max Participants -->
                <div class="mb-6">
                    <label for="max_participants" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nombre maximum de participants <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="range" name="max_participants" id="max_participants" 
                               min="5" max="100" step="5" 
                               value="{{ $duplicateEvent->max_participants ?? old('max_participants', 25) }}"
                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span>5</span>
                            <span>25</span>
                            <span>50</span>
                            <span>75</span>
                            <span>100</span>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <span class="text-2xl font-bold text-primary" id="participants-display">25</span>
                        <span class="text-gray-600"> participants maximum</span>
                    </div>
                </div>

                <!-- Price -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Prix de participation <span class="text-red-500">*</span>
                    </label>
                    
                    <div class="space-y-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="price_type" value="free" 
                                   {{ ($duplicateEvent && $duplicateEvent->price == 0) || old('price_type') == 'free' ? 'checked' : '' }}
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
                                   {{ ($duplicateEvent && $duplicateEvent->price > 0) || old('price_type') == 'paid' ? 'checked' : '' }}
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

                    <div id="price-input" class="mt-4 {{ ($duplicateEvent && $duplicateEvent->price > 0) || old('price_type') == 'paid' ? '' : 'hidden' }}">
                        <div class="relative">
                            <input type="number" name="price" id="price" min="0" step="0.01"
                                   value="{{ $duplicateEvent->price ?? old('price') }}"
                                   class="w-full pl-4 pr-12 py-3 text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="0.00">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <span class="text-gray-500 font-medium">DT</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Options -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Options avancées
                    </label>
                    
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="waiting_list" value="1" 
                                   {{ old('waiting_list') ? 'checked' : '' }}
                                   class="mr-3 text-primary focus:ring-primary">
                            <span>Créer une liste d'attente si l'événement est complet</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" name="manual_approval" value="1"
                                   {{ old('manual_approval') ? 'checked' : '' }}
                                   class="mr-3 text-primary focus:ring-primary">
                            <span>Confirmation manuelle des inscriptions</span>
                        </label>

                        <div class="border border-gray-200 rounded-lg p-4">
                            <label class="flex items-center mb-2">
                                <input type="checkbox" name="age_limit_enabled" value="1"
                                       {{ old('age_limit_enabled') ? 'checked' : '' }}
                                       class="mr-3 text-primary focus:ring-primary">
                                <span>Limite d'âge</span>
                            </label>
                            <div id="age-limits" class="grid grid-cols-2 gap-4 {{ old('age_limit_enabled') ? '' : 'hidden' }}">
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Âge minimum</label>
                                    <input type="number" name="min_age" min="0" max="100"
                                           value="{{ old('min_age') }}"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Âge maximum</label>
                                    <input type="number" name="max_age" min="0" max="100"
                                           value="{{ old('max_age') }}"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex justify-between">
                    <button type="button" class="prev-step border border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Précédent
                    </button>
                    <button type="button" class="next-step bg-primary text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors font-medium">
                        Suivant <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 4: Media and Publication -->
        <div class="wizard-step" data-step="4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">
                    <i class="fas fa-images mr-3 text-primary"></i>
                    Média et publication
                </h2>

                <!-- Image Upload -->
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Image de l'événement
                    </label>
                    
                    <div class="drag-drop-area border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-primary transition-colors cursor-pointer" id="drop-area">
                        <div id="drop-content">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                            <p class="text-lg font-medium text-gray-600 mb-2">Glissez votre image ici</p>
                            <p class="text-sm text-gray-500 mb-4">ou cliquez pour sélectionner un fichier</p>
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

                <!-- Event Preview -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium mb-4">Aperçu de votre événement</h3>
                    <div class="preview-card text-white rounded-xl p-6" id="event-preview">
                        <div class="flex justify-between items-start mb-4">
                            <span class="bg-white/20 px-3 py-1 rounded-full text-sm" id="preview-type">
                                🛠️ Workshop
                            </span>
                            <span class="bg-white/20 px-3 py-1 rounded-full text-sm" id="preview-price">
                                Gratuit
                            </span>
                        </div>
                        <h3 class="text-xl font-bold mb-3" id="preview-title">Titre de votre événement</h3>
                        <div class="space-y-2 text-sm opacity-90">
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-2"></i>
                                <span id="preview-date">Date non définie</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <span id="preview-location">Lieu non défini</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-users mr-2"></i>
                                <span>0/<span id="preview-participants">25</span> inscrits</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Publication Status -->
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Statut de publication <span class="text-red-500">*</span>
                    </label>
                    
                    <div class="space-y-4">
                        <label class="flex items-start cursor-pointer">
                            <input type="radio" name="status" value="draft" 
                                   {{ old('status', 'draft') == 'draft' ? 'checked' : '' }}
                                   class="mr-3 mt-1 text-primary focus:ring-primary">
                            <div>
                                <div class="font-medium">Brouillon</div>
                                <div class="text-sm text-gray-500">Visible uniquement pour vous. Vous pourrez le publier plus tard.</div>
                            </div>
                        </label>

                        <label class="flex items-start cursor-pointer">
                            <input type="radio" name="status" value="published"
                                   {{ old('status') == 'published' ? 'checked' : '' }}
                                   class="mr-3 mt-1 text-primary focus:ring-primary">
                            <div>
                                <div class="font-medium">Publier immédiatement</div>
                                <div class="text-sm text-gray-500">L'événement sera visible par tous les utilisateurs.</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Terms -->
                <div class="mb-8">
                    <label class="flex items-start cursor-pointer">
                        <input type="checkbox" name="accept_terms" value="1" required
                               class="mr-3 mt-1 text-primary focus:ring-primary">
                        <div>
                            <span class="font-medium">J'accepte les conditions d'organisation <span class="text-red-500">*</span></span>
                            <div class="text-sm text-gray-500 mt-1">
                                En créant cet événement, je m'engage à respecter les règles de la communauté et à assurer un environnement sûr pour tous les participants.
                            </div>
                        </div>
                    </label>
                    <div class="error-message mt-1" id="accept_terms-error"></div>
                </div>

                <!-- Navigation -->
                <div class="flex justify-between">
                    <button type="button" class="prev-step border border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Précédent
                    </button>
                    <button type="submit" class="bg-primary text-white px-8 py-3 rounded-lg hover:bg-green-600 transition-colors font-medium">
                        <i class="fas fa-calendar-plus mr-2"></i>
                        {{ $duplicateEvent ? 'Dupliquer l\'événement' : 'Créer l\'événement' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Auto-save notification -->
<div id="autosave-notification" class="fixed bottom-4 left-4 bg-white border border-gray-200 rounded-lg p-3 shadow-lg hidden">
    <div class="flex items-center text-sm">
        <i class="fas fa-save text-success mr-2"></i>
        <span>Brouillon sauvegardé automatiquement</span>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Form data and current step
    let currentStep = 1;
    const totalSteps = 4;
    let formData = {};

    // Types configuration
    const typesConfig = @json($types);

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        updateCharacterCounters();
        updateProgressBar();
        updatePreview();
        setupEventListeners();
        
        // Auto-save every 30 seconds
        setInterval(autoSave, 30000);
    });

    // Setup event listeners
    function setupEventListeners() {
        // Character counters
        document.getElementById('title').addEventListener('input', updateCharacterCounters);
        document.getElementById('description').addEventListener('input', updateCharacterCounters);
        
        // Preview updates
        document.getElementById('title').addEventListener('input', updatePreview);
        document.querySelectorAll('input[name="type"]').forEach(input => {
            input.addEventListener('change', updatePreview);
        });
        document.getElementById('date_start').addEventListener('change', updatePreview);
        document.getElementById('location').addEventListener('change', updatePreview);
        document.getElementById('max_participants').addEventListener('input', updatePreview);
        document.querySelectorAll('input[name="price_type"]').forEach(input => {
            input.addEventListener('change', updatePreview);
        });
        document.getElementById('price').addEventListener('input', updatePreview);
        
        // Duration calculation
        document.getElementById('date_start').addEventListener('change', calculateDuration);
        document.getElementById('date_end').addEventListener('change', calculateDuration);
        
        // Price type toggle
        document.querySelectorAll('input[name="price_type"]').forEach(input => {
            input.addEventListener('change', togglePriceInput);
        });
        
        // Age limit toggle
        document.querySelector('input[name="age_limit_enabled"]').addEventListener('change', toggleAgeLimit);
        
        // Participants slider
        document.getElementById('max_participants').addEventListener('input', updateParticipantsDisplay);
        
        // Navigation buttons
        document.querySelectorAll('.next-step').forEach(btn => {
            btn.addEventListener('click', nextStep);
        });
        document.querySelectorAll('.prev-step').forEach(btn => {
            btn.addEventListener('click', prevStep);
        });
        
        // Image upload
        setupImageUpload();
        
        // Form submission
        document.getElementById('event-form').addEventListener('submit', validateAndSubmit);
    }

    // Update character counters
    function updateCharacterCounters() {
        const title = document.getElementById('title').value;
        const description = document.getElementById('description').value;
        
        document.getElementById('title-count').textContent = title.length;
        document.getElementById('description-count').textContent = description.length;
        
        // Update color based on length
        const titleCounter = document.getElementById('title-count');
        const descCounter = document.getElementById('description-count');
        
        titleCounter.className = title.length > 200 ? 'text-warning' : 'text-gray-500';
        descCounter.className = description.length < 100 ? 'text-accent' : 'text-gray-500';
    }

    // Calculate duration
    function calculateDuration() {
        const startDate = new Date(document.getElementById('date_start').value);
        const endDate = new Date(document.getElementById('date_end').value);
        
        if (startDate && endDate && endDate > startDate) {
            const diffMs = endDate - startDate;
            const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
            const diffMinutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
            
            let durationText = '';
            if (diffHours > 0) {
                durationText = `${diffHours}h`;
                if (diffMinutes > 0) durationText += ` ${diffMinutes}min`;
            } else {
                durationText = `${diffMinutes}min`;
            }
            
            document.getElementById('duration-display').textContent = durationText;
        } else {
            document.getElementById('duration-display').textContent = 'Non calculée';
        }
    }

    // Toggle price input
    function togglePriceInput() {
        const priceType = document.querySelector('input[name="price_type"]:checked').value;
        const priceInput = document.getElementById('price-input');
        
        if (priceType === 'paid') {
            priceInput.classList.remove('hidden');
            document.getElementById('price').required = true;
        } else {
            priceInput.classList.add('hidden');
            document.getElementById('price').required = false;
            document.getElementById('price').value = '';
        }
        
        updatePreview();
    }

    // Toggle age limit
    function toggleAgeLimit() {
        const enabled = document.querySelector('input[name="age_limit_enabled"]').checked;
        const ageLimits = document.getElementById('age-limits');
        
        if (enabled) {
            ageLimits.classList.remove('hidden');
        } else {
            ageLimits.classList.add('hidden');
        }
    }

    // Update participants display
    function updateParticipantsDisplay() {
        const value = document.getElementById('max_participants').value;
        document.getElementById('participants-display').textContent = value;
        updatePreview();
    }

    // Update preview
    function updatePreview() {
        const title = document.getElementById('title').value || 'Titre de votre événement';
        const typeRadio = document.querySelector('input[name="type"]:checked');
        const type = typeRadio ? typeRadio.value : 'workshop';
        const dateStart = document.getElementById('date_start').value;
        const location = document.getElementById('location').value || 'Lieu non défini';
        const maxParticipants = document.getElementById('max_participants').value;
        const priceType = document.querySelector('input[name="price_type"]:checked');
        const price = document.getElementById('price').value;
        
        // Update preview elements
        document.getElementById('preview-title').textContent = title;
        document.getElementById('preview-type').textContent = typesConfig[type]?.label || '🛠️ Workshop';
        document.getElementById('preview-location').textContent = location;
        document.getElementById('preview-participants').textContent = maxParticipants;
        
        // Update date
        if (dateStart) {
            const date = new Date(dateStart);
            const formatted = date.toLocaleDateString('fr-FR', {
                weekday: 'short',
                day: 'numeric',
                month: 'short',
                hour: '2-digit',
                minute: '2-digit'
            });
            document.getElementById('preview-date').textContent = formatted;
        }
        
        // Update price
        if (priceType) {
            if (priceType.value === 'free') {
                document.getElementById('preview-price').textContent = 'Gratuit';
            } else if (price) {
                document.getElementById('preview-price').textContent = `${price} DT`;
            } else {
                document.getElementById('preview-price').textContent = 'Prix à définir';
            }
        }
    }

    // Setup image upload
    function setupImageUpload() {
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('image-input');
        const dropContent = document.getElementById('drop-content');
        const previewContent = document.getElementById('preview-content');
        const imagePreview = document.getElementById('image-preview');
        const fileName = document.getElementById('file-name');
        const removeBtn = document.getElementById('remove-image');

        // Click to select file
        dropArea.addEventListener('click', () => fileInput.click());

        // Drag and drop
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

        // File input change
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length) handleFile(e.target.files[0]);
        });

        // Remove image
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

    // Navigation functions
    function nextStep() {
        if (validateStep(currentStep)) {
            currentStep++;
            showStep(currentStep);
            updateProgressBar();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    function prevStep() {
        currentStep--;
        showStep(currentStep);
        updateProgressBar();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function showStep(step) {
        document.querySelectorAll('.wizard-step').forEach(el => {
            el.classList.remove('active');
        });
        document.querySelector(`.wizard-step[data-step="${step}"]`).classList.add('active');
        
        // Update mobile step indicator
        document.getElementById('current-step-mobile').textContent = step;
    }

    function updateProgressBar() {
        const progressPercent = (currentStep / totalSteps) * 100;
        
        // Update step indicators
        document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
            const stepNum = index + 1;
            if (stepNum < currentStep) {
                indicator.classList.add('completed');
                indicator.classList.remove('active');
            } else if (stepNum === currentStep) {
                indicator.classList.add('active');
                indicator.classList.remove('completed');
            } else {
                indicator.classList.remove('active', 'completed');
            }
        });

        // Update progress bars
        document.querySelectorAll('.progress-bar').forEach((bar, index) => {
            const stepProgressPercent = Math.max(0, Math.min(100, ((currentStep - index - 1) / 1) * 100));
            bar.style.width = stepProgressPercent + '%';
            
            if (stepProgressPercent > 0) {
                bar.classList.remove('bg-gray-200');
                bar.classList.add('bg-primary');
            } else {
                bar.classList.remove('bg-primary');
                bar.classList.add('bg-gray-200');
            }
        });
    }

    // Validation functions
    function validateStep(step) {
        clearErrors();
        let isValid = true;

        if (step === 1) {
            // Validate general information
            const title = document.getElementById('title').value.trim();
            const type = document.querySelector('input[name="type"]:checked');
            const description = document.getElementById('description').value.trim();

            if (!title) {
                showError('title', 'Le titre est requis');
                isValid = false;
            }

            if (!type) {
                showError('type', 'Veuillez sélectionner un type d\'événement');
                isValid = false;
            }

            if (description.length < 100) {
                showError('description', 'La description doit contenir au moins 100 caractères');
                isValid = false;
            }

        } else if (step === 2) {
            // Validate date and location
            const dateStart = document.getElementById('date_start').value;
            const dateEnd = document.getElementById('date_end').value;
            const city = document.getElementById('city').value;
            const location = document.getElementById('location').value.trim();

            if (!dateStart) {
                showError('date_start', 'La date de début est requise');
                isValid = false;
            }

            if (!dateEnd) {
                showError('date_end', 'La date de fin est requise');
                isValid = false;
            }

            if (dateStart && dateEnd && new Date(dateEnd) <= new Date(dateStart)) {
                showError('date_end', 'La date de fin doit être après la date de début');
                isValid = false;
            }

            if (!city) {
                showError('city', 'Veuillez sélectionner une ville');
                isValid = false;
            }

            if (!location) {
                showError('location', 'L\'adresse est requise');
                isValid = false;
            }

        } else if (step === 4) {
            // Validate final step
            const acceptTerms = document.querySelector('input[name="accept_terms"]').checked;

            if (!acceptTerms) {
                showError('accept_terms', 'Vous devez accepter les conditions');
                isValid = false;
            }
        }

        return isValid;
    }

    function validateAndSubmit(e) {
        if (!validateStep(4)) {
            e.preventDefault();
            return false;
        }

        // Show loading state
        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Création en cours...';
        submitBtn.disabled = true;

        // Allow form submission to proceed
        return true;
    }

    function showError(fieldId, message) {
        const field = document.getElementById(fieldId);
        const errorDiv = document.getElementById(fieldId + '-error');
        
        if (field) {
            field.classList.add('input-error');
        }
        
        if (errorDiv) {
            errorDiv.textContent = message;
        }
    }

    function clearErrors() {
        document.querySelectorAll('.input-error').forEach(el => {
            el.classList.remove('input-error');
        });
        document.querySelectorAll('.error-message').forEach(el => {
            el.textContent = '';
        });
    }

    // Auto-save functionality
    function autoSave() {
        const formData = new FormData(document.getElementById('event-form'));
        
        // Show notification
        const notification = document.getElementById('autosave-notification');
        notification.classList.remove('hidden');
        setTimeout(() => {
            notification.classList.add('hidden');
        }, 3000);
        
        // Here you would typically send the data to the server
        console.log('Auto-saving draft...');
    }

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
</script>
@endpush