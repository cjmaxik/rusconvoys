<?php

namespace App\Models\Convoy;

use App\Models\Convoy;

/**
 * Class ConvoySlugObserver
 *
 * @package App\Models\Convoy
 */
class ConvoySlugObserver
{

    /**
     * Generate slug on creating convoy
     *
     * @param  Convoy $convoy
     *
     * @return void
     */
    public function creating(Convoy $convoy)
    {
        $convoy->slug = str_slug($this->getLastId() + 1);
    }

    /**
     * Get last Convoy ID
     *
     * @return int Last ID
     */
    private function getLastId()
    {
        $last = Convoy::withTrashed()->orderBy('id', 'desc')->first();

        return $last ? $last->id : 0;
    }

    /**
     * @param \App\Models\Convoy $convoy
     */
    public function updating(Convoy $convoy)
    {
        if ($convoy->slug === null) {
            $convoy->slug = str_slug($convoy->id . ' - ' . $this->getNormTitle($convoy));
        }
    }

    /**
     * Get normalize convoy title
     *
     * @param \App\Models\Convoy $convoy Convoy
     *
     * @return string normalized title
     */
    private function getNormTitle(Convoy $convoy)
    {
//        dd($convoy);
        if (!$convoy->title) {
            return $convoy->start_town->name . ' - ' . $convoy->finish_town->name;
        } else {
            return str_limit(html_entity_decode($convoy->title), 30);
        }
    }

}
