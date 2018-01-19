<?php

namespace App\Models;

use App\Traits\dateLoc;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Convoy
 *
 * @property int                                                                       $id
 * @property string                                                                    $title
 * @property string                                                                    $slug
 * @property bool                                                                      $pinned
 * @property int                                                                       $user_id
 * @property int                                                                       $server_id
 * @property string                                                                    $meeting_datetime
 * @property string                                                                    $leaving_datetime
 * @property int                                                                       $start_town_id
 * @property string                                                                    $start_place
 * @property int                                                                       $finish_town_id
 * @property string                                                                    $finish_place
 * @property string                                                                    $stops
 * @property string                                                                    $background_url
 * @property string                                                                    $background_url_safe
 * @property string                                                                    $map_url
 * @property string                                                                    $map_url_safe
 * @property string                                                                    $voice_description
 * @property string                                                                    $description
 * @property string                                                                    $cancelled_message
 * @property string                                                                    $status
 * @property bool                                                                      $mailed
 * @property string                                                                    $deleted_at
 * @property \Carbon\Carbon                                                            $created_at
 * @property \Carbon\Carbon                                                            $updated_at
 * @property array                                                                     $dlcs
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[]       $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[]       $commentsWithTrashed
 * @property-read \App\Models\City                                                     $finish_town
 * @property-read mixed                                                                $participations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Participation[] $participations
 * @property-read \App\Models\Server                                                   $server
 * @property-read \App\Models\City                                                     $start_town
 * @property-read \App\Models\User                                                     $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereBackgroundUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereBackgroundUrlSafe($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereCancelledMessage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereDlcs($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereFinishPlace($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereFinishTownId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereLeavingDatetime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereMailed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereMapUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereMapUrlSafe($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereMeetingDatetime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy wherePinned($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereServerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereStartPlace($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereStartTownId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereStops($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Convoy whereVoiceDescription($value)
 * @mixin \Eloquent
 */
class Convoy extends Model
{
    use dateLoc, SoftDeletes;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'dlcs' => 'array',
    ];

    /**
     * List of guarded attributes
     *
     * @var array
     */
    protected $guarded = [
        'mailed', 'slug',
    ];

    /**
     * The attributes that should be visible in arrays.
     *
     * @var array
     */
    protected $visible = [
        'id', 'title', 'start_town', 'finish_town', 'user', 'status',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participations()
    {
        return $this->hasMany(Participation::class);
    }

    /**
     * @return mixed
     */
    public function commentsWithTrashed()
    {
        return $this->hasMany(Comment::class)->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function start_town()
    {
        return $this->hasOne(City::class, 'id', 'start_town_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function finish_town()
    {
        return $this->hasOne(City::class, 'id', 'finish_town_id');
    }

    public function server()
    {
        return $this->hasOne(Server::class, 'id', 'server_id')->withTrashed();
    }

    /**
     * @param bool $short
     *
     * @return string
     */
    public function getServerName($short = false)
    {
        if ($short) {
            return $this->server->game->shortname . ', ' . $this->server->shortname;
        }

        return $this->server->game->name . ', ' . $this->server->name;
    }

    /**
     * Get title for SEO
     *
     * @return string Title
     */
    public function getSEOTitle()
    {
        if (!$this->title) {
            return 'из ' . $this->start_town->name . ' в ' . $this->finish_town->name;
        }

        return $this->title;
    }

    /**
     * Get title for SEO
     *
     * @return string Title
     */
    public function getNormTitle()
    {
        return !$this->title ? $this->getRoute() : $this->title;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->start_town->name . ' - ' . $this->finish_town->name;
    }

    /**
     * @return mixed
     */
    public function getParticipationsCountAttribute()
    {
        return $this->participations->count() - 1;
    }
}
