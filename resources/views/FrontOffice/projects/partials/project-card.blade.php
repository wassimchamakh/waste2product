<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
    @if($project->photo)
        <img src="{{ asset('uploads/projects/' . $project->photo) }}" alt="{{ $project->title }}" class="w-full h-40 sm:h-48 object-cover">
    @else
        <div class="w-full h-40 sm:h-48 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 flex items-center justify-center">
    <div class="text-center px-4">
        <i class="fas fa-recycle text-3xl sm:text-4xl text-green-400 mb-2"></i>
        <p class="text-xs sm:text-sm text-green-600 dark:text-green-400 font-medium line-clamp-2">{{ $project->title }}</p>
    </div>
</div>
    @endif
    
    <div class="p-3 sm:p-4">
        <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-2 line-clamp-2">{{ $project->title }}</h3>
        <p class="text-sm sm:text-base text-gray-700 mb-3 sm:mb-4 line-clamp-2">{{ Str::limit($project->description, 100) }}</p>
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-0 mb-3 sm:mb-4">
            <span class="text-xs sm:text-sm text-gray-500">Difficulté: <strong>{{ $project->difficultyLabel }}</strong></span>
            <span class="text-xs sm:text-sm text-gray-500">Impact: <strong>{{ $project->impact_score }}</strong></span>
        </div>
        <div class="flex flex-col sm:flex-row flex-wrap gap-2 sm:gap-3">
            <a href="{{ route('projects.show', $project->id) }}" class="inline-flex items-center justify-center text-white bg-primary hover:bg-green-700 px-3 sm:px-4 py-2 rounded-lg transition-colors text-sm sm:text-base flex-1 sm:flex-initial">
                <i class="fas fa-eye mr-2"></i> <span class="hidden sm:inline">Voir Détails</span><span class="sm:inline md:hidden">Voir</span>
            </a>
            @if(6 === $project->user_id)
                <a href="{{ route('projects.edit', $project->id) }}" class="inline-flex items-center justify-center text-gray-800 hover:text-green-500 px-3 sm:px-4 py-2 rounded-lg transition-colors text-sm sm:text-base">
                    <i class="fas fa-edit mr-2"></i> <span class="hidden lg:inline">Éditer</span>
                </a>
                <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="inline w-full sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full sm:w-auto text-red-500 hover:text-red-700 px-3 sm:px-4 py-2 rounded-lg transition-colors text-sm sm:text-base">
                        <i class="fas fa-trash-alt mr-2"></i> <span class="hidden lg:inline">Supprimer</span>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>