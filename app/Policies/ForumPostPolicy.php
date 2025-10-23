<?php

namespace App\Policies;

use App\Models\ForumPost;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ForumPostPolicy
{
    /**
     * Determine whether the user can view any models.
     * Anyone can browse posts (even guests)
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * Anyone can view a published post
     */
    public function view(?User $user, ForumPost $forumPost): bool
    {
        // Published posts are public
        if ($forumPost->status === 'published' && !$forumPost->is_spam) {
            return true;
        }

        // Author can view their own drafts
        if ($user && $forumPost->user_id === $user->id) {
            return true;
        }

        // Admins can view everything
        if ($user && $user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     * Any authenticated user can create posts
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     * Author or admin can edit
     */
    public function update(User $user, ForumPost $forumPost): bool
    {
        // Locked posts cannot be edited (except by admins)
        if ($forumPost->is_locked && !$user->isAdmin()) {
            return false;
        }

        return $user->id === $forumPost->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     * Author or admin can delete
     */
    public function delete(User $user, ForumPost $forumPost): bool
    {
        return $user->id === $forumPost->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     * Only admins can restore soft-deleted posts
     */
    public function restore(User $user, ForumPost $forumPost): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     * Only admins can force delete
     */
    public function forceDelete(User $user, ForumPost $forumPost): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can pin/unpin the post.
     * Only admins can pin posts
     */
    public function pin(User $user, ForumPost $forumPost): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can lock/unlock the post.
     * Only admins can lock posts
     */
    public function lock(User $user, ForumPost $forumPost): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can vote on the post.
     * Any authenticated user can vote (except on their own posts)
     */
    public function vote(User $user, ForumPost $forumPost): bool
    {
        return $user->id !== $forumPost->user_id;
    }
}
