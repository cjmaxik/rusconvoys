<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Models\Server;
use Illuminate\Console\Command;
use TruckersMP\API\APIClient;

class ServersChanger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convoy:servers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update servers list';

    /**
     * @var \TruckersMP\API\APIClient
     */
    private $client;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->client = new APIClient();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     * @throws \Http\Client\Exception
     */
    public function handle()
    {
        $tmp = $this->client->servers();

        /** @var \Illuminate\Support\Collection|\App\Models\Game[] $games */
        /** @var \Illuminate\Support\Collection|\App\Models\Server[] $servers_db */
        /** @var \Illuminate\Support\Collection|\TruckersMP\Types\Server[] $servers_actual */
        $games      = Game::all();
        $servers_db = Server::all();

//        $new            = json_decode('{"id":16,"game":"ETS2","ip":"43.251.158.210","port":42860,"name":"Convoy","shortname":"Convoy","online":true,"players":4,"queue":0,"maxplayers":500,"speedlimiter":false,"collisions":true,"carsforplayers":true,"policecarsforplayers":false,"afkenabled":true,"syncdelay":100}',
//            true);
//        $tmp->servers[] = new \TruckersMP\Types\Server($new);
        $servers_actual = collect($tmp);

        $existed_servers = [];
        foreach ($servers_actual as $actual) {
            /** @var \Illuminate\Support\Collection|\App\Models\Server[] $db */
            /** @var \TruckersMP\Types\Server $actual */
            $db = $servers_db->where('actual_id', $actual->id)
                ->where('name', $actual->name)
                ->where('shortname', $actual->shortName)
                ->sortByDesc('created_at');

            if ($db->isEmpty()) {
                /** @var \App\Models\Server $s */
                $s = Server::create([
                    'actual_id' => $actual->id,
                    'game_id'   => $games->where('shortname', $actual->game)->first()->id,
                    'name'      => $actual->name,
                    'shortname' => $actual->shortName,
                    'online'    => $actual->online,
                ]);

                $this->info('New server - ' . $actual->name);
                $existed_servers[] = $s->id;
                continue;
            }

            /** @var \App\Models\Server $main */
            if ($db->count() > 1) {
                $main = $db->shift();
                $db->each(function ($server) {
                    /** @var \App\Models\Server $server */
                    $server->online = false;
                    $server->update();
                    $server->delete();
                });
            } else {
                $main = $db->first();
            }

            if ($main->online !== $actual->online) {
                $actual_online = $actual->online ? 'online' : 'offline';
                $main->online  = $actual->online;

                $this->comment("{$main->shortname} went {$actual_online}");
            }

            $existed_servers[] = $main->id;
        };

        $servers_db->each->update();
        Server::whereNotIn('id', $existed_servers)->delete();
    }
}