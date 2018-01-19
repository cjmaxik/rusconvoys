<?php

use Illuminate\Database\Seeder;

class ConstantTranslationSeeder extends Seeder
{
    /**
     * @return void
     * @throws \Exception
     * @throws \Throwable
     * @deprecated THIS IS NOT NEEDED!!!
     */
    public function run()
    {
        DB::transaction(function () {
            echo "\nCountries Translation...\n";
            foreach (App\Models\Country::get() as $country) {
                $country->rus_name = trim(trans('countries.' . $country->name));
                $country->save();

                echo "{$country->name} => {$country->rus_name}...\n";
            }

            echo "\nCities Translation...\n";
            foreach (App\Models\City::get() as $city) {
                $city->rus_name = trim(trans('cities.' . $city->name));
                $city->save();

                echo "{$city->name} => {$city->rus_name}...\n";
            }

            echo "\nDLCs Translation...\n";
            foreach (App\Models\DLC::get() as $dlc) {
                $dlc->screen_name = trim(trans('dlcs.' . $dlc->name));
                $dlc->save();

                echo "{$dlc->name} => {$dlc->screen_name}...\n";
            }
        });
    }
}
