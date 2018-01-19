<?php

namespace App\Models\User;

use App\Models\User;

class UserSlugObserver
{

    /**
     * Generate slug on creating user
     *
     * @param  User $user
     *
     * @return void
     */
    public function creating(User $user)
    {
        $user->slug = str_slug($this->getLastId() + 1 . ' - ' . html_entity_decode($user->nickname));
    }

    /**
     * Get last User ID
     *
     * @return int Last ID
     */
    private function getLastId()
    {
        $last = User::orderBy('id', 'desc')->first();

        return $last ? $last->id : 0;
    }

    /**
     * Update slug if nickname was changed
     *
     * @param  User $user
     *
     * @return void
     */
    public function updating(User $user)
    {
        $user->slug = str_slug($user->id . ' - ' . html_entity_decode($user->nickname));
    }

}
