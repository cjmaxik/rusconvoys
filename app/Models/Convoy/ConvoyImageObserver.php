<?php

namespace App\Models\Convoy;


use App\Jobs\UploadImageProcess;
use App\Models\Convoy;


class ConvoyImageObserver
{

    /**
     * @param \App\Models\Convoy $convoy
     */
    public function created(Convoy $convoy)
    {
        if (config('app.stop_jobs')) {
            return;
        }
        dispatch(new UploadImageProcess($convoy));
    }

    /**
     * @param \App\Models\Convoy $convoy
     */
    public function updated(Convoy $convoy)
    {
        if (config('app.stop_jobs')) {
            return;
        }
        dispatch(new UploadImageProcess($convoy));
    }

}