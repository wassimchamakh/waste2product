@extends('BackOffice.layouts.app')

@section('title', 'Étapes du Tutoriel')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.tutorials.show', $tutorial->id) }}" class="text-gray-600 hover:text-gray-800">
                ← Retour
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Étapes du Tutoriel</h1>
                <p class="text-gray-600 mt-1">{{ $tutorial->title }}</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.tutorials.steps.create', $tutorial->id) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                ➕ Nouvelle Étape
            </a>
            <a href="{{ route('admin.tutorials.edit', $tutorial->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                ✏️ Modifier le Tutoriel
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Total Étapes</div>
            <div class="text-3xl font-bold text-blue-600">{{ $steps->count() }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Complétions Totales</div>
            <div class="text-3xl font-bold text-green-600">{{ number_format($totalCompletions) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Durée Moyenne</div>
            <div class="text-3xl font-bold text-purple-600">{{ number_format($avgDuration, 0) }} min</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Taux Complétion Moy.</div>
            <div class="text-3xl font-bold text-yellow-600">{{ number_format($avgCompletionRate, 1) }}%</div>
        </div>
    </div>

    <!-- Steps List -->
    <div class="space-y-4">
        @forelse($steps as $step)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <div class="flex gap-6">
                    <!-- Step Number Badge -->
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-blue-600 text-white rounded-full flex items-center justify-center text-2xl font-bold">
                            {{ $step->step_number }}
                        </div>
                    </div>

                    <!-- Step Content -->
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">{{ $step->title }}</h3>
                                <p class="text-gray-600 mt-1">{{ Str::limit($step->content, 200) }}</p>
                            </div>
                            @if($step->image)
                            <img src="{{ $step->image_full_url }}" alt="{{ $step->title }}"
                                class="w-32 h-32 object-cover rounded-lg shadow ml-4">
                            @endif
                        </div>

                        <!-- Step Statistics -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                            <div class="bg-blue-50 p-3 rounded-lg">
                                <div class="text-sm text-gray-600">Durée Estimée</div>
                                <div class="text-lg font-bold text-blue-600">{{ $step->estimated_duration }} min</div>
                            </div>
                            <div class="bg-green-50 p-3 rounded-lg">
                                <div class="text-sm text-gray-600">Complétions</div>
                                <div class="text-lg font-bold text-green-600">{{ $step->completions_count ?? 0 }}</div>
                            </div>
                            <div class="bg-purple-50 p-3 rounded-lg">
                                <div class="text-sm text-gray-600">Taux de Complétion</div>
                                <div class="text-lg font-bold text-purple-600">
                                    {{ $totalUsers > 0 ? number_format(($step->completions_count ?? 0) / $totalUsers * 100, 1) : 0 }}%
                                </div>
                            </div>
                            <div class="bg-yellow-50 p-3 rounded-lg">
                                <div class="text-sm text-gray-600">Temps Moyen</div>
                                <div class="text-lg font-bold text-yellow-600">
                                    {{ number_format($step->avg_time_spent ?? 0, 0) }} min
                                </div>
                            </div>
                        </div>

                        <!-- Additional Info -->
                        <div class="flex gap-6 text-sm">
                            @if($step->video_url)
                            <div class="flex items-center text-gray-600">
                                <span class="mr-2">🎥</span>
                                <a href="{{ $step->video_url }}" target="_blank" class="text-blue-600 hover:underline">
                                    Vidéo disponible
                                </a>
                            </div>
                            @endif

                            @if($step->tips)
                            <div class="flex items-center text-gray-600">
                                <span class="mr-2">💡</span>
                                <span>{{ count(json_decode($step->tips, true) ?? []) }} astuces</span>
                            </div>
                            @endif

                            @if($step->common_mistakes)
                            <div class="flex items-center text-gray-600">
                                <span class="mr-2">⚠️</span>
                                <span>{{ count(json_decode($step->common_mistakes, true) ?? []) }} erreurs courantes</span>
                            </div>
                            @endif

                            @if($step->required_materials)
                            <div class="flex items-center text-gray-600">
                                <span class="mr-2">🛠️</span>
                                <span>{{ count(json_decode($step->required_materials, true) ?? []) }} matériaux</span>
                            </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2 mt-4">
                            <a href="{{ route('admin.tutorials.steps.edit', [$tutorial->id, $step->id]) }}" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                                ✏️ Modifier
                            </a>
                            <form action="{{ route('admin.tutorials.steps.destroy', [$tutorial->id, $step->id]) }}" method="POST" class="inline"
                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette étape ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition">
                                    🗑️ Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- User Completion Details -->
                @if($step->recent_completions && $step->recent_completions->count() > 0)
                <div class="mt-4 pt-4 border-t">
                    <h4 class="font-semibold text-gray-800 mb-3">Complétions Récentes</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($step->recent_completions->take(6) as $completion)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center font-bold">
                                {{ substr($completion->user->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-sm">{{ $completion->user->name }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ $completion->completed_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <div class="text-5xl mb-3">📋</div>
            <div class="text-lg text-gray-600">Aucune étape trouvée</div>
            <a href="{{ route('admin.tutorials.edit', $tutorial->id) }}" class="text-blue-600 hover:underline mt-2 inline-block">
                Ajouter des étapes
            </a>
        </div>
        @endforelse
    </div>

    <!-- Completion Funnel Chart -->
    @if($steps->count() > 0)
    <div class="mt-8 bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Tunnel de Conversion (Complétion par Étape)</h3>
        <div class="space-y-3">
            @foreach($steps as $step)
            @php
                $completionPercentage = $totalUsers > 0 ? (($step->completions_count ?? 0) / $totalUsers * 100) : 0;
            @endphp
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-semibold">Étape {{ $step->step_number }}: {{ Str::limit($step->title, 50) }}</span>
                    <span class="text-gray-600">{{ $step->completions_count ?? 0 }} / {{ $totalUsers }} ({{ number_format($completionPercentage, 1) }}%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="bg-gradient-to-r from-blue-500 to-green-500 h-4 rounded-full transition-all duration-300"
                        style="width: {{ $completionPercentage }}%">
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
