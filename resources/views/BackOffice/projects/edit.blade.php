@extends('BackOffice.layouts.app')

@section('title', 'Modifier le Projet')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Modifier le Projet</h1>

<<<<<<< HEAD
    <form action="{{ route('admin.projects.update', $project->id) }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        @method('PUT')
=======
    <form action="{{ route('admin.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
    @csrf
    @method('PUT')
>>>>>>> tutoral-branch

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

<<<<<<< HEAD
=======
        <!-- Photo principale du projet -->
        <div class="mb-6">
            <label for="photo" class="block text-gray-700 font-medium mb-2">Photo principale du projet</label>
            @if($project->photo)
                <img src="{{ asset('uploads/projects/' . $project->photo) }}" alt="Photo du projet" class="mb-2 max-h-40 rounded shadow">
            @endif
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
                @foreach($project->steps as $i => $step)
                <div class="step-item mb-4 p-4 border rounded-lg bg-gray-50">
                    <div class="mb-2">
                        <label class="block text-gray-600 mb-1">Titre de l'étape</label>
                        <input type="text" name="steps[{{ $i }}][title]" value="{{ $step->title }}" class="w-full border rounded px-2 py-1" required>
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-600 mb-1">Description de l'étape</label>
                        <textarea name="steps[{{ $i }}][description]" class="w-full border rounded px-2 py-1" rows="2" required>{{ $step->description }}</textarea>
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-600 mb-1">Durée estimée</label>
                        <input type="text" name="steps[{{ $i }}][duration]" value="{{ $step->duration }}" class="w-full border rounded px-2 py-1">
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-600 mb-1">Matériaux nécessaires</label>
                        <input type="text" name="steps[{{ $i }}][materials_needed]" value="{{ $step->materials_needed }}" class="w-full border rounded px-2 py-1">
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-600 mb-1">Outils requis</label>
                        <input type="text" name="steps[{{ $i }}][tools_required]" value="{{ $step->tools_required }}" class="w-full border rounded px-2 py-1">
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 mt-2">Supprimer cette étape</button>
                </div>
                @endforeach
            </div>
            <button type="button" onclick="addStep()" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 mt-2">Ajouter une étape</button>
        </div>

>>>>>>> tutoral-branch
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.projects.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Annuler</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Mettre à jour</button>
        </div>
    </form>
<<<<<<< HEAD
=======
    <script>
    let stepIndex = {{ $project->steps->count() }};
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
>>>>>>> tutoral-branch
</div>
@endsection
