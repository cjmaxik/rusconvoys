<?php

namespace App\Models\User;

use App\Jobs\UploadAvatarProcess;
use App\Models\User;

/**
 * Class AvatarDownloadObserver
 *
 * @package App\Models\User
 */
class AvatarDownloadObserver
{

    /**
     * @param \App\Models\User $user
     */
    public function updated(User $user)
    {
        if (config('app.stop_jobs')) {
            return;
        }
        $this->check($user);
    }

    /**
     * @param \App\Models\User $user
     */
    public function saved(User $user)
    {
        if (config('app.stop_jobs')) {
            return;
        }
        $this->check($user);
    }

    /**
     * @param \App\Models\User $user
     */
    private function check(User $user)
    {
        $changes = [];

        if (!$this->checkIfUrlIsCached($user->steam_avatar)) {
            $changes[] = 'steam_avatar';
        };

        if (!$this->checkIfUrlIsCached($user->truckersmp_avatar)) {
            $changes[] = 'truckersmp_avatar';
        };

        if (count($changes)) {
            dispatch(new UploadAvatarProcess($user, $changes));
        }
    }

    private function checkIfUrlIsCached($url)
    {
        return str_contains($url, '/storage/avatars/');
    }

}