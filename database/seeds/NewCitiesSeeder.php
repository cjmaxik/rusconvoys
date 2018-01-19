<?php

use App\Models\City;
use App\Models\Country;
use App\Models\DLC;
use Illuminate\Database\Seeder;

/**
 * Class NewCitiesSeeder
 */
class NewCitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     * @throws \Throwable
     */
    public function run()
    {

        DB::transaction(function () {
            $content = json_decode(file_get_contents(base_path('database/seeds/cities.json')));
            $index = 1;

            foreach ($content as $game) {
                echo "\n\n\n" . $game->shortname . "\n";
                foreach ($game->countries as $country) {
                    foreach ($country->cities as $city) {
                        if (!City::where('name', $city->name)->count()) {
                            echo $city->name . "\n";

                            City::firstOrCreate([
                                'country_id' => Country::where('name', $country->name)->first()->id,
                                'name'       => $city->name,
                                'rus_name'   => trans('cities.' . $city->name),
                                'dlc_id'     => DLC::where('name', $city->dlc)->first()->id,
                            ]);
                        }
                    }

                    $index++;
                }
            }
        });
    }
}
