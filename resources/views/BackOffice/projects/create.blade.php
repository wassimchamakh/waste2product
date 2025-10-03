@extends('BackOffice.layouts.app')

@section('title', 'Ajouter un Projet')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Ajouter un Projet</h1>

    <form action="{{ route('admin.projects.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf

        <div class="mb-4">
            <label for="title" class="block text-gray-700 font-medium mb-2">Titre</label>
            <input type="text" name="title" id="title" 
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300"
                   value="{{ old('title') }}" required>
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 font-medium mb-2">Description</label>
            <textarea name="description" id="description" rows="4"
                      class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="category_id" class="block text-gray-700 font-medium mb-2">Catégorie</label>
            <select name="category_id" id="category_id"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" required>
                <option value="">-- Sélectionnez une catégorie --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="estimated_time" class="block text-gray-700 font-medium mb-2">Temps estimé (heures)</label>
            <input type="number" name="estimated_time" id="estimated_time" min="1"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300"
                   value="{{ old('estimated_time') }}" required>
            @error('estimated_time')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.projects.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Annuler</a>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Ajouter</button>
        </div>
    </form>
</div>
@endsection