<?php

namespace App\Models;

use App\Traits\nameLoc;
use Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\City
 *
 * @property int $id
 * @property int $country_id
 * @property string City name $name
 * @property string $rus_name
 * @property string $dlc_id
 * @property-read \App\Models\Country $country
 * @property-read \App\Models\DLC $dlc
 * @method static \Illuminate\Database\Query\Builder|\App\Models\City dlcId($dlc_id)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\City whereCountryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\City whereDlcId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\City whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\City whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\City whereRusName($value)
 * @mixin \Eloquent
 */

class City extends Model
{
    public $timestamps = false;
    protected $casts = [
        'id' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dlc()
    {
        return $this->belongsTo(DLC::class);
    }

    /**
     * @return string
     */
    public function getWithCountry()
    {
        return $this->country->name . ': ' . $this->name;
    }

    /**
     * Get city name depends on rus_names setting
     *
     * @param $name
     *
     * @return string City name
     * @internal param string $name City name
     */
    public function getNameAttribute($name)
    {
        if (Auth::check() and Auth::user()->getOption('rus_names')) {
            return $this->rus_name;
        };

        return $name;
    }

    /**
     * @param $query
     * @param $dlc_id
     *
     * @return mixed
     */
    public function scopeDlcId($query, $dlc_id)
    {
        return $query->select('dlc_id')->where('id', $dlc_id)->pluck('dlc_id')->first();
    }

}
