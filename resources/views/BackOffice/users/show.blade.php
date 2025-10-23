@extends('BackOffice.layouts.app')

@section('title', 'Détails de l\'utilisateur')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Profil utilisateur</h1>
            <p class="text-gray-600 mt-1">Informations détaillées sur {{ $user->name }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i class="fas fa-edit"></i>
                Modifier
            </a>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                Retour
            </a>
        </div>
    </div>

    <!-- User Profile Card -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <div class="flex items-start gap-6">
                <!-- Avatar -->
                <div class="flex-shrink-0">
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="h-32 w-32 rounded-lg object-cover">
                    @else
                        <div class="h-32 w-32 rounded-lg bg-green-100 flex items-center justify-center">
                            <span class="text-green-600 font-bold text-4xl">{{ substr($user->name, 0, 2) }}</span>
                        </div>
                    @endif
                </div>

                <!-- User Info -->
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
                        @if($user->is_admin)
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                <i class="fas fa-shield-alt mr-1"></i> Administrateur
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                <i class="fas fa-user mr-1"></i> Utilisateur
                            </span>
                        @endif
                        @if($user->email_verified_at)
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Vérifié
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="text-gray-800 font-medium">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Téléphone</p>
                            <p class="text-gray-800 font-medium">{{ $user->phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Ville</p>
                            <p class="text-gray-800 font-medium">{{ $user->city ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Inscrit le</p>
                            <p class="text-gray-800 font-medium">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        @if($user->address)
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Adresse</p>
                            <p class="text-gray-800 font-medium">{{ $user->address }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Déchets</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['dechets_count'] }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-recycle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Projets</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['projects_count'] }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-project-diagram text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Tutoriels créés</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['tutorials_created'] }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-book text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">CO2 économisé</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_co2_saved'], 2) }}</p>
                    <p class="text-xs text-gray-500">kg</p>
                </div>
                <div class="bg-teal-100 rounded-full p-3">
                    <i class="fas fa-leaf text-teal-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tutorial Progress -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold text-gray-800">Tutoriels complétés</h3>
                <span class="text-2xl font-bold text-green-600">{{ $stats['tutorials_completed'] }}</span>
            </div>
            <div class="text-sm text-gray-600">
                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                Terminés avec succès
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold text-gray-800">En cours</h3>
                <span class="text-2xl font-bold text-blue-600">{{ $stats['tutorials_in_progress'] }}</span>
            </div>
            <div class="text-sm text-gray-600">
                <i class="fas fa-spinner text-blue-600 mr-2"></i>
                Tutoriels en progression
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold text-gray-800">Commentaires</h3>
                <span class="text-2xl font-bold text-purple-600">{{ $stats['comments_count'] }}</span>
            </div>
            <div class="text-sm text-gray-600">
                <i class="fas fa-comment text-purple-600 mr-2"></i>
                Commentaires postés
            </div>
        </div>
    </div>

    <!-- Recent Activity Tabs -->
    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button onclick="showTab('dechets')" id="tab-dechets" class="tab-button active px-6 py-4 text-sm font-medium border-b-2 border-green-600 text-green-600">
                    <i class="fas fa-recycle mr-2"></i>Déchets récents
                </button>
                <button onclick="showTab('projects')" id="tab-projects" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <i class="fas fa-project-diagram mr-2"></i>Projets récents
                </button>
                <button onclick="showTab('comments')" id="tab-comments" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <i class="fas fa-comments mr-2"></i>Commentaires récents
                </button>
            </nav>
        </div>

        <!-- Dechets Tab -->
        <div id="content-dechets" class="tab-content p-6">
            @forelse($recentDechets as $dechet)
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                    <div>
                        <p class="font-medium text-gray-800">{{ $dechet->name }}</p>
                        <p class="text-sm text-gray-500">{{ $dechet->created_at->format('d/m/Y') }}</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        {{ $dechet->status }}
                    </span>
                </div>
            @empty
                <p class="text-center text-gray-500 py-8">Aucun déchet trouvé</p>
            @endforelse
        </div>

        <!-- Projects Tab -->
        <div id="content-projects" class="tab-content p-6 hidden">
            @forelse($recentProjects as $project)
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                    <div>
                        <p class="font-medium text-gray-800">{{ $project->name }}</p>
                        <p class="text-sm text-gray-500">{{ $project->created_at->format('d/m/Y') }}</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $project->status }}
                    </span>
                </div>
            @empty
                <p class="text-center text-gray-500 py-8">Aucun projet trouvé</p>
            @endforelse
        </div>

        <!-- Comments Tab -->
        <div id="content-comments" class="tab-content p-6 hidden">
            @forelse($recentComments as $comment)
                <div class="py-3 border-b border-gray-100 last:border-0">
                    <div class="flex items-start justify-between mb-2">
                        <p class="font-medium text-gray-800">{{ $comment->tutorial->title ?? 'N/A' }}</p>
                        <span class="text-sm text-gray-500">{{ $comment->created_at->format('d/m/Y') }}</span>
                    </div>
                    <p class="text-sm text-gray-600">{{ Str::limit($comment->content, 100) }}</p>
                    @if($comment->rating)
                        <div class="mt-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $comment->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-center text-gray-500 py-8">Aucun commentaire trouvé</p>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        // Remove active class from all buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active', 'border-green-600', 'text-green-600');
            button.classList.add('border-transparent', 'text-gray-500');
        });

        // Show selected tab content
        document.getElementById('content-' + tabName).classList.remove('hidden');

        // Add active class to clicked button
        const activeButton = document.getElementById('tab-' + tabName);
        activeButton.classList.add('active', 'border-green-600', 'text-green-600');
        activeButton.classList.remove('border-transparent', 'text-gray-500');
    }
</script>
@endpush
@endsection
