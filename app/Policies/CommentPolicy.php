<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class CommentPolicy
 *
 * @package App\Policies
 */
class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\User $user
     * @param                  $ability
     *
     * @return bool
     */
    public function before(User $user, $ability)
    {
        // Rule for GODS
        if ($user->isRole('admin') and config('app.env') === 'production') {
            return true;
        }
    }

    /**
     * Determine whether the user can view the comment.
     *
     * @param \App\Models\User    $user
     * @param \App\Models\Comment $comment
     *
     * @return bool
     */
    public function view(User $user, Comment $comment): bool
    {
        if ($user->isGroup(config('roles.admins'))) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the deleted comment.
     *
     * @param \App\Models\User $user
     *
     * @return bool
     * @internal param \App\Models\Comment $comment
     */
    public function see_deleted(User $user): bool
    {
        if ($user->isGroup(config('roles.admins'))) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create comments.
     *
     * @param \App\Models\User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the comment.
     *
     * @param \App\Models\User    $user
     * @param \App\Models\Comment $comment
     *
     * @return bool
     */
    public function update(User $user, Comment $comment): bool
    {
//        if ($user->id === $comment->user_id) {
//            $now = Carbon::now();
//            $created = $comment->created_at;
//
//            if ($now->diffInSeconds($created, true) <= 60 * 5) {
//                return true;
//            };
//        }

        return false;
    }

    /**
     * Determine whether the user can delete the comment.
     *
     * @param \App\Models\User    $user
     * @param \App\Models\Comment $comment
     *
     * @return bool
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->isGroup(config('roles.admins')) or $user->id === $comment->user_id;
    }
}
