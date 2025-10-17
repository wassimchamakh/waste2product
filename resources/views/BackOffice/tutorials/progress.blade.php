@extends('BackOffice.layouts.app')

@section('title', 'Progression des Utilisateurs')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.tutorials.show', $tutorial->id) }}" class="text-gray-600 hover:text-gray-800">
                ← Retour
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Progression des Utilisateurs</h1>
                <p class="text-gray-600 mt-1">{{ $tutorial->title }}</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Utilisateurs Actifs</div>
            <div class="text-3xl font-bold text-blue-600">{{ $totalUsers }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Ont Terminé</div>
            <div class="text-3xl font-bold text-green-600">{{ $completedUsers }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">En Cours</div>
            <div class="text-3xl font-bold text-yellow-600">{{ $inProgressUsers }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Taux de Complétion</div>
            <div class="text-3xl font-bold text-purple-600">{{ number_format($completionRate, 1) }}%</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Temps Moyen</div>
            <div class="text-3xl font-bold text-cyan-600">{{ number_format($avgTimeSpent, 0) }} min</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" action="{{ route('admin.tutorials.progress', $tutorial->id) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher un utilisateur</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Nom, email..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En cours</option>
                    <option value="started" {{ request('status') == 'started' ? 'selected' : '' }}>Commencé</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trier par</label>
                <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="progress_desc" {{ request('sort') == 'progress_desc' ? 'selected' : '' }}>Progression (décroissant)</option>
                    <option value="progress_asc" {{ request('sort') == 'progress_asc' ? 'selected' : '' }}>Progression (croissant)</option>
                    <option value="time_desc" {{ request('sort') == 'time_desc' ? 'selected' : '' }}>Temps passé (décroissant)</option>
                    <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Plus récent</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    🔍 Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Progress Distribution Chart -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Distribution de la Progression</h3>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            @php
                $ranges = [
                    ['label' => '0-20%', 'min' => 0, 'max' => 20, 'color' => 'red'],
                    ['label' => '21-40%', 'min' => 21, 'max' => 40, 'color' => 'orange'],
                    ['label' => '41-60%', 'min' => 41, 'max' => 60, 'color' => 'yellow'],
                    ['label' => '61-80%', 'min' => 61, 'max' => 80, 'color' => 'blue'],
                    ['label' => '81-100%', 'min' => 81, 'max' => 100, 'color' => 'green'],
                ];
            @endphp

            @foreach($ranges as $range)
            @php
                $count = $progressData->filter(function($p) use ($range) {
                    return $p->progress_percentage >= $range['min'] && $p->progress_percentage <= $range['max'];
                })->count();
                $percentage = $totalUsers > 0 ? ($count / $totalUsers * 100) : 0;
            @endphp
            <div class="text-center p-4 bg-{{ $range['color'] }}-50 rounded-lg">
                <div class="text-3xl font-bold text-{{ $range['color'] }}-600">{{ $count }}</div>
                <div class="text-sm text-gray-600 mt-1">{{ $range['label'] }}</div>
                <div class="text-xs text-gray-500">{{ number_format($percentage, 1) }}%</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Users Progress Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="table-auto w-full">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Utilisateur</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Progression</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Étapes</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Temps Passé</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Note</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Statut</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Activité</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($progressData as $progress)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 text-white rounded-full flex items-center justify-center font-bold">
                                {{ substr($progress->user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-semibold text-gray-800">{{ $progress->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $progress->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="mb-2">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-semibold text-gray-700">{{ number_format($progress->progress_percentage, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                @php
                                    $progressColor = $progress->progress_percentage >= 80 ? 'bg-green-500' : 
                                                    ($progress->progress_percentage >= 50 ? 'bg-yellow-500' : 'bg-red-500');
                                @endphp
                                <div class="{{ $progressColor }} h-3 rounded-full transition-all duration-300"
                                    style="width: {{ $progress->progress_percentage }}%">
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-800">{{ $progress->completed_steps }}</div>
                            <div class="text-sm text-gray-500">sur {{ $progress->total_steps }}</div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-center">
                            <div class="text-xl font-bold text-purple-600">{{ number_format($progress->time_spent ?? 0, 0) }}</div>
                            <div class="text-sm text-gray-500">minutes</div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        @if($progress->rating)
                        <div class="flex items-center gap-1">
                            <span class="text-yellow-500 text-xl">⭐</span>
                            <span class="font-bold text-gray-800">{{ number_format($progress->rating, 1) }}</span>
                            <span class="text-gray-500 text-sm">/5</span>
                        </div>
                        @else
                        <span class="text-gray-400 text-sm">Pas de note</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($progress->is_completed)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            ✓ Terminé
                        </span>
                        @elseif($progress->completed_steps > 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            🔄 En cours
                        </span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            📝 Commencé
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-sm text-gray-600">
                            <div>Début: {{ $progress->started_at->format('d/m/Y') }}</div>
                            @if($progress->completed_at)
                            <div>Fin: {{ $progress->completed_at->format('d/m/Y') }}</div>
                            @endif
                            <div class="text-xs text-gray-500">{{ $progress->last_activity_at?->diffForHumans() ?? 'Aucune activité' }}</div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('tutorials.show', $tutorial->slug) }}?user={{ $progress->user_id }}" 
                                class="text-blue-600 hover:text-blue-800 text-lg" title="Voir détails" target="_blank">
                                👁️
                            </a>
                            <a href="mailto:{{ $progress->user->email }}" 
                                class="text-green-600 hover:text-green-800 text-lg" title="Envoyer un email">
                                ✉️
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center text-gray-500">
                        <div class="text-5xl mb-3">📊</div>
                        <div class="text-lg">Aucune progression trouvée</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $progressData->links() }}
    </div>

    <!-- Export Options -->
    <div class="mt-6 bg-white rounded-lg shadow p-4">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="font-semibold text-gray-800">Exporter les données</h3>
                <p class="text-sm text-gray-600">Télécharger les données de progression</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.tutorials.progress', $tutorial->id) }}?export=csv" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition">
                    📊 Export CSV
                </a>
                <a href="{{ route('admin.tutorials.progress', $tutorial->id) }}?export=pdf" 
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition">
                    📄 Export PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
