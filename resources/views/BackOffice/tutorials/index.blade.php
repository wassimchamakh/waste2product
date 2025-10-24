@extends('BackOffice.layouts.app')

@section('title', 'Gestion des Tutoriels')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Gestion des Tutoriels</h1>
        <div class="flex gap-3">
            <a href="{{ route('admin.tutorials.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg shadow transition">
                ➕ Nouveau Tutoriel
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Total Tutoriels</div>
            <div class="text-3xl font-bold text-blue-600">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Publiés</div>
            <div class="text-3xl font-bold text-green-600">{{ $stats['published'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Brouillons</div>
            <div class="text-3xl font-bold text-yellow-600">{{ $stats['draft'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Vues Totales</div>
            <div class="text-3xl font-bold text-purple-600">{{ number_format($stats['total_views']) }}</div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('admin.tutorials.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Titre, auteur..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous</option>
                    <option value="Published" {{ request('status') == 'Published' ? 'selected' : '' }}>Publié</option>
                    <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Brouillon</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Niveau</label>
                <select name="level" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous</option>
                    <option value="Beginner" {{ request('level') == 'Beginner' ? 'selected' : '' }}>Débutant</option>
                    <option value="Intermediate" {{ request('level') == 'Intermediate' ? 'selected' : '' }}>Intermédiaire</option>
                    <option value="Advanced" {{ request('level') == 'Advanced' ? 'selected' : '' }}>Avancé</option>
                    <option value="Expert" {{ request('level') == 'Expert' ? 'selected' : '' }}>Expert</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    🔍 Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="bg-white rounded-lg shadow p-4 mb-6" id="bulk-actions" style="display: none;">
        <form method="POST" action="{{ route('admin.tutorials.bulk') }}" id="bulk-form">
            @csrf
            <div class="flex gap-3 items-center">
                <span class="text-gray-700 font-medium"><span id="selected-count">0</span> sélectionné(s)</span>
                <select name="action" class="px-4 py-2 border border-gray-300 rounded-lg" required>
                    <option value="">Action groupée...</option>
                    <option value="publish">Publier</option>
                    <option value="draft">Mettre en brouillon</option>
                    <option value="feature">Mettre en vedette</option>
                    <option value="unfeature">Retirer de vedette</option>
                    <option value="delete">Supprimer</option>
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                    Appliquer
                </button>
            </div>
        </form>
    </div>

    <!-- Tutorials Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="table-auto w-full">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-4 py-3 text-left">
                        <input type="checkbox" id="select-all" class="w-4 h-4 text-blue-600 rounded">
                    </th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Miniature</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Titre</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Auteur</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Statut</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Statistiques</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($tutorials as $tutorial)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">
                        <input type="checkbox" name="tutorial_ids[]" value="{{ $tutorial->id }}" class="tutorial-checkbox w-4 h-4 text-blue-600 rounded">
                    </td>
                    <td class="px-4 py-3">
                        <img src="{{ $tutorial->thumbnail_url }}" alt="{{ $tutorial->title }}" 
                            class="w-16 h-16 object-cover rounded-lg shadow">
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-semibold text-gray-800">{{ Str::limit($tutorial->title, 40) }}</div>
                        <div class="text-sm text-gray-600">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-gray-100">
                                {{ $tutorial->difficulty_level }}
                            </span>
                            @if($tutorial->is_featured)
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800">
                                ⭐ Vedette
                            </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-gray-800">{{ $tutorial->creator->name ?? '-' }}</div>
                        <div class="text-sm text-gray-500">{{ $tutorial->creator->email ?? '-' }}</div>
                    </td>
                    <td class="px-4 py-3">
                        @if($tutorial->status === 'Published')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            ✓ Publié
                        </span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            📝 Brouillon
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-sm space-y-1">
                            <div>👁️ {{ number_format($tutorial->views_count ?? 0) }} vues</div>
                            <div>📝 {{ $tutorial->steps_count ?? 0 }} étapes</div>
                            <div>💬 {{ $tutorial->comments_count ?? 0 }} commentaires</div>
                            <div>⭐ {{ number_format($tutorial->avg_rating ?? 0, 1) }}/5</div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600">
                        <div>{{ $tutorial->created_at->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $tutorial->created_at->diffForHumans() }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.tutorials.show', $tutorial->id) }}" 
                                class="text-blue-600 hover:text-blue-800 text-lg" title="Voir détails">
                                👁️
                            </a>
                            <a href="{{ route('admin.tutorials.edit', $tutorial->id) }}" 
                                class="text-green-600 hover:text-green-800 text-lg" title="Modifier">
                                ✏️
                            </a>
                            <a href="{{ route('admin.tutorials.steps', $tutorial->id) }}" 
                                class="text-purple-600 hover:text-purple-800 text-lg" title="Étapes">
                                📋
                            </a>
                            <a href="{{ route('admin.tutorials.comments', $tutorial->id) }}" 
                                class="text-indigo-600 hover:text-indigo-800 text-lg" title="Commentaires">
                                💬
                            </a>
                            <a href="{{ route('admin.tutorials.progress', $tutorial->id) }}" 
                                class="text-cyan-600 hover:text-cyan-800 text-lg" title="Progression">
                                📊
                            </a>
                            <form method="POST" action="{{ route('admin.tutorials.destroy', $tutorial->id) }}" class="inline"
                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce tutoriel ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-lg" title="Supprimer">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                        <div class="text-5xl mb-3">📚</div>
                        <div class="text-lg">Aucun tutoriel trouvé</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $tutorials->links() }}
    </div>
</div>

<script>
// Select all checkboxes
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.tutorial-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    updateBulkActions();
});

// Update bulk actions visibility
document.querySelectorAll('.tutorial-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateBulkActions);
});

function updateBulkActions() {
    const checked = document.querySelectorAll('.tutorial-checkbox:checked');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    
    selectedCount.textContent = checked.length;
    bulkActions.style.display = checked.length > 0 ? 'block' : 'none';
}

// Handle bulk form submission
document.getElementById('bulk-form').addEventListener('submit', function(e) {
    const checked = document.querySelectorAll('.tutorial-checkbox:checked');
    if (checked.length === 0) {
        e.preventDefault();
        alert('Veuillez sélectionner au moins un tutoriel');
        return;
    }
    
    // Add checked IDs to form
    checked.forEach(checkbox => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'tutorial_ids[]';
        input.value = checkbox.value;
        this.appendChild(input);
    });
});
</script>
@endsection
