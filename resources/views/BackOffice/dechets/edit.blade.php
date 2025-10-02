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
                            Localisation
                        </label>
                        <input type="text" name="location" id="location" 
                               value="{{ old('location', $dechet->location) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                                      focus:ring-2 focus:ring-green-500 focus:border-transparent 
                                      transition-all duration-200">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Catégorie -->
                <div>
                    <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Catégorie
                    </label>
                    <select name="category_id" id="category_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                                   focus:ring-2 focus:ring-green-500 focus:border-transparent 
                                   transition-all duration-200">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ $dechet->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
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
