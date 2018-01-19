<?php

namespace App\Traits;

use Auth;
use Jenssegers\Date\Date;

trait dateLoc
{

    public function dateTz(string $value)
    {
        $date = new Date($value, Auth::user()->timezone);
        $date->setTimezone(config('app.timezone'));

        return $date;
    }

    public function datePicker(string $attribute)
    {
        $date = $this->dateLoc($attribute);

        return $date->format(trans('app.datetime_picker_format'));
    }

    public function dateLoc(string $attribute)
    {
        $date = new Date($this->{$attribute});
        $date->setLocale(config('app.locale'));
        if (Auth::check()) {
            $date->setTimezone(Auth::user()->timezone);
        };

        return $date;
    }
}
