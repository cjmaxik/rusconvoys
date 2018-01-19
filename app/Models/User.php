<?php

namespace App\Models;

use App\Traits\dateLoc;
use Carbon\Carbon;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use HttpOz\Roles\Models\Role;
use HttpOz\Roles\Traits\HasRole;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

/**
 * App\Models\User
 *
 * @property int                                                                                                            $id
 * @property string                                                                                                         $nickname
 * @property string                                                                                                         $email
 * @property bool                                                                                                           $subscribe
 * @property string                                                                                                         $slug
 * @property string                                                                                                         $timezone
 * @property string                                                                                                         $about
 * @property string                                                                                                         $tag
 * @property string                                                                                                         $tag_color
 * @property string                                                                                                         $steam_username
 * @property string                                                                                                         $steamid
 * @property string                                                                                                         $truckersmp_username
 * @property string                                                                                                         $truckersmpid
 * @property string                                                                                                         $steam_avatar
 * @property string                                                                                                         $truckersmp_avatar
 * @property bool                                                                                                           $is_steam_avatar
 * @property array                                                                                                          $options
 * @property object                                                                                                         $ban
 * @property bool                                                                                                           $rules_accepted
 * @property string                                                                                                         $remember_token
 * @property \Carbon\Carbon                                                                                                 $created_at
 * @property \Carbon\Carbon                                                                                                 $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[]                                            $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Convoy[]                                             $convoys
 * @property-read mixed                                                                                                     $avatar
 * @property-read int                                                                                                       $drafts_count
 * @property-read mixed                                                                                                     $role
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\History[]                                            $histories
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Participation[]                                      $participations
 * @property-read \Illuminate\Database\Eloquent\Collection|\HttpOz\Roles\Models\Role[]                                      $roles
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereAbout($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereBan($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereIsSteamAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereNickname($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereOptions($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereRulesAccepted($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereSteamAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereSteamUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereSteamid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereSubscribe($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereTag($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereTagColor($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereTimezone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereTruckersmpAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereTruckersmpUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereTruckersmpid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements HasRoleContract
{
    use HasRole, Notifiable, dateLoc;

    /**
     * The attributes that should be visible in arrays.
     *
     * @var array
     */
    protected $visible = ['id', 'nickname', 'tag', 'slug', 'avatar', 'role'];
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['avatar', 'role'];
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['remember_token'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['remember_token'];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array',
        'ban'     => 'object',
        'history' => 'object',
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
     * Get the route for the notifications
     *
     * @return mixed
     */
    public function routeNotificationForMail()
    {
        return $this->email;
    }

    /**
     * Comments OneToMany Relation
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @return mixed
     */
    public function getOwnConvoys()
    {
        return $this->convoys()->whereNotIn('status', ['draft', 'cancelled'])->get();
    }

    /**
     * Convoys OneToMany Relation
     *
     * @return Convoys Collection
     */
    public function convoys()
    {
        return $this->hasMany(Convoy::class);
    }

    /**
     * @return mixed
     */
    public function getDrafts()
    {
        return $this->convoys()->where('status', 'draft')->get();
    }

    /**
     * @return mixed
     */
    public function getOwnConvoysCount()
    {
        return $this->convoys()->whereNotIn('status', ['draft', 'cancelled'])->count();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getConvoysParticipations()
    {
        return $this->participations()->with(['convoy' => function ($query) {
            $query->orderBy('created_at', 'desc')->whereNotIn('status', ['draft', 'cancelled']);
        }])->get()->pluck('convoy')->filter(function ($value) {
            return $value !== null;
        });
    }

    /**
     * Participations OneToMany Relation
     *
     * @return Participation Collection
     */
    public function participations()
    {
        return $this->hasMany(Participation::class);
    }

    /**
     * @return mixed
     */
    public function getHistory()
    {
        return $this->histories()->orderBy('created_at', 'desc')->get();
    }

    /**
     * Histories OneToMany Relation
     *
     * @return Histories collection
     */
    public function histories()
    {
        return $this->hasMany(History::class);
    }

    /**
     * @return mixed
     */
    public function getParticipations()
    {
        return $this->participations()->get();
    }

    /**
     * OPTIONS
     */

    /**
     * @return mixed
     */
    public function getAvatarAttribute()
    {
        if (($this->is_steam_avatar)) {
            return $this->steam_avatar;
        } else {
            return $this->truckersmp_avatar;
        }
    }

    /**
     * @return int
     */
    public function getDraftsCountAttribute()
    {
        return $this->convoys()
            ->where('user_id', $this->id)
            ->where('status', 'draft')
            ->count();
    }

    /**
     * Search through user site options.
     *
     * @param $needle
     *
     * @return bool
     */
    public function getOption($needle)
    {
        if (is_array($this->options)) {
            if (array_key_exists($needle, $this->options)) {
                return $this->options[$needle];
            };
        }

        return false;
    }

    /**
     * Set all the options
     *
     * @param array $new_options New options to set up
     */
    public function setOptions($new_options)
    {
        $old = config('site-vars.user_options');
        $new = $new_options;

        if (is_array($new_options)) {
            foreach ($old as $option => $value) {
                if (array_key_exists($option, $new)) {
                    ($new[$option] == 'on') ? $old[$option] = true : $old[$option] = $new[$option];
                }
            }
        }

        $this->options = $old;
        $this->save();
    }

    /**
     * Get Cached role attribute
     *
     * @return mixed
     */
    public function getRoleAttribute()
    {
        return $this->getRoles()->first();
    }

    /**
     * @param $message
     */
    public function ban($message)
    {
        $old_role = $this->role->description;

        $ban = [
            'message'   => $message,
            'timestamp' => Carbon::now()->format('d.m.Y H:i:s'),
        ];

        $this->assignNewRole('banned', false);
        $this->ban = $ban;
        $this->save();

        $this->addToHistory('ban', $old_role, 'Забаненный', $ban['message']);
    }

    /**
     * @param      $role
     * @param bool $add_to_history
     * @param bool $first
     */
    public function assignNewRole($role, $add_to_history = true, $first = false)
    {
        if ($add_to_history) {
            $old_role = $first ? 'Дальнобойщик' : $this->role->description;
        }

        $this->detachAllRoles();

        $new_role = Role::whereSlug($role)->first();
        $this->attachRole($new_role);

        if ($add_to_history) {
            $this->addToHistory('role_change', $old_role, $new_role->description);
        }
    }

    /**
     * Added new history entry
     *
     * @param string      $type    type
     * @param null|string $from    Initial state
     * @param null|string $to      New state
     * @param null|string $message Message
     */
    public function addToHistory(string $type, string $from = null, string $to = null, string $message = null)
    {
        History::create([
            'user_id'  => $this->id,
            'type'     => $type,
            'from'     => $from,
            'to'       => $to,
            'message'  => $message,
            'staff_id' => Auth::id(),
        ]);
    }

    /**
     * @param string $role
     */
    public function unban($role = 'user')
    {
        $this->ban = false;
        $this->save();
        $this->assignNewRole($role, false);

        $this->addToHistory('unban', 'Забаненный', $this->role->description);
    }

    /**
     * Get user signature to Chatbro
     *
     * @return string signature
     */
    public function getChatbroSignature()
    {
        $signature = config('app.domain') .
            $this->id .
            addslashes($this->nickname) .
            $this->fullAvatarLink() .
            route('profile_page', ['slug' => $this->slug], true);

        if ($this->isGroup(config('roles.admins'))) {
            $signature .= 'bandelete';
        }

        return md5($signature . config('app.CHATBRO_token'));
    }

    public function fullAvatarLink()
    {
        if (str_contains($this->avatar, '/storage/avatars/')) {
            return config('app.url') . $this->avatar;
        }

        return $this->avatar;
    }

    /**
     * Check if user is in certain group
     * (since ACL package has no function about this)
     * (it is LAME)
     *
     * @param  string $group Group name
     *
     * @return boolean        Answer
     */
    public function isGroup($group)
    {
        return $this->group() === $group;
    }

}
