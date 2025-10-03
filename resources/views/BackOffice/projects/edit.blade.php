@extends('BackOffice.layouts.app')

@section('title', 'Modifier le Projet')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Modifier le Projet</h1>

    <form action="{{ route('admin.projects.update', $project->id) }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="title" class="block text-gray-700 font-medium mb-2">Titre</label>
            <input type="text" name="title" id="title" 
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300"
                   value="{{ old('title', $project->title) }}" required>
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 font-medium mb-2">Description</label>
            <textarea name="description" id="description" rows="4"
                      class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">{{ old('description', $project->description) }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="category_id" class="block text-gray-700 font-medium mb-2">Catégorie</label>
            <select name="category_id" id="category_id"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $project->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.projects.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Annuler</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection
