<?php

namespace App\Policies;

use App\Models\Convoy;
use App\Models\User;
use Cache;
use Illuminate\Auth\Access\HandlesAuthorization;
use Log;

/**
 * Class ConvoyPolicy
 *
 * @package App\Policies
 */
class ConvoyPolicy
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
     * Determine whether the user can view the convoy.
     *
     * @param \App\Models\User   $user
     * @param \App\Models\Convoy $convoy
     *
     * @return bool
     */
    public function view(User $user, Convoy $convoy): bool
    {
        // Administrators can do anything
        // Chain: admin moderator donator user
        if ($user->isGroup(config('roles.admins'))) {
            return true;
        }

        // Trashed convoys is not accessible at all
        // Chain: donator user
        if ($convoy->trashed()) {
            return false;
        }

        // Chain: donator user
        if ($convoy->status === 'draft') {
            if ($user->id !== $convoy->user_id) {
                return false;
            }
        }

        // Chain: donator user
        return true;
    }

    /**
     * Determine whether the user can create convoys.
     *
     * @param \App\Models\User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return !$user->isRole('banned');
    }

    /**
     * Return if user is privileged
     *
     * @param \App\Models\User $user
     *
     * @return bool
     */
    public function privileged(User $user): bool
    {
        return $user->isRole(config('roles.privileged'));
    }

    /**
     * @param \App\Models\User $user
     *
     * @return bool
     */
    public function create_within_timeout(User $user): bool
    {
        if (!$this->privileged($user)) {
            return !(boolean)Cache::has('convoy_timeout_' . $user->id);
        }

        return true;
    }

    /**
     * Determine whether the user can update the convoy.
     *
     * @param \App\Models\User   $user
     * @param \App\Models\Convoy $convoy
     *
     * @return bool
     */
    public function update(User $user, Convoy $convoy): bool
    {
        return $user->isGroup(config('roles.admins')) or $user->id === $convoy->user_id;
    }

    public function pin(User $user): bool
    {
        return $user->isGroup(config('roles.admins'));
    }

    /**
     * Determine whether the user can delete the convoy.
     *
     * @param \App\Models\User   $user
     * @param \App\Models\Convoy $convoy
     *
     * @return bool
     */
    public function delete(User $user, Convoy $convoy): bool
    {
        return $user->isGroup(config('roles.admins')) or $user->id === $convoy->user_id;
    }
    /**
     * Determine whether the user can cancel the convoy.
     *
     * @param \App\Models\User   $user
     * @param \App\Models\Convoy $convoy
     *
     * @return bool
     */
    public function cancel(User $user, Convoy $convoy): bool
    {
        return $user->isGroup(config('roles.admins')) or $user->id === $convoy->user_id;
    }
}
