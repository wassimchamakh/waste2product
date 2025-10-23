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
                                <button type="button" onclick="openJitsiModal('{{ Str::slug($project->title, '-') }}')" 
                                        class="inline-block bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded-lg text-sm">
                                    Video Call
                                </button>
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
    <!-- Modal Jitsi -->
    <div id="jitsi-modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:12px; max-width:900px; width:95vw; height:80vh; margin:auto; position:relative; display:flex; flex-direction:column;">
            <button onclick="closeJitsiModal()" style="position:absolute; top:10px; right:20px; background:#e53e3e; color:#fff; border:none; border-radius:6px; padding:6px 14px; font-weight:bold; cursor:pointer;">Fermer</button>
            <div style="padding:18px 0 8px 0; text-align:center; background:#f3f4f6; border-radius:12px 12px 0 0;">
                <!--<img src='{{ asset('logo.png') }}' alt='Logo' style='height:32px; display:inline-block; vertical-align:middle; margin-right:10px;'>-->
                <span style="font-size:1.2rem; font-weight:bold; color:#2d3748;">Bienvenue sur le Video Call Waste2Product</span>
                <span id="jitsi-project-title" style="display:block; color:#4b5563; font-size:1rem; margin-top:2px;"></span>
                <span id="jitsi-project-message" style="display:block; color:#2563eb; font-size:0.98rem; margin-top:2px;"></span>
                <button id="copy-invite-btn" onclick="copyInviteLink()" style="margin-top:10px; background:#2563eb; color:#fff; border:none; border-radius:6px; padding:6px 18px; font-weight:bold; cursor:pointer; font-size:0.98rem;">Copier le lien d'invitation</button>
                <span id="copy-success" style="display:none; color:#16a34a; margin-left:10px; font-size:0.95rem;">Lien copié !</span>
            </div>
            <div id="jitsi-embed" style="flex:1; width:100%; border:none; border-radius:0 0 12px 12px;"></div>
        </div>
    </div>
    <script>
        var currentRoomUrl = '';
        function openJitsiModal(room) {
            // Nom d'affichage automatique
            var userName = @json(Auth::user()->name);
            // Titre du projet (affiché dans la modale)
            var projectTitle = '';
            // On retrouve le titre du projet dans la table (par le slug)
            document.querySelectorAll('tr').forEach(function(tr) {
                if(tr.innerHTML.includes(room)) {
                    var tds = tr.querySelectorAll('td');
                    if(tds.length > 0) projectTitle = tds[0].innerText;
                }
            });
            document.getElementById('jitsi-project-title').innerText = projectTitle ? 'Projet : ' + projectTitle : '';
            document.getElementById('jitsi-project-message').innerText = projectTitle ? 'Partagez ce lien pour inviter d\'autres admins à rejoindre la réunion vidéo de ce projet.' : '';
            document.getElementById('copy-success').style.display = 'none';
            currentRoomUrl = 'https://meet.jit.si/' + room;
            document.getElementById('jitsi-modal').style.display = 'flex';
            // Nettoyage éventuel
            if(window.jitsiApi) { window.jitsiApi.dispose(); }
            // Intégration Jitsi via l'API externe
            window.jitsiApi = new JitsiMeetExternalAPI('meet.jit.si', {
                roomName: room,
                width: '100%',
                height: '100%',
                parentNode: document.getElementById('jitsi-embed'),
                userInfo: { displayName: userName }
            });
        }
        function closeJitsiModal() {
            if(window.jitsiApi) { window.jitsiApi.dispose(); }
            document.getElementById('jitsi-modal').style.display = 'none';
        }
        function copyInviteLink() {
            if(currentRoomUrl) {
                navigator.clipboard.writeText(currentRoomUrl).then(function() {
                    document.getElementById('copy-success').style.display = 'inline';
                    setTimeout(function(){ document.getElementById('copy-success').style.display = 'none'; }, 2000);
                });
            }
        }
        // Charger le script Jitsi externe si pas déjà présent
        if(!window.JitsiMeetExternalAPI) {
            var s = document.createElement('script');
            s.src = 'https://meet.jit.si/external_api.js';
            document.head.appendChild(s);
        }
    </script>
            {{ $projects->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection