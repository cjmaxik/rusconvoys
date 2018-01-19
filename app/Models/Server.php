<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Server
 *
 * @property int                                                                $id
 * @property int                                                                $game_id
 * @property bool                                                               $online
 * @property int                                                                $actual_id
 * @property string                                                             $name
 * @property string                                                             $shortname
 * @property \Carbon\Carbon                                                     $created_at
 * @property \Carbon\Carbon                                                     $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Convoy[] $convoys
 * @property-read \App\Models\Game                                              $game
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Server whereActualId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Server whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Server whereGameId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Server whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Server whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Server whereOnline($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Server whereShortname($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Server whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Server extends Model
{
    use SoftDeletes;

//    public $timestamps = false;
    protected $dates = ['deleted_at'];
    protected $guarded = [];
    protected $casts = [
        'speedlimiter' => 'boolean',
        'online'       => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function convoys()
    {
        return $this->belongsToMany(Convoy::class);
    }

    /**
     * @return string
     */
    public function getWithGame()
    {
        return $this->game->name . ': ' . $this->name;
    }
}
