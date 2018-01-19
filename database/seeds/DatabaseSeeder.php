<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesPermissionsSeeder::class);
        $this->call(CountryCitiesSeeder::class);
        $this->call(ServersSeeder::class);

        if (config('app.env') === 'local') {
            $this->call(TestThingsSeeder::class);
        }
    }
}
