<?php

use Faker\Factory as Faker;
use HttpOz\Roles\Models\Role;
use Illuminate\Database\Seeder;

class TestThingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Storage::deleteDirectory('descriptions');
        Storage::deleteDirectory('public/avatars');
        Storage::deleteDirectory('public/convoys');

        \Cache::flush();
        opcache_reset();

        $html = '<html><head><title>Aut illo dolorem et accusantium eum.</title></head><body><form action="example.com" method="POST"><label for="username">sequi</label><input type="text" id="username"><label for="password">et</label><input type="password" id="password"></form><b>Id aut saepe non mollitia voluptas voluptas.</b><table><thead><tr><tr>Non consequatur.</tr><tr>Incidunt est.</tr><tr>Aut voluptatem.</tr><tr>Officia voluptas rerum quo.</tr><tr>Asperiores similique.</tr></tr></thead><tbody><tr><td>Sapiente dolorum dolorem sint laboriosam commodi qui.</td><td>Commodi nihil nesciunt eveniet quo repudiandae.</td><td>Voluptates explicabo numquam distinctio necessitatibus repellat.</td><td>Provident ut doloremque nam eum modi aspernatur.</td><td>Iusto inventore.</td></tr><tr><td>Animi nihil ratione id mollitia libero ipsa quia tempore.</td><td>Velit est officia et aut tenetur dolorem sed mollitia expedita.</td><td>Modi modi repudiandae pariatur voluptas rerum ea incidunt non molestiae eligendi eos deleniti.</td><td>Exercitationem voluptatibus dolor est iste quod molestiae.</td><td>Quia reiciendis.</td></tr><tr><td>Inventore impedit exercitationem voluptatibus rerum cupiditate.</td><td>Qui.</td><td>Aliquam.</td><td>Autem nihil aut et.</td><td>Dolor ut quia error.</td></tr><tr><td>Enim facilis iusto earum et minus rerum assumenda quis quia.</td><td>Reprehenderit ut sapiente occaecati voluptatum dolor voluptatem vitae qui velit.</td><td>Quod fugiat non.</td><td>Sunt nobis totam mollitia sed nesciunt est deleniti cumque.</td><td>Repudiandae quo.</td></tr><tr><td>Modi dicta libero quisquam doloremque qui autem.</td><td>Voluptatem aliquid saepe laudantium facere eos sunt dolor.</td><td>Est eos quis laboriosam officia expedita repellendus quia natus.</td><td>Et neque delectus quod fugit enim repudiandae qui.</td><td>Fugit soluta sit facilis facere repellat culpa magni voluptatem maiores tempora.</td></tr><tr><td>Enim dolores doloremque.</td><td>Assumenda voluptatem eum perferendis exercitationem.</td><td>Quasi in fugit deserunt ea perferendis sunt nemo consequatur dolorum soluta.</td><td>Maxime repellat qui numquam voluptatem est modi.</td><td>Alias rerum rerum hic hic eveniet.</td></tr><tr><td>Tempore voluptatem.</td><td>Eaque.</td><td>Et sit quas fugit iusto.</td><td>Nemo nihil rerum dignissimos et esse.</td><td>Repudiandae ipsum numquam.</td></tr><tr><td>Nemo sunt quia.</td><td>Sint tempore est neque ducimus harum sed.</td><td>Dicta placeat atque libero nihil.</td><td>Et qui aperiam temporibus facilis eum.</td><td>Ut dolores qui enim et maiores nesciunt.</td></tr><tr><td>Dolorum totam sint debitis saepe laborum.</td><td>Quidem corrupti ea.</td><td>Cum voluptas quod.</td><td>Possimus consequatur quasi dolorem ut et.</td><td>Et velit non hic labore repudiandae quis.</td></tr></tbody></table></body></html>';

        /**
         * Creating user
         */

        $faker = Faker::create('ru_RU');

        $user      = new App\Models\User();
        $adminRole = Role::whereSlug('admin')->first();

        $user->nickname            = 'CJMAXiK';
        $user->email               = 'email@email.ru';
        $user->subscribe           = true;
        $user->timezone            = "Asia/Vladivostok";
        $user->steam_username      = $user->nickname;
        $user->steamid             = '76561198063301489';
        $user->truckersmp_username = $user->nickname;
        $user->truckersmpid        = '3861';
        $user->tag                 = 'TruckersMP';
        $user->tag_color           = 'deep-purple';
        $user->steam_avatar        = 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/83/83b09f2e37333cffa2a0da076b2c45ae22a387d5_full.jpg';
        $user->truckersmp_avatar   = 'https://static.truckersmp.com/avatars/3861.1476253275.png';
        $user->about               = $html;
        $user->rules_accepted      = true;

        $user->save();
        $user->attachRole($adminRole);

        /**
         * Creating another user
         */

        $user          = new App\Models\User();
        $moderatorRole = Role::whereSlug('moderator')->first();

        $user->nickname            = 'ResTed';
        $user->email               = null;
        $user->subscribe           = false;
        $user->steam_username      = "[Интегра] ResTed [RU]";
        $user->steamid             = '76561198068301986';
        $user->truckersmp_username = $user->nickname;
        $user->truckersmpid        = '3861';
        $user->tag                 = '[Интегра]';
        $user->tag_color           = 'green';
        $user->steam_avatar        = 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/e2/e231567a77a37b2d828debfc2bdf9b067891792a_full.jpg';
        $user->truckersmp_avatar   = 'https://www.gravatar.com/avatar/e9ad25d84e2a31bd149f19665b3fefdf?s=260';
        $user->about               = $html;
        $user->rules_accepted      = true;

        $user->save();
        $user->attachRole($moderatorRole);


        /**
         * Creating convoy
         */
        $d = \App\Models\Server::pluck('id')->toArray();

        factory(App\Models\Convoy::class)->create([
            'user_id'   => 1,
            'pinned'    => true,
            'server_id' => $d[array_rand($d)],
        ]);

        factory(App\Models\Convoy::class)->create([
            'user_id'   => 2,
            'pinned'    => true,
            'server_id' => $d[array_rand($d)],
        ]);

        $users    = 100;
        $userRole = Role::whereSlug('user')->first();
        factory(App\Models\User::class, $users)->create()->each->attachRole($userRole);

        echo "\nConvoys...\n";
        for ($i = 3; $i < $users; $i++) {
            factory(App\Models\Convoy::class)->create([
                'user_id' => $i,
            ]);
            echo $i . "... ";
        }

        echo "\nComments...\n";
        factory(App\Models\Comment::class, 100)->create();

        echo "\nParticipations...\n";
        $participations = ['yep', 'thinking'];
        foreach (App\Models\Convoy::all() as $convoy) {
            $participation            = new App\Models\Participation;
            $participation->type      = $participations[mt_rand(0, count($participations) - 1)];
            $participation->user_id   = 1;
            $participation->convoy_id = $convoy->id;
            $participation->save();

            echo $convoy->id . "... ";
        }

        echo "\n";
        \Cache::flush();
        opcache_reset();
    }
}
