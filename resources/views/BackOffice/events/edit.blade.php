@extends('BackOffice.layouts.app')

@section('title', 'Modifier un Événement')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Modifier un Événement</h1>

    <form method="POST" action="{{ route('admin.events.update', $event->id) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <label class="block mb-2">Titre</label>
        <input type="text" name="title" value="{{ $event->title }}" class="border p-2 w-full mb-4" required>

        <label class="block mb-2">Description</label>
        <textarea name="description" class="border p-2 w-full mb-4">{{ $event->description }}</textarea>

        <label class="block mb-2">Image</label>
        <input type="file" name="image" class="mb-4">

        @if($event->image)
            <img src="{{ asset('storage/'.$event->image) }}" class="h-24 mb-4">
        @endif

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Mettre à jour</button>
    </form>
</div>
@endsection
