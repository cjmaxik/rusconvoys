<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Invite
 *
 * @mixin \Eloquent
 */
class Invite extends Model
{
    use SoftDeletes;
}
