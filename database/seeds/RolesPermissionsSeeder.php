<?php

use HttpOz\Roles\Models\Role;
use Illuminate\Database\Seeder;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['Admin', 'admin', 'Администратор', 'administration'],
            ['Moderator', 'moderator', 'Модератор', 'administration'],
            ['Donator', 'donator', 'Донатор', null],
            ['Tester', 'tester', 'Тестировщик', null],
            ['User', 'user', 'Дальнобойщик', null],
            ['Banned', 'banned', 'Забаненный', 'restricted'],
        ];

        foreach ($roles as $role) {
            Role::create([
                'name'        => $role[0],
                'slug'        => $role[1],
                'description' => $role[2],
                'group'       => $role[3],
            ]);
        }
    }
}