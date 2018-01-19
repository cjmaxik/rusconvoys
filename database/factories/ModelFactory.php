<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/


/** @var \Illuminate\Database\Eloquent\FactoryBuilder $factory */
$factory->define(App\Models\Convoy::class, function (Faker\Generator $faker) {
    $localisedFaker = Faker\Factory::create("ru_RU");
    $html = '<div style="background: #fff;">
                    <div class="conv-desc">
                      <h1 style="font-family: arial;font-size: 22px;">Конвой в Euro Truck Simulator 2 от LOL Truckers</h1>
                      <h2 style="font-family: arial;font-size: 18px;">10.02.2017</h2>
                      <p class="opt-conv"><i class="fa fa-eye" aria-hidden="true"></i> - 8 <i class="fa fa-commenting-o" aria-hidden="true" style="margin-left: 15px;"></i> - 0 <i style="margin-left: 15px;" class="fa fa-unlock" aria-hidden="true"></i> - открытый

                      </p>
                      <ul>

                        <li>Старт: Uppsala (DREKKAR-TRANS)</li>
                        <li>Финиш: Calais (NBFC)</li>
                        <li>Общее расстояние: ~ 1947км</li>
                        <li>Сервер: Europe 2 | Загружен на 26% (916 из 3500) </li>
                        <li>Сборы: 19:30</li>
                        <li>Выезд: 20:00</li>
                        <li>Отдых: на усмотрение ведущего</li>

                      </ul>
                      <div class="box-body">Окраска Официальная для компании LOL Truckers <br>
на момент начало конвоя быть в TeamSpeak 3 по адресу lol.com</div>
                    </div>

                      <div class="conv-desc">
                        <h3 style="font-family: arial;font-size: 22px;margin-top: 0;">Условия мероприятия для сотрудников компании</h3>
                        <p>Приглашаем в компанию все желающих и гостей от 18+ ВТК-[LOL Truckers] <br>
на момент начало конвоя быть в TeamSpeak 3 по адресу lol.com</p>
                          <div class="box-body" style="padding: 0;">
                            <a href="http://lol.com/truck_images/3959-3999.jpg" target="_blank">
                             <img style="width: 100%;" src="http://lol.com/truck_images/3959-3999.jpg">
                           </a>
                         </div>
                     </div>
                   <div>

                    <div class="block_reload_mods_mng">

                    <div class="row" style="margin: 0;padding: 6px;">

  </div>

                    </div>
                   </div>
                 </div>';
    $meeting_datetime = $localisedFaker->dateTimeBetween('-30minutes', '+6hours', 'Europe/Moscow');
    $leaving_datetime = \Carbon\Carbon::instance($meeting_datetime)->addMinutes(30);

    $server = App\Models\Server::all()->random();
    $start_town = App\Models\City::where('country_id', $server->game->countries->random()->id)->get()->random();
    $finish_town = App\Models\City::where('country_id', $server->game->countries->random()->id)->get()->random();

    $dlcs = [
        (int)$start_town->dlc->id,
        (int)$finish_town->dlc->id,
    ];

    $dlcs = array_values(array_unique($dlcs));

    $title = rand(0, 1) ? $localisedFaker->words(5, true) : null;

    return [
        'title'             => htmlentities($title),
        'pinned'            => rand(0, 1),
        'meeting_datetime'  => $meeting_datetime,
        'leaving_datetime'  => $leaving_datetime,
        'start_town_id'     => $start_town->id,
        'start_place'       => htmlentities($localisedFaker->city),
        'finish_town_id'    => $finish_town->id,
        'finish_place'      => htmlentities($localisedFaker->city),
        'server_id'         => $server->id,
        'stops'             => htmlentities($localisedFaker->city),
        'voice_description' => htmlentities($localisedFaker->sentence),
        'description'       => $html,
        'dlcs'              => $dlcs,
        'background_url'    => $faker->randomElement(['https://pp.userapi.com/c629202/v629202687/4d16f/DE0OPlISOzw.jpg', 'https://pp.userapi.com/c629202/v629202687/4d181/MyaiwzMiMl4.jpg']),
        'map_url'           => $faker->randomElement(['https://pp.userapi.com/c629202/v629202687/4d16f/DE0OPlISOzw.jpg', 'https://pp.userapi.com/c629202/v629202687/4d181/MyaiwzMiMl4.jpg']),
        'status'            => 'open',
    ];
});

$factory->define(App\Models\Comment::class, function () {
    $localisedFaker = Faker\Factory::create("ru_RU");

    return [
        'convoy_id' => App\Models\Convoy::all()->random()->id,
        'user_id'   => App\Models\User::all()->random()->id,
        'text'      => htmlentities($localisedFaker->sentence),
    ];

});

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    $localisedFaker = Faker\Factory::create("ru_RU");

    $name = $faker->userName;
    $avatar = 'http://cdn.edgecast.steamstatic.com/steamcommunity/public/images/avatars/81/81164934f157daa50f440dbf8108914c663c7a49_full.jpg';

    return [
        'nickname'            => htmlentities($name),
        'email'               => $localisedFaker->email,
        'steam_username'      => htmlentities($name),
        'steamid'             => '7656119806330' . rand(1000, 9999),
        'truckersmp_username' => htmlentities($name),
        'truckersmpid'        => rand(100000, 1000000),
        'steam_avatar'        => $avatar,
        'truckersmp_avatar'   => $avatar,
        'rules_accepted'      => true,
    ];
});
