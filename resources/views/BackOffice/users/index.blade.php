@extends('BackOffice.layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Gestion des Utilisateurs</h1>
            <p class="text-gray-600 mt-1">Gérer tous les utilisateurs de la plateforme</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Ajouter un utilisateur
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Admins</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['admins'] }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-user-shield text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Utilisateurs</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['regular'] }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-user text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Vérifiés</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['verified'] }}</p>
                </div>
                <div class="bg-teal-100 rounded-full p-3">
                    <i class="fas fa-check-circle text-teal-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Non vérifiés</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['unverified'] }}</p>
                </div>
                <div class="bg-orange-100 rounded-full p-3">
                    <i class="fas fa-exclamation-circle text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Ce mois</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['this_month'] }}</p>
                </div>
                <div class="bg-indigo-100 rounded-full p-3">
                    <i class="fas fa-calendar-alt text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Filtres</h3>
        </div>
        <div class="p-4">
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Nom, email, téléphone, ville..." 
                           class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                </div>

                <!-- Role Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                    <select name="role" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        <option value="">Tous les rôles</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrateurs</option>
                        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Utilisateurs</option>
                    </select>
                </div>

                <!-- Verification Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vérification</label>
                    <select name="verified" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        <option value="">Tous</option>
                        <option value="yes" {{ request('verified') === 'yes' ? 'selected' : '' }}>Vérifiés</option>
                        <option value="no" {{ request('verified') === 'no' ? 'selected' : '' }}>Non vérifiés</option>
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Trier par</label>
                    <select name="sort" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date d'inscription</option>
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nom</option>
                        <option value="email" {{ request('sort') === 'email' ? 'selected' : '' }}>Email</option>
                    </select>
                </div>

                <div class="md:col-span-4 flex gap-2">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-filter mr-2"></i>Filtrer
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">
                        <i class="fas fa-times mr-2"></i>Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <form id="bulk-action-form" method="POST" action="{{ route('admin.users.bulk-action') }}">
        @csrf
        <div class="bg-white rounded-lg shadow mb-6 p-4">
            <div class="flex items-center gap-4">
                <label class="text-sm font-medium text-gray-700">Actions groupées:</label>
                <select name="action" class="rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    <option value="">Sélectionner une action</option>
                    <option value="verify">Vérifier les emails</option>
                    <option value="unverify">Retirer la vérification</option>
                    <option value="make_admin">Promouvoir en admin</option>
                    <option value="remove_admin">Révoquer admin</option>
                    <option value="delete">Supprimer</option>
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg" onclick="return confirm('Êtes-vous sûr de vouloir effectuer cette action?')">
                    Appliquer
                </button>
                <span id="selected-count" class="text-sm text-gray-600">0 sélectionné(s)</span>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Utilisateur
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rôle
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Inscrit le
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="user-checkbox rounded border-gray-300 text-green-600 focus:ring-green-500">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($user->avatar)
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                            <span class="text-green-600 font-semibold text-sm">{{ substr($user->name, 0, 2) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->city ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                            <div class="text-sm text-gray-500">{{ $user->phone ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->is_admin)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                    <i class="fas fa-shield-alt mr-1"></i> Admin
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    <i class="fas fa-user mr-1"></i> Utilisateur
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->email_verified_at)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Vérifié
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    <i class="fas fa-clock mr-1"></i> Non vérifié
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="text-blue-600 hover:text-blue-900 mr-3" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-green-600 hover:text-green-900 mr-3" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-users text-4xl mb-4 text-gray-300"></i>
                            <p>Aucun utilisateur trouvé</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>

@push('scripts')
<script>
    // Select All Checkbox
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        updateSelectedCount();
    });

    // Update selected count
    document.querySelectorAll('.user-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    function updateSelectedCount() {
        const count = document.querySelectorAll('.user-checkbox:checked').length;
        document.getElementById('selected-count').textContent = count + ' sélectionné(s)';
    }
</script>
@endpush
@endsection
