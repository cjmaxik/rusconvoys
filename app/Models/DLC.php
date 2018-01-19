<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

/**
 * App\Models\DLC
 *
 * @property int $id
 * @property string $name
 * @property string $screen_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\City[] $cities
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DLC whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DLC whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\DLC whereScreenName($value)
 * @mixin \Eloquent
 */
class DLC extends Model
{
    protected $table = 'dlcs';
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
