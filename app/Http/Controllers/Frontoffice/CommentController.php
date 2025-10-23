<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Project;
use Illuminate\Http\Request;

class CommentController extends Controller {

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
        // Notification au propriétaire du projet
        $owner = $project->user;
        if ($owner && $owner->id !== auth()->id()) {
            $commenterName = auth()->user()->name;
            $projectTitle = $project->title;
            $owner->notify(new \App\Notifications\ProjectCommentedNotification($commenterName, $projectTitle, $project->id));
        }

        return redirect()->route('projects.show', $project->id)
            ->with('success', 'Commentaire ajouté !');
    }
    /**
     * Supprime un commentaire de l'utilisateur
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->user_id !== auth()->id()) {
            return redirect()->back()->with('error', "Vous n'avez pas le droit de supprimer ce commentaire.");
        }
        $comment->delete();
        return redirect()->back()->with('success', 'Commentaire supprimé !');
    }
}
