<?php

namespace App\Policies;

use App\Models\ForumComment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ForumCommentPolicy
{
    /**
     * Determine whether the user can view any models.
     * Anyone can view comments
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * Anyone can view non-spam comments
     */
    public function view(?User $user, ForumComment $forumComment): bool
    {
        // Non-spam comments are public
        if (!$forumComment->is_spam) {
            return true;
        }

        // Author and admins can view spam comments
        if ($user && ($forumComment->user_id === $user->id || $user->isAdmin())) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     * Any authenticated user can comment
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     * Author or admin can edit (within time limit)
     */
    public function update(User $user, ForumComment $forumComment): bool
    {
        // Check if post is locked
        if ($forumComment->forumPost->is_locked && !$user->isAdmin()) {
            return false;
        }

        // Admins can always edit
        if ($user->isAdmin()) {
            return true;
        }

        // Authors can edit their own comments within 15 minutes
        if ($user->id === $forumComment->user_id) {
            return $forumComment->created_at->diffInMinutes(now()) < 15;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     * Author or admin can delete
     */
    public function delete(User $user, ForumComment $forumComment): bool
    {
        // Check if post is locked
        if ($forumComment->forumPost->is_locked && !$user->isAdmin()) {
            return false;
        }

        return $user->id === $forumComment->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     * Only admins can restore
     */
    public function restore(User $user, ForumComment $forumComment): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     * Only admins can force delete
     */
    public function forceDelete(User $user, ForumComment $forumComment): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can vote on the comment.
     * Any authenticated user can vote (except on their own comments)
     */
    public function vote(User $user, ForumComment $forumComment): bool
    {
        return $user->id !== $forumComment->user_id;
    }

    /**
     * Determine whether the user can mark comment as best answer.
     * Only the post author or admin can mark best answer
     */
    public function markAsBestAnswer(User $user, ForumComment $forumComment): bool
    {
        return $user->id === $forumComment->forumPost->user_id || $user->isAdmin();
    }
}
