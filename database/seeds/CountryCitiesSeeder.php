<?php

use App\Models\City;
use App\Models\Country;
use App\Models\DLC;
use App\Models\Game;
use Illuminate\Database\Seeder;

class CountryCitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $dlcs = [
            ['name' => 'base', 'screen_name' => 'Базовая игра'],
            ['name' => 'dlc_east', 'screen_name' => 'Going East'],
            ['name' => 'dlc_north', 'screen_name' => 'Scandinavia'],
            ['name' => 'dlc_fr', 'screen_name' => 'Vive la France'],
        ];
        DLC::insert($dlcs);

        $content = json_decode(file_get_contents(base_path('database/seeds/cities.json')));

        $index = 1;

        foreach ($content as $game) {
            Game::insert([
                'id'        => $game->id,
                'name'      => $game->name,
                'shortname' => $game->shortname,
            ]);

            foreach ($game->countries as $country) {
                Country::insert([
                    'game_id'  => $game->id,
                    'name'     => $country->name,
                    'rus_name' => trans('countries.' . $country->name),
                ]);

                foreach ($country->cities as $city) {
                    City::firstOrCreate([
                        'country_id' => $index,
                        'name'       => $city->name,
                        'rus_name'   => trans('cities.' . $city->name),
                        'dlc_id'     => DLC::where('name', $city->dlc)->first()->id,
                    ]);
                }

                $index++;
            }
        }
    }
}
