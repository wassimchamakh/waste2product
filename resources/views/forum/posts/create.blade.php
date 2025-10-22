@extends(auth()->check() ? 'FrontOffice.layout1.app' : 'FrontOffice.layout.app')

@section('title', 'Créer un Post - Forum Waste2Product')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('forum.index') }}" 
               class="inline-flex items-center text-primary hover:text-primary/80 transition font-semibold mb-4">
                <i class="fas fa-arrow-left mr-2"></i> Retour au forum
            </a>
            <h1 class="text-3xl font-bold text-gray-800">✍️ Créer un nouveau post</h1>
            <p class="text-gray-600 mt-2">Partagez vos idées, questions ou conseils avec la communauté</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <form action="{{ route('forum.posts.store') }}" method="POST">
                @csrf

                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                        Titre du post <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('title') border-red-500 @enderror"
                           placeholder="Un titre clair et descriptif (minimum 10 caractères)"
                           required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        <span id="title-count">0</span>/255 caractères
                    </p>
                </div>

                <!-- Body -->
                <div class="mb-6">
                    <label for="body" class="block text-sm font-semibold text-gray-700 mb-2">
                        Contenu <span class="text-red-500">*</span>
                    </label>
                    <textarea name="body" 
                              id="body" 
                              rows="12"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent @error('body') border-red-500 @enderror"
                              placeholder="Décrivez votre question ou partagez votre idée en détail (minimum 20 caractères)"
                              required>{{ old('body') }}</textarea>
                    @error('body')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        <span id="body-count">0</span>/10000 caractères
                    </p>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        Statut de publication
                    </label>
                    <select name="status" 
                            id="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                            📢 Publié (visible par tous)
                        </option>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>
                            📝 Brouillon (non visible)
                        </option>
                    </select>
                </div>

                <!-- Guidelines -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                    <h3 class="font-semibold text-blue-800 mb-2">📜 Conseils pour un bon post</h3>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>✓ Utilisez un titre clair et spécifique</li>
                        <li>✓ Fournissez suffisamment de détails dans votre question</li>
                        <li>✓ Soyez respectueux envers les autres membres</li>
                        <li>✓ Évitez le spam et le contenu inapproprié</li>
                    </ul>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-4">
                    <a href="{{ route('forum.index') }}" 
                       class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-semibold">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary/90 transition font-semibold">
                        <i class="fas fa-paper-plane mr-2"></i> Publier le post
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Title character counter
    const titleInput = document.getElementById('title');
    const titleCount = document.getElementById('title-count');
    
    titleInput.addEventListener('input', function() {
        titleCount.textContent = this.value.length;
    });
    
    // Body character counter
    const bodyInput = document.getElementById('body');
    const bodyCount = document.getElementById('body-count');
    
    bodyInput.addEventListener('input', function() {
        bodyCount.textContent = this.value.length;
    });
    
    // Initialize counts
    titleCount.textContent = titleInput.value.length;
    bodyCount.textContent = bodyInput.value.length;
});
</script>
@endpush
@endsection
