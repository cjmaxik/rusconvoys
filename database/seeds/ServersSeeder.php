<?php

use App\Models\Server;
use Illuminate\Database\Seeder;
use TruckersMP\API\APIClient;

class ServersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Http\Client\Exception
     */
    public function run()
    {
        echo "\nServers...\n";
        $client = new APIClient();
        try {
            $servers = $client->servers();
        } catch (\Exception $e) {
            return null;
        }

        if (is_null($client)) {
            dd('ERROR!');
        }

        $game['ETS2'] = 1;
        $game['ATS']  = 2;

        foreach ($servers as $server) {
            $s            = new Server();
            $s->actual_id = $server->id;
            $s->game_id   = $game[$server->game];
            $s->name      = $server->name;
            $s->shortname = $server->shortName;
            $s->online    = $server->online;
            $s->save();

            echo $server->name . "... ";

//            sleep(5);
        }
    }
}
