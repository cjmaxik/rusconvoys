<?php
use Jenssegers\Date\Date;


/**
 * @param $time
 *
 * @return \Jenssegers\Date\Date
 */
function timeLoc($time)
{
    $date = new Date($time);

    $date->setLocale(config('app.locale'));
    if (\Auth::check()) {
        $date->setTimezone(\Auth::user()->timezone);
    };
    return $date;
}

/**
 * @param $time
 *
 * @return string
 */
function timePicker($time)
{
    $date = new Date($time);
    $date = timeLoc($date);
    return $date->format('Y-m-d\TH:i:s');
}