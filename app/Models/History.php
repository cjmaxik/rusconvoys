<?php

namespace App\Models;

use App\Traits\dateLoc;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\History
 *
 * @property int $id
 * @property int $user_id
 * @property int $staff_id
 * @property string $type
 * @property string $from
 * @property string $to
 * @property string $message
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\History whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\History whereFrom($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\History whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\History whereMessage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\History whereStaffId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\History whereTo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\History whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\History whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\History whereUserId($value)
 * @mixin \Eloquent
 */
class History extends Model
{
    use dateLoc;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
