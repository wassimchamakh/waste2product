@extends('BackOffice.layouts.app')

@section('title', 'Créer un Événement')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Créer un Événement</h1>

    <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data">
        @csrf
        <label class="block mb-2">Titre</label>
        <input type="text" name="title" class="border p-2 w-full mb-4" required>

        <label class="block mb-2">Description</label>
        <textarea name="description" class="border p-2 w-full mb-4"></textarea>

        <label class="block mb-2">Image</label>
        <input type="file" name="image" class="mb-4">

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Enregistrer</button>
    </form>
</div>
@endsection
