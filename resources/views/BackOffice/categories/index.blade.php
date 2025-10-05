@extends('BackOffice.layouts.app')

@section('title', 'Gestion des Catégories')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Catégories de Déchets</h1>
            <p class="text-gray-600 mt-1">Gérez les catégories utilisées pour classifier les déchets</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-primary to-primary-dark text-white rounded-xl font-semibold hover:shadow-lg hover:scale-105 transition-all duration-300">
            <i class="fas fa-plus"></i>
            <span>Nouvelle Catégorie</span>
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                <p class="text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Catégories</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $categories->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-layer-group text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Déchets Totaux</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $categories->sum('dechets_count') }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-recycle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Avec Images</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $categories->whereNotNull('image')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-image text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $category)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <!-- Category Header with Image -->
                @if($category->image)
                    <div class="h-40 bg-gradient-to-br from-gray-100 to-gray-200 relative overflow-hidden">
                        <img src="{{ asset('storage/categories/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: {{ $category->color }}20; color: {{ $category->color }}">
                                    <i class="{{ $category->icon }} text-lg"></i>
                                </div>
                                <h3 class="text-white font-bold text-lg">{{ $category->name }}</h3>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="h-40 flex items-center justify-center" style="background: {{ $category->color }}10">
                        <div class="w-20 h-20 rounded-full flex items-center justify-center" style="background: {{ $category->color }}20; color: {{ $category->color }}">
                            <i class="{{ $category->icon }} text-4xl"></i>
                        </div>
                    </div>
                @endif

                <!-- Category Content -->
                <div class="p-5">
                    @if(!$category->image)
                        <h3 class="font-bold text-xl text-gray-900 mb-2">{{ $category->name }}</h3>
                    @endif

                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $category->description }}</p>

                    <!-- Stats -->
                    <div class="flex items-center gap-4 mb-4 pb-4 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-recycle text-gray-400 text-sm"></i>
                            <span class="text-sm text-gray-600">{{ $category->dechets_count }} déchets</span>
                        </div>
                        @if($category->certifications && count($category->certifications) > 0)
                            <div class="flex items-center gap-2">
                                <i class="fas fa-certificate text-yellow-500 text-sm"></i>
                                <span class="text-sm text-gray-600">{{ count($category->certifications) }} certif.</span>
                            </div>
                        @endif
                    </div>

                    <!-- Certifications -->
                    @if($category->certifications && count($category->certifications) > 0)
                        <div class="mb-4">
                            <p class="text-xs font-semibold text-gray-700 mb-2">Certifications:</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach(array_slice($category->certifications, 0, 3) as $cert)
                                    <span class="px-2 py-1 bg-yellow-50 text-yellow-700 text-xs rounded-full">{{ $cert }}</span>
                                @endforeach
                                @if(count($category->certifications) > 3)
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">+{{ count($category->certifications) - 3 }}</span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex gap-2">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-lg font-medium hover:bg-blue-100 transition-colors">
                            <i class="fas fa-edit"></i>
                            <span>Modifier</span>
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="flex-1" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-50 text-red-600 rounded-lg font-medium hover:bg-red-100 transition-colors" {{ $category->dechets_count > 0 ? 'disabled title=Cette catégorie contient des déchets' : '' }}>
                                <i class="fas fa-trash"></i>
                                <span>Supprimer</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-layer-group text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucune catégorie</h3>
                <p class="text-gray-600 mb-6">Commencez par créer votre première catégorie de déchets</p>
                <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-primary to-primary-dark text-white rounded-xl font-semibold hover:shadow-lg hover:scale-105 transition-all duration-300">
                    <i class="fas fa-plus"></i>
                    <span>Créer une Catégorie</span>
                </a>
            </div>
        @endforelse
    </div>
</div>

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
@endpush
@endsection
