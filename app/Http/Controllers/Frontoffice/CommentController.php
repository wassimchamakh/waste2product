<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Project;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Enregistre un nouveau commentaire pour un projet
     */
    public function store(Request $request, $projectId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $project = Project::findOrFail($projectId);

        Comment::create([
            'user_id' => auth()->id(),
            'project_id' => $project->id,
            'content' => $request->input('content'),
        ]);

        return redirect()->route('projects.show', $project->id)
            ->with('success', 'Commentaire ajouté !');
    }
}
