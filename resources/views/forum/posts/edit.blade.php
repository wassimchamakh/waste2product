@extends(auth()->check() ? 'FrontOffice.layout1.app' : 'FrontOffice.layout.app')

@section('title', 'Modifier - ' . $post->title)

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('forum.posts.show', $post) }}" 
               class="inline-flex items-center text-primary hover:text-primary/80 transition font-semibold mb-4">
                <i class="fas fa-arrow-left mr-2"></i> Retour au post
            </a>
            <h1 class="text-3xl font-bold text-gray-800">✏️ Modifier le post</h1>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <form action="{{ route('forum.posts.update', $post) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                        Titre du post <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title', $post->title) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Body -->
                <div class="mb-6">
                    <label for="body" class="block text-sm font-semibold text-gray-700 mb-2">
                        Contenu <span class="text-red-500">*</span>
                    </label>
                    <textarea name="body" 
                              id="body" 
                              rows="12"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                              required>{{ old('body', $post->body) }}</textarea>
                    @error('body')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        Statut
                    </label>
                    <select name="status" 
                            id="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>
                            📢 Publié
                        </option>
                        <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>
                            📝 Brouillon
                        </option>
                        <option value="archived" {{ old('status', $post->status) == 'archived' ? 'selected' : '' }}>
                            📦 Archivé
                        </option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-4">
                    <a href="{{ route('forum.posts.show', $post) }}" 
                       class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-semibold">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary/90 transition font-semibold">
                        <i class="fas fa-save mr-2"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
