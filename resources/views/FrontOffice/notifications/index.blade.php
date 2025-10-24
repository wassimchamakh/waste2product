@extends('FrontOffice.layout1.app')

@section('title', 'Mes Notifications')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-green-800 hover:text-green-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center space-x-3">
                        <i class="fas fa-bell text-primary"></i>
                        <span>Mes Notifications</span>
                    </h1>
                    <p class="text-gray-600 mt-2">Restez informé de toutes vos activités</p>
                </div>
                <div class="flex items-center space-x-3">
                    @if($stats['unread'] > 0)
                        <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors flex items-center space-x-2 shadow-sm">
                                <i class="fas fa-check-double"></i>
                                <span>Tout marquer comme lu</span>
                            </button>
                        </form>
                    @endif
                    @if($stats['read'] > 0)
                        <form action="{{ route('notifications.delete-all-read') }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer toutes les notifications lues ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center space-x-2 shadow-sm">
                                <i class="fas fa-trash"></i>
                                <span>Supprimer les lues</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-100 mb-1">Total</p>
                        <h3 class="text-4xl font-bold">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-bell text-3xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-orange-100 mb-1">Non lues</p>
                        <h3 class="text-4xl font-bold">{{ $stats['unread'] }}</h3>
                    </div>
                    <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-envelope text-3xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-100 mb-1">Lues</p>
                        <h3 class="text-4xl font-bold">{{ $stats['read'] }}</h3>
                    </div>
                    <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-envelope-open text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Type Stats -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600">Projets</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['project'] }}</p>
                    </div>
                    <i class="fas fa-project-diagram text-green-500 text-xl"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600">Événements</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['event'] }}</p>
                    </div>
                    <i class="fas fa-calendar text-purple-500 text-xl"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600">Tutoriels</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['tutorial'] }}</p>
                    </div>
                    <i class="fas fa-book text-blue-500 text-xl"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600">Déchets</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['dechet'] }}</p>
                    </div>
                    <i class="fas fa-recycle text-orange-500 text-xl"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600">Commentaires</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['comment'] }}</p>
                    </div>
                    <i class="fas fa-comment text-yellow-500 text-xl"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-pink-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600">Messages</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['message'] }}</p>
                    </div>
                    <i class="fas fa-envelope text-pink-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-100">
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-sm font-medium text-gray-700">
                    <i class="fas fa-filter mr-2"></i>Filtrer:
                </span>
                <!-- Status Filters -->
                <a href="{{ route('notifications.index') }}" 
                   class="px-4 py-2 rounded-lg text-sm transition-colors {{ !request('filter') ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Toutes
                </a>
                <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" 
                   class="px-4 py-2 rounded-lg text-sm transition-colors {{ request('filter') === 'unread' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Non lues
                </a>
                <a href="{{ route('notifications.index', ['filter' => 'read']) }}" 
                   class="px-4 py-2 rounded-lg text-sm transition-colors {{ request('filter') === 'read' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Lues
                </a>
                
                <span class="text-gray-300">|</span>
                
                <!-- Type Filters -->
                <a href="{{ route('notifications.index', ['type' => 'project']) }}" 
                   class="px-3 py-2 rounded-lg text-xs transition-colors {{ request('type') === 'project' ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-project-diagram mr-1"></i>Projets
                </a>
                <a href="{{ route('notifications.index', ['type' => 'event']) }}" 
                   class="px-3 py-2 rounded-lg text-xs transition-colors {{ request('type') === 'event' ? 'bg-purple-100 text-purple-700 border border-purple-300' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-calendar mr-1"></i>Événements
                </a>
                <a href="{{ route('notifications.index', ['type' => 'tutorial']) }}" 
                   class="px-3 py-2 rounded-lg text-xs transition-colors {{ request('type') === 'tutorial' ? 'bg-blue-100 text-blue-700 border border-blue-300' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-book mr-1"></i>Tutoriels
                </a>
                <a href="{{ route('notifications.index', ['type' => 'dechet']) }}" 
                   class="px-3 py-2 rounded-lg text-xs transition-colors {{ request('type') === 'dechet' ? 'bg-orange-100 text-orange-700 border border-orange-300' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-recycle mr-1"></i>Déchets
                </a>
                <a href="{{ route('notifications.index', ['type' => 'comment']) }}" 
                   class="px-3 py-2 rounded-lg text-xs transition-colors {{ request('type') === 'comment' ? 'bg-yellow-100 text-yellow-700 border border-yellow-300' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-comment mr-1"></i>Commentaires
                </a>
                <a href="{{ route('notifications.index', ['type' => 'message']) }}" 
                   class="px-3 py-2 rounded-lg text-xs transition-colors {{ request('type') === 'message' ? 'bg-pink-100 text-pink-700 border border-pink-300' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-envelope mr-1"></i>Messages
                </a>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="space-y-3">
            @forelse($notifications as $notification)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow {{ $notification->is_read ? '' : 'border-l-4 border-l-primary' }}">
                    <div class="p-5 {{ $notification->is_read ? '' : 'bg-blue-50 bg-opacity-30' }}">
                        <div class="flex items-start space-x-4">
                            <!-- Icon -->
                            <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0
                                @if($notification->color === 'blue') bg-blue-100
                                @elseif($notification->color === 'green') bg-green-100
                                @elseif($notification->color === 'purple') bg-purple-100
                                @elseif($notification->color === 'orange') bg-orange-100
                                @elseif($notification->color === 'red') bg-red-100
                                @elseif($notification->color === 'yellow') bg-yellow-100
                                @elseif($notification->color === 'pink') bg-pink-100
                                @else bg-gray-100
                                @endif">
                                <i class="fas {{ $notification->icon }} text-xl
                                    @if($notification->color === 'blue') text-blue-600
                                    @elseif($notification->color === 'green') text-green-600
                                    @elseif($notification->color === 'purple') text-purple-600
                                    @elseif($notification->color === 'orange') text-orange-600
                                    @elseif($notification->color === 'red') text-red-600
                                    @elseif($notification->color === 'yellow') text-yellow-600
                                    @elseif($notification->color === 'pink') text-pink-600
                                    @else text-gray-600
                                    @endif"></i>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-base font-semibold text-gray-900 mb-1 flex items-center space-x-2">
                                            <span>{{ $notification->title }}</span>
                                            @if(!$notification->is_read)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary text-white">
                                                    Nouveau
                                                </span>
                                            @endif
                                        </h3>
                                        <p class="text-sm text-gray-600 mb-3">{{ $notification->message }}</p>
                                        <div class="flex items-center space-x-4 text-xs text-gray-500">
                                            <span class="flex items-center">
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                            @if($notification->is_read && $notification->read_at)
                                                <span class="flex items-center">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Lu {{ $notification->read_at->diffForHumans() }}
                                                </span>
                                            @endif
                                            <span class="flex items-center">
                                                <i class="fas fa-tag mr-1"></i>
                                                {{ ucfirst($notification->type) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center space-x-2 ml-4">
                                        @if($notification->link)
                                            <a href="{{ $notification->link }}" 
                                               class="p-2 text-primary hover:bg-primary hover:text-white rounded-lg transition-colors"
                                               title="Voir">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @endif
                                        
                                        @if(!$notification->is_read)
                                            <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="p-2 text-green-600 hover:bg-green-600 hover:text-white rounded-lg transition-colors"
                                                        title="Marquer comme lu">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form action="{{ route('notifications.destroy', $notification->id) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-colors"
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-100">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bell-slash text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucune notification</h3>
                    <p class="text-gray-600">Vous n'avez aucune notification pour le moment.</p>
                    <a href="/" class="mt-4 inline-block px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                        Retour à l'accueil
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
