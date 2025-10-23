@extends('BackOffice.layouts.app')

@section('title', 'Ajouter un Projet')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Ajouter un Projet</h1>

    <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
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

        <!-- Photo principale du projet -->
        <div class="mb-6">
            <label for="photo" class="block text-gray-700 font-medium mb-2">Photo principale du projet</label>
            <input type="file" name="photo" id="photo" accept="image/*"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
            @error('photo')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Étapes du projet -->
        <div class="mb-6">
            <label class="block text-gray-700 font-medium mb-2">Étapes du projet</label>
            <div id="steps-container">
                <div class="step-item mb-4 p-4 border rounded-lg bg-gray-50">
                    <div class="mb-2">
                        <label class="block text-gray-600 mb-1">Titre de l'étape</label>
                        <input type="text" name="steps[0][title]" class="w-full border rounded px-2 py-1" required>
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-600 mb-1">Description de l'étape</label>
                        <textarea name="steps[0][description]" class="w-full border rounded px-2 py-1" rows="2" required></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-600 mb-1">Durée estimée</label>
                        <input type="text" name="steps[0][duration]" class="w-full border rounded px-2 py-1">
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-600 mb-1">Matériaux nécessaires</label>
                        <input type="text" name="steps[0][materials_needed]" class="w-full border rounded px-2 py-1">
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-600 mb-1">Outils requis</label>
                        <input type="text" name="steps[0][tools_required]" class="w-full border rounded px-2 py-1">
                    </div>
                </div>
            </div>
            <button type="button" onclick="addStep()" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 mt-2">Ajouter une étape</button>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.projects.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Annuler</a>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Ajouter</button>
        </div>
        </form>
        <script>
        let stepIndex = 1;
        function addStep() {
            const container = document.getElementById('steps-container');
            const step = document.createElement('div');
            step.className = 'step-item mb-4 p-4 border rounded-lg bg-gray-50';
            step.innerHTML = `
                <div class="mb-2">
                    <label class="block text-gray-600 mb-1">Titre de l'étape</label>
                    <input type="text" name="steps[${stepIndex}][title]" class="w-full border rounded px-2 py-1" required>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-600 mb-1">Description de l'étape</label>
                    <textarea name="steps[${stepIndex}][description]" class="w-full border rounded px-2 py-1" rows="2" required></textarea>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-600 mb-1">Durée estimée</label>
                    <input type="text" name="steps[${stepIndex}][duration]" class="w-full border rounded px-2 py-1">
                </div>
                <div class="mb-2">
                    <label class="block text-gray-600 mb-1">Matériaux nécessaires</label>
                    <input type="text" name="steps[${stepIndex}][materials_needed]" class="w-full border rounded px-2 py-1">
                </div>
                <div class="mb-2">
                    <label class="block text-gray-600 mb-1">Outils requis</label>
                    <input type="text" name="steps[${stepIndex}][tools_required]" class="w-full border rounded px-2 py-1">
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 mt-2">Supprimer cette étape</button>
            `;
            container.appendChild(step);
            stepIndex++;
        }
        </script>
</div>
@endsection