<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Participation
 *
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $convoy_id
 * @property int $user_id
 * @property string $type
 * @property-read \App\Models\Convoy $convoy
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Participation whereConvoyId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Participation whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Participation whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Participation whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Participation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Participation whereUserId($value)
 * @mixin \Eloquent
 */
class Participation extends Model
{

    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function convoy()
    {
        return $this->belongsTo(Convoy::class);
    }
}
