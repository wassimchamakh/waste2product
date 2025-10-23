<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectLike;

class ProjectLikeController extends Controller
{
    public function toggle($projectId)
    {
        $userId = auth()->id();
        $project = Project::findOrFail($projectId);
        $like = ProjectLike::where('user_id', $userId)->where('project_id', $projectId)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            ProjectLike::create([
                'user_id' => $userId,
                'project_id' => $projectId,
            ]);
            $liked = true;
            // Notification au propriétaire du projet
            $owner = $project->user;
            if ($owner && $owner->id !== $userId) {
                $likerName = auth()->user()->name;
                $projectTitle = $project->title;
                $owner->notify(new \App\Notifications\ProjectLikedNotification($likerName, $projectTitle, $project->id));
            }
        }

        $likesCount = $project->likes()->count();

        return response()->json([
            'liked' => $liked,
            'likes_count' => $likesCount,
        ]);
    }
}
