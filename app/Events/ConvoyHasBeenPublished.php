<?php

namespace App\Events;

use App\Models\Convoy;
use Illuminate\Queue\SerializesModels;

/**
 * Class ConvoyHasBeenPublished
 *
 * @package App\Events
 */
class ConvoyHasBeenPublished
{
    use SerializesModels;

    public $title;
    public $nickname;
    public $link;
    public $slack_link;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Convoy $convoy
     */
    public function __construct(Convoy $convoy)
    {
        $this->title      = html_entity_decode($convoy->getNormTitle());
        $this->nickname   = html_entity_decode($convoy->user->nickname);
        $this->link       = route('convoy_show', ['slug' => $convoy->slug]);
        $this->slack_link = route('convoy.id_redirect', ['id' => $convoy->id]);
    }
}
