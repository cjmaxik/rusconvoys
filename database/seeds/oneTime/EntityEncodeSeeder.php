<?php

use App\Models\City;
use App\Models\Comment;
use App\Models\Convoy;
use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Class EntityEncodeSeeder
 */
class EntityEncodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $convoys = Convoy::withTrashed()->get();
        foreach ($convoys as $convoy) {
            $convoy->title = htmlentities(html_entity_decode($convoy->title));
            $convoy->start_place = htmlentities(html_entity_decode($convoy->start_place));
            $convoy->finish_place = htmlentities(html_entity_decode($convoy->finish_place));
            $convoy->stops = htmlentities(html_entity_decode($convoy->stops));
            $convoy->voice_description = htmlentities(html_entity_decode($convoy->voice_description));
            $convoy->cancelled_message = htmlentities(html_entity_decode($convoy->cancelled_message));

            $convoy->update();
        }

        $users = User::all();
        foreach ($users as $user) {
            $user->nickname = htmlentities(html_entity_decode($user->nickname));
            $user->tag = htmlentities(html_entity_decode($user->tag));
            $user->steam_username = htmlentities(html_entity_decode($user->steam_username));
            $user->truckersmp_username = htmlentities(html_entity_decode($user->truckersmp_username));

            $user->update();
        }

        $cities = City::all();
        foreach ($cities as $city) {
            $city->name = htmlentities(html_entity_decode($city->name));
            $city->rus_name = htmlentities(html_entity_decode($city->rus_name));

            $city->update();
        }

        $countries = Country::all();
        foreach ($countries as $country) {
            $country->name = htmlentities(html_entity_decode($country->name));
            $country->rus_name = htmlentities(html_entity_decode($country->rus_name));

            $country->update();
        }

        $comments = Comment::withTrashed()->get();
        foreach ($comments as $comment) {
            $comment->text = htmlentities(html_entity_decode($comment->text));

            $comment->update();
        }

        Cache::flush();
    }
}
