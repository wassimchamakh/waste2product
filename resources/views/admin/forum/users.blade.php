@extends('BackOffice.layouts.app')

@section('title', 'Utilisateurs - Forum Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">👥 Utilisateurs du Forum</h1>
            <p class="text-gray-600">Classement par réputation et activité</p>
        </div>
        <a href="{{ route('admin.forum.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
            <i class="fas fa-arrow-left mr-2"></i> Retour
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Utilisateur</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Réputation</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Posts</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Commentaires</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Best Answers</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-primary to-success rounded-full flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                        @if($user->badge)
                                            @php $badgeInfo = $user->getBadgeInfo(); @endphp
                                            <span class="inline-flex items-center px-2 py-1 text-xs {{ $badgeInfo['color'] }} {{ $badgeInfo['class'] }} rounded">
                                                {{ $badgeInfo['icon'] }} {{ $badgeInfo['name'] }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-primary">{{ $user->reputation }}</td>
                            <td class="px-6 py-4 text-center">{{ $user->posts_count }}</td>
                            <td class="px-6 py-4 text-center">{{ $user->comments_count }}</td>
                            <td class="px-6 py-4 text-center">{{ $user->best_answers_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-users-slash text-4xl mb-2"></i>
                                <p>Aucun utilisateur trouvé</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
