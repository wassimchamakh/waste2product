<?php

namespace App\Observers;

use App\Models\Project;
use App\Helpers\NotificationHelper;

/**
 * Project Observer - Auto-create notifications when project events occur
 * 
 * To register this observer, add to AppServiceProvider boot():
 * Project::observe(ProjectObserver::class);
 */
class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        // Notify the user that their project was created successfully
        NotificationHelper::projectCreated(
            $project->user,
            $project->title,
            $project->id
        );
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        // Check if the project status changed to approved
        if ($project->isDirty('status') && $project->status === 'approved') {
            NotificationHelper::projectApproved(
                $project->user,
                $project->title,
                $project->id
            );
        }
    }
}
