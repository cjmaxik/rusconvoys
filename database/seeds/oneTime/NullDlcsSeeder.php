<?php

use App\Models\City;
use App\Models\Convoy;
use Illuminate\Database\Seeder;

class NullDlcsSeeder extends Seeder
{
    /**
     * @return void
     * @throws \Exception
     * @throws \Throwable
     * @deprecated THIS IS NOT NEEDED!!!
     */
    public function run()
    {
        echo "\nDLCs...\n";

        $convoys = Convoy::withTrashed()->get();

        foreach ($convoys as $convoy) {
            if (!is_null($convoy->dlcs)) {
                continue;
            }

            $presented_dlcs = [
                (int)City::DlcID($convoy->start_town_id),
                (int)City::DlcID($convoy->finish_town_id),
            ];
            $dlcs = array_unique($presented_dlcs);
            if (($key = array_search(1, $dlcs)) !== false) {
                unset($dlcs[$key]);
            }

            DB::transaction(function () use ($dlcs, $convoy) {
                $convoy->dlcs = array_values($dlcs);
                $convoy->update();
            });
        }

    }
}
