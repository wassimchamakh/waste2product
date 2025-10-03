@extends('BackOffice.layouts.app')

@section('title', 'Liste des Projets')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-10">
    <div class="container mx-auto px-4 max-w-6xl">

        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-4">
            <h1 class="text-3xl font-bold text-gray-800">Liste des Projets</h1>
            <a href="{{ route('admin.projects.create') }}" 
               class="inline-block bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg font-semibold shadow transition-colors duration-200">
                + Ajouter Projet
            </a>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 text-green-800 border border-green-300 px-4 py-3 rounded-lg mb-6 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter Form -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <form method="GET" action="{{ route('admin.projects.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="search" placeholder="Rechercher..."
                       value="{{ request('search') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                
                <select name="category" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Toutes les catégories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <select name="difficulty" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Toutes les difficultés</option>
                    <option value="facile" {{ request('difficulty') == 'facile' ? 'selected' : '' }}>Facile</option>
                    <option value="intermédiaire" {{ request('difficulty') == 'intermédiaire' ? 'selected' : '' }}>Intermédiaire</option>
                    <option value="difficile" {{ request('difficulty') == 'difficile' ? 'selected' : '' }}>Difficile</option>
                </select>

                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-semibold shadow transition">
                    Filtrer
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Titre</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Catégorie</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Difficulté</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Durée</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Statut</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($projects as $project)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-800 font-medium">{{ $project->title }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $project->category->name ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    {{ $project->difficulty_color === 'green' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $project->difficulty_color === 'orange' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $project->difficulty_color === 'red' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ $project->difficulty_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $project->estimated_time ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    {{ $project->status_color === 'green' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $project->status_color === 'gray' ? 'bg-gray-200 text-gray-700' : '' }}
                                    {{ $project->status_color === 'red' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ $project->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.projects.edit', $project->id) }}" 
                                   class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm">
                                    Modifier
                                </a>
                                <form action="{{ route('admin.projects.destroy', $project->id) }}" 
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')" 
                                            class="inline-block bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Aucun projet trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $projects->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection
