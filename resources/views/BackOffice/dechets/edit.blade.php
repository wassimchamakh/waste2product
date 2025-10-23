@extends('BackOffice.layouts.app')

@section('title', 'Modifier Déchet')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-10">
    <div class="container mx-auto px-4 max-w-4xl">
        
        <!-- Header with Back Button -->
        <div class="mb-6">
            <a href="{{ route('admin.dechets.index') }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-800 transition-colors duration-150 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour à la liste
            </a>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Modifier le Déchet</h1>
                <p class="text-gray-600">Mettez à jour les informations du déchet.</p>
            </div>
        </div>

        <!-- Form Section -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <form action="{{ route('admin.dechets.update', $dechet->id) }}" 
                  method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Titre -->
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                        Titre <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" 
                           value="{{ old('title', $dechet->title) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                                  focus:ring-2 focus:ring-green-500 focus:border-transparent 
                                  transition-all duration-200"
                           placeholder="Entrez le titre du déchet" required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                                     focus:ring-2 focus:ring-green-500 focus:border-transparent 
                                     transition-all duration-200 resize-none"
                              placeholder="Décrivez le déchet en détail..." required>{{ old('description', $dechet->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quantity & Location Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="quantity" class="block text-sm font-semibold text-gray-700 mb-2">
                            Quantité <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="quantity" id="quantity" 
                               value="{{ old('quantity', $dechet->quantity) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                                      focus:ring-2 focus:ring-green-500 focus:border-transparent 
                                      transition-all duration-200" required>
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">
                            Localisation (Gouvernorat) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <select name="location" 
                                   id="location" 
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all appearance-none bg-white"
                                   required>
                                <option value="">Sélectionnez un gouvernorat</option>
                                <option value="Tunis" {{ old('location', $dechet->location) == 'Tunis' ? 'selected' : '' }}>Tunis</option>
                                <option value="Ariana" {{ old('location', $dechet->location) == 'Ariana' ? 'selected' : '' }}>Ariana</option>
                                <option value="Ben Arous" {{ old('location', $dechet->location) == 'Ben Arous' ? 'selected' : '' }}>Ben Arous</option>
                                <option value="Manouba" {{ old('location', $dechet->location) == 'Manouba' ? 'selected' : '' }}>Manouba</option>
                                <option value="Nabeul" {{ old('location', $dechet->location) == 'Nabeul' ? 'selected' : '' }}>Nabeul</option>
                                <option value="Zaghouan" {{ old('location', $dechet->location) == 'Zaghouan' ? 'selected' : '' }}>Zaghouan</option>
                                <option value="Bizerte" {{ old('location', $dechet->location) == 'Bizerte' ? 'selected' : '' }}>Bizerte</option>
                                <option value="Béja" {{ old('location', $dechet->location) == 'Béja' ? 'selected' : '' }}>Béja</option>
                                <option value="Jendouba" {{ old('location', $dechet->location) == 'Jendouba' ? 'selected' : '' }}>Jendouba</option>
                                <option value="Kef" {{ old('location', $dechet->location) == 'Kef' ? 'selected' : '' }}>Kef</option>
                                <option value="Siliana" {{ old('location', $dechet->location) == 'Siliana' ? 'selected' : '' }}>Siliana</option>
                                <option value="Sousse" {{ old('location', $dechet->location) == 'Sousse' ? 'selected' : '' }}>Sousse</option>
                                <option value="Monastir" {{ old('location', $dechet->location) == 'Monastir' ? 'selected' : '' }}>Monastir</option>
                                <option value="Mahdia" {{ old('location', $dechet->location) == 'Mahdia' ? 'selected' : '' }}>Mahdia</option>
                                <option value="Sfax" {{ old('location', $dechet->location) == 'Sfax' ? 'selected' : '' }}>Sfax</option>
                                <option value="Kairouan" {{ old('location', $dechet->location) == 'Kairouan' ? 'selected' : '' }}>Kairouan</option>
                                <option value="Kasserine" {{ old('location', $dechet->location) == 'Kasserine' ? 'selected' : '' }}>Kasserine</option>
                                <option value="Sidi Bouzid" {{ old('location', $dechet->location) == 'Sidi Bouzid' ? 'selected' : '' }}>Sidi Bouzid</option>
                                <option value="Gabès" {{ old('location', $dechet->location) == 'Gabès' ? 'selected' : '' }}>Gabès</option>
                                <option value="Medenine" {{ old('location', $dechet->location) == 'Medenine' ? 'selected' : '' }}>Medenine</option>
                                <option value="Tataouine" {{ old('location', $dechet->location) == 'Tataouine' ? 'selected' : '' }}>Tataouine</option>
                                <option value="Gafsa" {{ old('location', $dechet->location) == 'Gafsa' ? 'selected' : '' }}>Gafsa</option>
                                <option value="Tozeur" {{ old('location', $dechet->location) == 'Tozeur' ? 'selected' : '' }}>Tozeur</option>
                                <option value="Kebili" {{ old('location', $dechet->location) == 'Kebili' ? 'selected' : '' }}>Kebili</option>
                            </select>
                        </div>
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Category Selection (Select Dropdown) -->
                <div>
                    <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Catégorie <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="category_id" 
                               id="category_id" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all appearance-none bg-white"
                               required>
                            <option value="">Sélectionnez une catégorie</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" 
                                        data-icon="{{ $category->icon }}" 
                                        data-color="{{ $category->color }}"
                                        {{ old('category_id', $dechet->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} @if($category->description) - {{ Str::limit($category->description, 50) }} @endif
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Sélectionnez la catégorie de déchets appropriée</p>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Photo -->
                <div>
                    <label for="photo" class="block text-sm font-semibold text-gray-700 mb-2">
                        Photo actuelle
                    </label>
                    <div class="flex items-center gap-4">
                        <img src="{{ $dechet->getPhotoUrlAttribute() }}" 
                             alt="Current Photo" 
                             class="w-32 h-32 object-cover rounded-lg shadow">
                        <input type="file" name="photo" id="photo"
                               class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg cursor-pointer focus:outline-none">
                    </div>
                    @error('photo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" 
                            class="w-full bg-green-600 text-white px-6 py-3 rounded-lg 
                                   font-semibold shadow hover:bg-green-700 
                                   transition-colors duration-200">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
