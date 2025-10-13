<div class="bg-white rounded-lg shadow-md overflow-hidden">
    @if($project->photo)
        <img src="{{ asset('uploads/projects/' . $project->photo) }}" alt="{{ $project->title }}" class="w-full h-48 object-cover">
    @else
        <div class="w-full h-48 bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center">
    <div class="text-center">
        <i class="fas fa-recycle text-4xl text-green-400 mb-2"></i>
        <p class="text-sm text-green-600 font-medium"> {{ $project->title }}</p>
    </div>
</div>
    @endif
    
    <div class="p-4">
        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $project->title }}</h3>
        <p class="text-gray-700 mb-4">{{ Str::limit($project->description, 100) }}</p>
        <div class="flex justify-between items-center">
            <span class="text-sm text-gray-500">Difficulté: <strong>{{ $project->difficultyLabel }}</strong></span>
            <span class="text-sm text-gray-500">Impact: <strong>{{ $project->impact_score }}</strong></span>
        </div>
        <div class="mt-4">
            <a href="{{ route('projects.show', $project->id) }}" class="inline-flex items-center text-white bg-primary hover:bg-green-700 px-4 py-2 rounded-lg">
                <i class="fas fa-eye mr-2"></i> Voir Détails
            </a>
            @if(6 === $project->user_id)
                <a href="{{ route('projects.edit', $project->id) }}" class="inline-flex items-center text-gray-800 hover:text-green-500 px-4 py-2 rounded-lg">
                    <i class="fas fa-edit mr-2"></i> Éditer
                </a>
                <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700 px-4 py-2 rounded-lg">
                        <i class="fas fa-trash-alt mr-2"></i> Supprimer
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>