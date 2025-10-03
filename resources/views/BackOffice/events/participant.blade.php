@extends('BackOffice.layouts.app')

@section('title', 'Participants de '.$event->title)

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Participants - {{ $event->title }}</h1>

    <table class="table-auto w-full mt-6">
        <thead>
            <tr class="bg-gray-200">
                <th class="px-4 py-2">Nom</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Statut</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($event->participants as $participant)
            <tr>
                <td class="border px-4 py-2">{{ $participant->user->name }}</td>
                <td class="border px-4 py-2">{{ $participant->user->email }}</td>
                <td class="border px-4 py-2">{{ $participant->attendance_status }}</td>
                <td class="border px-4 py-2">
                    <form method="POST" action="{{ route('admin.events.removeParticipant', [$event->id, $participant->id]) }}">
                        @csrf @method('DELETE')
                        <button class="text-red-500" onclick="return confirm('Supprimer ce participant ?')">🗑️ Retirer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
