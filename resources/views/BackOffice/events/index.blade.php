@extends('BackOffice.layouts.app')

@section('title', 'Liste des Événements')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Liste des Événements</h1>
    <a href="{{ route('admin.events.create') }}" class="bg-green-500 text-white px-4 py-2 rounded">Créer un Événement</a>

    <table class="table-auto w-full mt-6">
        <thead>
            <tr class="bg-gray-200">
                <th class="px-4 py-2">Titre</th>
                <th class="px-4 py-2">Organisateur</th>
                <th class="px-4 py-2">Date début</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
            <tr>
                <td class="border px-4 py-2">{{ $event->title }}</td>
                <td class="border px-4 py-2">{{ $event->organizer->name ?? '-' }}</td>
                <td class="border px-4 py-2">{{ $event->date_start }}</td> 
                <td class="border px-4 py-2">{{ $event->status }}</td>
                <td class="border px-4 py-2 flex gap-2">
                    <a href="{{ route('admin.events.edit', $event->id) }}" class="text-blue-500">✏️</a>
                    <a href="{{ route('admin.events.participants', $event->id) }}" class="text-indigo-500">👥</a>
                    <form method="POST" action="{{ route('admin.events.destroy', $event->id) }}">
                        @csrf @method('DELETE')
                        <button class="text-red-500" onclick="return confirm('Supprimer ?')">🗑️</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $events->links() }}
</div>
@endsection
