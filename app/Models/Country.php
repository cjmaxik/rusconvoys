<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

/**
 * App\Models\Country
 *
 * @property int $id
 * @property int $game_id
 * @property \Illuminate\Contracts\Translation\Translator|string $name
 * @property string $rus_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\City[] $cities
 * @property-read \App\Models\Game $game
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Country whereGameId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Country whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Country whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Country whereRusName($value)
 * @mixin \Eloquent
 */
class Country extends Model
{
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * @param $name
     *
     * @return \Illuminate\Contracts\Translation\Translator|string
     */
    public function getNameAttribute($name)
    {
        if (Auth::check() and Auth::user()->getOption('rus_names')) {
            return $this->rus_name;
        };

        return $name;
    }
}
